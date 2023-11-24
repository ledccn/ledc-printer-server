<?php

namespace Ledc\Printer\Model\Enum;

use InvalidArgumentException;

/**
 * 打印状态
 */
enum PrintStatusEnum: int
{
    /**
     * 默认
     */
    case default = 0;
    /**
     * 已调度
     */
    case dispatched = 1;
    /**
     * 处理中
     */
    case pending = 2;
    /**
     * 失败
     */
    case failed = 3;
    /**
     * 成功
     */
    case success = 4;
    /**
     * 错误
     */
    case error = 5;

    /**
     * @param self $enum
     * @return string
     */
    public static function text(self $enum): string
    {
        return match ($enum) {
            self::default => '默认',
            self::pending => '处理中',
            self::failed => '失败',
            self::success => '成功',
            self::error => '错误',
            self::dispatched => '已调度',
        };
    }

    /**
     * 获取枚举
     * @param string $name
     * @return self
     */
    public static function create(string $name): self
    {
        return self::from(self::getValue($name));
    }

    /**
     * 检查name
     * @param string $name
     * @return int
     */
    public static function getValue(string $name): int
    {
        $list = self::toArray();
        if (!array_key_exists($name, $list)) {
            throw new InvalidArgumentException('打印状态不存在');
        }

        return $list[$name];
    }

    /**
     * 枚举条目转为数组
     * - 名 => 值
     * @return array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * @return array
     */
    public static function select(): array
    {
        $rs = [];
        foreach (self::cases() as $enum) {
            $rs[self::text($enum)] = $enum->value;
        }
        return $rs;
    }
}
