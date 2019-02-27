<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/19
 * Time: 18:08
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Tool;

class SeippdController extends AdminController
{
    public function index(){
		$acd = M('activities_winning')->select();
		$this->assign('ppd',$acd);
		
		$aar = array();
		$ca = M('statistical')->select();
		foreach($ca as $key=>$val){
			$sel = ''.$val['name'].'_activity_warehouse';
			$D = M("$sel")->select();
			foreach($D as $k=>$v){
				switch($v['name']){
				case '手机碎片1':
					$aar['yi'] += $v['num'];
					break;
				case '手机碎片2':
					$aar['er'] += $v['num'];
					break;
				case '手机碎片3':
					$aar['san'] += $v['num'];
					break;
				case '手机碎片4':
					$aar['si'] += $v['num'];
					break;
				case '手机碎片5':
					$aar['wu'] += $v['num'];
					break;
				case '手机碎片6':
					$aar['liu'] += $v['num'];
					break;
				case '手机碎片':
					$aar['lin'] += $v['num'];
					break;
					}
			}
			
		}
		$this->assign('aar',$aar);
       $this->display();
    }
	


}