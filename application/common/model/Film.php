<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 12:06
 * 电影表的模型类
 */

namespace app\common\model;


use think\Model;

class Film extends Model
{
    public function label()
    {
        return $this->belongsTo('label');
    }
    public function frequency()
    {
        return $this->hasMany('frequency','film_id','id');
    }
}