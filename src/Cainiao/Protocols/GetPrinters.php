<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\CmdEnum;
use Ledc\Printer\Cainiao\RequestProtocols;

/**
 * 获取打印机列表
 */
class GetPrinters extends RequestProtocols
{
    /**
     * @param string $requestID
     */
    public function __construct(string $requestID)
    {
        $this->cmd = CmdEnum::getPrinters->value;
        $this->requestID = $requestID;
    }
}
