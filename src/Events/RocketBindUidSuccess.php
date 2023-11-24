<?php

namespace Ledc\Printer\Events;

use Ledc\Printer\Model\Application;

/**
 * 小火箭：client_id与uid绑定成功
 */
class RocketBindUidSuccess
{
    /**
     * 构造函数
     * @param string $client_id 长连接客户端id
     * @param string $uid 用户UID（带前缀）
     * @param Application $application 应用
     */
    public function __construct(public readonly string $client_id, public readonly string $uid, public Application $application)
    {
    }
}