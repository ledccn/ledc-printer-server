<?php

namespace Ledc\Printer\Controller;

use Ledc\Printer\Helper;
use Ledc\Printer\Model\Enum\EnabledEnum;
use Ledc\Printer\Model\Enum\OnlineEnum;
use Ledc\Printer\Model\Enum\PrintStatusEnum;
use Ledc\Printer\Support\JsonResponse;
use support\Request;
use support\Response;

/**
 * 字典
 */
class DictController
{
    /**
     * 返回枚举响应
     * @param array $select
     * @return Response
     */
    private function successEnum(array $select): Response
    {
        return JsonResponse::getInstance()->success('ok', Helper::formatSelectEnum($select));
    }

    /**
     * 默认
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return JsonResponse::getInstance()->success();
    }

    /**
     * 打印状态
     * - 打印任务的打印状态
     * @param Request $request
     * @return Response
     */
    public function printStatus(Request $request): Response
    {
        return $this->successEnum(PrintStatusEnum::select());
    }

    /**
     * 在线状态
     * - gateway的在线状态
     * @param Request $request
     * @return Response
     */
    public function online(Request $request): Response
    {
        return $this->successEnum(OnlineEnum::select());
    }

    /**
     * 启用状态
     * - 应用的启用状态
     * @param Request $request
     * @return Response
     */
    public function enabled(Request $request): Response
    {
        return $this->successEnum(EnabledEnum::select());
    }
}
