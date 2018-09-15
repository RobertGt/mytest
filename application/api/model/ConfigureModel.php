<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/16 0:38
 * Email: 1183@mapgoo.net
 */

namespace app\api\model;


use think\Model;

class ConfigureModel extends Model
{
    protected $table = 'bas_configure';
    protected $pk = 'imei';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'createTime';
    protected $updateTime = 'updateTime';
    protected $ringModel = 'bas_ring';
    protected $themeModel = 'bas_theme';

    public function settingInfo($imei = '')
    {
        $field = "c.theme, t.taskColour, t.restColour, r1.taskFinish, r1.ringName taskFinishRing, r1.ringUrl taskRingUrl, 
                   t.restFinish, r2.ringName restFinishRing, r2.ringUrl restRingUrl, r3.noise, r3.ringName noiseRing, r3.ringUrl noiseRingUrl, 
                   t.taskTime, t.sortRest, t.longRest, t.taskNum, t.autoNext, t.screenOn, t.shockOn, t.strict";
        $setting = $this->alias('c')
                        ->join($this->themeModel . ' t' , 't.theme = c.theme', 'LEFT')
                        ->join($this->ringModel . ' r1' , 'r1.ringId = c.taskFinish', 'LEFT')
                        ->join($this->ringModel . ' r2' , 'r2.ringId = c.restFinish', 'LEFT')
                        ->join($this->ringModel . ' r3' , 'r3.ringId = c.noise', 'LEFT')
                        ->where(['imei' => $imei])
                        ->field($field)
                        ->find();
        return $setting;
    }
}