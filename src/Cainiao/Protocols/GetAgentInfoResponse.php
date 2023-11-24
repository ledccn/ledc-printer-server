<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】获取客户端版本信息
 */
class GetAgentInfoResponse extends ResponseProtocols
{
    /**
     * 表示命令成功或失败，取值“success”或者“failed”
     * @var string
     */
    public string $status = '';
    /**
     * 如果出错，错误原因
     * @var string
     */
    public string $msg = '';
    /**
     * 版本号
     * @var string
     */
    public string $version = '';

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return 'success' === $this->status;
    }
}
