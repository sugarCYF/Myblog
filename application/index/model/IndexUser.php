<?php
namespace app\index\model;

use think\Model;

class IndexUser extends Model
{
    protected $pk = 'user_id';
    public function changeVali($user_id)
    {
        $res = IndexUser::where('user_id','=',"$user_id")->update(['user_vali' => '1']);
        return $res;
    }
    public function checkUserName($user_name)
    {
        $arr = IndexUser::where('user_name','=',"$user_name")->find();
        return $arr;
    }
    public function checkUserEmail($user_email)
    {
        $arr = IndexUser::where('user_email','=',"$user_email")->find();
        return $arr;
    }
    public function savePwd($user_id,$data)
    {
        $res = IndexUser::where('user_id','=',"$user_id")->update($data);
        return $res;
    }
    public function fgPwd($user_email,$data)
    {
        $res = IndexUser::where('user_email','=',"$user_email")->update($data);
        return $res;
    }
}