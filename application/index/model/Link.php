<?php
namespace app\index\model;

use think\Model;

class Link extends Model
{
    protected $pk = 'link_id';
    public function getLinkList()
    {
        $linkList = Link::order('link_addtime','desc')->select();
        return $linkList;
    }
}