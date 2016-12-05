<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/17
 * Time: 10:04
 */

namespace app\api\controller;


use app\common\Communal;
use app\common\controller\BaseController;
use app\common\model\User;
use think\Controller;
use think\Log;
use think\Request;

class UserController extends Controller
{

    public function login()
    {
        $data = Request::instance()->param();
        $email = $data['email'];
        $password = $data['password'];
        if (User::login($email, $password)) {
            $user = Communal::getUserByEmail($email);
            return json($user);
        }
        return ('{"msg":"邮箱或密码错误","state":404}');
    }

    public function register()
    {
        header('Content-Type: image/jpg; charset=utf-8');
        $userdata = Request::instance()->post();
        if ($userdata != "") {
            $email = $userdata['email'];
            $username = $userdata['username'];
            $password = $userdata['password'];
            $phone = $userdata['phone'];
            $address = $userdata['address'];

            //验证邮箱是否唯一
            $flga = Communal::getUserByEmail($email);
            if ($flga != null) {
                //邮箱已被注册状态码------304
                return json(304);
            }
            //验证必填信息
            $user = new User();
            $user->email = $email;
            $user->username = $username;
            $user->password = User::encryptPassword($password);
            $user->phone = $phone;
            $user->address = $address;
            $user->icon = Communal::uploadsIconForAPI($userdata['icon']);
            if ($user->validate(true)->save($user->getData())) {
                //写入默认权限
//                Communal::defaltPermission($user->getData("id"));
                //注册成功状态码---200
                return json(200);
            } else {
                //注册信息验证不通过状态码----305
                $error = $user->getError();
                return json("{'msg':'$error','state':305}");
            }
        } else {
            //没有提交数据状态码-------404
            return json(404);
        }
    }

    /**
     * 获取一个用户信息
     * @return \think\response\Json
     */
    public function read()
    {
        $userId = Request::instance()->post("userId");
        if ($userId != "") {
            $user = User::get($userId);
            return json($user);
        } else {
            return json("请求信息不全");
        }
    }

    public function edit(){
        header('Content-Type: image/jpg; charset=utf-8');
        $userdata = Request::instance()->post();
        if ($userdata != "") {
            $id = $userdata["id"];
            $email = $userdata['email'];
            $username = $userdata['username'];
            $password = $userdata['password'];
            $phone = $userdata['phone'];
            $address = $userdata['address'];

//            //验证邮箱是否唯一
//            $flga = Communal::getUserByEmail($email);
//            if ($flga != null) {
//                //邮箱已被注册状态码------304
//                return json(304);
//            }
            //验证必填信息
            $user = User::get($id);
            if ($user==null){
                return json(300);
            }
            $user->email = $email;
            $user->username = $username;
            if ($password != ""){
                $user->password = User::encryptPassword($password);
            }
            $user->phone = $phone;
            $user->address = $address;
            $user->icon = Communal::uploadsIconForAPI($userdata['icon']);
            if ($user->validate(true)->save($user->getData())) {
                //写入默认权限
//                Communal::defaltPermission($user->getData("id"));
                //修改成功状态码---200
                return json(200);
            } else {
                //注册信息验证不通过状态码----305
                $error = $user->getError();
                return json("{'msg':'$error','state':305}");
            }
        } else {
            //没有提交数据状态码-------404
            return json(404);
        }
    }

}