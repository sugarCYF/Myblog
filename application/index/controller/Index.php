<?php
namespace app\index\controller;

use app\index\model\Advert;
use app\index\model\Blog;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $Advert = new Advert();
        $advertList = $Advert->getAdvertList();

        $Blog = new Blog();
        $blogList = $Blog->getBlogList();
//        echo "<pre>";
//        var_dump($blogList);die;
        return view('index',['advertList' => $advertList , 'blogList' => $blogList]);
    }

    public function sign()
    {
        return view('sign');
    }

}
