<?php

namespace Ledc\Printer\Services;

use Error;
use Exception;
use Ledc\Printer\Cainiao\CmdEnum;
use Ledc\Printer\Cainiao\Protocols\Factory;
use Ledc\Printer\Cainiao\Protocols\GetAgentInfoResponse;
use Ledc\Printer\Cainiao\Protocols\NotifyDocResultResponse;
use Ledc\Printer\Cainiao\Protocols\NotifyPrintResultResponse;
use Ledc\Printer\Cainiao\Protocols\NotifyTaskResultResponse;
use Ledc\Printer\Cainiao\Protocols\PrintProxyResponse;
use Ledc\Printer\Cainiao\Protocols\PrintResponse;
use Ledc\Printer\Cainiao\ResponseProtocols;
use Ledc\Printer\Model\Application;
use Ledc\Printer\Model\Enum\PrintStatusEnum;
use Ledc\Printer\Model\GatewayOnline;
use Ledc\RedisQueue\JobsAbstract;
use support\exception\BusinessException;
use support\Log;
use Throwable;

/**
 * 通知结果处理者
 */
class NotifyHandler extends JobsAbstract
{
    /**
     * 任务默认执行的方法
     * @param int $app_id
     * @param string $cmd
     * @param array $post
     * @return void
     */
    public function execute(int $app_id = 0, string $cmd = '', array $post = []): void
    {
        try {
            self::process(Application::find($app_id), CmdEnum::from($cmd), $post);
        } catch (Error|Exception|Throwable $throwable) {
            Log::error(__METHOD__ . ' | ' . $throwable->getMessage());
        }
    }

    /**
     * 处理通知的逻辑
     * @param Application $application
     * @param CmdEnum $cmdEnum
     * @param array $buffer
     * @return bool
     * @throws BusinessException
     */
    public static function process(Application $application, CmdEnum $cmdEnum, array $buffer): bool
    {
        $notify = Factory::createResponse($cmdEnum, $buffer);
        return match ($cmdEnum) {
            CmdEnum::getAgentInfo => self::getAgentInfo($notify, $application),
            CmdEnum::ledcPrintProxy => self::ledcPrintProxy($notify, $application),
            CmdEnum::print => self::print($notify, $application),
            CmdEnum::notifyTaskResult => self::notifyTaskResult($notify, $application),
            CmdEnum::notifyDocResult => self::notifyDocResult($notify, $application),
            CmdEnum::notifyPrintResult => self::notifyPrintResult($notify, $application),
            default => true
        };
    }

    /**
     * 打印代理组件调度响应（自定义协议）
     * @param PrintProxyResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function ledcPrintProxy(PrintProxyResponse|ResponseProtocols $notify, Application $application): bool
    {
        if ($notify->isSuccess()) {
            return QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::dispatched);
        }

        return QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::error, '请求菜鸟组件失败');
    }

    /**
     * 获取客户端版本信息
     * @param GetAgentInfoResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function getAgentInfo(GetAgentInfoResponse|ResponseProtocols $notify, Application $application): bool
    {
        if ($notify->isSuccess()) {
            $gatewayOnline = GatewayOnline::getByAppId($application->app_id);
            if ($gatewayOnline instanceof GatewayOnline) {
                $gatewayOnline->version = $notify->version;
                $gatewayOnline->save();
            }
        }
        return true;
    }

    /**
     * 文档纬度的通知
     * @param NotifyDocResultResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function notifyDocResult(NotifyDocResultResponse|ResponseProtocols $notify, Application $application): bool
    {
        $data = [];
        $status = $notify->status;
        /**
         * 渲染阶段
         */
        if ('rendered' === $status) {
            if ($notify->isSuccess()) {
                return QueuePrintServices::updateStatus(
                    $notify->requestID,
                    $application->app_id,
                    PrintStatusEnum::pending,
                    'render渲染成功'
                );
            } else {
                return QueuePrintServices::updateStatus(
                    $notify->requestID,
                    $application->app_id,
                    PrintStatusEnum::failed,
                    $notify->detail ?? 'render渲染失败'
                );
            }
        }

        /**
         * 打印阶段
         */
        if ('printed' === $status) {
            if ($notify->isSuccess()) {
                if ($printer = $notify->printer ?? '') {
                    $data = [
                        'printer' => $printer
                    ];
                }
                return QueuePrintServices::updateStatus(
                    $notify->requestID,
                    $application->app_id,
                    PrintStatusEnum::success,
                    'printed打印成功',
                    $data
                );
            } else {
                return QueuePrintServices::updateStatus(
                    $notify->requestID,
                    $application->app_id,
                    PrintStatusEnum::failed,
                    'printed打印失败'
                );
            }
        }

        return true;
    }

    /**
     * 任务纬度的通知
     * @param NotifyTaskResultResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function notifyTaskResult(NotifyTaskResultResponse|ResponseProtocols $notify, Application $application): bool
    {
        $data = [];
        $status = $notify->status;
        if ($printer = $notify->printer ?? '') {
            $data = [
                'printer' => $printer
            ];
        }
        return match ($status) {
            // "status": "initial"
            'initial' => QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::pending, '任务initial', $data),
            // "status": "completeFailure"
            'completeFailure' => QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::failed, '任务completeFailure', $data),
            // "status": "completeSuccess"
            'completeSuccess' => QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::success, '任务completeSuccess', $data),
            default => true
        };
    }

    /**
     * 打印纬度的通知
     * @param PrintResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function print(PrintResponse|ResponseProtocols $notify, Application $application): bool
    {
        /*$status = $notify->status;
        if ('success' === $status) {
            // "status": "success",
            return QueuePrintServices::updateStatus($notify->requestID, $application->app_id, PrintStatusEnum::success);
        }*/

        return true;
    }

    /**
     * 打印结果纬度的通知
     * @param NotifyPrintResultResponse|ResponseProtocols $notify
     * @param Application $application
     * @return bool
     */
    protected static function notifyPrintResult(NotifyPrintResultResponse|ResponseProtocols $notify, Application $application): bool
    {
        return true;
    }
}
