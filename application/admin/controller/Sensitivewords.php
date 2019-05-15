<?php
namespace app\admin\controller;

use think\Db;

class Sensitivewords extends Rbac
{
    public function swadd(){
        if($_POST){
            $word=$_POST['word'];
            $arr=db('sensitive_words')->where('word','=',"$word")->find();
            if(isset($arr['word'])||!empty($arr['word'])){
                $this->error('添加词汇已存在','/admin/sensitivewords/swadd');
            }
            $res=db('sensitive_words')->insert(['word'=>$word]);
            if($res){
                $this->success('添加成功','/admin/sensitivewords/swadd');
            }else{
                $this->error('添加失败','/admin/sensitivewords/swadd');
            }
        }else{
            return view('swadd');
        }
    }
    public function swlist(){
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';

        $swlist=db('sensitive_words')->where('word','like',"%$keywords%")->paginate(5);
        $page=$swlist->render();
        return view('swlist',['swlist'=>$swlist,'keywords'=>$keywords,'page'=>$page]);
    }
    public function swdel(){
        $id=$_GET['id'];
        db('sensitive_words')->where('id','=',"$id")->delete();
        return $this->swlist();
    }
}