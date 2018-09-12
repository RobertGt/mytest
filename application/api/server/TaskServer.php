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
}