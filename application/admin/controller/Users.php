<?php
namespace app\admin\controller;

use think\Db;

class Users extends Rbac
{
    public function index_userlist(){
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';
        $count=Db::query("select count(*) as count from index_user where user_name like '%$keywords%'");
        $page=ceil($count[0]['count']/5);
        $p=isset($_GET['p'])?$_GET['p']:'1';
        if($p<1){
            $p=1;
        }elseif($p>$page){
            $p=$page;
        }
        $limit=($p-1)*5;
        $userlist=db('index_user')->where('user_name','like',"%$keywords%")->limit("$limit",'5')->select();
//        return view('index_userlist',['userlist'=>$userlist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);


        return $this->fetch('index_userlist',['userlist'=>$userlist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);
    }
    public function changeable(){
        $id=$_GET['id'];
        $user_able=$_GET['user_able'];
        db('index_user')->where('user_id','=',"$id")->update(['user_able'=>$user_able]);
        return $this->index_userlist();
    }
}