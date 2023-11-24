<?php

namespace Ledc\Printer\Gateway;

use Ledc\Printer\Events\Observer;
use Webman\Bootstrap;

/**
 * 事件枚举
 */
enum EventEnum implements Bootstrap
{
    use HasStart;

    /**
     * 当businessWorker进程启动时触发。每个进程生命周期内都只会触发一次【businessWorker进程】
     */
    case onWorkerStart;
    /**
     * 当客户端连接上gateway进程时(TCP三次握手完毕时)触发的回调函数【businessWorker进程】
     */
    case onConnect;
    /**
     * 客户端与Gateway进程的连接断开时触发【businessWorker进程】
     */
    case onClose;
    /**
     * 收到客户端ping消息【businessWorker进程】
     */
    case onMessagePing;
    /**
     * client_id与uid绑定成功【web进程】
     */
    case bindUidSuccess;
    /**
     * 将client_id加入组成功后触发【web进程】
     */
    case joinGroupSuccess;

    /**
     * 事件观察者
     * @param EventEnum $enum
     * @return array
     */
    public static function observers(EventEnum $enum): array
    {
        return match ($enum) {
            EventEnum::onConnect => [
                [Observer::class, 'onConnect'],
            ],
            EventEnum::onMessagePing => [
                [Observer::class, 'onMessagePing'],
            ],
            EventEnum::onClose => [
                [Observer::class, 'onClose'],
            ],
            EventEnum::bindUidSuccess => [
                [Observer::class, 'bindUidSuccess'],
            ],
            EventEnum::joinGroupSuccess => [
                [Observer::class, 'joinGroupSuccess'],
            ],
            default => []
        };
    }
}
