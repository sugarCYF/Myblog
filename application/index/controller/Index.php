<?php
namespace app\index\controller;

use app\index\model\Advert;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $Advert = new Advert();
        $advertList = $Advert->getAdvertList();
//        echo "<pre>";
//        var_dump($advertList);die;
//        $this->view->engine->layout(true);
//        return $this->fetch('index');
        return view('index',['advertList' => $advertList]);
    }


}
