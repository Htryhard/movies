<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/12
 * Time: 13:36
 * 公共方法放置类，比如上传
 */

namespace app\common;


use app\common\model\Auth;
use app\common\model\Film;
use app\common\model\Screens;
use app\common\model\User;
use think\Request;

class Communal
{
    public static function getClientIP()
    {
//        global $ip;
//        if (getenv("HTTP_CLIENT_IP"))
//            $ip = getenv("HTTP_CLIENT_IP");
//        else if(getenv("HTTP_X_FORWARDED_FOR"))
//            $ip = getenv("HTTP_X_FORWARDED_FOR");
//        else if(getenv("REMOTE_ADDR"))
//            $ip = getenv("REMOTE_ADDR");
//        else $ip = "Unknow";
//        return $ip;
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (@$_SERVER["HTTP_CLIENT_IP"])
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        else if (@$_SERVER["REMOTE_ADDR"])
            $ip = $_SERVER["REMOTE_ADDR"];
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (@getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (@getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknown";
        return $ip;
    }
    /**
     * 影片图片上传,返回保存的文件名
     * @param $file
     * @return string
     */
    public static function uploads($file)
    {

        $info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'film');
        if ($info) {
//            $this->success('文件上传成功：' . $info->getRealPath());
            return $info->getSaveName();
        } else {
            // 上传失败获取错误信息
//            $this->error($file->getError());
            return 'no';
        }
    }

    /**
     * 上传头像
     * @param $base64Icon
     * @return bool|string
     */
    public static function uploadsIcon($base64Icon)
    {
        //获取扩展名和文件名
        if (preg_match('/(?<=\/)[^\/]+(?=\;)/', $base64Icon, $pregR))
            $streamFileType = '.' . $pregR[0];
        //读取扩展名，如果你的程序仅限于画板上来的，那一定是png，这句可以直接streamFileType 赋值png
        //产生一个随机文件名（因为你base64上来肯定没有文件名，这里你可以自己设置一个也行）
        $streamFileRand = date('YmdHis') . rand(1000, 9999);
        $streamFilename = ROOT_PATH . "/public/uploads/icon/" . $streamFileRand . $streamFileType;
        //存入数据库的位置
        $path = "/uploads/icon/" . $streamFileRand . $streamFileType;
        //处理base64文本，用正则把第一个base64,之前的部分砍掉
        preg_match('/(?<=base64,)[\S|\s]+/', $base64Icon, $streamForW);
        if (file_put_contents($streamFilename, base64_decode($streamForW[0])) === false) {
//                //这是我自己的一个静态类，输出错误信息的，你可以换成你的程序
//                Common::exitWithError("文件写入失败!","");
            return false;
        } else {
            return $path;
        }

    }

    public static function uploadsIconForAPI($base64Icon)
    {
        $streamFileType = ".png";
        //产生一个随机文件名（因为你base64上来肯定没有文件名，这里你可以自己设置一个也行）
        $streamFileRand = date('YmdHis') . rand(1000, 9999);
        $streamFilename = ROOT_PATH . "/public/uploads/icon/" . $streamFileRand . $streamFileType;
        //存入数据库的位置
        $path = "/uploads/icon/" . $streamFileRand . $streamFileType;

        //处理base64文本
        if (file_put_contents($streamFilename, base64_decode($base64Icon)) === false) {
//                //这是我自己的一个静态类，输出错误信息的，你可以换成你的程序
//                Common::exitWithError("文件写入失败!","");
            return false;
        } else {
            return $path;
        }

    }


    /**
     * 根据邮箱获取一个用户
     * @param $email
     * @return $user
     */
    public static function getUserByEmail($email)
    {
        $user = User::get(['email' => $email]);
        return $user;
    }

    /**
     * 根据id获取一个电影
     * @param $id
     * @return static
     */
    public static function getFilmById($id)
    {
        if ($id != null) {
            $film = Film::get($id);
            return $film;
        } else {
            return null;
        }
    }

    /**
     * 根据id获取一个影厅信息
     * @param $id
     * @return null|static
     */
    public static function getScreenById($id)
    {
        if ($id != null) {
            $screen = Screens::get($id);
            return $screen;
        } else {
            return null;
        }
    }

    /**
     * 把表单里的二维数字转换为一维数组
     * @param $arr
     * @return array|null
     */
    public static function arrayConvert($arr)
    {
        if (count($arr) > 0) {
            $result = array();
            foreach ($arr as $item) {
                $result += [$item['name'] => $item['value']];
            }
            return $result;
        } else {
            return null;
        }
    }

    /**
     * 根据一部电影的时长，和一个场次的开始时间，返回场次的结束时间
     * @param $filmId
     * @param $startTime
     * @return false|null|string
     */
    public static function getEndTime($filmId, $startTime)
    {
        $film = Film::get($filmId);
        if ($film != null) {
            $mins = $film->getData('mins');
            return date("Y-m-d H:i", strtotime("+$mins minute", strtotime($startTime)));
        } else {
            return null;
        }
    }

    /**
     * [std_class_object_to_array 将对象转成数组]
     * @param [stdclass] $stdclassobject [对象]
     * @return [array] [数组]
     */
    public static function std_class_object_to_array($stdclassobject)
    {
        $array = array();
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;

//        return $_array;
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? self::std_class_object_to_array($value) : $value;
            $array[$key] = $value;
//            $array += [$key=>$value];
        }
        return $array;
    }

    /**
     * 获取一个用户的base64头像
     * @return string
     */
    public static function getUserIcon()
    {
        $user = User::getUserBySession();
        $type = getimagesize(ROOT_PATH . $user->getData("icon"));//取得图片的大小，类型等
//        dump($type);
        $fp = fopen(ROOT_PATH . $user->getData("icon"), "r") or die("Can't open file");
        $file_content = chunk_split(base64_encode(fread($fp, filesize(ROOT_PATH . $user->getData("icon")))));//base64编码
        switch ($type[2]) {//判读图片类型
            case 1:
                $img_type = "gif";
                break;
            case 2:
                $img_type = "jpg";
                break;
            case 3:
                $img_type = "png";
                break;
        }
        $img = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码
        fclose($fp);
        return $img;
    }

    /**
     * 设置默认权限
     * @param $userId
     */
    public static function defaltPermission($userId)
    {

        //访问后台主页
        $auth1 = new Auth();
        $auth1->rule_id = 2;
        $auth1->user_id = $userId;
        $auth1->save();

        //注销登陆
        $auth2 = new Auth();
        $auth2->rule_id = 3;
        $auth2->user_id = $userId;
        $auth2->save();

        //编辑管理员个人信息
        $auth3 = new Auth();
        $auth3->rule_id = 21;
        $auth3->user_id = $userId;
        $auth3->save();

        //提交编辑的信息
        $auth4 = new Auth();
        $auth4->rule_id = 22;
        $auth4->user_id = $userId;
        $auth4->save();

    }

}