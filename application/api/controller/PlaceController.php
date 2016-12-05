<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/30
 * Time: 13:55
 */

namespace app\api\controller;


use app\common\model\Order;
use app\common\model\Place;
use think\Controller;
use think\Request;

class PlaceController extends Controller
{
    public function getPlacesForFrequency()
    {
        $frequencyId = Request::instance()->param("frequencyId");
        if ($frequencyId != null) {
            $seats = array();
            $orders = Order::all(["frequency_id" => $frequencyId]);
            foreach ($orders as $order) {
                $places = Place::all(["order_id" => $order->getData("id")]);
                foreach ($places as $place) {
                    array_push($seats, $place);
                }
            }
            return json($seats);
        } else {
            return json(['error' => '没有传入指定参数'], 404);
        }
    }

    public function getSeats()
    {
        $data = Request::instance()->param("orderId");
        if ($data != null) {
            $seats = Place::all(['order_id' => $data]);
            if (count($seats) > 0) {
                return json($seats);
            } else {
                return json(['error' => '没有数据'], 304);
            }
        } else {
            return json(['error' => '没有传入指定参数'], 404);
        }
    }

}