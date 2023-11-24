<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】打印通知-任务纬度
 */
class NotifyTaskResultResponse extends ResponseProtocols
{
    /**
     * @var string
     */
    public string $status = '';
    /**
     * 打印机名
     * @var string
     */
    public string $printer = '';
    /**
     * @var string
     */
    public string $taskId = '';
    /**
     * @var array
     */
    public array $spendTime = [];
    /**
     * @var array
     */
    public array $docs = [];
}
