<?php
namespace app\admin\controller;

use think\Db;

class Blog extends Rbac
{
    public function bloglist(){
        $is_show=$_GET['is_show'];
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';
        $sql="select count(*) as count from blog join index_user on index_user.user_id=blog.user_id where blog.is_show = $is_show and (blog.blog_title like '%$keywords%' or index_user.user_name like '%$keywords%')";
//        echo $sql;die;
        $count = Db::query($sql);
        $page = ceil($count[0]['count']/5);
        $p = isset($_GET['p']) ? $_GET['p'] : '1';
        if($p<1){
            $p=1;
        }elseif($p > $page){
            $p=$page;
        }
        $limit=($p-1)*5;
        $bloglist=db('blog')->join('index_user','index_user.user_id=blog.user_id')->where('is_show','=',"$is_show")->where('blog_title|user_name','like',"%$keywords%")->limit("$limit",'5')->select();

        return $this->fetch('bloglist',['bloglist'=>$bloglist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);
    }
    public function blogcontent(){
        $id=$_GET['id'];
        $arr=db('blog')->join('index_user','index_user.user_id=blog.user_id')->where('blog.blog_id','=',"$id")->find();

        return $this->fetch('blogcontent',['arr'=>$arr]);
    }
    public function changeshow(){
        $id=$_GET['id'];
        $is_show=$_GET['is_show'];
        db('blog')->where('blog.blog_id','=',"$id")->update(['is_show'=>$is_show]);
        return $this->blogcontent();
    }
}