<?php
namespace app\index\controller;

use app\index\model\Advert;
use app\index\model\Blog;
use app\index\model\IndexUser;
use think\Controller;
use think\facade\Session;
use think\facade\Validate;

class Index extends Controller
{
    public function initialize()
    {
        $this->view->engine->layout(true);
    }
    public function index()
    {
        $Advert = new Advert();
        $advertList = $Advert->getAdvertList();

        $Blog = new Blog();
        $blogList = $Blog->getBlogList();
        return view('index',['advertList' => $advertList , 'blogList' => $blogList]);
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
}
