<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/15
 * Time: 8:40
 */

namespace app\admin\controller;


use app\common\Communal;
use app\common\controller\BaseController;
use app\common\model\Frequency;
use app\common\model\Screens;
use app\common\model\User;

class ScreensController extends BaseController
{

    /**
     * 影厅资源管理的首页
     * @return mixed
     */
    public function home()
    {
        // 获取查询信息
        $name = input('get.name');
        $pageSize = 5; // 每页显示5条数据
//        $screens = Screens::all();
        $screen = new Screens();
        // 定制查询信息
        if (!empty($name)) {
            $screens = $screen->where('name', 'like', '%' . $name . '%')->paginate($pageSize, false,
                [
                    'query' => [
                        'name' => $name,
                    ],
                ]);
        } else {
            $screens = $screen->paginate($pageSize);
        }
        $this->assign("user",User::getUserBySession());
        $this->assign('screens', $screens);
        return $this->fetch();
    }

    /**
     * 插入影厅
     * @return mixed|\think\response\Json
     */
    public function insert()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            $screenData = Communal::arrayConvert($data['formdata']);
            $screen = new Screens();
            $screen->name = $screenData['name'];
            $screen->describe = $screenData['describe'];
            $screen->row = $screenData['row'];
            $screen->cols = $screenData ['cols'];

            if ($screen->validate(true)->save($screen->getData())) {
                return json('succeed');
            } else {
                return json($screen->getError());
            }
        } else {
            return $this->fetch();
        }

    }

    public function update()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            $screenData = Communal::arrayConvert($data['formdata']);

            $id = $screenData['screenId'];
            if ($id != "") {
                $screen = Screens::get($id);
                if ($screen != null) {
                    $screen->name = $screenData['name'];
                    $screen->describe = $screenData['describe'];
                    $screen->row = $screenData['row'];
                    $screen->cols = $screenData ['cols'];
                    if ($screen->validate(true)->save($screen->getData())) {
                        return json('succeed');
                    } else {
                        return json($screen->getError());
                    }
                }
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 影厅明细页面
     */
    public function detail()
    {
        $id = $this->request->param('id');
        $screen = Communal::getScreenById($id);
        if ($screen != null) {
            $this->assign("screen", $screen);
            return $this->fetch();
        } else {
            $this->error("没有这个影厅");
        }
    }

    /**
     * 判断一个影厅是否处于可用状态，如果可用，返回一个场次的结束时间
     * @return \think\response\Json
     */
    public function isIdle()
    {
        $data = $this->request->param();
        if (count($data) > 0) {
            $stime = $data['startTime'];
            $screenid = $data['screensId'];
            $fimlId = $data['filmId'];
            $frequencys = Frequency::all(['screens_id' => $screenid]);
            if (count($frequencys) > 0) {
                $last_frequency_time = 946659661;
                foreach ($frequencys as $item) {
                    $end_time = Communal::getEndTime($item['film_id'], date("Y-m-d H:i:s", $item['start_time']));
                    if (strtotime($end_time) > $last_frequency_time) {
                        $last_frequency_time = strtotime($end_time);
                    }
                }
//                echo $last_frequency_time;
                if (strtotime($stime) > $last_frequency_time) {
                    return json(Communal::getEndTime($fimlId, $stime));
                } else {
                    return json("takeup");
                }
//                echo date("Y-m-d H:i",$last_frequency_time);
            } else {
                return json(Communal::getEndTime($fimlId, $stime));
            }
        } else {
            return json("error(data=null)");
        }
    }


}