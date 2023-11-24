<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\CmdEnum;
use Ledc\Printer\Cainiao\RequestProtocols;

/**
 * 获取打印机配置(getPrinterConfig)
 */
class GetPrinterConfig extends RequestProtocols
{
    /**
     * @param string $requestID
     */
    public function __construct(string $requestID)
    {
        $this->cmd = CmdEnum::getPrinterConfig->value;
        $this->requestID = $requestID;
    }
}
