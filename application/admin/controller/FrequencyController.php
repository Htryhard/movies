<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/16
 * Time: 11:39
 */

namespace app\admin\controller;


use app\common\Communal;
use app\common\controller\BaseController;
use app\common\model\Film;
use app\common\model\Frequency;
use app\common\model\Order;
use app\common\model\Screens;
use app\common\model\User;

class FrequencyController extends BaseController
{
    public function index(){
        $frequencys = Frequency::paginate(10);
        $user = User::getUserBySession();
        $this->assign("user",$user);
        $this->assign('frequencys',$frequencys);
        return $this->fetch();

    }

    public function insert(){
        $data = $this->request->param();
        if (count($data)>0) {
            $frequency = new Frequency();
            $frequency->start_time = strtotime($data['start_time']);
            $frequency->screens_id = $data['screens_id'];
            $frequency->film_id = $data['film_id'];
            if ($frequency->validate(true)->save($frequency->getData())){
                $this->redirect(url('admin/frequency/index'));
            }else{
                $frequency->getError();
            }

        }else{
            //查出所以影厅
            $films = Film::all();
            //查出所有电影
            $screens = Screens::all();
            $user = User::getUserBySession();
            $this->assign("user",$user);
            $this->assign('films',$films);
            $this->assign('screens',$screens);
            return $this->fetch();
        }
    }

    public function showOrder(){
        //TODO:显示此场次下所有的订单
        $id = $this->request->param("id");
        if ($id != null){
            $frequency = Frequency::get($id);
            if ($frequency != null){
                $orders = Order::all(["frequency_id"=>$id]);
                $this->assign("user",User::getUserBySession());
                $this->assign("frequency",$frequency);
                $this->assign("orders",$orders);
                return $this->fetch();
            }
        }else{
            return "必须传入一个参数";
        }
    }

}