<?php

namespace Ledc\Printer\Model;

use think\helper\Str;

/**
 * 模型观察者：pr_gateway_online
 * @usage Application::observe(ApplicationObserver::class);
 */
class ApplicationObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param Application $model
     * @return void
     */
    public function creating(Application $model): void
    {
        $model->app_secret = Str::random(40);
    }

    /**
     * 监听数据创建后的事件。
     *
     * @param Application $model
     * @return void
     */
    public function created(Application $model): void
    {
        $gatewayOnline = new GatewayOnline();
        $gatewayOnline->app_id = $model->app_id;
        $gatewayOnline->save();
    }

    /**
     * 监听数据即将更新的事件。
     *
     * @param Application $model
     * @return void
     */
    public function updating(Application $model): void
    {
    }

    /**
     * 监听数据更新后的事件。
     *
     * @param Application $model
     * @return void
     */
    public function updated(Application $model): void
    {
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param Application $model
     * @return void
     */
    public function saving(Application $model): void
    {
    }

    /**
     * 监听数据保存后的事件。
     *
     * @param Application $model
     * @return void
     */
    public function saved(Application $model): void
    {
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param Application $model
     * @return void
     */
    public function deleting(Application $model): void
    {
    }

    /**
     * 监听数据删除后的事件。
     *
     * @param Application $model
     * @return void
     */
    public function deleted(Application $model): void
    {
    }

    /**
     * 监听数据即将从软删除状态恢复的事件。
     *
     * @param Application $model
     * @return void
     */
    public function restoring(Application $model): void
    {
    }

    /**
     * 监听数据从软删除状态恢复后的事件。
     *
     * @param Application $model
     * @return void
     */
    public function restored(Application $model): void
    {
    }
}
