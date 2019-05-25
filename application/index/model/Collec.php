<?php
namespace app\index\model;

use think\Model;

class Collec extends Model
{
    public function getCollecList($user_id)
    {
        $collecList = Collec::join('blog','blog.blog_id = collec.blog_id')->join('index_user','index_user.user_id = blog.user_id')->where('blog.is_show','=',"1")->where('collec.user_id','=',"$user_id")->order('blog.publishtime','desc')->select();
        return $collecList;
    }
    public function cancelCollec($user_id,$blog_id)
    {
        $res = Collec::where('user_id','=',"$user_id")->where('blog_id','=',"$blog_id")->delete();
        return $res;
    }
    public function addCollec($user_id,$blog_id)
    {
        $res = Collec::insert(['user_id' => $user_id , 'blog_id' => $blog_id]);
        return $res;
    }
    public function isCollec($user_id,$blog_id)
    {
        $arr = Collec::where('user_id','=',"$user_id")->where('blog_id','=',"$blog_id")->find();
        return $arr;
    }
}