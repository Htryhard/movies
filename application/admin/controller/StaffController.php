<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/21
 * Time: 8:20
 */

namespace app\admin\controller;

use app\common\controller\BaseController;
use app\common\model\Auth;
use app\common\model\Rule;
use app\common\model\User;

class StaffController extends BaseController
{
    public function index()
    {
        $user = User::getUserBySession();
        $this->assign("user",$user);
        $users = User::all();
        $this->assign("users", $users);
        return $this->fetch();
    }


    //分配权限
    public function permissions()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            $userId = $data['id'];
            $user = User::get(["id" => $userId]);
            if ($user != null) {
                $this->assign("adminUser",User::getUserBySession());
                $this->assign("user", $user);
                $auths = Auth::all();
                $rules = Rule::all();
                $checkbox = "";
                foreach ($rules as $rule) {
                    foreach ($auths as $auth) {
                        if ($auth['user_id'] == $user['id'] && $rule['id'] == $auth['rule_id']) {
                            $checkbox .= " <input type='checkbox' value='" . $rule['id'] . "' name='checkRuleId[]' id='rule' checked/>" . $rule['title'] . "<br/>";
                            continue 2;
                        }
                    }
                    $checkbox .= " <input type='checkbox' value='" . $rule['id'] . "' name='checkRuleId[]' id='rule' />" . $rule['title'] . "<br/>";
                }

//                echo $checkbox;
                $this->assign("checkbox", $checkbox);
                return $this->fetch();


            } else {
                $user->getError();
            }
        } else {
            $this->error("请输入指定参数");
        }
    }

    /**
     *编辑管理员的权限
     */
    public function permissionHandle(){
        $data = $this->request->param();
//        dump($data);
        if ($data != null) {
            $user = User::get($data['userId']);
            $ruleIds = $data['checkRuleId'];
            if (count($ruleIds)>0){

                $auths = Auth::all(["user_id"=>$user->getData("id")]);
                foreach ($auths as $auth){
                    $auth->delete();
                }

                foreach ($ruleIds as $item) {
//                $auth = Auth::get([
//                    "user_id" => $user->getData("id"),
//                    "rule_id" => $item
//                ]);
//
//                if ($auth == null) {
                    $auth = new Auth();
                    $auth->user_id = $user->getData("id");
                    $auth->rule_id = $item;
                    $auth->save();
//                }
                }
            }
        } else {
            $this->error();
        }

        $this->redirect(url("admin/staff/index"));
    }

    public function addPermission(){
        $data = $this->request->param();
        if (count($data)>0){

        }else{
            return $this->fetch();
        }
    }

}