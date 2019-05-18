<?php
namespace app\index\model;

use think\Model;

class Blog extends Model
{
    protected $pk = 'blog_id';
    public function getBlogList()
    {
        $blogList = Blog::where('blog.is_show','=','1')->join('index_user','index_user.user_id = blog.user_id')->paginate('8')->toArray();
        return $blogList;
    }
}