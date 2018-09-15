<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/15 16:41
 * Email: 1183@mapgoo.net
 */

namespace app\api\server;


use app\api\model\RingModel;
use app\api\model\ThemeModel;

class ConfigureServer
{
    public function theme()
    {
        $theme = (new ThemeModel())->themeList();

        $themeList = [];

        foreach ($theme as $value){
            $info = $value->getData();
            $themeList[] = $info;
        }

        return $themeList;
    }

    public function ring($noise = 0)
    {
        $ring = (new RingModel())->ringList($noise);

        $ringList = [];

        foreach ($ring as $value){
            $info = $value->getData();
            $info['ringUrl'] = urlCompletion($info['ringUrl']);
            $ringList[] = $info;
        }

        return $ringList;
    }
}