<?php

namespace Ledc\Printer\Gateway;

use Closure;
use support\Container;
use Webman\Event\Event;
use Workerman\Worker;
use function array_values;
use function class_exists;
use function is_array;
use function is_string;

/**
 * 实现进程启动前，注册事件
 */
trait HasStart
{
    /**
     * 进程启动前运行次回调
     * @param Worker|null $worker
     * @return void
     */
    public static function start(?Worker $worker): void
    {
        foreach (self::cases() as $eventEnum) {
            foreach (self::getEvents($eventEnum) as $callback) {
                self::on($eventEnum, $callback);
            }
        }
    }

    /**
     * @param EventEnum $enum
     * @return array
     */
    protected static function getEvents(EventEnum $enum): array
    {
        $events = [];
        foreach (EventEnum::observers($enum) as $observer) {
            $callbacks = self::convertCallable($observer);
            if (is_callable($callbacks)) {
                $events[] = $callbacks;
            }
        }
        return $events;
    }

    /**
     * @param callable|array|Closure $callbacks
     * @return callable|array|Closure
     */
    protected static function convertCallable(callable|array|Closure $callbacks): callable|array|Closure
    {
        if (is_array($callbacks)) {
            $callback = array_values($callbacks);
            if (isset($callback[1]) && is_string($callback[0]) && class_exists($callback[0])) {
                return [Container::get($callback[0]), $callback[1]];
            }
        }
        return $callbacks;
    }

    /**
     * 注册观察者
     * @param EventEnum $eventEnum
     * @param callable $listener
     * @return int
     */
    public static function on(EventEnum $eventEnum, callable $listener): int
    {
        return Event::on($eventEnum->name, $listener);
    }

    /**
     * 触发事件
     * - 异常时捕获
     * @param EventEnum $eventEnum
     * @param mixed $data
     * @param bool $halt
     * @return array|mixed|null
     */
    public static function emit(EventEnum $eventEnum, mixed $data, bool $halt = false): mixed
    {
        return Event::emit($eventEnum->name, $data, $halt);
    }

    /**
     * 触发事件
     * - 异常时抛出
     * @param EventEnum $eventEnum
     * @param mixed $data
     * @param bool $halt
     * @return array|mixed|null
     */
    public static function dispatch(EventEnum $eventEnum, mixed $data, bool $halt = false): mixed
    {
        return Event::dispatch($eventEnum->name, $data, $halt);
    }
}
