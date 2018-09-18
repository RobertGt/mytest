<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post([
    'v1/taskCreate'        => 'api/Task/taskCreate',
    'v1/taskDelete'        => 'api/Task/taskDelete',
    'v1/taskUpdate'        => 'api/Task/taskUpdate',
    'v1/taskSort'          => 'api/Task/taskSort',
    'v1/taskUpload'        => 'api/Task/taskUpload',

    'v1/articleShare'      => 'api/article/articleShare',

    'v1/setting'           => 'api/Configure/setting',

    'admin/login'          => 'api/Index/login',
    'admin/reset'          => 'api/Index/reset',
]);

Route::get([
    'v1/taskList'          => 'api/Task/taskList',
    'v1/taskIcon'          => 'api/Task/taskIcon',
    'v1/taskNext'          => 'api/Task/taskNext',

    'v1/taskStatistic'     => 'api/Statistic/taskStatistic',
    'v1/taskDistribution'  => 'api/Statistic/taskDistribution',
    'v1/taskCurve'         => 'api/Statistic/taskCurve',
    'v1/coveStatistic'     => 'api/Statistic/coveStatistic',
    'v1/coveCurve'         => 'api/Statistic/coveCurve',

    'v1/articleList'       => 'api/article/articleList',
    'v1/articleInfo'       => 'api/article/articleInfo',

    'v1/settingInfo'       => 'api/Configure/settingInfo',
    'v1/theme'             => 'api/Configure/theme',
    'v1/ring'              => 'api/Configure/ring',
]);
