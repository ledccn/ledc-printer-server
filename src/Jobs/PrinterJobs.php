<?php

namespace Ledc\Printer\Jobs;

use Ledc\RedisQueue\JobsAbstract;

/**
 * 打印任务
 */
class PrinterJobs extends JobsAbstract
{
    /**
     * 任务默认执行的方法
     * @param array $data
     * @return void
     */
    public function execute(array $data = []): void
    {
        // 无需反序列化
        var_export($data);
    }

    /**
     * 自定义的示例方法
     * @param array $data
     * @return void
     */
    public function example(array $data = []): void
    {
        // 无需反序列化
        var_export($data);
    }
}
