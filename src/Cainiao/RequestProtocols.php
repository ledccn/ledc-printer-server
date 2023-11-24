<?php

namespace Ledc\Printer\Cainiao;

/**
 * 【请求】协议抽象类
 */
abstract class RequestProtocols implements SupportRequestInterface
{
    use HasRequestHeader;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_filter(get_object_vars($this), function ($v) {
            return (null !== $v && '' !== $v);
        });
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        $json = \json_encode($this->jsonSerialize(), JSON_UNESCAPED_UNICODE);
        if (\JSON_ERROR_NONE !== \json_last_error()) {
            throw new \InvalidArgumentException('json_encode error: ' . \json_last_error_msg());
        }
        return $json;
    }
}
