<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 不支持的协议
 */
class NotSupportResponse extends ResponseProtocols
{
    /**
     * @var string|array
     */
    protected string|array $data;

    /**
     * @param array|string $data
     */
    public function __construct(array|string $data = [])
    {
        parent::__construct($data);
        $this->data = $data;
    }
}
