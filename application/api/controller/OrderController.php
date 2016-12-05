<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/30
 * Time: 10:32
 */

namespace app\api\controller;


use app\common\model\Order;
use app\common\model\Place;
use think\Controller;
use think\Request;

class OrderController extends Controller
{

    /**
     * 添加订单
     * @return \think\response\Json
     */
    public function addOrder()
    {
        $orderData = Request::instance()->post();
        if ($orderData != "") {
            $user_id = $orderData["user_id"];
            $frequency_id = $orderData["frequency_id"];
            $number = $orderData["number"];
            $place = $orderData["place"];

            $placeArray = explode("-", $place);//分割字符串

            $order = new Order();
            $order->user_id = $user_id;
            $order->number = $number;
            $order->frequency_id = $frequency_id;
            $order->state = 0;
            $order->order_number = time();//单号
            $order->create_time = time();//订单创建时间
            $order->save();

            foreach ($placeArray as $placeItem) {
                $x = explode(",", $placeItem)[0];
                $y = explode(",", $placeItem)[1];

                //TODO：没有位置数据的校验，有可能直接从post工具被访问
//                $places = Place::all(["xplace" => $x, "yplace" => $y]);
//                if (count($places) > 0) {
//                    $orderModel = Order::get($order->id);
//                    $orderModel->delete();
//                    //位置已经被选
//                    return json(304);
//                } else {
                $p = new Place();
                $p->xplace = $x;
                $p->yplace = $y;
                $p->order_id = $order->id;
                $p->save();
//                }
            }
            $msg['orderId'] = $order->getData('id');
            $msg['code'] = 200;
            //创建订单成功
            return json($msg);

        }
    }

    public function read()
    {
        $userId = Request::instance()->post("userId");
        if ($userId != "") {
            $orders = Order::all(["user_id" => $userId]);
            return json($orders);
        } else {
            return json("请求信息不全");
        }
    }

    public function getOneOrder()
    {
        $orderId = Request::instance()->post("orderId");
        if ($orderId != null) {
            $order = Order::get($orderId);
            return json($order);
        } else {
            return json("请求信息不全");
        }
    }

}