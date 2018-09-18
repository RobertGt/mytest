<?php
namespace app\api\controller;

use app\admin\server\AdminServer;
use app\admin\validate\LoginValidate;
use think\Request;

class Index
{
    public function login(Request $request)
    {
        $param = [
            'account'     => $request->param('username',''),
            'password'    => $request->param('password','')
        ];

        $validate = new LoginValidate();
        if(!$validate->scene('login')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new AdminServer())->login($param);

        if($response){
            ajax_info(0,'success', $response);
        }else{
            ajax_info(1,'账号不存在或者密码错误');
        }
    }

    public function reset(Request $request)
    {
        $param = [
            'account'        => $request->param('username',''),
            'password'       => $request->param('password',''),
            'newPassword'    => $request->param('newPassword',''),
            'repeatPassword' => $request->param('repeatPassword','')
        ];

        $validate = new LoginValidate();
        if(!$validate->scene('reset')->check($param)){
            ajax_info(1 , $validate->getError());
        }

        $response = (new AdminServer())->reset($param);

        if($response){
            ajax_info(0,'success');
        }else{
            ajax_info(1,'重置密码失败');
        }
    }
}
