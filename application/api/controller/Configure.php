<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/13 0:06
 * Email: 1183@mapgoo.net
 */

namespace app\api\controller;


use app\api\server\ConfigureServer;
use think\Request;

class Configure extends Base
{
    public function setting()
    {

    }

    public function settingInfo()
    {

    }

    public function theme(Request $request)
    {
        $response = (new ConfigureServer())->theme();

        ajax_info(0,'success', $response);
    }

    public function ring(Request $request)
    {
        $noise = $request->param('noise', 0, 'intval') ? 1 : 0;

        $response = (new ConfigureServer())->ring($noise);

        ajax_info(0,'success', $response);
    }
}