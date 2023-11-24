<?php
/**
 * 路由配置
 * @link https://www.workerman.net/doc/webman/route.html
 */

use Ledc\Printer\Cainiao\CmdEnum;
use Ledc\Printer\Helper;
use Ledc\Printer\Middleware;
use Ledc\Printer\Services\ApplicationServices;
use Ledc\Printer\Services\HostConfig;
use Ledc\Printer\Services\NotifyHandler;
use Ledc\Printer\Services\QueuePrintServices;
use Ledc\Printer\Support\JsonResponse;
use support\Log;
use support\Request;
use Webman\Route;

//字典
Route::get('/dict/{method}', [Helper::class, 'dict']);

Route::group(config(plugin_ledc_printer_app . '.route_prefix'), function () {
    //获取主机配置
    Route::get('/config', function (Request $request) {
        $config = new HostConfig(config(plugin_ledc_printer_app . '.host'), config(plugin_ledc_printer_app . '.route_prefix'));
        $config->setWebsocket(config(plugin_ledc_printer_app . '.websocket'))
            ->setBindUrl('/bind')
            ->setNotifyUrl('/notify')->setCallbackUrl($request->application->callback_url);
        return json($config->toArray());
    });

    //用户绑定
    Route::get('/bind', function (Request $request) {
        try {
            return JsonResponse::getInstance()->success('ok', ['success' => ApplicationServices::bindUid($request->get(null), $request)]);
        } catch (Throwable $throwable) {
            return JsonResponse::getInstance()->fail($throwable->getMessage());
        }
    });

    //打印
    Route::post('/print', function (Request $request) {
        $data = $request->post();
        try {
            $queuePrint = QueuePrintServices::create($data, $request->application);
            return JsonResponse::getInstance()->success('ok', $queuePrint->only(['app_id', 'origin_id', 'task_id', 'id']));
        } catch (Throwable $throwable) {
            return JsonResponse::getInstance()->fail($throwable->getMessage());
        }
    });

    //通知地址
    Route::post('/notify', function (Request $request) {
        try {
            $cmd = $request->post('cmd', '');
            $cmdEnum = CmdEnum::from($cmd);
            if ($request->header('sync')) {
                $rs = NotifyHandler::process($request->application, $cmdEnum, $request->post());
            } else {
                $rs = NotifyHandler::dispatch([$request->application->app_id, $cmdEnum->value, $request->post()]);
            }

            return JsonResponse::getInstance()->success('ok', ['success' => $rs]);
        } catch (Error|Exception|Throwable $throwable) {
            Log::notice('菜鸟打印组件通知【请求头】：', $request->header());
            Log::info('菜鸟打印组件通知【数据包】：', $request->all());
            return JsonResponse::getInstance()->fail($throwable->getMessage());
        }
    });

    //字典
    Route::get('/dict/{method}', function (Request $request, string $method = '') {
        return Helper::dict($request, $method);
    })->setParams([Middleware::noNeedAuth => true]);

})->middleware([Middleware::class]);
