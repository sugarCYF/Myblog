<?php
namespace app\admin\controller;

use think\facade\Session;

class Menu extends Rbac
{
    public function index(){



        return $this->fetch('welcome');
//        return view('index',['arr'=>$arr]);
    }

    public function pass(){
        if($_POST){
            $arr=Session::get('userarr');
            if(md5(input('password_o'))==$arr['user_pwd']){
                $res=db('admin_user')->where('user_id','=',$arr['user_id'])->update(['user_pwd'=>md5($_POST['password'])]);
                if($res){
                    $arr['user_pwd']=md5($_POST['password']);
                    Session::set('userarr',$arr);
                    $this->success('修改成功','/admin/menu/welcome');
                }else{
                    $this->error('修改失败','/admin/menu/pass');
                }
            }
        }else{

            return $this->fetch('pass');
        }
    }
}