<?php
namespace app\admin\controller;

class Sensitivewords extends Rbac
{
    public function swadd(){
        if($_POST){
            $word=$_POST['word'];
            $arr=db('sensitive_words')->where('word','=',"$word")->find();
            if(isset($arr['word'])||!empty($arr['word'])){
                $this->error('添加词汇已存在','/admin/sensitivewords/swadd');
            }

            $result = $this->validate(['word'=>$word],'app\admin\validate\SensitiveWords');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/admin/admin/roleadd');
            }

            $res=db('sensitive_words')->insert(['word'=>$word]);
            if($res){
                $this->success('添加成功','/admin/sensitivewords/swadd');
            }else{
                $this->error('添加失败','/admin/sensitivewords/swadd');
            }
        }else{

            return $this->fetch('swadd');
        }
    }
    public function swlist(){
        $keywords=isset($_GET['keywords'])?$_GET['keywords']:'';

        $swlist=db('sensitive_words')->where('word','like',"%$keywords%")->paginate(5);
        $page=$swlist->render();

        return $this->fetch('swlist',['swlist'=>$swlist,'keywords'=>$keywords,'page'=>$page]);
    }
    public function swdel(){
        $id=$_GET['id'];
        db('sensitive_words')->where('id','=',"$id")->delete();
        return $this->swlist();
    }
}