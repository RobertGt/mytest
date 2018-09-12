<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/12 23:27
 * Email: 1183@mapgoo.net
 */

namespace app\api\controller;


use think\Config;
use think\Request;

class Base
{
    public function __construct()
    {
        $this->checkSign();
    }

    /**
     * 检查签名
     */
    private function checkSign()
    {
        $request = Request::instance();

        $nonce     = $request->header('nonce', '');
        $timestamp = $request->header('timestamp', '');
        $sign      = $request->header('sign', '');

        if(!$sign){
            ajax_info(400, 'invalid signature');
        }

        $appKey = Config::get('app_key');

        if (strtoupper(md5($nonce . $timestamp . $appKey)) != strtoupper($sign)){
            ajax_info(401, 'failure of authentication');
        }
        return true;
    }
}