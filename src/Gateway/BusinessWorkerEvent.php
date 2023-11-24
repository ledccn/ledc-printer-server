<?php

namespace Ledc\Printer\Gateway;

use Exception;
use GatewayWorker\Lib\Gateway;
use Ledc\WorkermanProcess\Events;
use Workerman\Timer;
use Workerman\Worker;

/**
 * 事件处理类
 */
class BusinessWorkerEvent extends Events
{
    /**
     * @param Worker $worker
     * @return void
     */
    public static function onWorkerStart(Worker $worker): void
    {
        parent::onWorkerStart($worker);
        EventEnum::emit(EventEnum::onWorkerStart, $worker);
    }

    /**
     * @param string $client_id
     * @return void
     * @throws Exception
     */
    public static function onConnect(string $client_id): void
    {
        parent::onConnect($client_id);
        EventEnum::emit(EventEnum::onConnect, $client_id);
    }

    /**
     * 当客户端发来数据(Gateway进程收到数据)后触发的回调函数
     * - https://www.workerman.net/doc/gateway-worker/on-messsge.html
     * @param string $client_id 全局唯一的客户端socket连接标识
     * @param mixed $message 完整的客户端请求数据，数据类型取决于Gateway所使用协议的decode方法的返回值类型
     * @throws Exception
     */
    public static function onMessage(string $client_id, $message): void
    {
        //简易ping、pong
        if ('ping' === $message) {
            Gateway::sendToCurrentClient('pong');
            EventEnum::emit(EventEnum::onMessagePing, $client_id);
            return;
        }

        $data = json_decode($message, true);
        if (empty($data)) {
            return;
        }

        switch ($data['event'] ?? '') {
            case 'ping':
                Gateway::sendToCurrentClient('{"event":"pong"}');
                //ping服务器时，携带应用的在线状态
                $online = $data['online'] ?? null;
                if (null !== $online) {
                    $_SESSION['online'] = $online;
                }
                EventEnum::emit(EventEnum::onMessagePing, $client_id);
                break;
            case 'keepalive':
                if (!empty($_SESSION[self::AUTH_TIMER_ID])) {
                    Timer::del($_SESSION[self::AUTH_TIMER_ID]);
                    unset($_SESSION[self::AUTH_TIMER_ID]);
                    Gateway::sendToCurrentClient('{"event":"keepalive","status":"ok"}');
                }
                break;
            default:
                static::onMessageHandler($client_id, $data, $message);
                break;
        }
    }

    /**
     * @param string $client_id
     * @return void
     * @throws Exception
     */
    public static function onClose(string $client_id): void
    {
        parent::onClose($client_id);
        EventEnum::emit(EventEnum::onClose, $client_id);
    }
}
