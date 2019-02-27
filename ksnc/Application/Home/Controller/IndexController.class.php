<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends HomeController {

    public function index(){

        $this->assign('nav_titels',"首页");
		$raiders_where['is_show'] = 1;
        $notice = M('notice');
        $res = $notice->order('time desc')->limit(5)->select();
        
        $raiders = M('raiders');
        $list = $raiders->order('listorder desc , reward desc')->where($raiders_where)->limit(5)->select();

        $this->assign('notice',$res);
        $this->assign('tip',$list);
        $this->display();

    }

}