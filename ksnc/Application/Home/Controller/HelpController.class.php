<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7
 * Time: 11:43
 */

namespace Home\Controller;


class HelpController extends HomeController
{
    public function _initialize(){
        //先运行一次父类的构造方法
        //判断是否登录

        $son_list = array();
		$son_list[0]['title']   = "新手指南";
        $son_list[0]['i_class']   = "ti-direction";
        $son_list[0]['url']     = U('Help/guide');
        $son_list[1]['title']   = "常见问题";
        $son_list[1]['i_class']   = "ti-comments";
        $son_list[1]['url']     = U('Help/index');
       

        $this->assign('nav_titels',"帮助中心");
        $this->assign('son_nav',$son_list);
    }

    public function index(){
        $count=M('problem')->count();
        $num =6;
        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
        if(IS_POST){
            $p =I('post.p','','int');
        }else{
            $p =I('get.p',1,'int');
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
        $list =M('problem')->order('id asc')->page($p.','.$num)->select();
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

    public function guide(){
        $count=M('Guide')->count();
        $num =6;
        $pages = ceil($count/$num);
        //$this->assign('pages',$pages+1);
        if(IS_POST){
            $p =I('post.p','','int');
        }else{
            $p =I('get.p',1,'int');
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
        $list =M('Guide')->order('id asc')->page($p.','.$num)->select();
        $this->assign('data', $list); // 赋值数据集
        if(empty($list)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        //var_dump($count);
        $this->display();
    }
}