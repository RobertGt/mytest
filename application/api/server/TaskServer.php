<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/13 0:14
 * Email: 1183@mapgoo.net
 */

namespace app\api\server;


use app\api\model\IconModel;
use app\api\model\TaskModel;
use think\Exception;
use think\Log;

class TaskServer
{
    public function taskList($param = [])
    {
        $taskList = (new TaskModel())->taskList($param['imei'], $param['pageNum'], $param['pageSize']);

        $task = [];
        foreach ($taskList as $value){
            $info = $value->getData();
            $info['taskId'] = authcode($info['taskId'], 'ENCODE');
            $info['iconUrl'] = urlCompletion($value['iconUrl']);
            $task[] = $info;
        }

        return $task;
    }


    public function taskCreate($param = [])
    {
        try{
            (new TaskModel())->create($param);
        }catch (Exception $e){
            Log::error("taskCreate error:" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function taskDelete($param = [])
    {
        try{
            $where['taskId'] = authcode($param['taskId']);
            $where['imei'] = $param['imei'];
            (new TaskModel())->where($where)->delete();
        }catch (Exception $e){
            Log::error("taskDelete error:" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function taskUpdate($param = [])
    {
        try{
            $where['taskId'] = authcode($param['taskId']);
            $save['taskName'] = $param['taskName'];
            $save['colour'] = $param['colour'];
            $save['iconId'] = $param['iconId'];

            (new TaskModel())->save($save , $where);
        }catch (Exception $e){
            Log::error("taskDelete error:" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function taskIcon()
    {
        $icon = [];
        $taskIcon = (new IconModel())->where('isDelete', 0)->field('iconId, name, iconUrl')->order('sort DESC, updateTime DESC')->select();
        foreach ($taskIcon as $value){
            $info = $value->getData();
            $info['iconId'] = $info['iconId'];
            $info['iconUrl'] = urlCompletion($info['iconUrl']);

            $icon[] = $info;
        }
        
        return $info;
    }

    public function taskUpload($param = [])
    {
        $taskModel = new TaskModel();
        $where['taskId'] = authcode($param['taskId']);
        $task = $taskModel->field('imei')->where($where)->find();
        if(!$task){
            Log::info("taskUpload not find task:" . $where['taskId'] );
            return false;
        }
        $task = $task->getData();
        $create['imei']      = $task['imei'];
        $create['taskId']    = $where['taskId'];
        $create['second']    = $param['second'];
        $create['absorbed']  = $param['absorbed'] ? 1 : 0;
        $create['remark']    = $param['remark'];

        try{
            $taskModel->create($create);
        }catch (Exception $e){
            Log::error("taskUpload error:" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function taskNext($taskId = "")
    {
        $taskModel = new TaskModel();
        $where['taskId'] = authcode($taskId);
        $task = $taskModel->field('imei, sort, updateTime')->where($where)->find();
        if(!$task){
            Log::info("taskNext not find task:" . $where['taskId'] );
            return [];
        }
        $task = $task->getData();
        $where = [
            't.imei'        => $task['imei'],
            't.sort'        => ['elt', $task['sort']],
            't.updateTime'  => ['lt', $task['updateTime']]
        ];

        $taskInfo = $taskModel->taskInfo($where);

        if(!$taskInfo){
            return [];
        }

        $taskInfo = $taskInfo->getData();
        $taskInfo['taskId'] = authcode($taskInfo['taskId'], 'ENCODE');

        return $taskInfo;
    }

    public function taskSort($param = [])
    {
        $taskModel = new TaskModel();

        $taskId = authcode($param['taskId']);

        $afterId = $param['afterId'] ? authcode($param['afterId']) : 0;

        $taskList =  $taskModel->imeiTaskListByTaskId($taskId);

        $task = [];
        $moveKey = 0;
        $afterKey = 0;
        $moveInfo = [];
        $count = count($taskList);
        foreach ($taskList as $key => $value){
            $info = $value->getData();
            if($value['taskId'] == $taskId){
                $moveKey = $key;
                $moveInfo = $info;
                continue;
            }

            if($value['taskId'] == $afterId){
                $afterKey = $key;
            }

            $task[$key] = $info;
        }

        if(!$moveKey && !$afterKey && !$moveInfo){
            return true;
        }

        if(!$afterId){
            array_unshift($task, $moveInfo);
        }else{
            array_splice($task, $afterKey, 0, $moveInfo);
        }

        try{
            $taskModel->startTrans();
            foreach ($task as $value){
                $taskModel->save(['sort' => $count--], ['taskId' => $value['taskId']]);
            }
            $taskModel->commit();
        }catch (Exception $e){
            $taskModel->rollback();
            Log::error("taskSort error:" . $e->getMessage());
            return false;
        }
        return true;
    }
}