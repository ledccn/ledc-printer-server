<?php

namespace Ledc\Printer;

use Ledc\Printer\Controller\DictController;
use Ledc\Printer\Support\JsonResponse;
use support\Container;
use support\Request;
use support\Response;

/**
 * 助手
 */
class Helper
{
    /**
     * 格式化枚举选择框
     * @param array $items
     * @return array
     */
    public static function formatSelectEnum(array $items): array
    {
        $formatted_items = [];
        foreach ($items as $name => $value) {
            $formatted_items[] = [
                'name' => $name,
                'value' => $value
            ];
        }
        return $formatted_items;
    }

    /**
     * 字典
     * @param Request $request
     * @param string $method
     * @return mixed
     */
    public static function dict(Request $request, string $method = ''): Response
    {
        if (method_exists(DictController::class, $method)) {
            $controller = Container::get(DictController::class);
            if (is_callable([$controller, $method])) {
                return $controller->{$method}($request);
            }
        }
        return JsonResponse::getInstance()->fail('字典不存在');
    }
}
