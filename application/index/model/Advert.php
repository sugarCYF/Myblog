<?php
namespace app\index\model;

use think\Model;

class Advert extends Model
{
    protected $pk = 'advert_id';
    public function getAdvertList()
    {
        $advertList = Advert::where('advert_is_show','=','1')->select()->toArray();
        return $advertList;
    }
}