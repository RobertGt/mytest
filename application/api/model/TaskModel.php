<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/13 0:11
 * Email: 1183@mapgoo.net
 */

namespace app\api\model;


use think\Model;

class TaskModel extends Model
{
    protected $table = 'bas_task';
    protected $pk = 'taskId';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'createTime';
    protected $updateTime = 'updateTime';
    protected $iconModel = 'bas_icon';

    public function taskList($imei = '', $pageNum = 1, $pageSize = 10)
    {
        $where = [];
        if($imei){
            $where['imei'] = $imei;
        }
        $task = $this->alias('t')
                    ->join($this->iconModel . ' i' , 't.iconId = i.iconId' , 'LEFT')
                    ->field('t.taskId, t.taskName, t.colour, i.iconUrl')
                    ->page($pageNum, $pageSize)
                    ->order('sort DESC, updateTime DESC')
                    ->select();
        return $task;
    }
}