<?php
/**
 * 场次验证器
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:37
 */

namespace app\common\validate;


use think\Validate;

class Frequency extends Validate
{
    protected $rule = [
        'start_time'  => ['require'],
        'screens_id' =>'require',
        'film_id'=>'require',
    ];

    protected $message  =   [
        'start_time.require' => '场次开始时间不能为空哦',
        'screens_id.require' => '必须选择一个影厅哦',
        'film_id.require' => '必须选择一部电影哦',
    ];

}