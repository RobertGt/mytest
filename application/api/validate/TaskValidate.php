<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/12 23:41
 * Email: 1183@mapgoo.net
 */

namespace app\api\validate;


use think\Validate;

class TaskValidate extends Validate
{
    protected $rule = [
        'imei'        => 'require',
        'taskName'    => 'checkTaskName',
        'colour'      => 'require',
        'iconId'      => 'checkIconId',
        'taskId'      => 'checkTaskId'
    ];

    protected $message  =   [

    ];

    protected $scene = [
        'taskList'       =>  ['imei'],
        'taskCreate'     =>  ['imei', 'taskName', 'colour', 'iconId'],
        'taskDelete'     =>  ['imei', 'taskId'],
        'taskEdit'       =>  ['taskName', 'colour', 'iconId', 'taskId'],
    ];

    public function checkTaskName($taskName, $rule, $data){
        if(!$taskName){
            return '任务名称不能为空';
        }

        if(mb_strlen($taskName) > 15){
            return '任务名称不得超过15位字符';
        }

        return true;
    }

    public function checkIconId($iconId, $rule, $data)
    {
        if(!$iconId || !authcode($iconId)){
            return 'icon错误';
        }

        return true;
    }

    public function checkTaskId($taskId, $rule, $data)
    {
        if(!$taskId || !authcode($taskId)){
            return 'taskId错误';
        }

        return true;
    }
}