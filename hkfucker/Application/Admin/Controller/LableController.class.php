<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/3
 * Time: 17:22
 */

namespace Admin\Controller;


class LableController extends AdminController
{
    public function index(){
        $num= M('label')->distinct(true)->count();
        $per_num =8;
        $k=intval(I('get.k',1,'addslashes'));
        //print_r(I('get.','','intval'));
        $pages = ceil($num/$per_num);
        //$this->assign('pages',$pages+1);
        if($k!==null){
            $k=$k;
        }else{
            $k=1;
        }
        if($k<1){
            $k =1;
        }else if($k > $pages){
            $k = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$k-$off;
        $end=$k+$off;
        //起始页
        if($k-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($k+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('k',$k);
        $data =M('label')->page($k.','.$per_num)->filter('strip_tags')->select();
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign("data",$data);
        $this->assign('state',$state);
        $this->display();


    }

    public function add(){

        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Lable/index');
                return;
            }
            $lable = M("label");
            $lable->label_status=I('post.label_status','','intval');
            $lable->label_name=I('post.label_name','','stripslashes');
            if($lable->filter('strip_tags')->add()){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('添加成功');</script>";
                echo "<script> window.location.href='".U('Lable/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加失败2！"); </script>';
                echo "<script> window.location.href='".U('Lable/index')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $this->display();
        }
    }

    public function edit(){
        //$goodscate = new \Admin\Model\goodscateModel();
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Lable/index');
                return;
            }
            //print_r($_POST);
            $lable = M("label");
            $lable->label_status=I('post.label_status','','intval');
            $lable->label_name=I('post.label_name','','addslashes');
            $id = intval(I("post.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"); </script>';
                echo "<script> window.location.href='".U('Lable/index')."';</script>";
                exit();
            }else{
                if($lable->where('id=%d',array($id))->filter('strip_tags')->save() !== false){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改成功！"); </script>';
                    echo "<script> window.location.href='".U('Lable/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改失败！"); </script>';
                    echo "<script> window.location.href='".U('Lable/index')."';</script>";
                    exit();
                }
            }
        }else{
            $id = intval(I("get.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }else{
                $lable = D("label");
                $data = $lable->where('id = %d',array($id))->find();
                $this -> assign('data',$data);
            }
            creatToken();
            $this->display();
        }
    }

    public function del(){
        if(IS_AJAX){
            $id = intval(I("post.id",0,'addslashes'));
            if($id==0){
                echo 0;
            }else{
                $lable = M("label");
                if($lable->where('`id`= %d',array($id))->filter('strip_tags')->delete()){
                    echo 1;
                }else{
                    echo 0;
                }
            }
        }else{
            echo -1;
        }
    }
}