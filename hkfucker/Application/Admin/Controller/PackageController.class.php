<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 10:02
 */

namespace Admin\Controller;
use Think\Controller;

class PackageController extends AdminController
{
    public function index(){
        $data=M('Package')->order('id asc')->filter('strip_tags')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Package/index');
                return;
            }
            //print_r($_POST);
            //$id=I('post.id','','intval');
            $where['id'] = ':id';
            $data_prop= M('Shop')->where($where)->bind(':id',I('post.id'),\PDO::PARAM_INT)->filter('strip_tags')->find();
            //M('Shop')->where('id ='.$id)->find();

            //$num=I('post.num','','');
            //$diamond=I('post.diamond','','');
            //$data['name']=$data_prop['name'];
            //$data['num']=$num;
            //$data['type']=0;
            $Package = M('Package');
            $old['name'] = ':name';
            $data_old = $Package->where($old)->bind(':name',$data_prop['name'],\PDO::PARAM_STR)->filter('strip_tags')->find();

            if(empty($data_old)){
                //echo 1;die;
                $Package->name = $data_prop['name'];
                $Package->type = intval(0);
                $Package->num = I('post.num','','addslashes');
				$Package->prop_id = $data_prop['prop_id'];
                if($Package->filter('strip_tags')->add()){
                    $diamond = I('post.diamond','','addslashes');
                    if (!empty($diamond)){
                        $diam = $Package->where($old)->bind(':name','diamond',\PDO::PARAM_STR)->filter('strip_tags')->find();
                        if (empty($diam)){
                            $Package->name = 'diamond';
                            $Package->type = intval(1);
                            $Package->num = $diamond;
                            if($Package->add()){
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                echo '<script> alert("添加成功！"); </script>';
                                echo "<script> window.location.href='".U('Package/index')."';</script>";
                                exit();
                            }
                        }else{
                            $diamond = I('post.diamond','','addslashes');
                            $mond['id'] = ':id';
                            if($Package->where($mond)->bind(':id',$diam['id'],\PDO::PARAM_INT)->filter('strip_tags')->setInc('num',$diamond)){
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                                echo '<script> alert("添加成功！"); </script>';
                                echo "<script> window.location.href='".U('Package/index')."';</script>";
                                exit();
                            }
                        }
                    }else{
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("添加成功！"); </script>';
                        echo "<script> window.location.href='".U('Package/index')."';</script>";
                        exit();
                    }
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("该物品已存在,请直接修改数量！"),history.back(); </script>';
                    exit();
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("该物品已存在,请直接修改数量！"),history.back(); </script>';
                exit();
            }
        }else{
            creatToken();
            $data_prop=M('Shop')->select();
            $this->assign('data_prop',$data_prop);
            $this->display();
        }

    }
    
    
    public function edit(){
        $Package = M('Package');
        $where['id'] = ':id';
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Package/index');
                return;
            }
            //$id=I('post.id','','addslashes');
            $data_new['num']=I('post.num','','addslashes');
            if($Package->where($where)->bind(':id',I('post.id'),\PDO::PARAM_INT)->filter('strip_tags')->save($data_new) !==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改成功！"); </script>';
                echo "<script> window.location.href='".U('Package/index')."';</script>";
                exit();
            }
        }else{
            creatToken();
            //$id=I('get.id','','');
            $data=$Package->where($where)->bind(':id',I('get.id'),\PDO::PARAM_INT)->filter('strip_tags')->find();
            $this->assign('data',$data);
            $this->display();
        }
    }

    public function del(){
        if(IS_AJAX){
            $where['id'] = ':id';
            if(M('Package')->where($where)->bind(':id',I('post.id'),\PDO::PARAM_INT)->filter('strip_tags')->delete()){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo -1;
        }
    }
}