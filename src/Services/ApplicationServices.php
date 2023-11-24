<?php

namespace Ledc\Printer\Services;

use Exception;
use GatewayWorker\Lib\Gateway;
use Ledc\Printer\Events\RocketBindUidSuccess;
use Ledc\Printer\Gateway\EventEnum;
use Ledc\Printer\Model\Application;
use Ledc\Printer\Support\V;
use Ledc\WorkermanProcess\Events;
use support\exception\BusinessException;
use support\Request;
use think\exception\ValidateException;

/**
 * 应用服务
 */
class ApplicationServices
{
    /**
     * 打印机长连接用户绑定前缀
     */
    const bindUserPrefix = 'printer_';

    /**
     * @param array $data
     * @param Request $request
     * @return bool
     * @throws ValidateException|BusinessException|Exception
     */
    public static function bindUid(array $data, Request $request): bool
    {
        // Step1：验证入口参数
        $rule = [
            'appid|应用ID' => 'require|number',
            'client_id|长连接客户端ID' => 'require',
            'time|时间戳' => 'require|number',
            'sign|签名' => 'require',
            'auth|长连接验证参数' => 'require',
        ];
        $v = V::validate($rule);
        $v->check($data);
        $client_id = $data['client_id'];

        // Step2：查找应用
        $appid = $data['appid'];
        $model = self::find($appid);

        // Step3：验证签名
        $origin = [
            'appid' => $appid,
            'client_id' => $client_id,
            'time' => $data['time'],
            'token' => $model->app_secret,
        ];
        if ($data['sign'] !== md5(http_build_query($origin))) {
            throw new BusinessException('签名错误');
        }

        // Step4：绑定uid
        $uid = self::builderGatewayWorkerUid($data['appid']);
        if (Gateway::getClientIdByUid($uid)) {
            throw new BusinessException('仅允许一个客户端在线');
        }
        if (Events::bindUid($client_id, $uid, $data['auth'])) {
            //更新最后登录时间
            $now = time();
            $model->last_time = date('Y-m-d H:i:s', $now);
            $model->save();

            //更新设备在线
            GatewayOnlineServices::updateClientId($appid, $client_id);

            //更新长连接Session
            $session = Gateway::getSession($client_id);
            $session['app_id'] = $appid;
            $session['login_time'] = $now;
            Gateway::updateSession($client_id, $session);

            //调度事件
            EventEnum::emit(EventEnum::bindUidSuccess, new RocketBindUidSuccess($client_id, $uid, $model));
        }

        return true;
    }

    /**
     * 构造用户UID
     * - 用于GatewayWorker长连接绑定
     * @param int|string $app_id 应用表主键
     * @return string
     */
    final public static function builderGatewayWorkerUid(int|string $app_id): string
    {
        return self::bindUserPrefix . $app_id;
    }

    /**
     * 查询应用
     * @param int $app_id
     * @return Application
     * @throws BusinessException
     */
    public static function find(int $app_id): Application
    {
        $model = Application::find($app_id);
        if (!$model) {
            throw new BusinessException('应用不存在');
        }
        if (!$model->enabled) {
            throw new BusinessException('应用被禁用');
        }

        return $model;
    }
}
