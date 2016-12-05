<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 16:59
 * 管理员首页
 */

namespace app\admin\controller;


use app\common\controller\BaseController;
use app\common\model\User;

class HomeController extends BaseController
{
    public function index(){
        //TODO:管理员首页，显示登陆用户的头像、姓名、
        //TODO:模块：电影资源管理、影厅管理、员工管理
        $user = User::getUserBySession();
        $this->assign('user',$user);
        return $this->fetch();
    }

}