<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 14:43
 */

namespace app\common\validate;


use think\Validate;

class Label extends Validate
{
    protected $rule = [
        'content'  => ['require', 'max'=>20],
    ];

    protected $message  =   [
        'content.require' => '电影类型不能为空',
        'content.max' => '电影类型最多是20个字符',
    ];

}