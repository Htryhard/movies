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
use app\common\model\Film;
use app\common\model\Frequency;
use app\common\model\Order;
use app\common\model\User;

class HomeController extends BaseController
{
    public function index()
    {
        //TODO:管理员首页，显示登陆用户的头像、姓名、
        //TODO:模块：电影资源管理、影厅管理、员工管理
        $user = User::getUserBySession();
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function userDataShow()
    {
        // 获取查询信息
        $name = input('get.username');
        $pageSize = 10; // 每页显示10条数据
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
        $this->assign("DataType","userDataShow");
        $this->assign("user", User::getUserBySession());
        $this->assign('users', $users);
        return $this->fetch();
    }

    public function filmDataShow()
    {
        // 获取查询信息
        $title = input('get.title');
        $pageSize = 10; // 每页显示10条数据
        $film = new Film();
        // 定制查询信息
        if (!empty($title)) {
            $films = $film->where('title', 'like', '%' . $title . '%')->paginate($pageSize, false,
                [
                    'query' => [
                        'title' => $title,
                    ],
                ]);
        } else {
            $films = $film->paginate($pageSize);
        }

        $showCount = 0;
        foreach (Film::all() as $film) {
            if ($film['out_time'] > !time()) {
                $showCount++;
            }
        }
        $this->assign("DataType","filmDataShow");
        $this->assign("mOut",count(Film::all())-$showCount);
        $this->assign("mHot", $showCount);
        $this->assign("mFilmCount", count(Film::all()));
        $this->assign("user", User::getUserBySession());
        $this->assign("frequencies", Frequency::all());
        $this->assign('films', $films);
        return $this->fetch();
    }

    public function frequencyDataShow()
    {
        // 获取查询信息
        $startTime = input('get.start_time');
        $pageSize = 10; // 每页显示10条数据
        $frequency = new Frequency();
        // 定制查询信息
        if (!empty($startTime)) {
            $frequencies = $frequency->where('start_time', 'like', '%' . $startTime . '%')->paginate($pageSize, false,
                [
                    'query' => [
                        'start_time' => $startTime,
                    ],
                ]);
        } else {
            $frequencies = $frequency->paginate($pageSize);
        }

        $boxOffice = 0;
        foreach(Frequency::all() as $frequency){
            foreach($frequency['orders'] as $order){
                $boxOffice += $order['frequency']['film']['price']*$order['number'];
            }
        }
        $this->assign("DataType","frequencyDataShow");
        $this->assign("boxOffice",$boxOffice);
        $this->assign("countFrequency",count(Frequency::all()));
        $this->assign("user", User::getUserBySession());
        $this->assign('frequencies', $frequencies);
        return $this->fetch();
    }

    public function orderDataShow(){
        // 获取查询信息
        $susername = input('get.username');
        $u = User::get(['username'=>$susername]);
        $pageSize = 10; // 每页显示10条数据
        $order = new Order();
        // 定制查询信息
        if (!empty($u['id'])) {
            $orders = $order->where('user_id', 'like', '%' . $u['id'] . '%')->paginate($pageSize, false,
                [
                    'query' => [
                        'user_id' => $u['id'],
                    ],
                ]);
        } else {
            $orders = $order->paginate($pageSize);
        }

//        $boxOffice = 0;
//        foreach(Frequency::all() as $frequency){
//            foreach($frequency['orders'] as $order){
//                $boxOffice += $order['frequency']['film']['price']*$order['number'];
//            }
//        }
        $this->assign("DataType","orderDataShow");
        $this->assign("allOrders",Order::all());
        $this->assign("countOrder",count(Order::all()));
        $this->assign("user", User::getUserBySession());
        $this->assign('orders', $orders);
        return $this->fetch();
    }

}