<?php
/**
 * Created by PhpStorm.
 * User: Huan
 * Date: 2016/11/14
 * Time: 23:15
 */

namespace app\api\controller;

define("TOKEN", "hu0123456789an");
use app\common\model\Film;
use Exception;
use think\Controller;
use think\Request;

class FilmController extends Controller
{

    public function index(){
//        $echoStr = $_GET["echostr"];
        $echoStr = Request::instance()->param("echo");
        //valid signature , option
        if($this->checkSignature()){
//            $this->responseMsg();
            echo "aaa";
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */


            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            //如果我们做微信开发时，需要调试输出信息，请将信息输出到文件中
            file_put_contents('abc.log',$fromUsername.''.$toUsername.''.$keyword,FILE_APPEND);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            //如果用户输入不为空
            if(!empty( $keyword ))
            {
                $msgType = "text";
                if ($keyword=='缘分') {
//                    $contentStr = '啊罗哈！大圆圆';
                    $contentStr = Film::all();

                }else {
                    $contentStr = '你好！我是赖宝宝';
                    if ($keyword=='陈有欢') {
                        $contentStr = '哈哈哈哈哈哈哈！你是小智障，对，没错';
                    }if ($keyword=='欢哥'){
                        $contentStr='对，没错就是这家伙';
                    }
                    preg_match('/(\d+)([+ -])(\d+)/i',$keyword,$res);

                    if ($res[2] == '+'){
                        $result= $res[1]+$res[3];
                        $contentStr='运算结果是'.$result;
                    }elseif ($res[2] =='-'){
                        $result=$res[1]-$res[3];
                        $contentStr='运算结果是'.$result;
                    }
                }

                //填充模板
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }
//
        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;

        $word = "token:".$token;
        $filename = 'file.txt';
        file_put_contents($filename,$word);

        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

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

    /**
     * 根据条件获取指定电影
     * @return \think\response\Json
     */
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