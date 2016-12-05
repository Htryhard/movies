<?php
/**
 * 影厅实体
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:35
 */

namespace app\common\model;


use think\Model;

class Screens extends Model
{
    public function frequency()
    {
        return $this->hasMany('frequency','screens_id','id');
    }
}