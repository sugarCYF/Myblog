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
    public function cancelCollec($blog_id)
    {
        $res = Collec::where('blog_id','=',"$blog_id")->delete();
        return $res;
    }
}