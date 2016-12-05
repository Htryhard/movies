<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/20
 * Time: 17:19
 */

namespace app\api\controller;


use app\common\model\Frequency;
use think\Controller;
use think\Request;

class FrequencyController extends Controller
{
    public function getFrequencies($filmId)
    {
        if ($filmId != null) {
            $data = Frequency::all(['film_id' => $filmId]);
            return $data != null ? json($data) : json(['error' => '没有这部电影'], 404);
        }
        return json(['error' => '没有传入指定参数'], 404);
    }

    public function getFrequencyById()
    {
        $id = Request::instance()->param("id");
        if ($id != "") {
            $frequency = Frequency::get($id);
            if ($frequency != null) {
                return json($frequency);
            } else {
                return json("场次不存在");
            }
        } else {
            return json("请求参数不全");
        }

    }

    public function getAllFrequencies()
    {
        //不允许过滤场次
        $frequencies = Frequency::all();
        return json($frequencies);
    }

}