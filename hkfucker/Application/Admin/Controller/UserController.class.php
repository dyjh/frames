<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/20 0020
 * Time: 16:35
 */

namespace Admin\Controller;

use Org\Our\Admin;
use Think\Controller;
use Think\Model;
use Think\Find;
class UserController extends Controller{
	//添加机构账户
	public function gg_add(){
		if(IS_AJAX){
			$user=I('post.user');
			//var_dump($user);die;
			if(preg_match("/^1[34578]\d{9}$/", $user)){
				$institutions=M('institutions');
				$in=$institutions->where("user='%s'",array($user))->find();
				if(!empty($in)){
					echo -1;
					exit;
				}
				$num=substr($user,0,3);
				$case=''.$num.'_members';
				$member=M($case)->where("user='%s'",array($user))->find();
				$save['identity']='编外股东';
				M($case)->where('id='.$member['id'].'')->save($save);
				$data['card']=$member['id_card'];
				$data['time']=time();
				$data['user']=$member['user'];
				$data['name']=$member['name'];
				$data['level']=$member['level'];
				if($institutions->add($data)){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo -2;
			}
		}
	}
	
	
	
	public function check_user(){
		$sta=M('statistical');
		$data=array();
		$p=0;
		foreach($sta as $key=>$val){
			$case=''.$val['name'].'_members';
			$member=M($case)->field('num_id,user,tel')->select();
			$count=M($case)->field('num_id,user,tel')->count();
			for($i=0;$i<$count;$i++){
				if($member[$i]['user']!=$member[$i]['tel']){
					$data[$p]=$member[$i];
					$p++;
				}
			}
		}
		
	}
	//手动撤回
	public function pay_del(){
		if(IS_AJAX){
			$id= I('post.id');
			$time=I('post.time');
			$num=date('Y-m',$time);
			//print_r($id);
			$case=''.$num.'_pay';
			$data['state']=3;
			$data['system']=1;
			$data['queue']=0;
			if(M($case)->where('id='.$id)->save($data)!==false){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}
	}
	//冻结金币记录
	public function coin(){
		$user=I('get.user');
		$data=M('coin_record')->where('user='.$user)->select();
		if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);

        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o',1,'int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $off=floor($showPage/2);
        $start_page=$o-$off;
        $end_page=$o+$off;
        //起始页
        if($o-$off < 1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
        $this->assign('start_page',$start_page); //分页
        $this->assign('end_page',$end_page+1); //分页
	
        $res =array_slice($data,($o-1)*8,8);
		//print_r($data);
        $this->assign('data',$res);//分页内容
        

        $this->display();
	}
	//交易记录
	public function pay(){
        //种子
		$user=I('get.user');
        $mou=M('matching_statistical')->select();
        $m=$mou[0]['name'];
        $m_y=substr($m,0,4);
        $m_m=substr($m,5,2);
        $m_time = mktime(0,0,0,$m_m,01,$m_y);


        $where['varieties']=array('not in',"摇钱树");
        $data_seed=M('seeds')->where($where)->select();
        $this->assign('data_seed',$data_seed);
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        //$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        $time=time();
        //print_r($start);die;
        //$end = mktime($e_data['value'],0,0,$m,$d,$y);

        //$id=1;
		 $thismonth = date('m');
        $thisyear = date('Y');
        if ($thismonth == 1) {
            $lastmonth = 12;
            $lastyear = $thisyear - 1;
        } else {
            $lastmonth = $thismonth - 1;
            $lastyear = $thisyear;
        }
        if ($thismonth == 1) {
            $lastmonth_s = 11;
            $lastyear_s = $thisyear - 1;
        } elseif($thismonth == 2) {
            $lastmonth_s = 12;
            $lastyear_s = $thisyear - 1;
        }else{
            $lastmonth_s = $thismonth - 2;
            $lastyear_s = $thisyear;
        }
        if( $lastmonth_s<10){
            $lastmonth_s='0'.$lastmonth_s.'';
        }
        if( $lastmonth<10){
            $lastmonth='0'.$lastmonth.'';
        }
        $st=M('matching_statistical')->order('id desc')->select();
        $lastEndDay_t=date('Y-m');//本月
        $lastEndDay_f = $st[1]['name'] ;  //上个月
        $lastEndDay_s = $st[2]['name'] ; //前月
        $case_t=''.$st[0]['name'].'_matching';
        $case_f=''.$st[1]['name'].'_matching';
        $case_s=''.$st[2]['name'].'_matching';
		
        $s_T = mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
        $f_T = mktime(0,0,0,$lastmonth,01,$lastyear);
        if(IS_POST){
            $id=I('post.id');
            $this->assign('id',$id);
            $type=I('post.type');     //买入1卖出0
            $start_times=I('post.start');
            $end_times=I('post.end');

            $s_y=substr($start_times,0,4);
            $s_m=substr($start_times,5,2);
            $star = mktime(0,0,0,$s_m,01,$s_y);
            $e_y=substr($end_times,0,4);
            $e_m=substr($end_times,5,2);
            $end = mktime(0,0,0,$e_m,01,$e_y);
            //输出
            if(empty($id)){
                $seed='';
            }else{
                $data_fruit=M('Seeds')->where('id ='.$id)->find();
                $seed=$data_fruit['varieties'];
            }
			
            if(empty($end_times)&& empty($start_times)){
                $end_time=date('Y-m-d');
                if($s_T<$m_time){
                    if($f_T<$m_time){
                        $start_time=''.date('Y').'-'.date('m').'-01';
                    }else{
                        $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                    }
                }else{
                    $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                }
            }elseif (!empty($end_times) && !empty($start_times)){
                if($m_time<=$star){
					//echo 1;die;
                    $start_time=$start_times;
                }else{
                    if($s_T<$m_time){
                        if($f_T<$m_time){
                            $start_time=''.$s_y.'-'.$s_m.'-01';
                        }else{
                            $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                        }
                    }else{
                        $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                    }
                }
                if($end>time()){
                    $end_time=''.date('Y').'-'.date('m').'-'.date('d').'';
                }else{
                    $end_time=$end_times;
                }
            }elseif (!empty($end_times)&&empty($start_times)){
                //$start_time=mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
                if($s_T<$m_time){
                    if($f_T<$m_time){
                        $start_time=''.$m_y.'-'.$m_m.'-01';
                    }else{
                        $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                    }
                }else{
                    $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                }
                if($end>time()){
                    $end_time=''.date('Y').'-'.date('m').'-'.date('d').'';
                }else{
                    $end_time=$end_times;
                }
            }elseif (empty($end_times)&&!empty($start_times)){
                if($m_time<=$star){
					//echo 1;die;
                    $start_time=$start_times;
                }else{
                    if($s_T<$m_time){
                        if($f_T<$m_time){
                            $start_time=''.$s_y.'-'.$s_m.'-01';
                        }else{
                            $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                        }
                    }else{
                        $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                    }
                }
                $end_time=date('Y-m-d');
            }
            
			//print_r($start_time);echo "<br/>";
            //print_r($end_time);die;
			//print_r($type);die;
            $find= new Find();
            $data=$find->total($start_time,$end_time,$seed,$type,$user);
            //print_r($data['sstate_t']);
        }else{
			if(IS_GET){
				$start_time=I('get.start');
				$end_time=I('get.end');
				if(empty($end_times)&&empty($start_times)){
					$end_time=date('Y-m-d');
					if($s_T<$m_time){
						if($f_T<$m_time){
							$start_time=''.date('Y').'-'.date('m').'-01';
						}else{
							$start_time=''.$lastyear.'-'.$lastmonth.'-01';
						}
					}else{
						$start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
					}
				}
				$id=I('get.id');
				$this->assign('id',$id);
				$type=I('get.type');     //买入1卖出0
				if(empty($id)){
					$seed='';
				}else{
					$data_fruit=M('Seeds')->where('id ='.$id)->find();
					$seed=$data_fruit['varieties'];
				}
				$find= new Find();
				$data=$find->total($start_time,$end_time,$seed,$type,$user);
			}
        }
		//print_r($data);die;
		array_multisort(i_array_column($data,'time'),SORT_DESC,$data);
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);

        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o',1,'int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $off=floor($showPage/2);
        $start_page=$o-$off;
        $end_page=$o+$off;
        //起始页
        if($o-$off < 1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
        $this->assign('start_page',$start_page); //分页
        $this->assign('end_page',$end_page+1); //分页
	
        $res =array_slice($data,($o-1)*8,8);
		//print_r($data);
        $this->assign('data',$res);//分页内容
        $this->assign('type',$type);
        $this->assign('start',$start_time);
        $this->assign('end',$end_time);
        $this->assign('id',$id);
        $this->assign('user',$user);

        $this->display();
    }
	//委托记录
    public function entrust(){
		$user=I('get.user');
        $mou=M('matching_statistical')->select();
        $m=$mou[0]['name'];
        $m_y=substr($m,0,4);
        $m_m=substr($m,5,2);
        $m_time = mktime(0,0,0,$m_m,01,$m_y);
//echo $m;die;
        $where['varieties']=array('not in',"摇钱树");
        $data_seed=M('seeds')->where($where)->select();
        $this->assign('data_seed',$data_seed);
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        //$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        //$time=time();
        //print_r($start);die;
        //$end = mktime($e_data['value'],0,0,$m,$d,$y);
        //$id=1;
         $thismonth = date('m');
        $thisyear = date('Y');
        if ($thismonth == 1) {
            $lastmonth = 12;
            $lastyear = $thisyear - 1;
        } else {
            $lastmonth = $thismonth - 1;
            $lastyear = $thisyear;
        }
        if ($thismonth == 1) {
            $lastmonth_s = 11;
            $lastyear_s = $thisyear - 1;
        } elseif($thismonth == 2) {
            $lastmonth_s = 12;
            $lastyear_s = $thisyear - 1;
        }else{
            $lastmonth_s = $thismonth - 2;
            $lastyear_s = $thisyear;
        }
        if( $lastmonth_s<10){
            $lastmonth_s='0'.$lastmonth_s.'';
        }
        if( $lastmonth<10){
            $lastmonth='0'.$lastmonth.'';
        }
        
		$st=M('matching_statistical')->order('id desc')->select();
        $lastEndDay_t=date('Y-m');//本月
        $lastEndDay_f = $st[1]['name'];  //上个月
        $lastEndDay_s = $st[2]['name'] ; //前月
        $case_t=''.$st[0]['name'].'_pay';
        $case_f=''.$st[1]['name'].'_pay';
        $case_s=''.$st[2]['name'].'_pay';
        $s_T = mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
        $f_T = mktime(0,0,0,$lastmonth,01,$lastyear);
        if(IS_POST){
            $id=I('post.id');
            $this->assign('id',$id);
            $type=I('post.type');     //买入1卖出0
            $start_times=I('post.start');
            $end_times=I('post.end');

            $s_y=substr($start_times,0,4);
            $s_m=substr($start_times,5,2);
            $star = mktime(0,0,0,$s_m,01,$s_y);
            $e_y=substr($end_times,0,4);
            $e_m=substr($end_times,5,2);
            $end = mktime(0,0,0,$e_m,01,$e_y);
			//echo $start_times;echo $end_times;die;
            //输出
            if(empty($id)){
                $seed='';
            }else{
                $data_fruit=M('Seeds')->where('id ='.$id)->find();
                $seed=$data_fruit['varieties'];
            }
            if(empty($end_times)&&empty($start_times)){
                $end_time=date('Y-m-d');
                if($s_T<$m_time){
                    if($f_T<$m_time){
                        $start_time=''.date('Y').'-'.date('m').'-01';
                    }else{
                        $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                    }
                }else{
                    $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                }
            }elseif (!empty($end_times)&&!empty($start_times)){
				//echo $star;die;
                if($m_time<=$star){
					//echo 1;die;
                    $start_time=$start_times;
                }else{
                    if($s_T<$m_time){
                        if($f_T<$m_time){
                            $start_time=''.$s_y.'-'.$s_m.'-01';
                        }else{
                            $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                        }
                    }else{
                        $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                    }
                }
				
                if($end>time()){
                    $end_time=''.date('Y').'-'.date('m').'-'.date('d').'';
                }else{
                    $end_time=$end_times;
                }
            }elseif (!empty($end_times)&&empty($start_times)){
                //$start_time=mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
                if($s_T<$m_time){
                    if($f_T<$m_time){
                        $start_time=''.$m_y.'-'.$m_m.'-01';
                    }else{
                        $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                    }
                }else{
                    $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                }
                if($end>time()){
                    $end_time=''.date('Y').'-'.date('m').'-'.date('d').'';
                }else{
                    $end_time=$end_times;
                }
            }elseif (empty($end_times)&&!empty($start_times)){
                if($m_time<=$star){
                    $start_time=$start_times;
                }else{
                    if($s_T<$m_time){
                        if($f_T<$m_time){
                            $start_time=''.$s_y.'-'.$s_m.'-01';
                        }else{
                            $start_time=''.$lastyear.'-'.$lastmonth.'-01';
                        }
                    }else{
                        $start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
                    }
                }
                $end_time=date('Y-m-d');
            }
            $trans_type = 0;
            $tool= new \Think\Find();
            $data=$tool->total_pay($start_time,$end_time,$seed,$type,$user);
            //print_r($data['sstate_t']);
        }else{
			if(IS_GET){
				$start_time=I('get.start');
				$end_time=I('get.end');
				if(empty($end_time)&&empty($start_time)){
					$end_time=date('Y-m-d');
					if($s_T<$m_time){
						if($f_T<$m_time){
							$start_time=''.date('Y').'-'.date('m').'-01';
						}else{
							$start_time=''.$lastyear.'-'.$lastmonth.'-01';
						}
					}else{
						$start_time=''.$lastyear_s.'-'.$lastmonth_s.'-01';
					}
				}
				$id=I('get.id');
				$this->assign('id',$id);
				$type=I('get.type');     //买入1卖出0
				if(empty($id)){
					$seed='';
				}else{
					$data_fruit=M('Seeds')->where('id ='.$id)->find();
					$seed=$data_fruit['varieties'];
				}
				 $find= new Find();
				$data=$find->total_pay($start_time,$end_time,$seed,$type,$user);
			}
            
        }
		array_multisort(i_array_column($data,'time'),SORT_DESC,$data);
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);
//print_r($pages);die;
        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o','','int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $start_page=$o-floor($showPage/2);
        $end_page=$o+floor($showPage/2);
        //起始页
		//print_r($pages);
        if($o-$showPage < 1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$showPage > $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
        $this->assign('start_page',$start_page); //分页
        $this->assign('end_page',$end_page+1); //分页

        $res =array_slice($data,($o-1)*8,8);
        $this->assign('data',$res);//分页内容
        $this->assign('type',$type);
        $this->assign('start',$start_time);
        $this->assign('end',$end_time);
        $this->assign('id',$id);
		$this->assign('user',$user);
        $this->display();
    }
	
	//用户基础信息
    public function User_info(){
        if (IS_POST){
            /*if (!checkToken($_POST['TOKEN'])) {		
                $this->redirect('User/User_info');
                return;
            }*/
            $Admin = new Admin();
            $post=array_filter(I('post.')); //回调函数过滤数组中的值
            $user=$post['start_user'];
            //print_r($post);die;
			//preg_match('/^\d{3,11}$/', )
            if($user){
                $data = $Admin->Set_Level($user);//调用Admin 查询指定用户的类
				foreach($data as $k=>$v){ //解析用户数组，重新查询，查询指定数据给予判断
					$institutions=M('institutions');
					$in=$institutions->where("user='%s'",array($v['user']))->find();
					if(!empty($in)){
						$data[$k]['state_gg']=1;
					}else{
						$data[$k]['state_gg']=0;
					}
				}
				
				$columnKey='level';//以等级为排序条件
				array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
				
                $count=count($data);//得到数组元素个数
                $num =8;//数组类分页
                $pages = ceil($count/$num);
                $p=intval(I('get.p',1,'addslashes'));	
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
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
				$this->assign('user',$user);
                $this->assign('start',$start); //分页
                $this->assign('end',$end+1); //分页
                $this->assign('p',$p);
                $res =array_slice($data,($p-1)*8,8);
                if(empty($res)){
                    $state=0;
                    $state_p=0;
                }else{
                    $state=1;
                    $state_p=1;
                }
                $this->assign('state',$state);
                $this->assign('state_p',$state_p);
                $this->assign('user_info',$res);
                $this->display('info');
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
			$sta=M('statistical')->select();//查询所有号码段
			$data_member=array();//空数组
			$p=0;//记录循环次数
			foreach($sta as $key=>$val){
				$case=''.$val['name'].'_members';
				$member=M($case)->field('num_id,user,tel')->select();
				//echo M($case)->getLastsql();
				$count=M($case)->field('num_id,user,tel')->count();
				for($i=0;$i<$count;$i++){
					if($member[$i]['user']!=$member[$i]['tel']){
						$data_member[$p]=$member[$i];
						$p++;
					}
				}
			}
			/***分页修正***/
			$user=I('get.user');
			$Admin = new Admin();//调用查询类-查询所以用户
			if(empty($user)){
				//print_r($data_member);die;
				$data = $Admin->Set_Level();
			}else{
				$data = $Admin->Set_Level($user);
			}
			$this->assign('user',$user);
			/******/

			foreach($data as $k=>$v){
				$institutions=M('institutions');
				$in=$institutions->where("user='%s'",array($v['user']))->find();
				if(!empty($in)){
					$data[$k]['state_gg']=1;
				}else{
					$data[$k]['state_gg']=0;
				}
			}
			
			$columnKey='level';
			array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
			
            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
            }
            if($p<1){//分类
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
			if(empty($data_member)){
                $state_member=0;
            }else{
                $state_member=1;
            }
            $this->assign('start',$start); //分页
            $this->assign('end',$end+1); //分页
            $this->assign('p',$p);
            $res =array_slice($data,($p-1)*8,8);
            if(empty($res)){
                $state=0;
                $state_p=0;
            }else{
                $state=1;
                $state_p=1;
            }
            creatToken();
			$this->assign('state_member',$state_member);
			$this->assign('member',$data_member);
            $this->assign('state',$state);
            $this->assign('state_p',$state_p);
            $this->assign('user_info',$res);
            $this->display('info');
        }
    }

	public function bbdt(){
        $sta = M('statistical')->field('name')->filter('strip_tags')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_members';
                $sqllist = M($sqlname)->order('num_id')->where($date)->field('num_id,user')->filter('strip_tags')->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        print_r($List);
	}
	
    public function team(){
        $user=I('get.user');
        if(preg_match("/^1[34578]\d{9}$/", $user)){
            $this->assign('user',$user);
            $data_s = M('statistical')->select();
            $num_team=0;
            $num_st=0;
            foreach ($data_s as $k=>$v){
                $case = '' . $v['name'] . '_members';
                $k=M($case)->where('team regexp "[[:<:]]'.$user.'"')->count();
				$dd = M($case)->field('id,name,user,level,nickname')->where('team regexp "[[:<:]]'.$user.'"')->count();
				$data_team=M($case)->field('id,name,user,level,nickname')->where('team regexp "[[:<:]]'.$user.'"')->select();

                //print_r($num_team);echo "\n";
				$ttp = M($case)->where('referees ='.$user)->count();
                $num_st=$num_st+$ttp;
                for ($i=0;$i<$k;$i++){
                    $data[$num_team+$i]=$data_team[$i];					
                }
				$num_team=$num_team+$dd;
            }
			
			$columnKey='level';
			array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
			foreach($data as $key=>$val){
             switch ($val['level']){				
				case "1":
					$dengji['yi']=$dengji['yi']+1;
					break;
				case "2":
					$dengji['er']=$dengji['er']+1;
					break;
				case "3":
					$dengji['san']=$dengji['san']+1;
					break;
				case "4":
					$dengji['si']=$dengji['si']+1;
					break;
				case "5":
					$dengji['wu']=$dengji['wu']+1;
					break;
				case "6":
					$dengji['liu']=$dengji['liu']+1;
					break;
				case "7":
					$dengji['qi']=$dengji['qi']+1;
					break;
				case "8":
					$dengji['ba']=$dengji['ba']+1;
					break;
				case "9":
					$dengji['jiu']=$dengji['jiu']+1;
					break;
				case "10":
					$dengji['shi']=$dengji['shi']+1;
					break;
				case "11":
					$dengji['shiyi']=$dengji['shiyi']+1;
					break;
				case "12":
					$dengji['shier']=$dengji['shier']+1;
					break;
				default:
					echo ':),欢迎光临！';
					break;
			}
		}
			$dengji['zong']=$dengji['yi']+$dengji['er']+$dengji['san']+$dengji['si']+$dengji['wu']+$dengji['liu']+$dengji['qi']+$dengji['ba']+$dengji['jiu']+$dengji['shi']+$dengji['shiyi']+$dengji['shier'];
			$this->assign('dengji',$dengji);
            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            //print_r($pages);echo "<br/>";
            // print_r($start);echo "<br/>";
            //print_r($end);die;
            $this->assign('start',$start); //分页
            $this->assign('end',$end+1); //分页
            $this->assign('p',$p);
            $res =array_slice($data,($p-1)*8,8);
            $this->assign('data',$res);
            $this->assign('num_team',$num_team);
            $this->assign('num_st',$num_st);
            //print_r($state);die;
            $this->display();
        }else{
            echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
            echo '<script> alert("有没有搞错！"); </script>';
            echo "<script> window.location.href='".U('User/User_info')."';</script>";
            exit();
        }
    }

	public function freeze_count(){
        $user=I('get.user');
        if(preg_match("/^1[34578]\d{9}$/", $user)){
            $this->assign('user',$user);
            $data_s = M('statistical')->select();
            $num_team=0;
            $num_st=0;
			$data = array();
            foreach ($data_s as $k=>$v){
                $case = '' . $v['name'] . '_members';
                //$k=M($case)->where('referees ='.$user)->count();
				$dd = M($case)->field('id,name,user,level,nickname')->where('team regexp "[[:<:]]'.$user.'"')->count();
				$data_st=M($case)->field('id,name,user,level,nickname,num_id')->where('referees ='.$user)->select();
                //print_r($num_team);echo "\n";
				$ttp = M($case)->where('referees ='.$user)->count();
                $num_st=$num_st+$ttp;
                for ($i=0;$i<$ttp;$i++){
                    $data[]=$data_st[$i];					
                }
				$num_team=$num_team+$dd;
				
            }
			$columnKey='level';
			array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
			foreach($data as $key=>$val){
             switch ($val['level']){				
				case "1":
					$dengji['yi']=$dengji['yi']+1;
					break;
				case "2":
					$dengji['er']=$dengji['er']+1;
					break;
				case "3":
					$dengji['san']=$dengji['san']+1;
					break;
				case "4":
					$dengji['si']=$dengji['si']+1;
					break;
				case "5":
					$dengji['wu']=$dengji['wu']+1;
					break;
				case "6":
					$dengji['liu']=$dengji['liu']+1;
					break;
				case "7":
					$dengji['qi']=$dengji['qi']+1;
					break;
				case "8":
					$dengji['ba']=$dengji['ba']+1;
					break;
				case "9":
					$dengji['jiu']=$dengji['jiu']+1;
					break;
				case "10":
					$dengji['shi']=$dengji['shi']+1;
					break;
				case "11":
					$dengji['shiyi']=$dengji['shiyi']+1;
					break;
				case "12":
					$dengji['shier']=$dengji['shier']+1;
					break;
				default:
					echo ':),欢迎光临！';
					break;
			}
		}
			$dengji['zong']=$dengji['yi']+$dengji['er']+$dengji['san']+$dengji['si']+$dengji['wu']+$dengji['liu']+$dengji['qi']+$dengji['ba']+$dengji['jiu']+$dengji['shi']+$dengji['shiyi']+$dengji['shier'];
			$this->assign('dengji',$dengji);
            $count=count($data);//得到数组元素个数
            $num =10;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            //print_r($pages);echo "<br/>";
            // print_r($start);echo "<br/>";
            //print_r($end);die;
            $this->assign('start',$start); //分页
            $this->assign('end',$end+1); //分页
            $this->assign('p',$p);
            $res =array_slice($data,($p-1)*10,10);
            $this->assign('data',$res);
            $this->assign('num_team',$num_team);
            $this->assign('num_st',$num_st);
            //print_r($state);die;
            $this->display();
        }else{
            echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
            echo '<script> alert("有没有搞错！"); </script>';
            echo "<script> window.location.href='".U('User/User_info')."';</script>";
            exit();
        }
    }
	
    public function User_info_del($user){
        if(preg_match("/^1[34578]\d{9}$/", $user)){
            $sqluser = substr($user, 0, 3);
            $sqlname = ''.$sqluser.'_members';
            $sqllist = M($sqlname);

            if($sqllist->where('user='.$user)->delete()){
                $this->redirect("User/User_info");
            }else{
                $this->redirect("User/User_info");
            }
        }else{
            echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
            echo '<script> alert("250号错误！"); </script>';
            echo "<script> window.location.href='".U('User/User_info')."';</script>";
            exit();
        }
    }

    public function User_info_edit(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/User_info');
                return;
            }
			$data=$_POST;
            if(preg_match("/^1[34578]\d{9}$/", $_POST['user'])){
                $sqluser = substr($_POST['user'], 0, 3);
                $sqlname = ''.$sqluser.'_members';
                if(M($sqlname)->where('user='.$_POST['user'])->save($_POST)){
					echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
					echo '<script> alert("修改成功"); </script>';
					echo "<script> window.location.href='".U('User/User_info')."';</script>";
					exit();
                }else{
					echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
					echo '<script> alert("修改错误"); </script>';
					echo "<script> window.location.href='".U('User/User_info_edit')."';</script>";
					exit();
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("PST错误！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            $user =I('get.user','','addslashes');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $sqluser = substr($user, 0, 3);
                $sqlname = ''.$sqluser.'_members';
                $sqllist = M($sqlname)->where('user='.$user)->find();
                $user_time = M('verification')->where('user='.$user)->find();
                $this->assign('time',$user_time);
                $this->assign('data',$sqllist);
                creatToken();
                $this->display('info_edit');
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("查询错误"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }
    }

    public function level(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/level');
                return;
            }
            $Admin = new Admin();
            //print_r($_POST);die;
            $user=I('post.start_user');
            $p=intval(I('get.p',1,'addslashes'));
            if(preg_match('/^\d{3,11}$/', $user)){
                $data = $Admin->Set_Level($user);
                $count=count($data);//得到数组元素个数
                $num =8;
                $pages = ceil($count/$num);
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
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
                $res = array_slice($data,($p-1)*8,8);
                if(empty($res)){
                    $state=0;
                }else{
                    $state=1;
                }
                $this->assign('state',$state);
                $this->assign('level_list',$res);
                $this->display('level');
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好没意思！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            $Admin = new Admin();
            $data = $Admin->Set_Level();

            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            $res = array_slice($data,($p-1)*8,8);

            if(empty($res)){
                $state=0;
            }else{
                $state=1;
            }
            creatToken();
            $this->assign('state',$state);
            $this->assign('level_list',$res);
            $this->display('level');
        }
    }

    public function level_add(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/level');
                return;
            }
            //print_r($_POST);die;
            $data['user']=I('post.user','','addslashes');
            $data['seeds']=_safe($_POST['seeds']);
            $data['num']=_safe($_POST['num']);
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])){
                $sqluser = substr($_POST['user'], 0, 3);
                $sqlname = ''.$sqluser.'_seed_warehouse';
                $add=M($sqlname);
                foreach ($data as $key=>$val){
                    if ($key == 'num'){
                        continue;
                    }else{
                        $where[$key] = $val;
                    }
                }
                //print_r($where);die;
                $set = $add->where("user='%s' AND seeds='%s'",array($where['user'],$where['seeds']))->filter('strip_tags')->find();
                if ($set !== null){
                    $add->user=$data['user'];
                    $add->seeds=$data['seeds'];
                    $bind[':id'] =$data['num'];
                    $add->where("user='%s' AND seeds='%s'",array($where['user'],$where['seeds']))->bind($bind)->setInc('num',$data['num']);
                    $this->redirect("User/level");
                }else{
                    $add->user=$data['user'];
                    $add->seeds=$data['seeds'];
                    $add->num=$data['num'];
                    $add->filter('strip_tags')->add();
                    $this->redirect("User/level");
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $Admin = new Admin();
            $data = $Admin->Set_Level();
            $shop = M('seeds')->field('varieties')->filter('strip_tags')->filter('strip_tags')->select();
            $this->assign('shop',$shop);
            $this->assign('user_data',$data);
            $this->display('level_add');
        }
    }

    public function level_edit(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/level');
                return;
            }
            $data['user']=I('post.user','','addslashes');
            $data['seeds']=_safe($_POST['seeds']);
            $data['num']=_safe($_POST['num']);
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])){
                foreach ($_POST as $key=>$val){
                    if ($key == 'num'){
                        continue;
                    }else{
                        $where[$key] = $val;
                    }
                }
                $sqluser = substr($_POST['user'], 0, 3);
                $sqlname = ''.$sqluser.'_seed_warehouse';
                $edit=M($sqlname);
                $edit->num=$data['num'];
                if ($edit->where("user='%s' AND seeds='%s'",array($where['user'],$where['seeds']))->filter('strip_tags')->save()){
                    $this->redirect("User/level");
                }else{
                    $this->redirect("User/level");
                }
            }
        }else{
            $user = $_GET['user'];
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $sqluser = substr($user, 0, 3);
				$serd = M(''.$sqluser.'_members')->where("user='%s'",array($user))->find();
                $sqlname = ''.$sqluser.'_seed_warehouse';
                $sqllist = M($sqlname)->where("user='%s'",array($user))->filter('strip_tags')->select();
                foreach ($sqllist as $key=>$val){
                    $arr[] = $val;
                }
                creatToken();
                $this->assign('ser',$serd);
                $this->assign('list',$arr);
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }
    }

    public function getNum(){
        if(IS_AJAX){
            $user = I('post.user');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $guoshi = _safe(I('post.guoshi'));
                $idArray = array();
                if($guoshi != ''){
                    $idArray['seeds'] = $guoshi;
                }
                if($user != 0){
                    $idArray['user'] = $user;
                }
                $sqluser = substr($user, 0, 3);
                $sqlname = ''.$sqluser.'_seed_warehouse';
                $list =  M($sqlname)->field('num')->where("user='%s' AND seeds='%s'",array($idArray['user'],$idArray['seeds']))->filter('strip_tags')->find();
                $num = 0;
                foreach($list as $key=>$val){
                    $num += $val;
                }
                echo $num;
            }else{
                echo -1;
            }
        }else{
            echo -1;
        }
    }

    public function level_Ajax(){
       $id = I('post.id');
        if(preg_match("/^1[34578]\d{9}$/", $id)){
            $sqluser = substr($id, 0, 3);
            $sqlname = ''.$sqluser.'_seed_warehouse';
            $sel=M($sqlname);
            $sel->user=$id;
            $list = $sel->where("user=%s",array($id))->filter('strip_tags')->select();
            $num = '0';
            foreach ($list as $key=>$val){
                if ($val['seeds'] !== '分红宝'){
                    $num += $val['num'];
                }
            }
            echo $num;
        }
    }

    public function level_Ajax_fhb(){
        $id = I('post.id');
        if(preg_match("/^1[34578]\d{9}$/", $id)){
            $sqluser = substr($id, 0, 3);
            $sqlname = ''.$sqluser.'_seed_warehouse';
            $sel=M($sqlname);
            $sel->user=$id;
            $list = M($sqlname)->where("user=%s",array($id))->filter('strip_tags')->select();
            $num = '0';
            foreach ($list as $key=>$val){
                if ($val['seeds'] == '分红宝'){
                    $num += $val['num'];
                }
            }
            echo $num;
        }
    }

    public function level_del(){
        $sqluser = substr($_GET['user'], 0, 3);
        $sqlname = ''.$sqluser.'_seed_warehouse';
        if(preg_match("/^1[34578]\d{9}$/",$_GET['user'])){
            if (M($sqlname)->where("user=%s",array($_GET['user']))->delete()){
                $this->redirect("User/level");
            }else{
                $this->redirect("User/level");
            }
        }else{
            $this->redirect("User/level");
        }
    }

    public function warehouse(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/warehouse');
                return;
            }
            $user=I('post.start_user');
            $p=intval(I('get.p',1,'addslashes'));
            if(preg_match('/^\d{3,11}$/', $user)) {
                $Admina = new Admin();
                $data = $Admina->Set_Prop($user);
                $count=count($data);//得到数组元素个数
                $num =8;
                $pages = ceil($count/$num);
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
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
                $res = array_slice($data,($p-1)*8,8);
                if (empty($res)) {
                    $state = 0;
                } else {
                    $state = 1;
                }
                $this->assign('state', $state);
                $this->assign('ware_list', $res);
                $this->display();
            }
        }else{
        $Admin = new Admin();
        $data = $Admin->Set_Prop();
        $count=count($data);//得到数组元素个数
        $num =8;
        $p=intval(I('get.p',1,'addslashes'));
        $pages = ceil($count/$num);
        if($p!==null){
            $p =$p;
        }else{
            $p =1;
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
        $res = array_slice($data,($p-1)*8,8);

        if(empty($res)){
            $state=0;
        }else{
            $state=1;
        }
        creatToken();
        $this->assign('state',$state);
        $this->assign('ware_list',$res);
        $this->display();
        }
    }

    public function warehouse_add(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/warehouse');
                return;
            }
            //print_r($_POST);die;
            $data['user']=I('post.user','','addslashes');
            $data['props']=_safe(I('post.props','','addslashes'));
            $data['num']=_safe(I('post.num','','intval'));
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])) {
                $sqluser = substr($data['user'], 0, 3);
                $sqlname = ''.$sqluser.'_prop_warehouse';
                $prop=M($sqlname);
                foreach ($data as $key=>$val){
                    if ($key == 'num'){
                        continue;
                    }else{
                        $where[$key] = $val;
                    }
                }
                //print_r($data);die;
                $set = $prop->where("user='%s' AND props='%s'",array($where['user'],$where['props']))->filter('strip_tags')->find();
                if ($set !== null){
                    //$prop->num=$data['num'];
                    $prop->user=$data['user'];
                    //$prop->num=$data['num'];
                    $prop->props=$data['props'];
                    $bind[':id'] =$data['num'];
                    $prop->where("user='%s' AND props='%s'",array($where['user'],$where['props']))->bind($bind)->setInc('num',$data['num']);
                    $this->redirect("User/warehouse");
                }else{
                    $prop->user=$data['user'];
                    $prop->num=$data['num'];
                    $prop->props=$data['props'];
                    $prop->filter('strip_tags')->add();
                    $this->redirect("User/warehouse");
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $Admin = new Admin();
            $data = $Admin->Set_Level();
            $shop = M('shop')->filter('strip_tags')->select();
            $this->assign('shop',$shop);
            $this->assign('user_data',$data);
            $this->display();
        }
    }

    public function warehouse_edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/warehouse');
                return;
            }
            $data['user']=I('post.user','','addslashes');
            $data['props']=_safe(I('post.props','','addslashes'));
            $data['num']=_safe(I('post.num','','intval'));
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])) {
                $sqluser = substr($data['user'], 0, 3);
                $sqlname = ''.$sqluser.'_prop_warehouse';
                $s=M($sqlname);
                foreach ($data as $key=>$val){
                    if ($key == 'user' || $key == 'props'){
                        $where[$key] = $val;
                    }else if ($key == 'num'){
                        $save[$key] = $val;
                    }
                }
                $s->num=$save['num'];
                if ($s->where("user='%s' AND props='%s'",array($where['user'],$where['props']))->save()){
                    $this->redirect("User/warehouse");
                }else{
                    $this->redirect("User/warehouse");
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            $Admin = new Admin();
            creatToken();
            $data['user']=I('get.user','','addslashes');
            $data['props']=_safe(I('get.props','','addslashes'));
            $data['num']=_safe(I('get.num','','intval'));
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])) {
                $res = $Admin->Set_Prop_Edit($data);
                $this->assign('ware_list',$res);
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }
    }

    public function warehouse_del(){
        $data['user']=I('get.user','','addslashes');
        $data['props']=_safe(I('get.props','','addslashes'));
        $data['num']=_safe(I('get.num','','intval'));
        //print_r($data);die;
        if(preg_match("/^1[34578]\d{9}$/", $data['user'])) {
            $sqluser = substr($data['user'], 0, 3);
            $sqlname = ''.$sqluser.'_prop_warehouse';
            if(M($sqlname)->where("user='%s' AND props='%s' AND num=%d",array($data['user'],$data['props'],$data['num']))->delete()){
                $this->redirect("User/warehouse");
            }else{
                $this->redirect("User/warehouse");
            }
        }else{
            echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
            echo '<script> alert("好无聊+1！"); </script>';
            echo "<script> window.location.href='".U('User/User_info')."';</script>";
            exit();
        }
    }
    
    public function freeze(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/freeze');
                return;
            }
            $user=I('post.user');

            if(preg_match('/^\d{3,11}$/', $user)) {
                $date['user']=array("LIKE",'%'.$user.'%');
                $data = M('user_freeze')->where($date)->select();
                $count=count($data);//得到数组元素个数
                $num =8;
                $pages = ceil($count/$num);
                $p=intval(I('get.p',1,'addslashes'));
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
                }
                if($p<1){
                    $p =1;
                }else if($p > $pages){
                    $p = $pages;
                }
                $showPage = 5;
                $off=floor($pages/2);
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
                $res = array_slice($data,($p-1)*8,8);
                if(empty($res)){
                    $state=0;
                }else{
                    $state=1;
                }
                $this->assign('state',$state);
                $this->assign('freeze',$res);
                $this->display();
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+2！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
            }
        }else{
            $data = M('user_freeze')->select();
            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
            }
            if($p<1){
                $p =1;
            }else if($p > $pages){
                $p = $pages;
            }
            $showPage = 5;
            $off=floor($pages/2);
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
            $res = array_slice($data,($p-1)*8,8);

            if(empty($res)){
                $state=0;
            }else{
                $state=1;
            }
          
			$code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));
			$key = "QHP747ZJJ955";
			$str = substr(md5($code), 8, 10);
			
			session('CASHTOKEN', md5($key . $str));		
			creatToken();
            $this->assign('state',$state);
            $this->assign('freeze',$res);
            $this->display();
        }
    }
	public function pay_edit(){
		$id=I('get.id');
		$data=M('user_freeze')->where('id='.$id.'')->find();
		if(!empty($data)){
			if($data['pay']==0){
				$save['pay']=1;
			}else{
				$save['pay']=0;
			}
			if(M('user_freeze')->where('id='.$id.'')->save($save)!==false){
				echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("修改成功！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
			}else{
				echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("修改失败！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
			}
		}else{
			echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("修改失败！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
		}
	}
    public function freeze_edit(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/freeze');
                return;
            }
			$model=new Model();
			$model->startTrans();
            //print_r($_POST);die;
            $data['id']=intval(I('post.id',0,'addslashes'));
            $data['state']=intval(I('post.state',-1,'addslashes'));
            if($data['id']==0){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
            }
            if($data['state']==-1){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
            }
            $data['user']=I('post.user');
            $free=M('user_freeze');
            if(preg_match("/^1[34578]\d{9}$/", $data['user'])) {
                if($data['state'] == 0){
                    $data['freeze_time'] = 0;
                    $free->freeze_time= $data['freeze_time'];
                    $free->user=$data['user'];
                    $free->state=$data['state'];
                    if ($free->where('id=%d',array($data['id']))->save()){
                        $sqluser = substr($data['user'], 0, 3);
                        $sqlname = ''.$sqluser.'_members';
                        $member=M($sqlname);
                        $save['freeze_state'] = $data['state'];
                        $member->freeze_state=$save['freeze_state'];
                        $member->user=$data['user'];
                        if($member->where("user='%s'",array($data['user']))->save()!==false){
							$model->commit();
							echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
							echo '<script> alert("成功！"); </script>';
							echo "<script> window.location.href='".U('User/freeze')."';</script>";
							exit();
						}else{
							$model->rollback();
							echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("失败！"); </script>';
						echo "<script> window.location.href='".U('User/freeze')."';</script>";
						exit();
						}
                    }else{
						$model->rollback();
                        echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("失败！"); </script>';
						echo "<script> window.location.href='".U('User/freeze')."';</script>";
						exit();
                    }
                }else{
					
                    $data['freeze_time'] = time();
                    $free->freeze_time=$data['freeze_time'];
                    $free->user=$data['user'];
                    $free->state=$data['state'];
                    if ($free->where('id=%d',array($data['id']))->save()){
                        $sqluser = substr($data['user'], 0, 3);
                        $sqlname = ''.$sqluser.'_members';
                        $save['freeze_state'] = $data['state'];
                        $member=M($sqlname);
                        $member->freeze_state=$save['freeze_state'];
                        $member->where("user=%s",array($data['user']))->save();
                        
						$model->commit();
						echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("成功！"); </script>';
						echo "<script> window.location.href='".U('User/freeze')."';</script>";
						exit();
									
                             

                    }else{
						$model->rollback();
                        echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
						echo '<script> alert("失败！"); </script>';
						echo "<script> window.location.href='".U('User/freeze')."';</script>";
						exit();
                    }
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $id = intval(I("get.id",0,'addslashes'));
            if($id==0){
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/freeze')."';</script>";
                exit();
            }else{
                $free=M('user_freeze');
                $data = $free->where('id='.$id)->find();
                $this->assign('data',$data);
                $this->display();
            }
        }
    }
	
	public function Frozen(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/Frozen');
                return;
            }
            $Admin = new Admin();
            $post=array_filter(I('post.'));
            $user=$post['start_user'];
            //print_r($post);die;
			//preg_match('/^\d{3,11}$/', )
            if($user){
                $data = $Admin->Set_Level($user);
				foreach($data as $k=>$v){
					$institutions=M('institutions');
					$in=$institutions->where("user='%s'",array($v['user']))->find();
					if(!empty($in)){
						$data[$k]['state_gg']=1;
					}else{
						$data[$k]['state_gg']=0;
					}
				}
				
				$columnKey='level';
				array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
				
                $count=count($data);//得到数组元素个数
                $num =8;
                $pages = ceil($count/$num);
                $p=intval(I('get.p',1,'addslashes'));
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
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
                $res =array_slice($data,($p-1)*8,8);
                if(empty($res)){
                    $state=0;
                    $state_p=0;
                }else{
                    $state=1;
                    $state_p=1;
                }
                $this->assign('state',$state);
                $this->assign('state_p',$state_p);
                $this->assign('user_info',$res);
                $this->display('frozen');
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('User/User_info')."';</script>";
                exit();
            }
        }else{
            $Admin = new Admin();
            $data = $Admin->Set_Level();
			foreach($data as $k=>$v){
				$institutions=M('institutions');
				$in=$institutions->where("user='%s'",array($v['user']))->find();
				if(!empty($in)){
					$data[$k]['state_gg']=1;
				}else{
					$data[$k]['state_gg']=0;
				}
			}
			
			$columnKey='level';
			array_multisort(i_array_column($data,$columnKey),SORT_DESC,$data);   //数组排序
			
            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            $res =array_slice($data,($p-1)*8,8);
            if(empty($res)){
                $state=0;
                $state_p=0;
            }else{
                $state=1;
                $state_p=1;
            }
            creatToken();
            $this->assign('state',$state);
            $this->assign('state_p',$state_p);
            $this->assign('user_info',$res);
            $this->display('frozen');
        }
    }
	
	public	function frozen_edit(){
		if(IS_POST){
			$C_n = I("post.coin_num");
			$Id = intval(I("post.user",0,'addslashes'));
			$Su = substr($Id,0,3);
			$Se = ''.$Su.'_members';
			$res = M("$Se")->where('user='.$Id)->find();
			if($C_n>=0){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('User/frozen_edit',array('id'=>$Id))."';</script>";
                exit();
			}
			
			if($res['coin_freeze']>=$C_n){
				$Model = new Model;
				$Model->startTrans();
				$User = M("$Se");
				if($User->where('user='.$Id)->setDec('coin_freeze',$C_n)){
					if($User->where('user='.$Id)->setInc('coin',$C_n)){
						$User->commit();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('返还成功');</script>";
						echo "<script> window.location.href='".U('User/frozen')."';</script>";
					}else{
						$User->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('返还失败');</script>";
						echo "<script> window.location.href='".U('User/frozen_edit',array('id'=>$Id))."';</script>";
						exit();
					}
				}else{
						$User->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('金币返还失败');</script>";
						echo "<script> window.location.href='".U('User/frozen_edit',array('id'=>$Id))."';</script>";
						exit();
				}
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误1');</script>";
                echo "<script> window.location.href='".U('User/frozen_edit',array('id'=>$Id))."';</script>";
                exit();
			}
		}else{
			$id = intval(I("get.id",0,'addslashes'));
			$sqluser = substr($id,0,3);
			$sqlname = ''.$sqluser.'_members';
			$frozen = M("$sqlname")->where('user='.$id)->find();
            $this->assign('data',$frozen);
            $this->display('frozen_edit');
		}
	}
	
	public function ppg(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('User/ppg');
                return;
            }
            $Admin = new Admin();
            $post=array_filter(I('post.'));
            $user=$post['start_user'];
            //print_r($post);die;
			//preg_match('/^\d{3,11}$/', )
            if($user){
                $data = $Admin->Set_ppg($user);
				
				//$seeds_where['varieties']  =  array("not in", "摇钱树");
				//$AllSeeds  =  M("seeds")->where($seeds_where)->field("varieties")->order('varieties asc')->select();

				//foreach($date as $key=>$val){
				//$data[$val['user']][]=$val;
				//}
			
                $count=count($data);//得到数组元素个数
                $num =8;
                $pages = ceil($count/$num);
                $p=intval(I('get.p',1,'addslashes'));
                if($p!==null){
                    $p =$p;
                }else{
                    $p =1;
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
                $res =array_slice($data,($p-1)*8,8,true);
                if(empty($res)){
                    $state=0;
                    $state_p=0;
                }else{
                    $state=1;
                    $state_p=1;
                }
				$this->assign('state',$state);
				//$this->assign('AllSeeds',$AllSeeds);
				$this->assign('state_p',$state_p);
				$this->assign('user_info',$res);
				$this->display('ppg');
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误');</script>";
                echo "<script> window.location.href='".U('User/ppg')."';</script>";
                exit();
            }
        }else{
            $Admin = new Admin();
            $data = $Admin->Set_ppg();
			
			//$seeds_where['varieties']  =  array("not in", "摇钱树");
			//$AllSeeds  =  M("seeds")->where($seeds_where)->field("varieties")->order('varieties asc')->select();
			
			//foreach($date as $key=>$val){
				//$data[$val['user']][]=$val;
			//}					
			
            $count=count($data);//得到数组元素个数
            $num =8;
            $pages = ceil($count/$num);
            $p=intval(I('get.p',1,'addslashes'));
            if($p!==null){
                $p =$p;
            }else{
                $p =1;
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
            $res =array_slice($data,($p-1)*8,8,true);
			
            if(empty($res)){
                $state=0;
                $state_p=0;
            }else{
                $state=1;
                $state_p=1;
            }
            creatToken();
            $this->assign('state',$state);
            //$this->assign('AllSeeds',$AllSeeds);
            $this->assign('state_p',$state_p);
            $this->assign('user_info',$res);
            $this->display('ppg');
        }
    }
	
	/*public function ppgNum(){
        if(IS_AJAX){
            $user = I('post.user');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                $guoshi = _safe(I('post.guoshi'));
                $idArray = array();
                if($guoshi != ''){
                    $idArray['seeds'] = $guoshi;
                }
                if($user != 0){
                    $idArray['user'] = $user;
                }
                $sqluser = substr($user, 0, 3);
                $sqlname = ''.$sqluser.'_fruit_record';
                $list =  M($sqlname)->field('num')->where("user='%s' AND seed='%s'",array($idArray['user'],$idArray['seeds']))->filter('strip_tags')->find();
                $num = 0;
                foreach($list as $key=>$val){
                    $num += $val;
                }
                echo $num;
            }else{
                echo -1;
            }
        }else{
            echo -1;
        }
    }*/
	
	public	function ppg_edit(){
		if(IS_POST){
			$C_n = I("post.num",0,'addslashes');
			$user = I("post.user",0,'addslashes');
			$Id = intval(I("post.id",0,'addslashes'));
			$Seeds = I("post.seeds",0,'addslashes');
			$Su = substr($user,0,3);
			$Se = ''.$Su.'_fruit_record';
			$res = M("$Se")->where('id="'.$Id.'" AND seed="'.$Seeds.'" AND user="'.$user.'"')->find();
			if($C_n<=0){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误0');</script>";
                echo "<script> window.location.href='".U('User/ppg_edit',array('id'=>$user))."';</script>";
                exit();
			}
			
			if($res['num']>=$C_n){
				$Model = new Model;
				$Model->startTrans();
				$User = M("$Se");
				$wa = ''.$Su.'_seed_warehouse';
				if($User->where('id="'.$Id.'" AND seed="'.$Seeds.'" AND user="'.$user.'"')->setDec('num',$C_n)){
					
					if(M("$wa")->where('seeds="'.$Seeds.'" AND user="'.$user.'"')->find() ==NULL){
						$User->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('非法操作');</script>";
						echo "<script> window.location.href='".U('User/ppg_edit',array('id'=>$user))."';</script>";
						exit();
					}
					
					if(M("$wa")->where('user="'.$user.'" AND seeds="'.$Seeds.'"')->setInc('num',$C_n)){
						$User->commit();
						M("$Se")->where('id="'.$Id.'" AND seed="'.$Seeds.'" AND user="'.$user.'"')->delete();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('返还成功');</script>";
						echo "<script> window.location.href='".U('User/ppg')."';</script>";
					}else{
						$User->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('返还失败');</script>";
						echo "<script> window.location.href='".U('User/ppg_edit',array('id'=>$user))."';</script>";
						exit();
					}
				}else{
						$User->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo "<script> alert('金币返还失败');</script>";
						echo "<script> window.location.href='".U('User/ppg_edit',array('id'=>$user))."';</script>";
						exit();
				}
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('输入信息有误1');</script>";
                echo "<script> window.location.href='".U('User/ppg_edit',array('id'=>$user))."';</script>";
                exit();
			}
		}else{
			$user = I('get.user');
			$id = I('get.id');
            if(preg_match("/^1[34578]\d{9}$/",$user)){
                $sqluser = substr($user,0,3);
				$serd = M(''.$sqluser.'_members')->where("user='%s'",array($user))->find();
                $sqlname = ''.$sqluser.'_fruit_record';
                $arr = M($sqlname)->where('user="'.$user.'" AND id="'.$id.'"')->filter('strip_tags')->find();
                /*foreach ($sqllist as $key=>$val){
                    $arr[] = $val;
                }*/
                creatToken();
                $this->assign('data',$serd);
                $this->assign('list',$arr);
                $this->display('ppg_edit');
            }else{
                echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
                echo '<script> alert("好无聊+1！"); </script>';
                echo "<script> window.location.href='".U('User/ppg')."';</script>";
                exit();
            }
			
            
		}
	}
	
	public function coce(){
        $code = rand(1,99);
		echo $code;
		$shop = M('shop');
		$res = $shop->where('id=6')->find();
		
		if($res['num'] !== '0'){
			if($code <= $res['num']){
				$shop->where('id=6')->setDec('num',$code);
			}else{
				echo '商品数量不足"'.$res['num'].'"';
			}
		}else{
			echo '商品已卖完';
		}
		
	}
	
	public function update_ajax(){
		
		if($_POST['object']['token'] == session('CASHTOKEN') &&IS_AJAX){
			
			$save_data['is_cash']   =   $_POST['object']['cash_cash'];
			echo M("user_freeze")->where('user="'.$_POST['material_id'].'"')->save($save_data);
			
		}else{
			
			echo json_encode($_POST);
			
		}
	}
	
	//冻结金币返还
	/*public function frozen_return(){
		$id = intval(I("get.user",0,'addslashes'));
		$sqluser = substr($id, 0, 3);
        $sqlname = ''.$sqluser.'_members';
		$frozen = M("$sqlname");
		$frozen->startTrans();
		$res = $frozen->where('user='.$id)->find();
		if($res['coin_freeze']<=0){
			$this->Frozen();
			exit;
		}
		if($frozen->where('user='.$id)->setInc('coin',$res['coin_freeze'])){
				$data['coin_freeze'] = 0;
		if($frozen->where('user='.$id)->save($data)){
				$frozen->commit();
				$this->Frozen();
			}else{
				$frozen->rollback();
				echo 2;
				$this->Frozen();
			}
		}else{
			$frozen->rollback();
			echo 1;
			$this->Frozen();
		}
	}*/
	
}