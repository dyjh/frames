<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 15:33
 */

namespace Home\Controller;


class IntroduceController extends HomeController
{
	public function _initialize(){
        //先运行一次父类的构造方法
        $this->assign('nav_titels',"游戏介绍");
    }
	
    public function index(){
        
        $this->display();
    }

    public function fruit(){

        $this->display('fruit');
    }

    public function porp(){

        $this->display('porp');
    }

    public function guardian(){

        $this->display('guardian');
    }


    public function listing(){

        $this->display('listing');
    }
}