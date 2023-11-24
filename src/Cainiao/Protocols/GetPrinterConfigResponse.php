<?php

namespace Ledc\Printer\Cainiao\Protocols;

use Ledc\Printer\Cainiao\ResponseProtocols;

/**
 * 【响应】获取打印机配置(getPrinterConfig)
 */
class GetPrinterConfigResponse extends ResponseProtocols
{
    /**
     * @var string
     */
    public string $status = '';
    /**
     * @var string
     */
    public string $msg = '';
    /**
     * @var array
     */
    protected array $printer = [];

    /**
     * @param string|null $field
     * @param mixed|null $default
     * @return mixed
     */
    public function getPrinter(string $field = null, mixed $default = null): mixed
    {
        //printer.name	string	打印机名称
        //printer.needTopLogo	bool	是否需要模板上联的快递logo
        //true为需要
        //false为不需要

        //printer.needBottomLogo	bool	是否需要模板下联的快递logo
        //true为需要
        //false为不需要

        //printer.horizontalOffset	float	水平偏移量
        //printer.verticalOffset	float	垂直偏移量
        //printer.forceNoPageMargins	bool	强制设置页面无空边
        //true为强制设置页面无空边
        //false为由打印机驱动决定

        //printer.paperSize.width	int	打印机纸张的宽度，单位是毫米
        //printer.paperSize.height	int	打印机纸张的高度，单位是毫米
        //printer. autoPageSize	bool	true：自适应纸张大小
        //false：不自适应

        //printer. orientation	int	0：纵向 1： 横向
        //printer. autoOrientation	bool	true：按照 orientation 适应纸张方向
        //false：不自适应

        return static::get($this->printer, $field, $default);
    }
}
