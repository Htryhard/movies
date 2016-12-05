<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/20
 * Time: 16:50
 */

namespace app\api\controller;


use app\common\model\Screens;
use think\Controller;

class ScreensController extends Controller
{
    public function getScreen($id)
    {
        if ($id != null) {
            $data = Screens::get($id);
            return $data != null ? json($data) : json(['error' => '没有影厅'], 404);
        }
        return json(['error' => '没有传入指定参数'], 404);
    }

    public function getScreens()
    {
        $screens = Screens::all();
        return json($screens);
    }

}