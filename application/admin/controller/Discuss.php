<?php
namespace app\admin\controller;

use think\Db;

class Discuss extends Rbac
{
    public function discusslist(){
        $discuss_is_show=$_GET['discuss_is_show'];
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';
        $sql="select count(*) as count from discuss join index_user on index_user.user_id=discuss.user_id join blog on discuss.blog_id=blog.blog_id where discuss.discuss_is_show = $discuss_is_show and (blog.blog_title like '%$keywords%' or index_user.user_name like '%$keywords%')";
//        echo $sql;die;
        $count=Db::query($sql);
        $page=ceil($count[0]['count']/5);
        $p=isset($_GET['p'])?$_GET['p']:'1';
        if($p<1){
            $p=1;
        }elseif($p>$page){
            $p=$page;
        }
        $limit=($p-1)*5;
        $discusslist=db('discuss')->join('index_user','index_user.user_id=discuss.user_id')->join('blog','blog.blog_id=discuss.blog_id')->where('discuss_is_show','=',"$discuss_is_show")->where('blog_title|user_name','like',"%$keywords%")->limit("$limit",'5')->select();

        return view('discusslist',['discusslist'=>$discusslist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);
    }
    public function changeshow(){
        $id=$_GET['id'];
        $is_show=$_GET['is_show'];

        db('discuss')->where('discuss_id','=',"$id")->update(['discuss_is_show'=>$is_show]);
        return $this->discusslist();
    }
}