<?php
namespace app\admin\controller;

use think\Db;

class Link extends Rbac
{
    public function linkadd(){
        if($_POST){
            $link_title=$_POST['link_title'];
            $arr=db('link')->where('link_title','=',"$link_title")->find();
            if(isset($arr['link_title'])||!empty($arr['link_title'])){
                $this->error('已存在请勿重复添加','/admin/link/linkadd');
            }
            $data=$_POST;
            $data['link_addtime']=time();

            $result = $this->validate($data,'app\admin\validate\Link');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/admin/admin/linkadd');
            }

            $res=db('link')->insert($data);
            if($res){
                $this->success('添加成功','/admin/link/linkadd');
            }else{
                $this->error('添加失败','/admin/link/linkadd');
            }
        }else{

            return $this->fetch('linkadd');
        }
    }
    public function linklist(){
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';

        $linklist=db('link')->where('link_title','like',"%$keywords%")->paginate(5);
        $page=$linklist->render();

        return $this->fetch('linklist',['linklist'=>$linklist,'keywords'=>$keywords,'page'=>$page]);
    }
    public function linkdel(){
        $id=$_GET['id'];
        db('link')->where('link_id','=',"$id")->delete();
        return $this->linklist();
    }
    public function linksave(){

        if($_POST){
            $id=$_POST['id'];
            $link_title=$_POST['link_title'];
            $arr=db('link')->where('link_id','<>',"$id")->where('link_title','=',"$link_title")->find();
            if(isset($arr['link_title'])||!empty($arr['link_title'])){
                $this->error('链接标题已存在','/admin/link/linklist');
            }
            $data=$_POST;
            unset($data['id']);
            $result = $this->validate($data,'app\admin\validate\Link');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/admin/admin/linklist');
            }
            $res=Db::table('link')->where('link_id','=',"$id")->update($data);
            if($res){
                $this->success('编辑成功','/admin/link/linklist');
            }else{
                $this->error('编辑失败','/admin/link/linklist');
            }
        }else{
            $id=$_GET['id'];
            $arr=db('link')->where('link_id','=',"$id")->find();

            return $this->fetch('linksave',['arr'=>$arr]);
        }
    }
}