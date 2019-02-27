<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/24
 * Time: 11:42
 */

namespace Admin\Controller;
use Think\Tool;
use Think\Model;
use Think\Controller;

class IndexController extends AdminController
{
    public function index(){
		$level=I('post.level');
		$time_t=I('post.time');
		//echo $time_p;die;
		$y = date("Y");
		//获取当天的月份
		$m = date("m");
		//获取当天的号数
		$d = date("d");
		if(!empty($time_t)){
			$s_y=substr($time_t,0,4);
            $s_m=substr($time_t,5,2);
            $s_d=substr($time_t,8,2);
			$time_p= mktime(0,0,0,$s_m,$s_d,$s_y);
			$today=$time_t;
		}else{	
			$today=''.$y.'-'.$m.'-'.$d.'';
		}
		
		$this->assign('today',$today);
		//print_r($m);die;
		//$tm =date("d")+1;
		$data_g=M('Global_conf')->where('cases="start_time" or cases="end_time"')->select();
		foreach ($data_g as $k=>$v){
			switch ($v['cases']) {
				case 'start_time':
					$s_data=$data_g[$k]['value'];
					break;
				case 'end_time':
					$e_data=$data_g[$k]['value'];
					break;
			}
		}
		$start= mktime($s_data,0,0,$m,$d,$y);//即是当天开盘的时间戳
		$start_t=$start-3600*24;
		$time=time();
		//print_r($start);die;
		$end = mktime($e_data,0,0,$m,$d,$y);
		if($time>=$end){
			$case=''.date('Y-m').'_pay';
			$count=M($case)->where('state<2')->count();
		}else{
			$count=0;
		}
		//echo $count;die;
		
		/***查询当天登录人数***/

		$y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        $start= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳
		//echo $start;echo '<br/>';
		//echo $time_p;
		if(empty($time_t)||$time_p>=$start){
			//echo 1;die;
			$p=0;
			if(empty($level)||$level==0){
				$st='login_time>='.$start.'';
			}else{
				$st='login_time>='.$start.' AND level='.$level.'';
			}
			$str=M('statistical')->select();
			foreach($str as $key=>$val){
				$case=''.$val['name'].'_members';
				$count_s=M($case)->where($st)->count();
				$p=$p+$count_s;
			}
		}else{
			//echo 2;die;
			$p_num=M('me_num_record')->where('time='.$time_p.'')->find();
			if(empty($p_num)){
				$p='当天无数据';
			}else{
				$p=$p_num['num'];
			}
		}
		
		$data=M('house')->field('level')->order('level')->select();

		$this->assign('level',$level);
		$this->assign('data',$data);
		$this->assign('p',$p);
		$this->assign('count',$count);
        $this->display();
    }
	/**清除用户相关数据   耗子**/
	public function clear_member(){
		//$day=I('post.day');
		//$money_l=I('post.money');
		//$level=I('post.level');
		$level=3;
		$money_l=10;
		$day=60;
		$day=$day*86400;
		$time=time()-$day;
		$f=0;
		$statistical=M('statistical')->select();
		foreach($statistical as $key=>$val){
			$case=''.$val['name'].'_members';
			$case_money=''.$val['name'].'_order';
			$case_fruit=''.$val['name'].'_seed_warehouse';
			$case_prop=''.$val['name'].'_prop_warehouse';
			$member=M($case)->where('login_time<='.$time.' AND coin<'.$money_l.' AND level<='.$level.'')->select();
			$count=count($member);
			for($i=0;$i<$count;$i++){
				$f++;
				echo ''.$member[$i]['user'].'等级：'.$member[$i]['level'].'金币：'.$member[$i]['coin'].'登录时间:'.date('Y-m_d',$member[$i]['login_time']).'<br/>';
				M($case_fruit)->where('user='.$member[$i]['user'].'')->delete();
				M($case_prop)->where('user='.$member[$i]['user'].'')->delete();
			}
			echo ''.$f.'个';
		}
	}
	
	
	public function error_re(){
		$err_p=0;
	   $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        $data_g=M('Global_conf')->where('cases="start_time" or cases="end_time"')->select();
        foreach ($data_g as $k=>$v){
            switch ($v['cases']) {
                case 'start_time':
                    $s_data=$data_g[$k]['value'];
                    break;
                case 'end_time':
                    $e_data=$data_g[$k]['value'];
                    break;
            }
        }
        //$e_data=M('Global_conf')->where('cases="end_time"')->find();
        $start= mktime($s_data,0,0,$m,$d,$y);//即是当天开盘的时间戳
		
        //$start_t=$start-3600*24;
        $time=time();
        $end = mktime($e_data,0,0,$m,$d,$y);
		$case=''.date('Y-m').'_pay';
		$case_m=''.date('Y-m').'_matching';
		/**恢复错误买单**/
		$buy=M($case)->where('state<2 AND type=1 AND time >'.$start.' AND time <'.$end.'')->select();
		$model=new Model();
		foreach($buy as $key=>$val){
			echo 1;echo '<br/>';
			$model->startTrans();
			$num_c=substr($val['user'],0,3);
			//**已撤销订单的提交总量*//
			$re_snum=M($case)->where('state=3 AND type = 1 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('submit_num');
			/**已撤销订单的剩余总量**/
			$re_num=M($case)->where('state=3 AND type = 1 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('num');
			/**未撤销成功的单的提交总量**/
			$pay_num=M($case)->where('state<=2 AND type = 1 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('submit_num');
			/**当天该用户交易总量**/
			$matching_num=M($case_m)->where('buy_user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'"  AND time >'.$start.' AND time <'.$end.'')->sum('num');
			/**查询该用户当前果实当前价位的所有数量统计  得出该返回的总数量**/
			$real_num=$pay_num-$matching_num+$re_snum-$re_num;
			echo $real_num;echo '<br/>';
			
			if($real_num==0){//如果数量为零   则实际果实数量正确   直接修改订单状态为交易完成  
				$sa['num']=0;
				$sa['state']=2;
				M($case)->where('user="'.$val['user'].'" AND system=0')->save($sa);
			}elseif($real_num>0){//如果大于零  执行金币撤回程序
				$real_money=$real_num*$val['money'];
				$case_member=''.$num_c.'_members';
				$member=M($case_member)->where('user="'.$val['user'].'"')->find();
				if($member['coin_freeze']<$real_money){
					$save_c['coin_freeze']=0;
					M($case_member)->where('user="'.$val['user'].'"')->save($save_c);
				}else{
					M($case_member)->where('user="'.$val['user'].'"')->setDec('coin_freeze',$real_money);
				}
				//$coin['coin_freeze']=0;//
				//恢复该果实当前价位该撤回的所有金额
				if(M($case_member)->where('user="'.$val['user'].'"')->setInc('coin',$real_money)){
					echo '恢复'.$real_money.'用户'.$val['user'];echo '<br/>';
					//$save['num']=0;//
					//循环修改该果实当前价位该撤回的所有单
					$pay_for=M($case)->where('state<2 AND type = 1 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->select();
					foreach($pay_for as $k=>$v){
						$save['state']=3;
						$save['system']=date('d');//
						if(M($case)->where('id='.$v['id'].'')->save($save)){
							$err_p++;
							echo 'id:'.$v['id'].';';echo '<br/>';
						}else{
							$model->rollback();
							echo 444;echo '<br/>';
						}
					}
				}else{
					$model->rollback();
					echo 44;echo '<br/>';
				}
				
			}
			$model->commit();
			
		}
		$buy=M($case)->where('state<2 AND type=0 AND time >'.$start.' AND time <'.$end.'')->select();
		foreach($buy as $key=>$val){
			
			$model->startTrans();
			$num_c=substr($val['user'],0,3);
			echo 1;echo '<br/>';
			$re_snum=M($case)->where('state=3 AND type = 0 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('submit_num');
			$re_num=M($case)->where('state=3 AND type = 0 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('num');
			$pay_num=M($case)->where('state<=2 AND type = 0 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->sum('submit_num');
			$matching_num=M($case_m)->where('sell_user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'"  AND time >'.$start.' AND time <'.$end.'')->sum('num');
			$real_num=$pay_num-$matching_num+$re_snum-$re_num;
			/*echo M($case)->getlastsql();echo '<br/>';
			echo M($case_m)->getlastsql();echo '<br/>';
			echo $pay_num;echo '<br/>';
			echo $matching_num;echo '<br/>';*/
			echo $real_num;echo '<br/>';
			if($real_num==0){
				$sa['num']=0;
				$sa['state']=2;
				M($case)->where('id="'.$val['id'].'" AND system=0')->save($sa);
			}elseif($real_num>0){
				echo -1;echo '<br/>';
				$case_fruit=''.$num_c.'_fruit_record';
				$case_seed=''.$num_c.'_seed_warehouse';
				$fruit=M($case_fruit)->where('seed="'.$val['seed'].'" AND money='.$val['money'].' AND user="'.$val['user'].'"')->find();
				$seed=M($case_seed)->where('seeds="'.$val['seed'].'" AND user="'.$val['user'].'"')->find();
				$fruit_num=$fruit['num'];
				if($real_num>=$fruit_num){
					M($case_fruit)->where('seed="'.$val['seed'].'" AND money='.$val['money'].' AND user="'.$val['user'].'"')->setDec('num',$fruit_num);
				}else{
					M($case_fruit)->where('seed="'.$val['seed'].'" AND money='.$val['money'].' AND user="'.$val['user'].'"')->setDec('num',$real_num);
				}
				if(empty($seed)){
					$add['num']=$real_num;
					$add['user']=$val['user'];
					$add['seeds']=$val['seed'];
					if(M($case_seed)->add($add)){
						
					}else{
						$model->rollback();
						echo 1.0;echo '<br/>';
					}//
				}else{
					if(M($case_seed)->where('seeds="'.$val['seed'].'" AND user="'.$val['user'].'"')->setInc('num',$real_num)){
						
					}else{
						$model->rollback();
						echo 1.0;echo '<br/>';
					}//
				}
				$conf=M('global_conf')->where('cases="poundage"')->find();
				$real_money=$real_num*$val['money']*$conf['value'];
				$case_member=''.$num_c.'_members';
				$member=M($case_member)->where('user="'.$val['user'].'"')->find();
				if($member['coin_freeze']<$real_money){
					$save_c['coin_freeze']=0;
					M($case_member)->where('user="'.$val['user'].'"')->save($save_c);
				}else{
					M($case_member)->where('user="'.$val['user'].'"')->setDec('coin_freeze',$real_money);
				}
				//$coin['coin_freeze']=0;
				if(M($case_member)->where('user="'.$val['user'].'"')->setInc('coin',$real_money)){
					echo '恢复'.$real_money.'用户'.$val['user'];echo '<br/>';
					//$save['num']=0;
					$pay_for=M($case)->where('state<2 AND type = 0 AND user="'.$val['user'].'" AND money='.$val['money'].' AND seed="'.$val['seed'].'" AND time >'.$start.' AND time <'.$end.'')->select();
					foreach($pay_for as $k=>$v){
						$save['state']=3;
						$save['system']=date('d');
						if(M($case)->where('id='.$v['id'].'')->save($save)){
							$err_p++;
							echo 'id:'.$v['id'].';';echo '<br/>';
						}else{
							$model->rollback();
							echo 444;echo '<br/>';
						}
					}
				}else{
					echo M($case_member)->getlastsql();echo '<br/>';
					$model->rollback();
					echo 44;echo '<br/>';
				}
			}
			
		}
		$model->commit();
		echo '成功'.$err_p.'个';
	 }
	 
	 /**查询新注册六级玩家相关**/
	public function xxx(){
		$start=I('post.start_time');
		$s_y=substr($start,0,4);
		$s_m=substr($start,5,2);
		$s_d=substr($start,8,2);
		$s_h=substr($start,11,2);
		$s_i=substr($start,14,2);
		$s_s=substr($start,17,2);
		$start_time = mktime($s_h,$s_i,$s_s,$s_m,$s_d,$s_y);
		$end=I('post.end_time');
		$e_y=substr($end,0,4);
		$e_m=substr($end,5,2);
		$e_d=substr($end,8,2);
		$e_h=substr($end,11,2);
		$e_i=substr($end,14,2);
		$e_s=substr($end,17,2);
		$end_time = mktime($e_h,$e_i,$e_s,$e_m,$e_d,$e_y);
		$member=array();
		$f=0;
		$verification=M('verification')->where('regis_time>'.$start_time.' AND regis_time<'.$end_time.'')->select();
		foreach($verification as $key=>$val){
			$member_team=M('team_relationship')->field('user,referees')->where('user='.$val['user'].' AND level>=6')->find();
			if(!empty($member_team)){
				$member[$f]=$member_team;
				if(!empty($member[$f]['referees'])){
					$member_ref=M('team_relationship')->where('user='.$member[$f]['referees'].'')->find();
					$member[$f]['level']=$member_ref['level'];
					$member[$f]['num']=$member_ref['activity_info'];
				}else{
					$member[$f]['level']='该用户无推荐人';
					$member[$f]['num']=0;
				}
				$member[$f]['count']=M('team_relationship')->where('referees='.$member[$f]['user'].'')->count();
				
				//echo $member['user'];echo '<br/>';
				$f++;
			}
		}
		$state=1;
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		$this->assign('member',$member);
		$this->assign('state',$state);
	}
	//当前活动统计
	public function active(){
		$data=M("team_relationship")->where("activity_info >= 10 or act_prop != ''")->order('activity_info')->select();
		$count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);
       //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =intval(I('get.o',1,'addslashes'));
        }else{
            $o =1;
        }
		
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);
        $start=$o-$off;
        $end=$o+$off;
        //起始页
        if($o-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('o',$o);
        $res =array_slice($data,($o-1)*8,8);
		foreach($res as $key=>$val){
			$num=substr($val['user'],0,3);
			$case=''.$num.'_members';
			$member=M($case)->where('user= '.$val['user'].'')->find();
			$res[$key]['name']=$member['name'];
		}
		if(empty($res)){
            $state=0;
        }else{
            $state=1;
        }
		
		/***查询达到六级用户***/
		$member=array();
		$f=0;
		$verification=M('verification')->where('regis_time>1505491200')->select();
		foreach($verification as $key=>$val){
			$member_team=M('team_relationship')->field('user,referees')->where('user='.$val['user'].' AND level>=6')->find();
			if(!empty($member_team)){
				$member[$f]=$member_team;
				if(!empty($member[$f]['referees'])){
					$member_ref=M('team_relationship')->where('user='.$member[$f]['referees'].'')->find();
					$member[$f]['level']=$member_ref['level'];
					$member[$f]['num']=$member_ref['activity_info'];
				}else{
					$member[$f]['level']='该用户无推荐人';
					$member[$f]['num']=0;
				}
				$member[$f]['count']=M('team_relationship')->where('referees='.$member[$f]['user'].'')->count();
				
				//echo $member['user'];echo '<br/>';
				$f++;
			}
		}
		
		$count_m=count($member);//得到数组元素个数
        $num_m =8;
        $pages_m = ceil($count_m/$num_m);
       //$this->assign('pages',$pages+1); //分页
        if($_GET['p']!==null){
            $p =intval(I('get.p',1,'addslashes'));
        }else{
            $p =1;
        }
		
        if($p<1){
            $p =1;
        }else if($p > $pages_m){
            $p = $pages_m;
        }
        $showPage_m = 5;
        $off_m=floor($showPage_m/2);
        $start_m=$p-$off_m;
        $end_m=$p+$off_m;
        //起始页
        if($p-$off_m < 1){
            $start_m = 1;
            $end_m = $showPage_m;
        }
        //结束页
        if($p+$off_m > $pages_m){
            $end_m = $pages_m;
            $start_m = $pages_m-$showPage_m+1;
        }
        if($pages_m < $showPage_m){
            $start_m = 1;
            $end_m = $pages_m;
        }
        $this->assign('start_m',$start_m); //分页
        $this->assign('end_m',$end_m+1); //分页
        $this->assign('p',$p);
        $res_m =array_slice($member,($p-1)*8,8);
		//print_r($res_m);die;
		//foreach($res_m as $key=>$val){
			//echo $val['user'];echo '<br/>';
			//echo $val['referees'];echo '<br/>';
		//	if(!empty($val['referees'])){
		//		$member_ref=M('team_relationship')->where('user='.$val['referees'].'')->find();
		///		$res_m[$key]['level']=$member_ref['level'];
		///		$res_m[$key]['num']=$member_ref['activity_info'];
		///	}else{
		///		$res_m[$key]['level']='该用户无推荐人';
		//		$res_m[$key]['num']=0;
		///	}
		//}
		if(empty($res_m)){
            $state_m=0;
        }else{
            $state_m=1;
        }
		
		$this->assign('member',$res_m);//分页内容
		$this->assign('state_m',$state_m);
		
        $this->assign('data',$res);//分页内容
		$this->assign('state',$state);
		$this->display();
	}
	public function deel(){
		if(IS_AJAX){
			$id=I('post.id');
			$save['act_prop']=' ';
			if(M("team_relationship")->where('id='.$id.'')->save($save)!=false){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}

	}
    //添加num_id
	public function demo(){
		//$id=new Tool();
        //echo 1;die;
		$data=array();
		$q=0;
		$sta=M('statistical')->select();
		foreach ($sta as $k=>$v){
		    $case=''.$v['name'].'_members';

		    $member=M($case)->field('user,num_id,headimg')->where('num_id="" AND headimg !="" ')->select();
		    if($member){
                for($i=0;$i<count($member);$i++){
		        $data[$q]=$member[$i];
		        $q++;
                    /*$tool=new Tool();
                    $user_num=substr($member[$i]['user'],8,4);
                    $num = 10000+1;
                    $num .=rand(100,999);
                    $num .=$user_num;
                    $data['num_id']=$tool->num_id($num,$member[$i]['user']);
                    $case=''.$v['name'].'_members';
                    //print_r($case);die;
                    ;
                    //print_r($member[$i]['user']);
                    if(M($case)->where('user='.$member[$i]['user'])->save($data)){
                        $q++;
                    }*/
                }
            }


        }
        print_r($data);die;
        echo $q;
	}
	public function run(){
		$path='Application/Runtime/Cache';
		$delDir='FALSE';
        $zhi=delDirAndFile($path, $delDir);
		if($zhi){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("删除成功！"); </script>';
			echo "<script> window.location.href='".U('Index/index')."';</script>";
			exit();
		}else{
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("删除失败！"); </script>';
			echo "<script> window.location.href='".U('Index/index')."';</script>";
			exit();
		}
    }
	
}