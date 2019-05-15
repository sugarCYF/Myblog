<?php
namespace app\admin\controller;

use think\Db;

class Advert extends Rbac
{
    public function advertadd(){
        if($_POST){
            $data=$_POST;
            $data['advert_image']=$this->upload();
            $data['advert_addtime']=time();
            if(empty($data['advert_link'])){
                $data['advert_link']='javascript:;';
            }
            if(empty($data['advert_remarks'])){
                $data['advert_remarks']='无';
            }
            $res=db('advert')->insert($data);
            if($res){
                $this->success('添加成功','/admin/advert/advertadd');
            }else{
                $this->error('添加失败','/admin/advert/advertadd');
            }
        }else{
            return view('advertadd');
        }
    }
    public function advertlist(){
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';
        $count=Db::query("select count(*) as count from advert where advert_name like '%$keywords%'");
        $page=ceil($count[0]['count']/5);
        $p=isset($_GET['p'])?$_GET['p']:'1';
        if($p<1){
            $p=1;
        }elseif($p>$page){
            $p=$page;
        }
        $limit=($p-1)*5;
        $advertlist=db('advert')->where('advert_name','like',"%$keywords%")->limit("$limit",'5')->select();
        return view('advertlist',['advertlist'=>$advertlist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);
    }
    public function changeshow(){
        $id=$_GET['id'];
        $advert_is_show=$_GET['advert_is_show'];
        db('advert')->where('advert_id','=',"$id")->update(['advert_is_show'=>$advert_is_show]);
        return $this->advertlist();
    }
    public function advertdel(){
        $id=$_GET['id'];
        $arr=db('advert')->where('advert_id','=',"$id")->find();
        unlink('../public/uploads/'.$arr['advert_image']);
        db('advert')->where('advert_id','=',"$id")->delete();
        return $this->advertlist();
    }
    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('advert_image');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move( '../public/uploads');
        if($info){
            // 成功上传后 获取上传信息

            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            return $info->getSaveName();
        }else{
            // 上传失败获取错误信息
            $this->error('添加失败','/admin/advert/advertadd');
        }
    }
}