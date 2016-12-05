<?php
namespace app\index\controller;


use app\common\Communal;
use app\common\model\Film;
use think\Controller;
use think\Request;
use think\Image;

class IndexController extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function up(){
        $file = Request::instance()->file('file');
        if (empty($file)) {
            $this->error('请选择上传文件');
        }
        $re = Communal::uploads($file,'icon');
        if ($re!="no"){
            echo $re;
        }else{
            echo "上传失败！";
        }
    }

    public function editimg(){
        return $this->fetch();
    }

    // 图片上传处理
    public function picture(Request $request)
    {
        // 获取表单上传文件
        $file = $request->file('image');
        dump($file);
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $this->error('请选择图像文件');
        } else {
            // 读取图片
            $image = Image::open($file);
            // 图片处理
            switch ($request->param('type')) {
                case 1: // 图片裁剪
                    $image->crop(300, 300);
                    break;
                case 2: // 缩略图
                    $image->thumb(150, 150, Image::THUMB_CENTER);
                    break;
                case 3: // 垂直翻转
                    $image->flip();
                    break;
                case 4: // 水平翻转
                    $image->flip(Image::FLIP_Y);
                    break;
                case 5: // 图片旋转
                    $image->rotate();
                    break;
                case 6: // 图片水印
                    $image->water('./logo.png', Image::WATER_NORTHWEST, 50);
                    break;
                case 7: // 文字水印
                    $image->text('ThinkPHP', VENDOR_PATH . 'topthink/think-captcha/assets/ttfs/1.ttf', 20, '#ffffff');
                    break;
            }
            // 保存图片（以当前时间戳）
            $saveName = $request->time() . '.png';
            $image->save(ROOT_PATH . 'public/uploads/' . $saveName);
            $this->success('图片处理完毕...', 'index/index/editimg' . $saveName, 1);
        }
    }

    public function newedit(){
        return $this->fetch();
    }
    public function getimg(){

    }

    public function getnewimg(){
        $img =Request::instance()->post('hidd');
//        echo $img;
        if ($img != null){
            //获取扩展名和文件名
            if(preg_match('/(?<=\/)[^\/]+(?=\;)/',$img,$pregR))
            $streamFileType='.'.$pregR[0];
            //读取扩展名，如果你的程序仅限于画板上来的，那一定是png，这句可以直接streamFileType 赋值png
            //产生一个随机文件名（因为你base64上来肯定没有文件名，这里你可以自己设置一个也行）
            $streamFileRand =date('YmdHis').rand(1000,9999);
            $streamFilename=ROOT_PATH."/public/uploads/icon/".$streamFileRand.$streamFileType;
            //处理base64文本，用正则把第一个base64,之前的部分砍掉
            preg_match('/(?<=base64,)[\S|\s]+/',$img,$streamForW);
            if(file_put_contents($streamFilename,base64_decode($streamForW[0]))===false)
//                //这是我自己的一个静态类，输出错误信息的，你可以换成你的程序
//                Common::exitWithError("文件写入失败!","");

            return json($img);
        }else{
        return json("nononono");
        }

    }

    public function gethi(){
        $hi =Request::instance()->post('hi');
        echo $hi."-----------";
    }

    public function img(){
        $data =Request::instance()->param();
        if (count($data)>0){
            dump($data);
        }else{
            return $this->fetch();
        }

    }

    public function insertfilm(){
        for ($i = 0;$i<100;$i++){
            $film = new Film();
            $film->title = "从程序猿到攻城狮";
            $film->synopsis = "这里将带你见证一只程序猿到攻城狮的一路历程！";
            $film->price = 99;
            $film->show_time = strtotime('');
            $film->out_time = strtotime('');
            $film->label_id = 15;
            $film->director = "因特网少爷";
            $film->mins = 0101;
            $film->actor = "Java、PHP、C#、HTML、CSS、Js";

            if ($film->validate(true)->save($film->getData())){

                $coverImg="/movies/public/uploads/film/df.png";
                $film->cover = $coverImg;
                $film->save();
//            return $this->success('保存成功',url('admin/film/home'));
            }else{
                return $this->error($film->getError());
            }
        }
    }

    public function getData(){
        echo file_get_contents(ROOT_PATH."/movies/public/uploads/film/df.png");
    }

    public function test4(){
        $user = Communal::getUserByEmail("a@qq.com");
        $type=getimagesize(ROOT_PATH.$user->getData("icon"));//取得图片的大小，类型等
//        dump($type);
        $fp=fopen(ROOT_PATH.$user->getData("icon"),"r")or die("Can't open file");
        $file_content=chunk_split(base64_encode(fread($fp,filesize(ROOT_PATH.$user->getData("icon")))));//base64编码
        switch($type[2]){//判读图片类型
            case 1:$img_type="gif";break;
            case 2:$img_type="jpg";break;
            case 3:$img_type="png";break;
        }
        $img='data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码
        fclose($fp);
        echo "<img src='$img' />";
    }
}
