<?php

namespace Ledc\Printer\Support;

use Closure;
use support\Redis;

/**
 * Redis列表的入队、出队
 */
trait HasRedisList
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * 移除并获取列表的第一个元素
     * @return bool|array
     */
    public function pop(): bool|array
    {
        $json = Redis::lPop($this->key);
        return is_bool($json) ? $json : json_decode($json, true);
    }

    /**
     * 将值插入到列表的尾部(最右边)
     * @param Closure|array $data
     * @return bool|int
     */
    public function push(Closure|array $data): bool|int
    {
        $data = $data instanceof Closure ? $data($this) : $data;
        //将一个或多个值插入到列表的尾部(最右边)
        return Redis::rPush($this->key, json_encode($data));
    }

    /**
     * 获取列表长度
     * @return int
     */
    public function length(): int
    {
        return Redis::lLen($this->key) ?: 0;
    }
}
