<?php
/**
 * 影厅验证器
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:35
 */

namespace app\common\validate;


use think\Validate;

class Screens extends Validate
{
    protected $rule = [
        'name'  => 'require|max:20',
        'row' => 'require|number|max:2',
        'cols' => 'require|number|max:2',
    ];

    protected $message  =   [
        'name.require' => '请输入影厅名称',
        'name.max'     => '影厅名称最多不能超过20个字符',
        'row.max'  => '行数最多两位数啦！',
        'row.require' => '行数不能为空！',
        'row.number'     => '行数必须是数字呢',
        'cols.require' => '列数不能为空！',
        'cols.max' => '列数最多两位数啦！',
        'cols.number' => '列数必须是数字呢',
    ];
}