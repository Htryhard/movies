<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 14:43
 */

namespace app\common\model;

use think\Model;

class Label extends Model
{
    /**
     * 模型关联
     */
    public function film()
    {
        return $this->hasMany('film','label_id','id');
    }

}