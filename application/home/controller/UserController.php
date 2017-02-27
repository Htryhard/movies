<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 15:12
 */

namespace app\home\controller;

use app\common\Communal;
use app\common\controller\BaseController;
use app\common\model\User;

class UserController extends BaseController
{
    public function index(){
// 获取查询信息
        $name = input('get.username');
        $pageSize = 15; // 每页显示15条数据
        $user = new User();
        // 定制查询信息
        if (!empty($name)) {
            $users = $user->where('username', 'like', '%' . $name . '%')->paginate($pageSize, false,
                [
                    'query' => [
                        'username' => $name,
                    ],
                ]);
        } else {
            $users = $user->paginate($pageSize);
        }
        $this->assign("user",User::getUserBySession());
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function logout(){
        User::logOut();
        $this->redirect("home/thing/login");
    }

    public function read(){
        $userId = $this->request->param("userId");
        if ($userId!=null){
            $user = User::get($userId);
            $this->assign("user",$user);
//            $base64Img = Communal::getUserIcon();
//            $this->assign("userIcon",$base64Img);
            return $this->fetch();
        }
    }

    public function readHandle(){

        $data = $this->request->param();
        if (count($data)>0){
            $data = $data['userdata'];
//            dump($data);
            $userdata = array();
            foreach ($data as $item){
                $userdata += [$item['name']=>$item['value']];
            }
            $userId = $userdata["userId"];
            $username = $userdata['name'];
            $password = $userdata['password'];
            $phone = $userdata['phone'];
            $address = $userdata['address'];

            //验证必填信息
            $user = User::get($userId);

            $user->username = $username;
            if ($password!=""){
                $user->password = User::encryptPassword($password);
            }
            $user->phone = $phone;
            $user->address = $address;
            $user->icon = Communal::uploadsIcon($userdata['base64Icon']);
            if ($user->validate(true)->save($user->getData())) {
//                $auth = new Auth();
                //写入默认权限
//                Communal::defaltPermission($userId);
                return json('succeed');
            } else {
                return json($user->getError());
            }
        }else{
           json("error");
        }

    }

}
