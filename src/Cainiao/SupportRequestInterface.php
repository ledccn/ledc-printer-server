<?php

namespace Ledc\Printer\Cainiao;

use JsonSerializable;

/**
 * 支持请求协议
 */
interface SupportRequestInterface extends JsonSerializable
{
    /**
     * 编码
     * @return string
     */
    public function encode(): string;
}
