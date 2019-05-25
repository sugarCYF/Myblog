<?php
namespace app\index\model;

use think\Db;
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
    public function getBlogListByDiscuss()
    {
        $arr = Blog::field('*,max(discuss.discuss_addtime) as time')->join('discuss','blog.blog_id = discuss.blog_id')->order('time','desc')->group('blog.blog_id')->limit('0','8')->select();
        return $arr;
    }
    public function getHostBlog()
    {
        $arr = Blog::where('is_show','=','1')->order('host_num','desc')->find();
        return $arr;
    }
    public function getMyBlogList($user_id)
    {
        $myBlogList = Blog::where('user_id','=',"$user_id")->select();
        return $myBlogList;
    }
    public function delBlog($user_id,$blog_id)
    {
        $res = Blog::where('user_id','=',"$user_id")->where('blog_id','=',"$blog_id")->delete();
        return $res;
    }
    public function addCollec($blog_id)
    {
        $res = Blog::where('blog_id','=',"$blog_id")->update(['collec_num' => Db::raw('collec_num + 1')]);
        return $res;
    }
    public function addHost($blog_id)
    {
        $res = Blog::where('blog_id','=',"$blog_id")->update(['host_num' => Db::raw('host_num + 1')]);
        return $res;
    }
    public function delHost($blog_id)
    {
        $res = Blog::where('blog_id','=',"$blog_id")->update(['host_num' => Db::raw('host_num - 1')]);
        return $res;
    }
    public function delCollec($blog_id)
    {
        $res = Blog::where('blog_id','=',"$blog_id")->update(['collec_num' => Db::raw('collec_num - 1')]);
        return $res;
    }
}