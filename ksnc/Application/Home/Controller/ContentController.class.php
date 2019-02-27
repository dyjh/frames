<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7
 * Time: 11:43
 */
namespace Home\Controller;
class ContentController extends HomeController
{
    public function _initialize(){
        //先运行一次父类的构造方法
        //判断是否登录

        $this->assign('nav_titels',"游戏公告");

    }
    public function index(){
        $num= M('notice')->count();
        $per_num =8;
        $pages = ceil($num/$per_num);
        //$this->assign('pages',$pages+1);
        if($_GET['k']!==null){
            $k =I('get.k','','int');
        }else{
            $k =1;
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
        $data =M('notice')->field('title,content,time,id,content_title')->where('type=1')->order('time desc')->page($k.','.$per_num)->limit(0,3)->select();
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign("data",$data);
        $this->assign('state',$state);
        //print_r($data);die;
        $this->display();
    }
    public function detail(){
        $id=I('get.id','','int');

        if(!is_numeric($id)){
            $this->error("该内容飞到火星了！");
        }

        $data=M('notice')->where('id='.$id)->find();

        if(!$data){
            $this->error("该内容飞到火星了！");
        }

        $this->assign('data',$data);
        //print_r($data);die;
        $this->display();
    }
}