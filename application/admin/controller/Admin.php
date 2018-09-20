<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/21 0:57
 * Email: 1183@mapgoo.net
 */

namespace app\admin\controller;


use app\admin\server\AdminServer;
use app\admin\validate\LoginValidate;
use think\Request;

class Admin extends Base
{
    public function adminList(Request $request)
    {
        $param = [
            'seach'    => $request->param('seach/a',[]),
            'pageNum'  => $request->param('pageNum',1,'intval'),
            'pageSize' => $request->param('pageSize',10,'intval'),
        ];

        $response = (new AdminServer())->adminList($param);

        ajax_info(0,'success', $response);
    }

    public function adminDelete(Request $request)
    {
        $param = [
            'aid'  => $request->param('id',0, 'intval')
        ];

        $response = (new AdminServer())->adminDelete($param['aid']);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(1,'操作失败');
        }
    }

    public function adminInsert(Request $request)
    {
        $param = [
            'account'  => $request->param('account',''),
            'password' => $request->param('password',''),
            'remark'   => $request->param('remark','')
        ];

        $validate = new LoginValidate();
        if(!$validate->scene('insert')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new AdminServer())->adminInsert($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(1,'添加失败');
        }
    }

    public function adminUpdate(Request $request)
    {
        $param = [
            'aid'       => $request->param('id',0, 'intval'),
            'account'  => $request->param('account',''),
            'password' => $request->param('password',''),
            'remark'   => $request->param('remark','')
        ];

        $validate = new LoginValidate();
        if(!$validate->scene('update')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new AdminServer())->adminUpdate($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(1,'修改失败');
        }
    }
}