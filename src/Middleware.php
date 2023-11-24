<?php

namespace Ledc\Printer;

use Ledc\Printer\Model\Application;
use Ledc\Printer\Services\ApplicationServices;
use Ledc\Printer\Support\V;
use ReflectionClass;
use support\exception\BusinessException;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

/**
 * 验证中间件
 * - 支持控制器鉴权
 * - 支持路由鉴权
 * - 支持路由向中间件传参
 */
class Middleware implements MiddlewareInterface
{
    /**
     * 无需鉴权的方法
     * - 路由传参数或控制器属性
     */
    const noNeedAuth = 'noNeedAuth';

    /**
     * @param Request|\support\Request $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request|\support\Request $request, callable $handler): Response
    {
        $controller = $request->controller;
        $action = $request->action;
        $route = $request->route;
        try {
            if ($controller) {
                $class = new ReflectionClass($controller);
                $properties = $class->getDefaultProperties();
                $noNeedAuth = $properties[self::noNeedAuth] ?? [];
                if (in_array($action, $noNeedAuth)) {
                    goto response;
                }
            } else {
                if ($route && $route->param(self::noNeedAuth)) {
                    goto response;
                }
            }
            //给请求对象添加属性：应用模型
            $request->application = $this->canAccess($request);

            response:
            $response = $request->method() === 'OPTIONS' ? response('') : $handler($request);
        } catch (Throwable $throwable) {
            $response = json(['code' => 403, 'msg' => $throwable->getMessage(), 'type' => __METHOD__]);
        }

        return $response;
    }

    /**
     * 验证请求
     * @param Request|\support\Request $request
     * @return Application
     * @throws BusinessException
     */
    protected function canAccess(Request|\support\Request $request): Application
    {
        // Step1：验证入口参数
        $authorization = $request->header('Authorization', '');
        if (empty($authorization)) {
            throw new BusinessException('请求头Authorization参数为空');
        }
        parse_str($authorization, $data);
        $rule = [
            'appid|应用ID' => 'require|number',
            'time|时间戳' => 'require|number',
            'md5|签名' => 'require'
        ];
        $v = V::validate($rule);
        $v->check($data);

        // Step2：查找应用
        $appid = $data['appid'];
        $model = ApplicationServices::find($appid);

        // Step3：验证签名
        $origin = [
            'appid' => $appid,
            'time' => $data['time'],
            'token' => $model->app_secret,
        ];
        if ($data['md5'] !== md5(http_build_query($origin))) {
            throw new BusinessException('签名错误');
        }

        return $model;
    }
}
