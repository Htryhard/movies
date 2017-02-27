<?php
/**
 * 所有控制器的基类，权限认证、登陆认证
 * User: Huan
 * Date: 2016/11/12
 * Time: 13:29
 */

namespace app\common\controller;


use app\common\Communal;
use app\common\model\Auth;
use app\common\model\Count;
use app\common\model\Rule;
use app\common\model\User;
use think\Controller;
use think\Request;
use think\Response;

class BaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;

        $requestIP =Communal::getClientIP();
        $requestDate = time();
        $count = new Count();
        $count->ipcontent = $requestIP;
        $count->date = $requestDate;
        $count->save();

        // 验证用户是否登陆
        if (!User::isLogin()) {
            $this->redirect(url('home/thing/login'));
        }
        //TODO:权限认证
//        $request->action();
        //获取到当前请求的  模块/控制器/方法
//        $condition = $request->path();
        $condition = $request->module() . "\\" . $request->controller() . "\\" . $request->action();
        $userId = User::getUserBySession()->getData("id");
        //获取中间表
        $auths = Auth::all(["user_id" => $userId]);

        $lowerCondition = strtolower($condition);
        $r = Rule::get(["condition"=>$lowerCondition]);
        if ($r==null){
            $ru = new Rule();
            $ru->title = "";
            $ru->status = 0;
            $ru->condition = $lowerCondition;
            $ru->save();
        }

//        //权限标识
        $flag = false;
//        echo "请求：".$condition;
//        //循环判断权限
        foreach ($auths as $item) {
            $rule = Rule::get($item->getData("rule_id"));
            if (strtolower($rule->getData("condition")) == strtolower($condition)) {
                $flag = true;
                break;
            }
        }

        if (!$flag) {
//            echo "<script>alert('您无权访问')</script>";
//                $this->redirect(url('home/thing/login'));
            $this->error('您无权限访问此页面', null, 1);
            return;
        }


    }
}