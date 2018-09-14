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
        $where['isDelete'] = 0;
        if($imei){
            $where['imei'] = $imei;
        }
        $task = $this->alias('t')
                    ->join($this->iconModel . ' i' , 't.iconId = i.iconId' , 'LEFT')
                    ->field('t.taskId, t.taskName, t.colour, i.iconUrl')
                    ->page($pageNum, $pageSize)
                    ->order('t.sort DESC, t.updateTime DESC')
                    ->select();
        return $task;
    }

    public function taskInfo($where = []){
        $task = $this->alias('t')
            ->join($this->iconModel . ' i' , 't.iconId = i.iconId' , 'LEFT')
            ->field('t.taskId, t.taskName, t.colour, i.iconUrl')
            ->where($where)
            ->order('t.sort DESC, t.updateTime DESC')
            ->find();
        return $task;
    }

    public function imeiTaskListByTaskId($taskId)
    {
        $table = $this->table;
        $taskList = $this->field('taskId')->where('imei', 'IN', function ($query) use ($taskId, $table) {
                        $query->table($table)->where(['taskId' => $taskId])->field('imei');
                    })->where('isDelete', 0)->select();

        return $taskList;
    }
}