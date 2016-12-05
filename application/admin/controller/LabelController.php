<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 14:37
 */

namespace app\admin\controller;


use app\common\model\Label;
use app\common\controller\BaseController;

class LabelController extends BaseController
{

    public function index()
    {
        $labels = Label::all();
        $this->assign("labels",$labels);
        return $this->fetch();
    }
    public function insert(){
        $data = $this->request->param();
        if (count($data)>0) {
            //添加分类
            $label = new Label();
            $label->content = $data['label'];
            if ($label->validate(true)->save($label->getData())) {
                return "添加成功！";
            }
            else {
                return $label->getError();
            }
        }
        else {
            //渲染添加分类的视图
            return $this->fetch();
        }
    }
}