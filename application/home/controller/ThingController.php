<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 17:23
 */

namespace app\home\controller;


use app\common\Communal;
use app\common\model\Auth;
use app\common\model\Count;
use app\common\model\User;
use think\captcha\Captcha;
use think\Controller;
use think\Request;

class ThingController extends Controller
{
    public function login()
    {
        return $this->fetch();
    }

    public function showIP(){
        $counts = Count::all();
        $this->assign("counts",$counts);
        return $this->fetch();
    }

    public function loginHandle()
    {
        // 接收post信息
        $data = Request::instance()->param();
        // 直接调用M层方法，进行登录。
        if (User::login($data['email'], $data['password'])) {
            return json("succeed");
        } else {
            return json('TheUserNameOrPasswordError');
        }
    }

    // 注销
    public function logOut()
    {
        if (User::logOut()) {
            return $this->success('logout success', url('index'));
        } else {
            return $this->error('logout error', url('index'));
        }
    }

    //注册用户
    public function register()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            $data = $data['userdata'];
            $userdata = array();
            foreach ($data as $item) {
                $userdata += [$item['name'] => $item['value']];
            }
            $email = $userdata['email'];
            $username = $userdata['name'];
            $password = $userdata['password'];
            $password2 = $userdata['password2'];
            $phone = $userdata['phone'];
            $address = $userdata['address'];
            //验证邮箱是否唯一
            $flga = Communal::getUserByEmail($email);
            if ($flga != null) {
                return json('repetition');
            }
            //验证密码
            if (!User::passwordFit($password, $password2)) {
                return json('passwordNoFit');
            }
            //验证必填信息
            $user = new User();

            $user->email = $email;
            $user->username = $username;
            $user->password = User::encryptPassword($password);
            $user->phone = $phone;
            $user->address = $address;
            $user->icon = Communal::uploadsIcon($userdata['base64Icon']);
            if ($user->validate(true)->save($user->getData())) {
//                $auth = new Auth();
                //写入默认权限
                Communal::defaltPermission($user->getData("id"));
                return json('succeed');
            } else {
                return json($user->getError());
            }
        } else {
            //渲染视图
            $model = User::getUserBySession();
            $this->assign("user", $model);
            return $this->fetch();
        }
    }

}