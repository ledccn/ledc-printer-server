<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;
use Ledc\Printer\Cainiao\StatusResponseInterface;

/**
 * 【响应】打印通知-文档纬度
 */
class NotifyDocResultResponse extends ResponseProtocols implements StatusResponseInterface
{
    /**
     * @var string
     */
    public string $status = '';
    /**
     * 进⾏处理的打印机名称
     * @var string
     */
    public string $printer = '';
    /**
     * @var string
     */
    public string $taskID = '';
    /**
     * 描述信息
     * @var string
     */
    public string $detail = '';
    /**
     * 该数据对应的 doc
     * @var string|int
     */
    public string|int $documentId = '';
    /**
     * 0 为成功,其他为失
     * @var int|string
     */
    public int|string $code = 0;

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->code === 0;
    }
}
