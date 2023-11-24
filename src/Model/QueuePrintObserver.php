<?php

namespace Ledc\Printer\Model;

use Ledc\Printer\Model\Enum\PrintStatusEnum;
use Ledc\Printer\Services\PrintRedisList;
use Ledc\Printer\Services\QueuePrintServices;
use support\exception\BusinessException;
use Webman\Event\Event;

/**
 * 模型观察者：pr_queue_print
 * @usage QueuePrint::observe(QueuePrintObserver::class);
 */
class QueuePrintObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param QueuePrint $model
     * @return void
     * @throws BusinessException
     */
    public function creating(QueuePrint $model): void
    {
        // 打印机任务ID
        if (empty($model->task_id)) {
            $model->task_id = QueuePrintServices::getRequestId();
        }

        // 查重
        if (QueuePrint::canExists($model->app_id, $model->origin_id, $model->task_id)) {
            throw new BusinessException('订单对应的任务ID已重复');
        }
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function created(QueuePrint $model): void
    {
        // 是否立刻打印
        $redisList = new PrintRedisList($model->app_id);
        QueuePrintServices::canPrint($redisList);
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function updating(QueuePrint $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function updated(QueuePrint $model): void
    {
        //打印状态变更
        $status = $model->status;
        $oriStatus = $model->getRawOriginal('status');
        if (null !== $status
            && null !== $oriStatus
            && $status != $oriStatus
        ) {
            //派发状态变更事件
            $statusEnum = PrintStatusEnum::from((int)$status);
            match ($statusEnum) {
                PrintStatusEnum::failed,
                PrintStatusEnum::success,
                PrintStatusEnum::error => Event::emit('QueuePrintStatusEnum.' . $statusEnum->name, $model),
                default => true
            };
        }
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function saving(QueuePrint $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function saved(QueuePrint $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function deleting(QueuePrint $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function deleted(QueuePrint $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function restoring(QueuePrint $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param QueuePrint $model
     * @return void
     */
    public function restored(QueuePrint $model): void
    {
    }
}
