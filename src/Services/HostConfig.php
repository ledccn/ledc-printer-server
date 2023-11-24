<?php

namespace Ledc\Printer\Services;

/**
 * 主机配置
 */
class HostConfig
{
    /**
     * @var string
     */
    protected string $websocket = '';
    /**
     * 【平台】打印机回调URL
     * @var string
     */
    protected string $notify_url = '/notify';
    /**
     * @var string
     */
    protected string $bind_url = '/bind';
    /**
     * 【用户】打印机回调URL
     * @var string
     */
    protected string $callback_url = '';
    /**
     * @var int
     */
    protected int $ping_second = 30;
    /**
     * @var int
     */
    protected int $time_out_second = 30;

    /**
     * @param string $host
     * @param string $route_prefix
     */
    public function __construct(protected readonly string $host, protected readonly string $route_prefix)
    {
    }

    /**
     * @param string $websocket
     * @return HostConfig
     */
    public function setWebsocket(string $websocket): self
    {
        $this->websocket = $websocket;
        return $this;
    }

    /**
     * @param string $notify_url
     * @return HostConfig
     */
    public function setNotifyUrl(string $notify_url): self
    {
        $this->notify_url = $this->host . $this->route_prefix . $notify_url;
        return $this;
    }

    /**
     * @param string $bind_url
     * @return HostConfig
     */
    public function setBindUrl(string $bind_url): self
    {
        $this->bind_url = $this->host . $this->route_prefix . $bind_url;
        return $this;
    }

    /**
     * 【用户】打印机回调URL
     * @param string $callback_url
     * @return HostConfig
     */
    public function setCallbackUrl(string $callback_url): self
    {
        $this->callback_url = $callback_url;
        return $this;
    }

    /**
     * 转数组
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
