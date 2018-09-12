<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/12 23:21
 * Email: 1183@mapgoo.net
 */

namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\Log;

class Http extends Handle
{
    public function render(Exception $e){

        if ($e instanceof HttpException) {
            ajax_info($e->getStatusCode(), $e->getMessage());
        }
        Log::error('Request ' . 'ErrorMsg : ' . $e->getMessage());
        //交由系统处理
        //return parent::render($e);
        ajax_info(500, '未知错误!!!');
    }
}