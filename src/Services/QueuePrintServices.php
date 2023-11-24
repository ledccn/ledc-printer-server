<?php

namespace Ledc\Printer\Services;

use Error;
use Exception;
use GatewayWorker\Lib\Gateway;
use Illuminate\Database\Eloquent\Collection;
use Ledc\Printer\Cainiao\Protocols\Prints;
use Ledc\Printer\Cainiao\Protocols\PrintsTask;
use Ledc\Printer\Locker\PrinterLocker;
use Ledc\Printer\Model\Application;
use Ledc\Printer\Model\Enum\PrintStatusEnum;
use Ledc\Printer\Model\QueuePrint;
use Ledc\Printer\Support\V;
use Ledc\Printer\Validate\QueuePrintValidate;
use RuntimeException;
use support\exception\BusinessException;
use support\Log;
use support\Redis;
use Throwable;

/**
 * 打印队列
 */
class QueuePrintServices
{
    /**
     * 创建打印任务
     * - 同步写库
     * @param array $data
     * @param Application $application
     * @return QueuePrint
     * @throws BusinessException
     */
    public static function create(array $data, Application $application): QueuePrint
    {
        // Step1：验证参数
        $v = V::validate(QueuePrintValidate::class);
        $v->scene(QueuePrintValidate::sceneCreate)->check($data);

        // Step2：打印内容入库
        $model = new QueuePrint();
        // 必须字段
        $model->app_id = $application->app_id;
        $model->origin_id = $data['origin_id'];
        $model->task_id = $data['task_id'];
        $documents = $data['documents'];
        $model->documents = is_string($documents) ? json_encode(json_decode($documents, true), JSON_UNESCAPED_UNICODE) : json_encode($documents, JSON_UNESCAPED_UNICODE);
        $model->preview = $data['preview'];
        // 可选字段
        $model->printer = $data['printer'] ?? '';

        // 查重：原子锁
        $lockKey = $model->app_id . ':' . $model->origin_id . ':' . $model->task_id;
        $lock = PrinterLocker::create($lockKey, 5, true);
        if (!$lock->acquire()) {
            throw new BusinessException('触发创建锁');
        }

        // 入库
        if (false === $model->save()) {
            throw new BusinessException('创建打印任务失败');
        }

        return $model;
    }

    /**
     * 把打印任务装载进Redis
     * @param PrintRedisList $redisList
     * @return bool
     */
    protected static function loadingToRedis(PrintRedisList $redisList): bool
    {
        $lock = PrinterLocker::loading($redisList->app_id, 30, true);
        if ($lock->acquire()) {
            $builder = QueuePrint::getBuilderAsc($redisList->app_id, PrintStatusEnum::default->value);
            $builder->where('dispatched', '<', time() - 60)
                ->chunkById(PrintRedisList::MAX_LENGTH, function (Collection $collection) use ($redisList) {
                    /** @var QueuePrint $model */
                    foreach ($collection as $model) {
                        if (!self::push($redisList, $model)) {
                            return false;
                        }
                    }
                    return true;
                });
        }

        return (bool)$redisList->length();
    }

    /**
     * 将打印任务插入到列表的尾部(最右边)
     * @param PrintRedisList $redisList
     * @param QueuePrint $model
     * @return bool
     */
    protected static function push(PrintRedisList $redisList, QueuePrint $model): bool
    {
        if ($redisList->length() < PrintRedisList::MAX_LENGTH) {
            //数据结构 #2023年11月15日16:16:45
            $data = ['id' => $model->id];
            if (false !== $redisList->push($data)) {
                $model->dispatched = time();
                $model->save();
                return true;
            }
        }

        return false;
    }

    /**
     * 弹出一个打印任务(最左边)
     * - 移除并获取列表的第一个元素
     * @param PrintRedisList $redisList
     * @return QueuePrint|null
     */
    protected static function pop(PrintRedisList $redisList): ?QueuePrint
    {
        //列表为空时自动载入
        if (!$redisList->length() && !self::loadingToRedis($redisList)) {
            return null;
        }

        /** @var array|bool $data */
        $data = $redisList->pop();
        if (!is_array($data)) {
            return null;
        }

        //数据结构 #2023年11月15日16:16:45
        $id = $data['id'] ?? null;
        if (empty($id)) {
            return null;
        }

        return QueuePrint::find($id) ?: null;
    }

    /**
     * 是否立刻打印【web进程，队列进程】
     * @param PrintRedisList $redisList
     * @return void
     */
    public static function canPrint(PrintRedisList $redisList): void
    {
        if (GatewayOnlineServices::canPrinterOnline($redisList->app_id)) {
            self::sendPrinter($redisList);
        }
    }

    /**
     * 是否立刻打印【businessWorker进程】
     * @param int $app_id 应用ID
     * @param string $client_id 长连接客户端ID
     * @param array $session 长连接Session
     * @return void
     */
    public static function canPrintByBusinessWorker(int $app_id, string $client_id, array $session): void
    {
        if ($session['online'] ?? null) {
            $redisList = new PrintRedisList($app_id);
            $redisList->add(3); //心跳奖励3个令牌
            self::sendPrinter($redisList, $client_id);
        }
    }

    /**
     * 发送到打印机，立刻开始打印
     * @param PrintRedisList $redisList
     * @param string|null $client_id
     * @return void
     */
    protected static function sendPrinter(PrintRedisList $redisList, string $client_id = null): void
    {
        if (!$redisList->get()) {
            return;
        }

        $model = self::pop($redisList);
        if (!$model) {
            return;
        }

        try {
            $message = self::builderPrintProtocol($model);
            if (empty($client_id)) {
                //uid发送
                $uid = ApplicationServices::builderGatewayWorkerUid($redisList->app_id);
                Gateway::sendToUid($uid, $message);
            } else {
                //client_id发送
                Gateway::sendToClient($client_id, $message);
            }
        } catch (Error|Exception|Throwable $throwable) {
            Log::error(__METHOD__.  ' | 错误 | ' . $throwable->getMessage());
        }
    }

    /**
     * 构造打印报文
     * @param QueuePrint $model
     * @return string
     */
    protected static function builderPrintProtocol(QueuePrint $model): string
    {
        try {
            $request_id = $model->id;
            $prints = new Prints($request_id);
            $task = new PrintsTask();
            $task->taskID = $model->task_id;
            $task->preview = (bool)$model->preview;
            $task->printer = $model->printer ?? '';
            $documents = is_array($model->documents) ? $model->documents : json_decode($model->documents, true);
            $task->setDocuments($documents);
            $prints->setTask($task);

            return $prints->encode();
        } catch (Error|Exception|Throwable $throwable) {
            $model->msg = $throwable->getMessage();
            $model->status = PrintStatusEnum::error->value;
            $model->save();

            throw new RuntimeException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * 更新打印任务的状态
     * @param int $id 主键（请求ID）
     * @param int $app_id 应用ID
     * @param PrintStatusEnum $statusEnum 待更新到的状态
     * @param string $msg 消息描述
     * @param array $updateData 需要更新的数据对象
     * @return bool
     */
    public static function updateStatus(int $id, int $app_id, PrintStatusEnum $statusEnum, string $msg = '', array $updateData = []): bool
    {
        $rs = false;
        $lock = PrinterLocker::update('id_' . $id . ':appid_' . $app_id, 5, true);
        if ($lock->acquire(true)) {
            $model = QueuePrint::getBuilderById($id, $app_id)->first();
            if ($model instanceof QueuePrint) {
                $rs = true;
                $status = $model->status;
                if (in_array($status, [PrintStatusEnum::failed->value, PrintStatusEnum::success->value])) {
                    return true;
                }

                //模型循环赋值
                foreach ($updateData as $key => $value) {
                    if (null !== $value && '' !== $value) {
                        $model->{$key} = $value;
                    }
                }

                //更新消息描述
                if ($msg) {
                    $model->msg = $msg;
                }

                //已调度、失败、成功、错误
                if (PrintStatusEnum::dispatched === $statusEnum
                    || PrintStatusEnum::failed === $statusEnum
                    || PrintStatusEnum::success === $statusEnum
                    || PrintStatusEnum::error === $statusEnum
                ) {
                    $model->status = $statusEnum->value;
                    $model->notify_time = time();
                    $model->save();

                    // 失败|成功
                    if (PrintStatusEnum::failed === $statusEnum || PrintStatusEnum::success === $statusEnum) {
                        // 后置操作：是否立刻打印
                        $redisList = new PrintRedisList($app_id);
                        $redisList->add(1);
                        QueuePrintServices::canPrint($redisList);
                    }

                    return true;
                }

                // 处理中
                if (PrintStatusEnum::pending === $statusEnum) {
                    if (in_array($status, [PrintStatusEnum::default->value, PrintStatusEnum::dispatched->value])) {
                        $model->status = $statusEnum->value;
                        $model->notify_time = time();
                        $model->save();
                    }

                    return true;
                }
            }
        }

        return $rs;
    }

    /**
     * 获取请求ID
     * - 请求的ID，用于唯一标识每个请求，每个客户端自己保证生成唯一ID，如UUID
     * @return int
     */
    public static function getRequestId(): int
    {
        return Redis::incr('QueuePrintServices:getRequestId');
    }
}
