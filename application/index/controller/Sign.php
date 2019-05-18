<?php
namespace app\index\controller;

use app\index\model\IndexUser;
use think\Controller;

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
        $result = $this->validate($data,'app\index\validate\IndexUser');
        if (true !== $result) {
            // 验证失败 输出错误信息
            $this->error($result,'/index/sign/index');
        }
        $data['addtime'] = time();
        $data['user_able'] = 1;
        $data['user_vali'] = 0;
        $IndexUser = new IndexUser();
        $res = $IndexUser->insert($data);
    }
    public function signIn()
    {

    }
}