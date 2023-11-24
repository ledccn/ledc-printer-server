<?php

namespace Ledc\Printer\Services;

use Ledc\Printer\Support\HasRedisList;
use support\Redis;

/**
 * 打印队列
 */
class PrintRedisList
{
    use HasRedisList;

    /**
     * Redis队列最大长度
     */
    const MAX_LENGTH = 30;

    /**
     * 构造函数
     * @param int $app_id
     * @param int $limit_max 令牌桶：最大令牌数
     */
    public function __construct(public readonly int $app_id, public readonly int $limit_max = 10)
    {
        $this->key = __CLASS__ . ':app_id:' . $app_id;
    }

    /**
     * 令牌桶Key
     * @return string
     */
    protected function bucketKey(): string
    {
        return $this->key . ':limit_bucket';
    }

    /**
     * 添加令牌
     * @param int $number 数量
     * @return int 实际加入的数量
     */
    public function add(int $number): int
    {
        $bucket = $this->bucketKey();
        $number = max(0, $number);
        $current = Redis::lLen($bucket) ?: 0;
        $number = $this->limit_max >= ($current + $number) ? $number : $this->limit_max - $current;
        if (0 < $number) {
            $token = array_fill(0, $number, 1);
            Redis::rPush($bucket, ... $token);
        }
        return $number;
    }

    /**
     * 获取令牌
     * @return bool
     */
    public function get(): bool
    {
        return (bool)Redis::lPop($this->bucketKey());
    }

    /**
     * 重置令牌桶
     * @return void
     */
    public function reset(): void
    {
        $this->add($this->limit_max);
    }
}
