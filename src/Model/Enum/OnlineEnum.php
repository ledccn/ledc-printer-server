<?php

namespace Ledc\Printer\Model\Enum;

/**
 * 在线状态枚举
 */
enum OnlineEnum: int
{
    /**
     * 在线
     */
    case yes = 1;
    /**
     * 离线
     */
    case no = 0;

    /**
     * @param self $enum
     * @return string
     */
    public static function text(self $enum): string
    {
        return match ($enum) {
            self::yes => '在线',
            self::no => '离线',
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
