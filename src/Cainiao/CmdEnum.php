<?php

namespace Ledc\Printer\Cainiao;

/**
 * 请求命令
 * @link https://support-cnkuaidi.taobao.com/doc.htm?spm=a219a.7629140.0.0.180d75fepcAdKQ#?docId=107014&docType=1
 */
enum CmdEnum: string
{
    /**
     * 打印代理组件调度响应（自定义协议）
     */
    case ledcPrintProxy = 'ledcPrintProxy';
    /**
     * 获取打印机列表(getPrinters)
     */
    case getPrinters = 'getPrinters';

    /**
     * 获取打印机配置(getPrinterConfig)
     */
    case getPrinterConfig = 'getPrinterConfig';

    /**
     * 设置打印机配置(setPrinterConfig)
     */
    case setPrinterConfig = 'setPrinterConfig';

    /**
     * 发送打印/预览数据协议(print)
     * - 注：因为打印机质量乘次不齐，建议 1 个 task 使用 一个 document，可以有效避免重打问题；
     */
    case print = 'print';

    /**
     * 打印通知(notifyTaskResult)
     */
    case notifyTaskResult = 'notifyTaskResult';

    /**
     * 打印通知(notifyDocResult)
     */
    case notifyDocResult = 'notifyDocResult';

    /**
     * 打印通知(notifyPrintResult)
     */
    case notifyPrintResult = 'notifyPrintResult';

    /**
     * 获取任务打印任务状态(getTaskStatus)
     */
    case getTaskStatus = 'getTaskStatus';

    /**
     * 获取全局配置(getGlobalConfig)
     */
    case getGlobalConfig = 'getGlobalConfig';

    /**
     * 设置全局配置(setGlobalConfig)
     */
    case setGlobalConfig = 'setGlobalConfig';

    /**
     * 获取客户端版本信息(getAgentInfo)
     */
    case getAgentInfo = 'getAgentInfo';
}
