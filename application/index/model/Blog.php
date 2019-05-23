<?php
namespace app\index\model;

use think\Model;

class Blog extends Model
{
    protected $pk = 'blog_id';
    public function getBlogList($keyword)
    {
        $blogList = Blog::join('index_user','index_user.user_id = blog.user_id')->where('blog.is_show','=','1')->where('blog.blog_title|index_user.user_name','like',"%$keyword%")->order('blog.publishtime','desc')->paginate('8');
        return $blogList;
    }
    public function getOneBlog($blog_id)
    {
        $arr = Blog::join('index_user','index_user.user_id = blog.user_id')->where('blog.blog_id','=',"$blog_id")->where('blog.is_show','=','1')->find();
        return $arr;
    }
}