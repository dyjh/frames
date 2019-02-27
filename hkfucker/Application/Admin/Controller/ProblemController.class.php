<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 11:02
 */

namespace Admin\Controller;


class ProblemController extends AdminController
{
    public function index(){
        $count=M('problem')->distinct(true)->count();
        $num =6;
        $p=intval(I('get.p',1,'addslashes'));
        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
        if($p!==null){
            $p=$p;
        }else{
            $p=1;
        }

        if($p<1){
            $p =1;
        }else if($p > $pages){
            $p = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$p-$off;
        $end=$p+$off;
        //起始页
        if($p-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($p+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }

        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页

        $this->assign('p',$p);
        $list =M('problem')->order('id DESC')->page($p.','.$num)->filter('strip_tags')->select();
        foreach ($list as $k=>$v){
            $list[$k]['content']=htmlspecialchars($v['content']);
        }
        $this->assign('data', $list); // 赋值数据集
        if(empty($list)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        //print_r($list);
        //var_dump($count);
        $this->display();
    }
    public function add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Guide/index');
                return;
            }
            $infor=M('Problem');
            $title=_safe(I('post.title'));
            $content=_safe(I('post.content'));
            $time=time();
            $infor->title=$title;
            $infor->content=$content;
            $infor->time=$time;
            if($infor->add()){
                ///echo __URL__;die;
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加成功！"); </script>';
                echo "<script> window.location.href='".U('Problem/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("添加失败！"),history.back(); </script>';
                exit();
            }
        }
        creatToken();
        $this->display();
    }
    public function pedit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Guide/index');
                return;
            }
            $infor=M('Problem');
            $id = intval(I("post.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }else{
                $title=_safe(I('post.title'));
                $content=_safe(I('post.content'));
                $time=time();
                $infor->title=$title;
                $infor->content=$content;
                $infor->time=$time;
                if($infor->where('id =%d',array($id))->save() !==false){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改成功！"); </script>';
                    echo "<script> window.location.href='".U('Problem/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改失败！"),history.back(); </script>';
                    exit();
                }
            }
        }else{
            //$id=I('get.id','','int');
            $id = intval(I('get.id',0,'addslashes'));
            $infor=M('Problem');
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"),history.back(); </script>';
                exit();
            }else{
                $data=$infor->where('id =%d',array($id))->find();
                $this->assign('id',$id);
                $this->assign('data',$data);
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
                $infor=M('Problem');
                if($infor->where('id =%d',array($id))->delete()){
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