<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/21
 * Time: 10:46
 */

namespace app\common\model;


use think\Model;

class Rule extends Model
{
    // 定义关联
    public function auths()
    {
        //user_rule
        return $this->hasMany('auth');
    }

}