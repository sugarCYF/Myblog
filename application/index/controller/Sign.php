<?php
namespace app\index\controller;

use app\index\model\IndexUser;
use think\Controller;
use think\facade\Session;

class Sign extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function signUp()
    {
        $data = $_POST;
        if($data['user_pwd'] != $data['pwd_c']){
            $this->error('两次输入密码不一至','/index/sign/index');
        }
        unset($data['pwd_c']);
        $IndexUser = new IndexUser();
        $user_name = $data['user_name'];
        $arr = $IndexUser->checkUserName($user_name);
        if(isset($arr['user_name']) || !empty($arr['user_name'])){
            $this->error('用户名已被占用','/index/sign/index');
        }
        $user_email = $data['user_email'];
        $arr = $IndexUser->checkUserEmail($user_email);
        if(isset($arr['user_email']) || !empty($arr['user_email'])){
            $this->error('邮箱已被占用','/index/sign/index');
        }

        $result = $this->validate($data,'app\index\validate\IndexUser');
        if (true !== $result) {
            // 验证失败 输出错误信息
            $this->error($result,'/index/sign/index');
        }
        $data['addtime'] = time();
        $data['user_able'] = 1;
        $data['user_vali'] = 0;

        $data['user_pwd'] = md5($data['user_pwd']);
        $res = $IndexUser->insert($data);
        if($res){
            $user_id = $IndexUser->getLastInsID();
            $message = 'http://www.blog.com/index/sign/changeVali?user_id='.$user_id.'   点击链接进行激活';
            sendEmail($data['user_email'],$message);
            $this->success('注册成功请进入邮箱验证激活','/index/sign/index');
        }else{
            $this->error('注册失败','/index/sign/index');
        }
    }
    public function signIn()
    {
        $user_name = $_POST['user_name'];
        $IndexUser = new IndexUser();
        $user_arr = $IndexUser->checkUserName($user_name);
        if(isset($user_arr['user_pwd']) && md5($_POST['user_pwd']) == $user_arr['user_pwd']){
            Session::set('user_arr',$user_arr);
            $this->success('登陆成功','/index/index/index');
        }else{
            $this->error('用户名或密码错误','index/sign/index');
        }
    }
    public function signOut()
    {
        Session::delete('user_arr');
        $this->success('已退出登陆','/index/index/index');
    }
    public function changeVali()
    {
        $user_id = $_GET['user_id'];
        $IndexUser = new IndexUser();
        $res = $IndexUser->changeVali($user_id);
        if($res){
            echo "<script>alert('激活成功，请登陆');location.href='http://www.blog.com/index/sign/index';</script>";
        }else{
            echo "<script>alert('激活失败，请联系管理员206615407@qq.com');location.href='http://www.blog.com/index/sign/index';</script>";
        }
    }
}