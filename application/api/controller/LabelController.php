<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/20
 * Time: 10:06
 */

namespace app\api\controller;


use app\common\model\Label;
use think\Controller;

class LabelController extends Controller
{
    public function getLabel($id){
        if ($id != null) {
            $data = Label::get($id);
            return $data != null ? json($data) : json(['error' => '没有这分类'], 404);
        }
        return json(['error' => '没有传入指定参数'], 404);
    }

    public function getLabels(){
        $labels = Label::all();
        return json($labels);
    }
}