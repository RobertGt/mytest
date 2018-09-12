<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/12 23:38
 * Email: 1183@mapgoo.net
 */

namespace app\api\controller;


use app\api\server\TaskServer;
use app\api\validate\TaskValidate;
use think\Request;

class Task extends Base
{
    public function taskList(Request $request)
    {
        $param = [
            'imei'     => $request->param('imei',''),
            'pageNum'  => $request->param('pageNum',1,'intval'),
            'pageSize' => $request->param('pageSize',10,'intval'),
        ];

        $validate = new TaskValidate();
        if(!$validate->scene('taskList')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new TaskServer())->taskList($param);

        ajax_info(0,'success', $response);
    }

    public function taskCreate(Request $request)
    {
        $param = [
            'imei'     => $request->param('imei',''),
            'taskName' => $request->param('taskName',''),
            'iconId'   => $request->param('iconId',0, 'intval'),
            'colour'   => $request->param('colour','')
        ];

        $validate = new TaskValidate();
        if(!$validate->scene('taskCreate')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new TaskServer())->taskCreate($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(0,'任务建立失败');
        }
    }

    public function taskDelete(Request $request)
    {
        $param = [
            'imei'     => $request->param('imei',''),
            'taskId'   => $request->param('taskId','')
        ];

        $validate = new TaskValidate();
        if(!$validate->scene('taskDelete')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new TaskServer())->taskDelete($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(0,'任务删除失败');
        }
    }

    public function taskUpdate(Request $request)
    {
        $param = [
            'taskName' => $request->param('taskName',''),
            'iconId'   => $request->param('iconId',0, 'intval'),
            'colour'   => $request->param('colour',''),
            'taskId'   => $request->param('taskId','')
        ];

        $validate = new TaskValidate();
        if(!$validate->scene('taskEdit')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new TaskServer())->taskUpdate($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(0,'任务修改失败');
        }
    }

    public function taskIcon(Request $request)
    {
        $response = (new TaskServer())->taskIcon();

        ajax_info(0,'success', $response);
    }

    public function taskSort(Request $request)
    {

    }

    public function taskNext(Request $request)
    {

    }

    public function taskUpload(Request $request)
    {

    }
}