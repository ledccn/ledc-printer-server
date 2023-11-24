<?php

namespace Ledc\Printer\Model;

/**
 * 模型观察者：pr_gateway_online
 * @usage GatewayOnline::observe(GatewayOnlineObserver::class);
 */
class GatewayOnlineObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function creating(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function created(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function updating(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function updated(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function saving(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function saved(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function deleting(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function deleted(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function restoring(GatewayOnline $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param GatewayOnline $model
     * @return void
     */
    public function restored(GatewayOnline $model): void
    {
    }
}
