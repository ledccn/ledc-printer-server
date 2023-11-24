<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】打印通知
 */
class PrintResponse extends ResponseProtocols
{
    /**
     * @var string
     */
    public string $taskID = '';
    /**
     * @var string
     */
    public string $status = '';
    /**
     * @var string
     */
    public string $msg = '';
    /**
     * @var int|string
     */
    public int|string $errorCode = 0;
}
