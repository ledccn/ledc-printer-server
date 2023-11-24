<?php

namespace Ledc\Printer\Services;

use Ledc\Printer\Model\Application;
use plugin\admin\app\model\User as WaUserByPluginAdmin;
use plugin\user\app\model\User as WaUserByPluginUser;

/**
 * 是打印请求
 * @property Application|null $application
 * @property WaUserByPluginAdmin|WaUserByPluginUser|null $user
 */
trait HasPrinterRequest
{
}
