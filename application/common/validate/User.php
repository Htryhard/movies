<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 15:12
 */

namespace app\common\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'email'  => 'require|email|max:20',
        'username' => 'require|min:2|max:20',
        'password' => 'require|max:40',
        'phone' => 'require|max:20',
    ];

    protected $message  =   [
        'email.require' => '请输入邮箱',
        'email.email'   => '邮箱格式不对',
        'email.max'     => '邮箱不能超过20个字符',
        'username.require' => '用户名不能为空',
        'username.min' => '用户名最少2个字符',
        'username.max' => '用户名最多20个字符',
        'password.require'     => '密码不能为空',
        'password.max' => '密码最多40个字符',
        'phone.require' => '电话号码不能为空',
        'phone.max' => '电话号码最多20个字符',
    ];

}