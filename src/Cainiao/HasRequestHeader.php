<?php

namespace Ledc\Printer\Cainiao;

/**
 * 请求协议头
 */
trait HasRequestHeader
{
    /**
     * 请求的命令名称
     * @var string
     */
    public string $cmd;

    /**
     * 请求的ID，用于唯一标识每个请求，每个客户端自己保证生成唯一ID，如UUID
     * @var string
     */
    public string $requestID;

    /**
     * 协议当前版本，当前为“1.0”
     * @var string
     */
    public string $version = '1.0';
}
