<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】打印通知(notifyPrintResult)
 */
class NotifyPrintResultResponse extends ResponseProtocols
{
    /**
     * @var string
     */
    public string $taskID = '';
    /**
     * @var int|string
     */
    public int|string $status = 0;
    /**
     * @var string
     */
    public string $msg = '';
    /**
     * @var string
     */
    public string $taskStatus = '';
    /**
     * @var string
     */
    public string $printer = '';
    /**
     * @var int|string
     */
    public int|string $evaluationSpendTime = 0;
    /**
     * @var int|string
     */
    public int|string $pendingSpendTime = 0;
    /**
     * @var int|string
     */
    public int|string $downloadingSpendTime = 0;
    /**
     * @var int|string
     */
    public int|string $totalSpendTime = 0;
    /**
     * @var array
     */
    protected array $printStatus = [];

    /**
     * @param string|null $field
     * @param mixed|null $default
     * @return mixed
     */
    public function getPrintStatus(string $field = null, mixed $default = null): mixed
    {
        return static::get($this->printStatus, $field, $default);
    }
}
