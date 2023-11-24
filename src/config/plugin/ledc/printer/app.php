<?php

//插件的配置键名
if (!defined('plugin_ledc_printer_app')) {
    define('plugin_ledc_printer_app', 'plugin.ledc.printer.app');
}

return [
    'enable' => true,
    // 路由前缀
    'route_prefix' => '/printer',
    //主机+端口
    'host' => 'http://hnshengzhong.cn',
    //WebSocket地址
    'websocket' => 'ws://hnshengzhong.cn/wss',
];
