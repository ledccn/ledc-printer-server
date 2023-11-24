<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;
use Ledc\Printer\Cainiao\StatusResponseInterface;

/**
 * 【响应】打印机代理组件（自定义协议）
 */
class PrintProxyResponse extends ResponseProtocols implements StatusResponseInterface
{
    /**
     * 状态
     * - 枚举值：success已转发给菜鸟打印组件、fail菜鸟组件不在线
     * @var string
     */
    public string $status = '';

    /**
     * @var string
     */
    public string $msg = '';

    /**
     * 是否成功
     * @return bool
     */
    public function isSuccess(): bool
    {
        return 'success' === $this->status;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * 是否失败
     * @return bool
     */
    public function isFail(): bool
    {
        return 'fail' === $this->status;
    }
}
