<?php

namespace Ledc\Printer\Model\Enum;

/**
 * 启用状态枚举
 */
enum EnabledEnum: int
{
    /**
     * 启用
     */
    case on = 1;
    /**
     * 禁止
     */
    case off = 0;

    /**
     * @param self $enum
     * @return string
     */
    public static function text(self $enum): string
    {
        return match ($enum) {
            self::on => '启用',
            self::off => '禁止',
        };
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
