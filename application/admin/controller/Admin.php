<?php
namespace app\admin\controller;

use think\Db;

class Admin extends Rbac
{
    public function adminlist()
    {
        $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
        $count = Db::query("select count(*) as count from admin_user where user_name like '%$keywords%'");
        $page = ceil($count[0]['count'] / 5);
        $p = isset($_GET['p']) ? $_GET['p'] : '1';
        if($p < 1){
            $p = 1;
        }elseif($p > $page){
            $p = $page;
        }
        $limit = ($p - 1) * 5;
        $adminlist = db('admin_user')->where('user_name','like',"%$keywords%")->limit("$limit",'5')->select();

        return $this->fetch('adminlist',['adminlist' => $adminlist,'keywords' => $keywords,'page' => $page,'count' => $count[0]['count'],'p' => $p]);
    }
    public function changeable()
    {
        $id = $_GET['id'];
        $is_able = $_GET['is_able'];
        db('admin_user')->where('user_id','=',"$id")->update(['is_able' => $is_able]);
        return $this->adminlist();
    }
    public function adminadd()
    {
        if($_POST){
            $user_name = $_POST['user_name'];
            $arr = db('admin_user')->where('user_name','=',"$user_name")->find();
            if(isset($arr['user_name']) || !empty($arr['user_name'])){
                $this->error('管理员已存在','/admin/admin/adminadd');
            }
            if(!isset($_POST['role_id'])||empty($_POST['role_id'])){
                $this->error('请选择职位','/admin/admin/adminadd');
            }
            $data = $_POST;
            $role_id = $_POST['role_id'];
            unset($data['role_id']);
            unset($data['pwd_c']);
            $data['user_pwd'] = md5($data['user_pwd']);
            $data['is_able'] = 0;
            //验证器
            $result = $this->validate($data,'app\admin\validate\AdminUser');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/admin/admin/adminadd');
            }

            db('admin_user')->insert($data);
            $arr = db('admin_user')->field('user_id')->where('user_name','=',"$user_name")->find();
            foreach($role_id as $k => $v){
                $array[$k]['user_id'] = $arr['user_id'];
                $array[$k]['role_id'] = $v;
            }
            db('user_role')->insertAll($array);
            $this->success('添加成功','/admin/admin/adminadd');
        }else{
            $rolelist=db('admin_role')->select();

            return $this->fetch('adminadd',['rolelist' => $rolelist]);
        }
    }
    public function admindel()
    {
        $id = $_GET['id'];
        db('admin_user')->where('user_id','=',"$id")->delete();
        db('user_role')->where('user_id','=',"$id")->delete();
        return $this->adminlist();
    }
    public function roleadd()
    {
        if($_POST){
            $role_name = $_POST['role_name'];
            $arr = db('admin_role')->where('role_name','=',"$role_name")->find();
            if(isset($arr['role_name']) || !empty($arr['role_name'])){
                $this->error('职位已存在','/admin/admin/roleadd');
            }
            if(!isset($_POST['node_id'])||empty($_POST['node_id'])){
                $this->error('请选择权限','/admin/admin/roleadd');
            }

            $result = $this->validate(['role_name' => $role_name],'app\admin\validate\AdminRole');
            if (true !== $result) {
                // 验证失败 输出错误信息
                $this->error($result,'/admin/admin/roleadd');
            }

            db('admin_role')->insert(['role_name' => $role_name]);

            $node_id = $_POST['node_id'];
            $arr = db('admin_role')->field('role_id')->where('role_name','=',"$role_name")->find();
            foreach($node_id as $k => $v){
                $array[$k]['role_id'] = $arr['role_id'];
                $array[$k]['node_id'] = $v;
            }
            db('role_node')->insertAll($array);
            $this->success('添加成功','/admin/admin/roleadd');
        }else{
            $nodelist = db('admin_node')->select();

            return $this->fetch('roleadd',['nodelist' => $nodelist]);
        }
    }
    public function changerole()
    {
        $id = $_GET['id'];
        if($_POST){
            if(!isset($_POST['role_id'])||empty($_POST['role_id'])){
                $this->error('请选择职位','/admin/admin/adminlist');
            }
            db('user_role')->where('user_id','=',"$id")->delete();
            $data = $_POST;
            for($i = 0 ; $i < count($data['role_id']) ; $i++){
                $arr[$i]['user_id'] = $id;
                $arr[$i]['role_id'] = $data['role_id'][$i];
            }
            db('user_role')->insertAll($arr);
            return $this->adminlist();
        }else{
            $adminlist = db('admin_user')->join('user_role','admin_user.user_id = user_role.user_id')->join('admin_role','admin_role.role_id = user_role.role_id')->where('admin_user.user_id','=',"$id")->select();
            foreach($adminlist as $k => $v){
                $array['user_id'] = $v['user_id'];
                $array['role_id'][$k] = $v['role_id'];
            }
            $rolelist=db('admin_role')->select();

            return $this->fetch('changerole',['adminlist' => $array,'rolelist' => $rolelist]);
        }
    }
    public function rolelist()
    {
        $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
        $count = Db::query("select count(*) as count from admin_role where role_name like '%$keywords%'");
        $page = ceil($count[0]['count']/5);
        $p = isset($_GET['p']) ? $_GET['p'] : '1';
        if($p < 1){
            $p = 1;
        }elseif($p > $page){
            $p = $page;
        }
        $limit = ($p - 1) * 5;
        $rolelist = db('admin_role')->where('role_name','like',"%$keywords%")->limit("$limit",'5')->select();

        return $this->fetch('rolelist',['rolelist'=>$rolelist,'keywords'=>$keywords,'page'=>$page,'count'=>$count[0]['count'],'p'=>$p]);
    }
    public function changenode()
    {
        $id=$_GET['id'];
        if($_POST){
            if(!isset($_POST['node_id'])||empty($_POST['node_id'])){
                $this->error('请选择权限','/admin/admin/rolelist');
            }
            db('role_node')->where('role_id','=',"$id")->delete();
            $data=$_POST;
            for($i=0;$i<count($data['node_id']);$i++){
                $arr[$i]['role_id']=$id;
                $arr[$i]['node_id']=$data['node_id'][$i];
            }
            db('role_node')->insertAll($arr);
            return $this->rolelist();
        }else{
            $rolelist=db('admin_role')->join('role_node','admin_role.role_id=role_node.role_id')->join('admin_node','admin_node.node_id=role_node.node_id')->where('admin_role.role_id','=',"$id")->select();
            foreach($rolelist as $k => $v){
                $array['role_id']=$v['role_id'];
                $array['node_id'][$k]=$v['node_id'];
            }
            $nodelist=db('admin_node')->select();

            return $this->fetch('changenode',['rolelist'=>$array,'nodelist'=>$nodelist]);
        }
    }
    public function roledel()
    {
        $id=$_GET['id'];
        db('admin_role')->where('role_id','=',"$id")->delete();
        db('role_node')->where('role_id','=',"$id")->delete();
        return $this->rolelist();
    }
}