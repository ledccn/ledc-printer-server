<?php

namespace Ledc\Printer\Events;

use Ledc\Printer\Model\GatewayOnline;
use Ledc\Printer\Services\GatewayOnlineServices;
use Ledc\Printer\Services\QueuePrintServices;

/**
 * 观察者
 */
class Observer
{
    /**
     * @param $data
     * @param $event_name
     * @return void
     */
    public function onConnect($data, $event_name): void
    {
        //var_dump([$data, $event_name]);
    }

    /**
     * @param string $client_id
     * @param string $event_name
     * @return void
     */
    public function onMessagePing(string $client_id, string $event_name): void
    {
        //绑定成功，才有值
        $app_id = $_SESSION['app_id'] ?? null;
        $online = $_SESSION['online'] ?? null;
        if (empty($app_id)) {
            return;
        }

        //更新设备在线
        $gatewayOnline = GatewayOnline::getByAppId($app_id);
        if ($gatewayOnline instanceof GatewayOnline) {
            $gatewayOnline->client_id = $client_id;
            $gatewayOnline->last_ping = time();
            $gatewayOnline->online = $online ? 1 : 0;
            $gatewayOnline->save();
        }

        //是否能打印
        QueuePrintServices::canPrintByBusinessWorker($app_id, $client_id, $_SESSION);
    }

    /**
     * @param string $client_id
     * @param string $event_name
     * @return void
     */
    public function onClose(string $client_id, string $event_name): void
    {
        //绑定成功，才有值
        $app_id = $_SESSION['app_id'] ?? null;
        if (empty($app_id)) {
            return;
        }

        //更新设备在线
        GatewayOnlineServices::updateClientId($app_id, '');
    }

    /**
     * @param RocketBindUidSuccess $rocket
     * @param string $event_name
     * @return void
     */
    public function bindUidSuccess(RocketBindUidSuccess $rocket, string $event_name): void
    {
    }

    /**
     * @param $data
     * @param string $event_name
     * @return void
     */
    public function joinGroupSuccess($data, string $event_name): void
    {
        var_dump([$data, $event_name]);
    }
}
