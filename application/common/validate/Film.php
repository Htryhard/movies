<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 13:53
 * 电影验证器
 */

namespace app\common\validate;


use think\Validate;

class Film extends Validate
{
    /**
     * @var array
     * title  电影标题
     * synopsis  电影简介
     * cover  电影封面
     * price  电影价格
     * show_time  上架时间
     * out_time  下架时间
     * label_id  分类id
     */
    protected $rule = [
        'title'  => 'require|max:50',
        'synopsis' => 'max:250',
        'price' => 'require|max:10',
        'show_time' => 'require',
        'out_time' => 'require',
        'label_id' => 'require',
        'mins'=>'require|number|max:5',
    ];

    protected $message  =   [
        'title.require' => '请输入电影名称',
        'title.max'     => '电影名称最多不能超过50个字符',
        'synopsis.max'  => '电影简介最多不能超过250个字符',
        'price.require' => '请输入电影价格',
        'price.max'     => '电影价格最多不能超过10个字符',
        'show_time.require' => '请输入电影上架时间',
        'out_time.require' => '请输入电影下架时间',
        'label_id.require' => '请输入电影分类',
        'mins.require' => '电影的时长不能为空哦',
        'mins.number' => '电影的时长必须为数字哦',
        'mins.max'     => '电影的时长最多不能超过5个字符',
    ];

}