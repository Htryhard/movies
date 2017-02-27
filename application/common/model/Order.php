<?php
/**
 * 订单实体
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:39
 */

namespace app\common\model;


use think\Model;

class Order extends Model
{

    public function user(){
        return $this->belongsTo('user');
    }

    public function frequency(){
        return $this->belongsTo('frequency');
    }

    public function places(){
        return $this->hasMany('place','order_id','id');
    }

}