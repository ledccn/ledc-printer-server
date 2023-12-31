<?php

namespace Ledc\Printer\Services;

use GatewayWorker\Lib\Gateway;
use Ledc\Printer\Model\GatewayOnline;

/**
 * 在线设备
 */
class GatewayOnlineServices
{
    /**
     * 更新客户端ID
     * @param int $app_id
     * @param string $client_id
     * @return GatewayOnline|null
     */
    public static function updateClientId(int $app_id, string $client_id): ?GatewayOnline
    {
        $model = GatewayOnline::getByAppId($app_id);
        if ($model instanceof GatewayOnline) {
            $model->client_id = $client_id;
            $model->save();
        }

        return null;
    }

    /**
     * 更新客户端在线
     * @param int $app_id
     * @param string $client_id
     * @param bool $online
     * @return GatewayOnline|null
     */
    public static function updateOnline(int $app_id, string $client_id, bool $online): ?GatewayOnline
    {
        $gatewayOnline = GatewayOnline::getByAppId($app_id);
        if ($gatewayOnline instanceof GatewayOnline) {
            $gatewayOnline->client_id = $client_id;
            $gatewayOnline->last_ping = time();
            $gatewayOnline->online = $online ? 1 : 0;
            $gatewayOnline->save();
        }

        return $gatewayOnline;
    }

    /**
     * 判断打印机是否在线
     * - 长连接在线 && 菜鸟组件在线
     * @param int $app_id
     * @return bool
     */
    public static function canPrinterOnline(int $app_id): bool
    {
        $uid = ApplicationServices::builderGatewayWorkerUid($app_id);
        foreach (Gateway::getClientIdByUid($uid) as $client_id) {
            $session = Gateway::getSession($client_id);
            if ($session['online'] ?? null) {
                return true;
            }
        }

        return false;
    }
}
