<?php
namespace app\index\model;

use think\Model;

class Fans extends Model
{
    public function getFansNum($user_id)
    {
        $fansNum = Fans::field('count(fans_id) as num')->where('user_id','=',"$user_id")->find();
        return $fansNum['num'];
    }
    public function getAttentionList($user_id)
    {
        $attentionList = Fans::field('index_user.user_id,index_user.user_name')->join('index_user','index_user.user_id = fans.user_id')->where('index_user.user_able','=','1')->where('fans.fans_id','=',"$user_id")->select();
        return $attentionList;
    }
}