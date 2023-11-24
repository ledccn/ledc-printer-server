<?php

namespace Ledc\Printer\Model;

use Illuminate\Database\Eloquent\Builder;
use plugin\admin\app\model\Base;

/**
 * 在线设备
 * @property int $id 主键(主键)
 * @property int $app_id 用户凭证
 * @property string $client_id 客户端ID
 * @property int $last_ping 最后通信
 * @property int $online 菜鸟组件在线
 * @property string $version 菜鸟组件版本号
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class GatewayOnline extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pr_gateway_online';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @param int $app_id 用户凭证
     * @return Builder|self|null
     */
    public static function getByAppId(int $app_id): self|Builder|null
    {
        return static::where('app_id', '=', $app_id)->first();
    }
}
