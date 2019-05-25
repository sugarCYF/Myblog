<?php
namespace app\index\model;

use think\Model;

class Host extends Model
{
    public function isHost($user_id,$blog_id)
    {
        $arr = Host::where('user_id','=',"$user_id")->where('blog_id','=',"$blog_id")->find();
        return $arr;
    }
    public function addHost($user_id,$blog_id)
    {
        $res = Host::insert(['user_id' => $user_id , 'blog_id' => $blog_id]);
        return $res;
    }
    public function cancelHost($user_id,$blog_id)
    {
        $res = Host::where('user_id','=',"$user_id")->where('blog_id','=',"$blog_id")->delete();
        return $res;
    }
}