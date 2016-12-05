<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 13:38
 */

namespace app\admin\controller;


use app\common\Communal;
use app\common\model\Film;
use app\common\controller\BaseController;
use app\common\model\Label;
use app\common\model\User;
use think\Request;

class FilmController extends BaseController
{
    /**
     * FilmController constructor.
     * @param Request $request
     * 电影资源控制器的构造方法，验证权限、数据校验
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        //TODO:此处需要写权限认证、数据校验工作
    }

    /**
     * 显示所有电影
     */
    public function home()
    {
        $film = Film::paginate(18);
        $label = Label::all();
        $user = User::getUserBySession();
        $this->assign("user", $user);
        $this->assign('films', $film);
        $this->assign('labels', $label);
        return $this->fetch();
    }

    /**
     * 添加电影
     * @return mixed
     */
    public function insert()
    {
        $labels = Label::all();
        $user = User::getUserBySession();
        $this->assign("user", $user);
        $this->assign('labels', $labels);
        $data = $this->request->param();
        if (count($data) > 0) {
            $coverImg = $this->request->file('cover');
            $film = new Film();
            $film->title = $data['title'];
            $film->synopsis = $data['synopsis'];
            $film->price = $data['price'];
            $film->show_time = strtotime($data['show_time']);
            $film->out_time = strtotime($data['out_time']);
            $film->label_id = $data['label_id'];
            $film->director = $data['director'];
            $film->mins = $data['mins'];
            $film->actor = $data['actor'];

            if ($film->validate(true)->save($film->getData())) {
                if ($coverImg != null) {
                    $coverImg = "/movies/public/uploads/film/" . Communal::uploads($coverImg);
                } else {
                    $coverImg = "/movies/public/uploads/film/df.png";
                }
                $film->cover = $coverImg;
                $film->save();
                return $this->success('保存成功', url('admin/film/home'));
            } else {
                return $this->error($film->getError());
            }
//            $time =
//            echo $time."<br/>".date('Y-m-d H:i:s',$time);

        } else {
            return $this->fetch();
        }

    }

    /**
     * 电影明细页面
     */
    public function detail()
    {
        $id = $this->request->param('id');
        $film = Communal::getFilmById($id);
        if ($film != null) {
            $user = User::getUserBySession();
            $this->assign("user", $user);
            $this->assign("film", $film);
            return $this->fetch();
        } else {
            $this->error("没有这部电影");
        }
    }

    /**
     * 渲染一部影片
     * @return mixed
     */
    public function edit()
    {
        $id = $this->request->param('id');
        $film = Communal::getFilmById($id);
        if ($film != null) {
            $label = Label::all();
            $user = User::getUserBySession();
            $this->assign("user", $user);
            $this->assign('labels', $label);
            $this->assign("film", $film);
            return $this->fetch();
        } else {
            $this->error("没有这部电影");
        }

    }

    /**
     * 处理编辑好的电影
     */
    public function editHandle()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            //有数据
//            $data = Communal::arrayConvert($data["filmdata"]);
            $filmId = $data["filmId"];
            $film = Film::get($filmId);
            if ($film != null) {
                $coverImg = $this->request->file('cover');
                $film->title = $data['title'];
                $film->synopsis = $data['synopsis'];
                $film->price = $data['price'];
                $film->show_time = strtotime($data['show_time']);
                $film->out_time = strtotime($data['out_time']);
                $film->label_id = $data['label_id'];
                $film->director = $data['director'];
                $film->mins = $data['mins'];
                $film->actor = $data['actor'];

                if ($film->validate(true)->save($film->getData())) {
                    if ($coverImg != null) {
                        $coverImg = "/movies/public/uploads/film/" . Communal::uploads($coverImg);
                        $film->cover = $coverImg;
                        $film->save();
                    }
                    return $this->success('保存成功', url('admin/film/detail?id='.$filmId));
                } else {
                    return $this->error($film->getError());
                }
            }
        } else {

        }

    }

    /**
     * 电影下架
     * @return \think\response\Json
     */
   public function soldOut(){
       $filmId = $this->request->param("filmId");
       if ($filmId != null){
            $film = Film::get($filmId);
           $film->out_time = time();
           $film->save();
           return json("succeed");
       }else{
           return json("下架失败！");
       }
   }


}