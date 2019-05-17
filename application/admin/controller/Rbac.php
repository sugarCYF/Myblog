<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Session;

class Rbac extends Controller
{
    public function initialize(){
        $this->layoutShow();
        $this->checkrbac();
        $this->checklogin();
    }
    public function checklogin(){
        if(!Session::has('userarr')){
            $this->error('请先登录','/admin/index/index');
        }
    }
    public function checkrbac(){
        $controllername=Request::instance()->controller();
        $actionname=Request::instance()->action();
        $node=$controllername.'/'.$actionname;
        $rbaclist=Session::get('rbaclist');
        if(!in_array($node,$rbaclist)){
            $this->error('无权限访问','/admin/menu/welcome');
        }
    }
    public function layoutShow(){
        $userarr = Session::get('userarr');
        $this->assign('userarr', $userarr);
        $this->view->engine->layout(true);
    }
}