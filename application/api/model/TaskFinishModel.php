<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/13 23:58
 * Email: 1183@mapgoo.net
 */

namespace app\api\model;


use think\Model;

class TaskFinishModel extends Model
{
    protected $table = 'bas_task_finish';
    protected $pk = 'recId';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'uploadTime';
    protected $updateTime = '';
}