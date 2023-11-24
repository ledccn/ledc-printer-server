<?php

namespace Ledc\Printer\Cainiao;

/**
 * 响应协议头
 */
trait HasResponseHeader
{
    /**
     * 请求的命令名称
     * @var string
     */
    public string $cmd;

    /**
     * 发送请求中的ID，原封不动返回，使客户端能识别出哪个请求对应的响应
     * @var string
     */
    public string $requestID;
}
