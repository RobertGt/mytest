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
    protected $taskModel = 'bas_task';

    public function taskStatistic($imei = '', $coves = 0)
    {
        $today = strtotime(date('Y-m-d'));
        $week = strtotime(date('Y-m-d', strtotime("-1 week")));

        $absorbed = [];
        if($coves){
            $absFiled = "SUM(CASE WHEN t.taskType = 0 and f.absorbed = 1 THEN second ELSE 0 END) absCount,
                         SUM(CASE WHEN t.taskType = 0 and f.absorbed = 1 and f.uploadTime >= {$week} THEN second ELSE 0 END) absWeek,
                         SUM(CASE WHEN t.taskType = 0 and f.absorbed = 1 and f.uploadTime >= {$today} THEN second ELSE 0 END) absDay";
            $absorbed = $this->alias('f')
                    ->join($this->taskModel . ' t' , 't.taskId = f.taskId')
                    ->where(['t.imei' => $imei])
                    ->field($absFiled)
                    ->find()->toArray();
            $field = "count(*) finish, sum(f.uploadTime >= {$week} and t.taskType = 0) week, sum(f.uploadTime >= {$today} and t.taskType = 0) today";
        }else{
            $field = "count(*) finish, sum(f.uploadTime >= {$week}) week, sum(f.uploadTime >= {$today}) today";
        }

        $statistic = $this->alias('f')
                        ->join($this->taskModel . ' t' , 't.taskId = f.taskId')
                        ->where(['t.imei' => $imei])
                        ->field($field)
                        ->find()->toArray();
        return ['statistic' => $statistic, 'absorbed' => $absorbed];
    }

    public function taskDistribution($imei = '', $startTime = 0, $endTime = 0)
    {
        $where['t.imei'] = $imei;
        $where['f.uploadTime'] = [['egt', $startTime], ['lt', $endTime]];

        $distribution = $this->alias('f')
                    ->join($this->taskModel . ' t' , 't.taskId = f.taskId')
                    ->where($where)
                    ->field('max(t.taskName) taskName, sum(f.second) hour, max(colour) colour')
                    ->group("t.taskId")
                    ->select();

        return $distribution;
    }

    public function minUploadTime($imei = '')
    {
        $uploadTime = $this->where(['imei' => $imei])->order('uploadTime asc')->find();
        if($uploadTime){
            return $uploadTime->getData('uploadTime');
        }else{
            return strtotime(date('Y-m-d'));;
        }
    }

    public function taskCurve($imei = '', $startTime = 0, $endTime = 0, $coves = 0)
    {
        $where['t.imei'] = $imei;
        $where['f.uploadTime'] = [['egt', $startTime], ['lt', $endTime]];
        if($coves){
            $where['t.taskType'] = 0;
        }

        $taskCurve = $this->alias('f')
            ->join($this->taskModel . ' t' , 't.taskId = f.taskId')
            ->where($where)
            ->field("count(f.recId) total, max(f.uploadTime) uploadTime")
            ->group("FROM_UNIXTIME(f.uploadTime,'%Y%m%d')")
            ->select();

        return $taskCurve;
    }
}