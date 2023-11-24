<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】获取打印机列表(getPrinters)
 */
class GetPrintersResponse extends ResponseProtocols
{
    /**
     * 默认打印机
     * @var string
     */
    public string $defaultPrinter = '';
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
    /**
     * @var array string printers.name 打印机的名字
     * @var array
     */
    public array $printers = [];
}