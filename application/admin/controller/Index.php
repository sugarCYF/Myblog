<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Index extends Controller
{
    public function index()
    {
        if($_POST){
            if(empty(input('user_name'))||empty(input('user_pwd'))){
                $this->error('登陆失败','/admin/index/index');
            }
            $user_name=input('user_name');
            $arr=db('admin_user')->where('user_name','=',"$user_name")->where('is_able','=','1')->find();
            if(isset($arr['user_pwd']) && $arr['user_pwd']==md5(input('user_pwd'))){
                $list=db('admin_user')->field('node_content')
                                                ->join('user_role','admin_user.user_id=user_role.user_id')
                                                ->join('admin_role','user_role.role_id=admin_role.role_id')
                                                ->join('role_node','admin_role.role_id=role_node.role_id')
                                                ->join('admin_node','role_node.node_id=admin_node.node_id')
                                                ->where('admin_user.user_name','=',"$user_name")
                                                ->select();
                foreach($list as $k => $v){
                    $rbaclist[$k]=$v['node_content'];
                }
                Session::set('userarr',$arr);
                Session::set('rbaclist',$rbaclist);
                $this->success('登陆成功','admin/menu/index');
            }else{
                $this->error('登陆失败','/admin/index/index');
            }
        }else{
            return view('index');
        }
    }
    public function signout(){
        Session::delete('userarr');
        Session::delete('rbaclist');
        return view('index');
    }
}
