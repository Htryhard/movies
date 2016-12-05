<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/21
 * Time: 10:46
 */

namespace app\common\model;


use think\Model;

class Auth extends Model
{
    // 设置数据表（不含前缀）
    protected $name = 'user_rule';

    // 定义关联方法
    public function user()
    {
        return $this->belongsTo('User');
    }

    // 定义关联方法
    public function rule()
    {
        return $this->belongsTo('rule');
    }

}