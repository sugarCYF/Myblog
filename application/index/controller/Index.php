<?php
namespace app\index\controller;

use app\index\model\Advert;
use app\index\model\Attention;
use app\index\model\Blog;
use app\index\model\Collec;
use app\index\model\Discuss;
use app\index\model\Fans;
use app\index\model\IndexUser;
use app\index\model\Link;
use think\Controller;
use think\facade\Session;
use think\facade\Validate;

class Index extends Controller
{
    static $array;
    public function initialize()
    {
        $this->view->engine->layout(true);
    }
    public function checkLogin()
    {
        if(!Session::has('user_arr')){
            $this->error('请先登陆','/index/index/index');
        }
    }
    public function index()
    {
        $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '' ;
        $Advert = new Advert();
        $advertList = $Advert->getAdvertList();

        $Blog = new Blog();
        $blogList = $Blog->getBlogList($keyword);
        $page = $blogList->render();

        $blogList_d = $Blog->getBlogListByDiscuss();

        $hostBlog = $Blog->getHostBlog();

        $Link = new Link();
        $linkList = $Link->getLinkList();

        $array = [
            'advertList' => $advertList ,
            'blogList' => $blogList ,
            'keyword' => $keyword ,
            'page' => $page ,
            'blogList_d' => $blogList_d ,
            'linkList' => $linkList ,
            'hostBlog' => $hostBlog
        ];
        return view('index',$array);
    }
    public function savePwd()
    {
        if($_POST){
            if($_POST['pwd_c'] != $_POST['user_pwd']){
                $this->error('两次输入密码不一致','/index/index/savePwd');
            }
            $user_arr = Session::get('user_arr');
            if(md5($_POST['pwd_o']) == $user_arr['user_pwd']){
                $IndexUser = new IndexUser();
                $data['user_pwd'] = $_POST['user_pwd'];

                $rule = ['user_pwd'   => [  'regex' => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,30}/'],];
                $msg = ['user_pwd.regex'   => '密码必须6-30位，有字母大小写和数字',];
                $validate = Validate::make($rule,$msg);
                $result = $validate->check($data);
                if (true !== $result) {
                    // 验证失败 输出错误信息
                    $this->error($validate->getError,'/index/index/savePwd');
                }
                $data['user_pwd'] = md5($data['user_pwd']);
                $res = $IndexUser->savePwd($user_arr['user_id'],$data);
                if($res){
                    $user_arr['user_pwd'] = md5($_POST['pwd_o']);
                    Session::set('user_arr',$user_arr);
                    $this->success('修改成功','/index/index/index');
                }else{
                    $this->error('修改失败','/index/index/savePwd');
                }
            }else{
                $this->error('原密码错误','/index/index/savePwd');
            }
        }else{
            return view('savePwd');
        }
    }
    public function forgotPassword()
    {
        if($_POST){
            if($_POST['user_pwd'] != $_POST['pwd_c']){
                $this->error('两次输入密码不一致','/index/index/forgotPassword');
            }
            if($_POST['code'] != Session::get('code') || time() > Session::get('time')){
                $this->error('验证码错误','/index/index/forgotPassword');
            }

            $IndexUser = new IndexUser();
            $data['user_pwd'] = $_POST['user_pwd'];

            $rule = ['user_pwd'   => [  'regex' => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,30}/'],];
            $msg = ['user_pwd.regex'   => '密码必须6-30位，有字母大小写和数字',];
            $validate = Validate::make($rule,$msg);
            $result = $validate->check($data);
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($validate->getError,'/index/index/forgotPassword');
            }
            $data['user_pwd'] = md5($data['user_pwd']);
            $res = $IndexUser->fgPwd($_POST['user_email'],$data);
            if($res){
                $this->success('找回成功','/index/index/index');
            }else{
                $this->error('找回失败','/index/index/forgotPasswordPwd');
            }

        }else{
            return view('forgotPassword');
        }
    }
    public function sendCode()
    {
        $user_email = $_GET['user_email'];
        $code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $message = '您的账号正在进行找回密码，本次验证码为'.$code.'两分钟后过期，如非本人操作请勿泄露验证码';
        $time = time() + 120;
        sendEmail($user_email,$message);
        Session::set('code',$code);
        Session::set('time',$time);

        echo json_encode(['code' => $code]);
    }
    public function writeBlog()
    {
        if($_POST){
            $data = $_POST;

            $result = $this->validate($data,'app\index\validate\Blog');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/index/index/writeBlog');
            }
            $data['publishtime'] = time();
            $data['host_num'] = 0;
            $data['collec_num'] = 0;
            $data['user_id'] = Session::get('user_arr')['user_id'];
            $data['is_show'] = 3;
            $Blog = new Blog();
            $res = $Blog->insert($data);
            if($res){
                $this->success('发表成功，待管理员审核','/index/index/writeBlog');
            }else{
                $this->error('发表失败','/index/index/writeBlog');
            }
        }else{
            return view('writeBlog');
        }
    }
    public function blogShow()
    {
        $blog_id = $_GET['blog_id'];
        $Blog = new Blog();
        $oneBlog = $Blog->getOneBlog($blog_id);
        if($oneBlog){
            $Discuss = new Discuss();
            $discussList = $Discuss->getDiscussList($blog_id);
            $array = $this->digui($discussList);
            $blogList_d = $Blog->getBlogListByDiscuss();
            return view('blogShow',['oneBlog' => $oneBlog , 'discussList' => $array , 'blogList_d' => $blogList_d]);
        }else{
            $this->error('未找到这篇博客','/index/index/index');
        }
    }
    public function digui($discussList,$pid=0,$level=0)
    {
        foreach ($discussList as $k => $v){
            if($v['pid'] == $pid){
                $v['level'] = $level;
                self::$array[] = $v;
                $this->digui($discussList,$v['discuss_id'],$level+1);
            }
        }
        return self::$array;
    }
    public function addDiscuss()
    {
        $data['discuss_content'] = $_POST['discuss_content'];

        $result = $this->validate($data,'app\index\validate\Discuss');
        if (true !== $result) {
            // 验证失败 输出错误信息
            $this->error($result,'/index/index/blogShow');
        }

        $data['blog_id'] = $_GET['blog_id'];
        $data['discuss_addtime'] = time();
        $data['pid'] = isset($_GET['pid']) ? $_GET['pid'] : 0 ;
        $data['user_id'] = Session::get('user_arr')['user_id'];
        $data['discuss_is_show'] = 3;
        $Discuss = new Discuss();
        $res = $Discuss->insert($data);
        if($res){
            $this->success('评论成功待管理员审核','/index/index/blogShow?blog_id='.$_GET['blog_id']);
        }else{
            $this->error('评论失败','/index/index/blogShow?blog_id='.$_GET['blog_id']);
        }
    }
    public function userCenter()
    {
        $user_arr = Session::get('user_arr');

        $Fans = new Fans();
        $fansNum = $Fans->getFansNum($user_arr['user_id']);


        $attentionList = $Fans->getAttentionList($user_arr['user_id']);
        return view('userCenter',['user_arr' => $user_arr , 'fansNum' => $fansNum , 'attention' => $attentionList]);
    }
    public function collec()
    {
        $Collec = new Collec();
        $collecList = $Collec->getCollecList(Session::get('user_arr')['user_id']);
        return view('collec',['collecList' => $collecList]);
    }
    public function delCollec()
    {
        $Collec = new Collec();
        $res = $Collec->cancelCollec($_GET['blog_id']);
        if($res){
            $this->success('取消成功','/index/index/collec');
        }else{
            $this->error('取消失败','/index/index/collec');
        }
    }
}
