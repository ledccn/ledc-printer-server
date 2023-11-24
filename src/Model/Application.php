<?php

namespace Ledc\Printer\Model;

use plugin\admin\app\model\Base;

/**
 * 应用
 * @property int $app_id 用户凭证（主键）
 * @property mixed $app_secret 用户凭证密钥
 * @property integer $user_id 用户
 * @property string $title 标题
 * @property string $description 描述
 * @property string $callback_url 打印机回调URL
 * @property integer $enabled 启用
 * @property string $last_time 最后登录
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Application extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pr_application';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'app_id';
}
