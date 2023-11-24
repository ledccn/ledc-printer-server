<?php

namespace Ledc\Printer\Locker;

use Ledc\Locker\Locker;
use Symfony\Component\Lock\LockInterface;

/**
 * 打印调度锁：PrinterLockerLocker
 * @method static LockInterface lock(?string $appid = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null) 打印时防止并发锁
 * @method static LockInterface create(?string $appid_originId_taskId = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null) 创建时防止重复锁
 * @method static LockInterface update(?string $appid = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null) 更新锁
 * @method static LockInterface loading(?string $appid = null, ?float $ttl = null, ?bool $autoRelease = null, ?string $prefix = null) 装载锁
 */
class PrinterLocker extends Locker
{
}
