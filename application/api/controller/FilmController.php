<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/14
 * Time: 23:15
 */

namespace app\api\controller;


use app\common\model\Film;
use think\Controller;
use think\Request;

class FilmController extends Controller
{
    public function film()
    {
        //过滤已经下架的电影
        //获取当前时间戳
        $films = Film::all(function ($query) {
//            $query->where('out_time', '>', time())->order('id', 'desc');
            $query->where('out_time', '>', time());
        });
        if (count($films) > 0) {
            return json($films);
        } else {
            return json(['error' => '还没有电影哦~'], 404);
        }
    }

    public function getFilmWhere(){
        $film = new Film();
        $where = Request::instance()->get("where");
        if ($where!=""){
            $films =  $film->where('title', 'like', '%' . $where . '%')->where('out_time', '>', time())->select();
        }else{
            $films = Film::all(function ($query) {
//            $query->where('out_time', '>', time())->order('id', 'desc');
                $query->where('out_time', '>', time());
            });
        }

        if (count($films) > 0) {
            return json($films);
        } else {
            return json(['error' => '还没有电影哦~'], 404);
        }

    }

    /**
     * 显示指定的电影
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        if ($id != null) {
            $data = Film::get($id);
            return $data != null ? json($data) : json(['error' => '没有这部电影'], 404);
        }
        return json(['error' => '没有传入指定参数'], 404);
    }

//    public function


}