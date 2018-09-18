<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/18 23:50
 * Email: 1183@mapgoo.net
 */

namespace app\admin\validate;


use app\admin\server\AdminServer;
use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'account'        => 'require|max:10',
        'password'       => 'require|min:6',
        'newPassword'    => 'require|min:6|checkPassword',
        'repeatPassword' => 'require|min:6',
    ];

    protected $scene = [
        'login'        =>  ['account', 'password'],
        'reset'        =>  ['account', 'password', 'newPassword', 'repeatPassword']
    ];

    public function checkPassword($newPassword, $rule, $data)
    {
        $checkPassword = (new AdminServer())->checkPassword($data['account'], $data['password']);

        if(!$checkPassword){
            return "原密码错误";
        }

        return true;
    }
}