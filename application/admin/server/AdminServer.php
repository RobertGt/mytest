<?php
/**
 * Created by PhpStorm.
 * Author: Robert
 * Date: 2018/9/18 23:38
 * Email: 1183@mapgoo.net
 */

namespace app\admin\server;


use app\admin\model\AdminModel;
use think\Exception;
use think\Log;

class AdminServer
{
    public function adminInfo($id = 0, $token = '')
    {
        $where = [];
        if($id){
            $where['aid'] = $id;
        }else{
            $where['token'] = $token;
        }

        $adminInfo = (new AdminModel())->field('aid, account, password, createTime')->where($where)->find();

        if($adminInfo){
            $adminInfo = $adminInfo->getData();
            $adminInfo['createTime'] = date('Y-m-d H:i:s', $adminInfo['createTime']);
        }else{
            $adminInfo = [];
        }
        return $adminInfo;
    }

    public function login($param = [])
    {
        $checkPassword = $this->checkPassword($param['account'], $param['password']);
        if(!$checkPassword){
            return false;
        }

        $save['loginTime'] = time();
        $save['loginIp'] = getClientIp();
        $save['loginCount'] = ['inc', 1];
        $save['token'] = md5($save['loginTime'] . $param['account'] . rand(100, 999));
        try{
            (new AdminModel())->save($save, ['aid' => $checkPassword['aid']]);
        }catch (Exception $e){
            Log::error("login error:" . $e->getMessage());
            return false;
        }
        $response['token'] = $save['token'];
        $response['account'] = $checkPassword['account'];
        return $response;
    }

    public function reset($param = [])
    {
        $save['salt'] = getRandStr();
        $save['password'] = md5($param['repeatPassword'] . $save['salt']);

        $where['account'] = $param['account'];
        try{
            (new AdminModel())->save($save, $where);
        }catch (Exception $e){
            Log::error("reset error:" . $e->getMessage());
            return false;
        }
        return true;
    }

    public function checkPassword($account = '', $password = '')
    {
        if(!$account || !$password)return false;
        $where['account'] = $account;
        $adminInfo = (new AdminModel())->field('aid, account, password, salt')->where($where)->find();

        if(!$adminInfo){
            return false;
        }
        $adminInfo = $adminInfo->getData();
        if($adminInfo['password'] == md5($password . $adminInfo['salt'])){
            return $adminInfo;
        }else{
            return false;
        }
    }
}