<?php

namespace Ledc\Printer\Model;

use Illuminate\Database\Eloquent\Builder;
use plugin\admin\app\model\Base;

/**
 * 打印队列
 * @property int $id 主键
 * @property int $app_id 用户凭证（外鍵）
 * @property string $origin_id 商户订单号（索引）
 * @property int $task_id 打印机任务ID
 * @property string $printer 打印机名
 * @property string $documents 原始报文
 * @property int $preview 是否预览
 * @property int $task_type 任务类型（索引）
 * @property int $status 打印状态（索引）
 * @property string $msg 消息描述
 * @property int $dispatched 调度时间
 * @property int $notify_time 通知时间
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class QueuePrint extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pr_queue_print';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 联合唯一
     * @param int $app_id
     * @param string $origin_id
     * @param int $task_id
     * @return array
     */
    public static function uniqueWhere(int $app_id, string $origin_id, int $task_id): array
    {
        return [
            'app_id' => $app_id,
            'origin_id' => $origin_id,
            'task_id' => $task_id,
        ];
    }

    /**
     * 是否重复
     * @param int $app_id
     * @param string $origin_id
     * @param int $task_id
     * @return bool
     */
    public static function canExists(int $app_id, string $origin_id, int $task_id): bool
    {
        $where = self::uniqueWhere($app_id, $origin_id, $task_id);
        return static::where($where)->exists();
    }

    /**
     * 获取builder查询构造器
     * @param int $app_id
     * @param int $status
     * @return Builder
     */
    public static function getBuilderAsc(int $app_id, int $status): Builder
    {
        return static::where('app_id', '=', $app_id)
            ->where('status', '=', $status)
            ->orderBy('id', 'asc');
    }

    /**
     * 获取builder查询构造器
     * @param int $id 主键（请求ID）
     * @param int $app_id 应用ID
     * @return Builder
     */
    public static function getBuilderById(int $id, int $app_id): Builder
    {
        return static::where('id', '=', $id)->where('app_id', '=', $app_id);
    }
}
