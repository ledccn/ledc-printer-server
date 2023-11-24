<?php

namespace Ledc\Printer\Support;

use support\Response;
use think\Container;

/**
 * JSON响应
 */
class JsonResponse
{
    /**
     * 成功响应码
     */
    const success = 0;
    /**
     * 失败响应码
     */
    const fail = 1;

    /**
     * 单例
     * - 使用ThinkPHP容器，实现单例
     * @param bool $newInstance
     * @return static
     */
    public static function getInstance(bool $newInstance = false): static
    {
        return Container::pull(static::class, [], $newInstance);
    }

    /**
     * 返回格式化json数据
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return Response
     */
    public function json(int $code, string $msg = 'ok', array $data = []): Response
    {
        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }

    /**
     * @param string $msg
     * @param array $data
     * @return Response
     */
    public function success(string $msg = 'ok', array $data = []): Response
    {
        return $this->json(static::success, $msg, $data);
    }

    /**
     * @param string $msg
     * @param array $data
     * @return Response
     */
    public function fail(string $msg = 'fail', array $data = []): Response
    {
        return $this->json(static::fail, $msg, $data);
    }
}