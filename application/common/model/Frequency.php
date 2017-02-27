<?php
/**
 * 场次实体
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:37
 */

namespace app\common\model;


use think\Model;

class Frequency extends Model
{
    public function film()
    {
        return $this->belongsTo('film');
    }

    public function screens()
    {
        return $this->belongsTo('screens');
    }

    public function orders(){
        return $this->hasMany('order','frequency_id','id');
    }

}