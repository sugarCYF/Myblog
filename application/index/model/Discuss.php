<?php
namespace app\index\model;

use think\Model;

class Discuss extends Model
{
    protected $pk = 'discuss_id';
    public function getDiscussList($blog_id)
    {
        $discussList = Discuss::field('discuss.discuss_id,discuss.discuss_content,discuss.blog_id,discuss.discuss_addtime,discuss.pid,index_user.user_name')->join('blog','blog.blog_id = discuss.blog_id')->join('index_user','index_user.user_id = discuss.user_id')->where('discuss.blog_id','=',"$blog_id")->where('discuss.discuss_is_show','=','1')->order('discuss.discuss_addtime','desc')->select();
        return $discussList;
    }
    public function getMyDiscussList($user_id)
    {
        $myDiscussList = Discuss::where('user_id','=',"$user_id")->order('discuss_addtime','desc')->select();
        return $myDiscussList;
    }
    public function delDiscuss($user_id,$discuss_id)
    {
        $res = Discuss::where('user_id','=',"$user_id")->where('discuss_id','=',"$discuss_id")->delete();
        return $res;
    }
}