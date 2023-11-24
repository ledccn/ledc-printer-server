<?php

namespace Ledc\Printer;

use GatewayWorker\Lib\Gateway;
use Ledc\Printer\Model\Application;
use Ledc\Printer\Model\ApplicationObserver;
use Ledc\Printer\Model\GatewayOnline;
use Ledc\Printer\Model\GatewayOnlineObserver;
use Ledc\Printer\Model\QueuePrint;
use Ledc\Printer\Model\QueuePrintObserver;
use Ledc\Printer\Model\WaUserObserver;
use plugin\admin\app\model\User as WaUserByPluginAdmin;
use plugin\user\app\model\User as WaUserByPluginUser;
use Workerman\Worker;

/**
 * 进程启动时onWorkerStart时运行的回调配置
 * @link https://learnku.com/articles/6657/model-events-and-observer-in-laravel
 */
class Bootstrap implements \Webman\Bootstrap
{
    /**
     * @param Worker|null $worker
     * @return void
     */
    public static function start(?Worker $worker): void
    {
        //【新增】依次触发的顺序是：
        //saving -> creating -> created -> saved

        //【更新】依次触发的顺序是:
        //saving -> updating -> updated -> saved

        // updating 和 updated 会在数据库中的真值修改前后触发。
        // saving 和 saved 则会在 Eloquent 实例的 original 数组真值更改前后触发
        Application::observe(ApplicationObserver::class);
        GatewayOnline::observe(GatewayOnlineObserver::class);
        QueuePrint::observe(QueuePrintObserver::class);

        //注册webman用户模型观察者
        if (class_exists(WaUserByPluginAdmin::class)) {
            WaUserByPluginAdmin::observe(WaUserObserver::class);
        }
        if (class_exists(WaUserByPluginUser::class)) {
            WaUserByPluginUser::observe(WaUserObserver::class);
        }
        //密钥
        $gatewaySecret = getenv('GATEWAY_SECRET') ?: '';
        //注册中心地址
        $registerAddress = getenv('GATEWAY_REGISTER_ADDRESS') ?: '127.0.0.1';
        //注册中心端口
        $registerPort = getenv('GATEWAY_REGISTER_PORT') ?: '1236';
        if (class_exists(Gateway::class)) {
            Gateway::$registerAddress = $registerAddress . ':' . $registerPort;
            Gateway::$secretKey = $gatewaySecret;
        }
    }
}
