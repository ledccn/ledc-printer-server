<?php

namespace Ledc\Printer\Cainiao\Protocols;

use JsonSerializable;

/**
 * 发送打印/预览数据协议
 * - task字段
 */
class PrintsTask implements JsonSerializable
{
    /**
     * 打印机任务ID，每个打印任务会分配不同的且唯一的ID
     * @var string
     */
    public string $taskID;

    /**
     * 是否预览.
     * - true为预览,false为打印
     * @var bool
     */
    public bool $preview = false;

    /**
     * 打印机名，如果为空，会使用默认打印机
     * @var string|null
     */
    public ?string $printer = '';

    /**
     * 属性取值“pdf” or “image” 预览模式，是以pdf还是image方式预览，二选一，此属性不是必选，默认以pdf预览。
     * @var string|null
     */
    public ?string $previewType = null;

    /**
     * 通知模式
     * @var string
     */
    public string $notifyMode = 'allInOne';

    /**
     * task 起始 document 序号
     * @var int|null
     */
    public ?int $firstDocumentNumber = null;

    /**
     * task document 总数
     * @var int|null
     */
    public ?int $totalDocumentCount = null;

    /**
     * 文档数组，每个数据表示一页
     * @var array
     */
    public array $documents = [];

    /**
     * 打印通知类型:“render”, “print”
     * - [“render”] : 仅渲染响应 notify
     * - [“print”] : 仅出纸响应 notify
     * - “render”, “print” : 渲染完成会响应 notify && 出纸完成后会响应 notify
     * - [] : 不允许
     * - 注:如果notifyType没有指定，默认为[“render”, “print”]
     * @var array|null
     */
    public ?array $notifyType = ['print'];

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param array $documents
     * @return PrintsTask
     */
    public function setDocuments(array $documents): self
    {
        //		"documents": [{
        //			"documentID": "0123456789",
        //			"contents": [{
        //				"data": {
        //					"nick": "张三"
        //				},
        //				"templateURL": "http://cloudprint.cainiao.com/template/standard/278250/1"
        //			},
        //			{
        //				"data": {
        //					"value": "测试字段值需要配合自定义区变量名"
        //				},
        //				"templateURL": "http://cloudprint.cainiao.com/template/customArea/440439"
        //			}]
        //		}]

        $this->documents = $documents;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        //	"task": {
        //		"taskID": "7293666",
        //		"preview": false,
        //		"printer": "",
        //		"previewType": "pdf",
        //		"firstDocumentNumber": 10,
        //		"totalDocumentCount": 100,
        //		"documents": [{
        //			"documentID": "0123456789",
        //			"contents": [{
        //				"data": {
        //					"nick": "张三"
        //				},
        //				"templateURL": "http://cloudprint.cainiao.com/template/standard/278250/1"
        //			},
        //			{
        //				"data": {
        //					"value": "测试字段值需要配合自定义区变量名"
        //				},
        //				"templateURL": "http://cloudprint.cainiao.com/template/customArea/440439"
        //			}]
        //		}]
        //	}
        return array_filter(get_object_vars($this), function ($v) {
            return (null !== $v && '' !== $v);
        });
    }
}
