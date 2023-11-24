<?php

namespace Ledc\Printer\Validate;

use think\Validate;

/**
 * 验证器
 * - 打印队列
 */
class QueuePrintValidate extends Validate
{
    /**
     * 场景：创建打印任务
     */
    const sceneCreate = 'create';
    /**
     * 场景：查询打印状态
     */
    const sceneStatus = 'status';
    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [
        'app_id|用户凭证' => 'require|number',
        'origin_id|商户订单号' => 'require',
        'task_id|打印任务ID' => 'require|number',
        'printer|打印机名' => 'require',
        'documents|原始报文' => 'require',
        'preview|是否预览' => 'require|in:0,1',
        'status|打印状态' => 'require|number',
    ];

    /**
     * 验证提示消息
     * @var array
     */
    protected $message = [];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [
        self::sceneCreate => ['origin_id', 'task_id', 'documents', 'preview'],
        self::sceneStatus => ['origin_id', 'task_id'],
    ];
}
