<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\CmdEnum;
use Ledc\Printer\Cainiao\ResponseProtocols;
use support\exception\BusinessException;

/**
 * 工厂协议
 */
class Factory
{
    /**
     * 创建响应
     * @param CmdEnum $cmdEnum
     * @param array $buffer
     * @return ResponseProtocols
     * @throws BusinessException
     */
    public static function createResponse(CmdEnum $cmdEnum, array $buffer): ResponseProtocols
    {
        $response = match ($cmdEnum) {
            CmdEnum::getAgentInfo => GetAgentInfoResponse::class,
            CmdEnum::ledcPrintProxy => PrintProxyResponse::class,
            CmdEnum::print => PrintResponse::class,
            CmdEnum::notifyTaskResult => NotifyTaskResultResponse::class,
            CmdEnum::notifyDocResult => NotifyDocResultResponse::class,
            CmdEnum::notifyPrintResult => NotifyPrintResultResponse::class,
            default => NotSupportResponse::class
        };

        if (!is_a($response, ResponseProtocols::class, true)) {
            throw new BusinessException($response . ' 响应协议处理类应继承' . ResponseProtocols::class);
        }

        return new $response($buffer);
    }
}
