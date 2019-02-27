<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-05-11
 * Time: 17:08
 */
namespace Admin\Controller;
use Think\Controller;
use Org\Util\ExcelToArrary ;
use Think\Model;
use Think\Find;
use Think\Upload;
use Think\Tool;
class FruitController extends AdminController
{
    //果实主页
    public function index(){
        $seed=M('Seeds');
        $data=$seed->filter('strip_tags')->order('ord')->select();
        foreach ($data as $k=>$v){
            $data[$k]['first_time']= $v['first_time']/3600;
            $data[$k]['second_time']= $v['second_time']/3600;
            $data[$k]['third_time']=$v['third_time']/3600;
        }
		$case=''.date('Y-m').'_pay';
		$data_s=M($case)->where('queue=2 AND num>0 AND state<2')->select();
		//print_r($data_s);die;
		if(empty($data_s)){
            $state=0;
        }else{
            $state=1;
        }
        $count=count($data_s);//得到数组元素个数
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
        $res =array_slice($data_s,($o-1)*8,8);
		if(empty($res)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('data_s',$res);//分页内容
        $this->assign('data',$data);
		$this->assign('state',$state);
		
		//$data=M('Seeds')->limit(0,6)->select();
		$d=date('d');
		$p=0;
		$data_err=array();
		foreach ($data as $k=>$v){
		  $seed=$v['varieties'];
		   $case_p=''.date('Y-m').'_pay';
		   $str = "";
		   //卖方数据
		   $data_p_s=M(''.$case_p.'')->order('money asc')->where('state<2 AND num=0 AND seed="'.$seed.'"')->select();
		   //print_r($data_p_s);
		   
		   $count_s=M(''.$case_p.'')->order('money asc')->where('state<2 AND num=0 AND seed="'.$seed.'"')->count();
		  //$p+=$count_s;
		   for($i=0;$i<$count_s;$i++){
			   
			   $data_err[$p]=$data_p_s[$i];
			   $p++;
			   //$save['state']=2;
			  // M(''.$case_p.'')->where('id='.$data_p_s[$i]['id'])->save($save);
		   }
		}
		if(empty($data_err)){
            $state_e=0;
        }else{
            $state_e=1;
        }
        $count_err=count($data_err);//得到数组元素个数
        $num_err =8;
        $pages_err = ceil($count_err/$num_err);
		
       //$this->assign('pages',$pages+1); //分页
        if($_GET['p']!==null){
            $p =intval(I('get.p',1,'addslashes'));
        }else{
            $p =1;
        }
		
        if($p<1){
            $p =1;
        }else if($p > $pages_err){
            $p = $pages_err;
        }
        $showPage_err = 5;
        $off_err=floor($showPage_err/2);
        $start_e=$p-$off_err;
        $end_e=$p+$off_err;
        //起始页
        if($p-$off_err < 1){
            $start_e = 1;
            $end_e = $showPage_err;
        }
        //结束页
        if($p+$off_err > $pages_err){
            $end_e = $pages_err;
            $start_e = $pages_err-$showPage_err+1;
        }
        if($pages_err < $showPage_err){
            $start_e = 1;
            $end_e = $pages_err;
        }
        $this->assign('start_e',$start_e); //分页
        $this->assign('end_e',$end_e+1); //分页
        $this->assign('p',$p);
        $res_e =array_slice($data_err,($p-1)*8,8);
        $this->assign('data_err',$res_e);//分页内容
		$this->assign('state_e',$state_e);
		
        $this->display();
    }
	public function check_safe_pay(){
		$data=M('Seeds')->limit(0,6)->select();
		$d=date('d');
		$p=0;
		$data_s=array();
		foreach ($data as $k=>$v){
		  $seed=$v['varieties'];
		   $case_p=''.date('Y-m').'_pay';
		   $str = "";
		   //卖方数据
		   $data_p_s=M(''.$case_p.'')->order('money asc')->where('state<2 AND num=0 AND seed="'.$seed.'"')->select();
		   //print_r($data_p_s);
		   
		   $count_s=M(''.$case_p.'')->order('money asc')->where('state<2 AND num=0 AND seed="'.$seed.'"')->count();
		  //$p+=$count_s;
		   for($i=0;$i<$count_s;$i++){
			   //$data_s[$p]=$data_p_s[$i];
			   //$p++;
			   $save['state']=2;
			   M(''.$case_p.'')->where('id='.$data_p_s[$i]['id'])->save($save);
		   }
		   
		}
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("修改成功！"); </script>';
			echo "<script> window.location.href='".U('Fruit/index')."';</script>";
			exit(); 
	}
	public function check_safe(){
		$num=date('Y-m');
		$case=''.$num.'_pay';
		$save['queue']=0;
		$y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");
            //print_r($m);die;
            $s_data=M('Global_conf')->where('cases="start_time"')->find();
            $e_data=M('Global_conf')->where('cases="end_time"')->find();

            $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
            $start_t=$start-3600*24;

            $time=time();

            $end = mktime($e_data['value'],0,0,$m,$d,$y);
		if(M($case)->where('queue=2 AND time>='.$start)->save($save)){
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("修改成功！"); </script>';
			echo "<script> window.location.href='".U('Fruit/index')."';</script>";
			exit(); 
		}else{
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("修改失败！"); </script>';
			echo "<script> window.location.href='".U('Fruit/index')."';</script>";
			exit();
		}
	}
	public function seed_num(){
		$seed_level=M('seed_level');
		$data=$seed_level->select();
		$this->assign('data',$data);
		$this->display();
	}
	public function edit_num(){
		$seed_level=M('seed_level');
		if(IS_POST){
			if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/seeed_num');
                return;
            }
			$id=I('post.id');
			$data['seed_level']=I('post.level');
			if($seed_level->where('id ='.$id)->save($data)!==false){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("修改成功！"); </script>';
				echo "<script> window.location.href='".U('Fruit/seed_num')."';</script>";
				exit(); 
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("修改失败！"); </script>';
				echo "<script> window.location.href='".U('Fruit/seed_num')."';</script>";
				exit();
			}
		}else{
			creatToken();
			$id=I('get.id');
			$data=$seed_level->where('id ='.$id)->find();
			$this->assign('data',$data);
			$this->display();
		}
	}
	
	public function add_matching(){
		if(IS_POST){
			//print_r($_POST);
			//exit;
			if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/index');
                return;
            }
			$money=I('post.money');
			$num=I('post.num');
			$user=I('post.user');
			$type=I('post.type');
			$seed=I('post.seed');
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;111
			$s_data=M('Global_conf')->where('cases="start_time"')->find();
			$e_data=M('Global_conf')->where('cases="end_time"')->find();
			$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
			$start_t=$start-3600*24;
			$time=time();
			//print_r($start);die;
			$end = mktime($e_data['value'],0,0,$m,$d,$y);
			$end_t=$end-3600*24;
			///////////////////////
			$data_seed=M('Seeds')->where('varieties ="'.$seed.'"')->find();
			$float_conf=M('Global_conf')->where('cases="float"')->find();
			$zhi=$float_conf['value'];
			/*if($time<$start){
				//echo json_encode(1);die;
				$money=M('Pay_statistical')->where('seed="'.$data_seed['varieties'].'"')->order('time DESC')->select();
				if(empty($money)){
				  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
				   $end_money=$data_seed['first_price'];
				}else{
				   $end_money=$money[0]['end_money'];
				}      
				//开盘前委托最高价格
				$max=$end_money+$end_money*$zhi;
				//最低价
				$min=$end_money-$end_money*$zhi;			  
			}elseif($time>$end){
				//echo json_encode(2);die;
				$money_s=M('Pay_statistical')->where('seed="'.$data_seed['varieties'].'"')->order('time DESC')->select();
				if(empty($money_s)){
				  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
				   $end_money=$data_seed['first_price'];
				}else{
				   $end_money=$money_s[0]['end_money'];
				}    
				//开盘前委托最高价格
				$max=$end_money+$end_money*$zhi;
				//最低价
				$min=$end_money-$end_money*$zhi;			  
			}else{	*/		   
				//echo json_encode(3);die;
				$cases=''.date('Y-m').'_matching';
				$data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$data_seed['varieties'].'"')->order('time')->select(); 
				if(empty($data_max)){					  
				  $money_s=M('Pay_statistical')->where('seed="'.$data_seed['varieties'].'"')->order('time DESC')->select();
				  if(empty($money_s)){						   
					  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
					  $end_money=$data_seed['first_price'];
				  }else{
					  $end_money=$money_s[0]['end_money'];						  
				  }
				}else{
				  $end_money=$data_max[0]['money'];					 
				}
				//开盘前委托最高价格
				$max=$end_money+$end_money*$zhi;
				//最低价
				$min=$end_money-$end_money*$zhi;				  
			//}
			$max=round($max,5);
			$min=round($min,5);
			$zhi=$max/$money-$min/$money;
			//print_r($max);echo '<br/>';
			//print_r($zhi);echo '<br/>';
			$time=floor($zhi)+1;
			//print_r($time);die;
			 $case=''.date('Y-m').'_pay';
       
			$num_tel=substr($user,0,3);
			 $model=new Model();
			$model->startTrans();
			for($i=0;$i<=$time;$i++){
				//echo $min;echo $money;
				$data['money']=$min+$money*$i;
				if($data['money']>$max){
					break;
				}else{
					$data['num']=$num;
					$data['trans_type']=0;
					$data['type']=$type;
					$data['user']=$user;
					$data['submit_num']=$num;
					$data['time']=time();
					$data['seed']=$seed;
					//print_r($data);die;
					if(M(''.$case.'')->add($data)){
						$id=M(''.$case.'')->GetlastinsID();
						if($data['type']==1){
							//$case_m=$table->table($user,$case);
							$case_m=''.$num_tel.'_members';
							$data_m['coin_freeze']=$data['money']*$data['num'];
							//$data_m['coin_freeze']=$data['money'];
							$data_coin=M(''.$case_m.'')->where('user ='.$user)->find();
							if($data_m['coin_freeze']>$data_coin['coin']){
								$model->rollback();
								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
								echo '<script> alert("金额不足！"); </script>';
								echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
								exit(); 
							}
							//echo $data_m['coin_freeze'];die;
							$data_m['coin_freeze']=(float)$data_m['coin_freeze'];
							$coin=M(''.$case_m.'')->where('user ='.$user)->find();
							$save['coin_freeze']=$coin['coin_freeze']+$data_m['coin_freeze'];
							$save['coin']=$coin['coin']-$data_m['coin_freeze'];
							//print_r($data_m['coin_freeze']);die;
							if(M(''.$case_m.'')->where('user ='.$user)->save($save)){
								 $model->commit();
								$mou=date('Y-m');
								$path='../log/order/'.$mou.'';
								if (!file_exists($path)){
									mkdir($path,0777,true);
								}
								$day=date('Y-m-d');
								$str='用户;'.$data['user'].';买入果实:'.$data['seed'].'买入数量：'.$data['num'].'买入单价:'.$data['money'].'';
								file_put_contents('../log/order/'.$mou.'/'.$day.'buyorder.log',$str.PHP_EOL."\n",FILE_APPEND);
							   
							}else{
								$model->rollback();
								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
								echo '<script> alert("冻结金币失败！"); </script>';
								echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
								exit();           
							}
						}else{
							
								//卖出
							//$table=new Tool();
							$case_f=''.$num_tel.'_fruit_record';
							$case_s=''.$num_tel.'_seed_warehouse';
							//$case_s=$table->table($user,$case);
							$data_num=M(''.$case_s.'')->where('user ='.$user.' AND seeds ="'.$data['seed'].'"')->find();
							//echo $data_num['num'];die;	
							if($data_num['num']<$num){
								$model->rollback();
								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
								echo '<script> alert("果实不足！"); </script>';
								echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
								exit();  
							}else{
								if(M(''.$case_s.'')->where('user ='.$user.' AND seeds ="'.$data['seed'].'"')->setDec('num',$num)){
									$data_s['seed']=$data['seed'];
									$data_s['num']=$data['num'];
									$data_s['time']=time();
									$data_s['user']=$_SESSION['user'];
									$data_s['money']=$data['money'];
									//$data['money']=0.001;
									$data_record_s=M($case_f)->where('user="'.$user.'" AND money='.$data_s['money'].' AND seed="'.$data_s['seed'].'"')->find();
									if(!$data_record_s){
										if(M(''.$case_f.'')->add($data_s)){
											//echo 11;die;
											$model->commit();
										}else{
											$model->rollback();
											echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
											echo '<script> alert("果实冻结失败！"); </script>';
											echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
											exit(); 
										}
									}else{
										if(M(''.$case_f.'')->where('user="'.$user.'" AND money='.$data['money'].' AND seed="'.$data['seed'].'"')->setInc('num',$data_s['num'])){
										$model->commit();
										}else{
											$model->rollback();
											echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
											echo '<script> alert("果实冻结失败！"); </script>';
											echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
											exit();           
										}
									}
									$mou=date('Y-m');
									$path='../log/order/'.$mou.'';
									if (!file_exists($path)){
										mkdir($path,0777,true);
									}
									$day=date('Y-m-d');
									$str='用户;'.$data['user'].';卖出果实:'.$data['seed'].'卖出数量：'.$data['num'].'卖出单价:'.$data['money'].'';
									file_put_contents('../log/order/'.$mou.'/'.$day.'sellorder.log',$str.PHP_EOL."\n",FILE_APPEND);
								}else{
									$model->rollback();
									echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
									echo '<script> alert("果实冻结失败！"); </script>';
									echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
									exit();           
								}
							}
							
						}
					}else{
						$model->rollback();
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo '<script> alert("订单信息添加失败！"); </script>';
						echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
						exit();           
					}
				}
			}
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("订单添加成功！"); </script>';
			echo "<script> window.location.href='".U('Fruit/add_matching')."';</script>";
			exit(); 
		}else{
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;111
			$s_data=M('Global_conf')->where('cases="start_time"')->find();
			$e_data=M('Global_conf')->where('cases="end_time"')->find();
			$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
			$start_t=$start-3600*24;
			$time=time();
			//print_r($start);die;
			$end = mktime($e_data['value'],0,0,$m,$d,$y);
			$end_t=$end-3600*24;
			///////////////////////
			$float_conf=M('Global_conf')->where('cases="float"')->find();
			$zhi=$float_conf['value'];	
			$data_seed=M('Seeds')->limit(0,6)->select();
			foreach($data_seed as $key=>$val){
				if($time<$start){
					//echo json_encode(1);die;
					$money=M('Pay_statistical')->where('seed="'.$val['varieties'].'"')->order('time DESC')->select();
					if(empty($money)){
					  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
					   $end_money=$val['first_price'];
					}else{
					   $end_money=$money[0]['end_money'];
					}      
					//开盘前委托最高价格
					$max_entrust=$end_money+$end_money*$zhi;
					//最低价
					$min_entrust=$end_money-$end_money*$zhi;			  
				}elseif($time>$end){
					//echo json_encode(2);die;
					$money=M('Pay_statistical')->where('seed="'.$val['varieties'].'"')->order('time DESC')->select();
					if(empty($money)){
					  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
					   $end_money=$val['first_price'];
					}else{
					   $end_money=$money[0]['end_money'];
					}    
					//开盘前委托最高价格
					$max_entrust=$end_money+$end_money*$zhi;
					//最低价
					$min_entrust=$end_money-$end_money*$zhi;			  
				}else{			   
					//echo json_encode(3);die;
					$cases=''.date('Y-m').'_matching';
					$data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$val['varieties'].'"')->order('time')->select(); 
					if(empty($data_max)){					  
					  $money=M('Pay_statistical')->where('seed="'.$val['varieties'].'"')->order('time DESC')->select();
					  if(empty($money)){						   
						  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
						  $end_money=$val['first_price'];
					  }else{
						  $end_money=$money[0]['end_money'];						  
					  }
					}else{
					  $end_money=$data_max[0]['money'];					 
					}
					//开盘前委托最高价格
					$max_entrust=$end_money+$end_money*$zhi;
					//最低价
					$min_entrust=$end_money-$end_money*$zhi;				  
				}
				$data[$key]['seed']=$val['varieties'];
				$data[$key]['max']=round($max_entrust,5);
				$data[$key]['min']=round($min_entrust,5);
			}
			creatToken();
			$this->assign('seed',$data_seed);
			$this->assign('data',$data);
			//print_r($data);die;
			$this->display();
		}
	}
	
    //果实添加
    public function fruit_add(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/index');
                return;
            }
            $seed=M('Seeds');
            //$first=I('post.first','','addslashes');
            //$second=I('post.second','','addslashes');
            //$third=I('post.third','','addslashes');
            //$harvest=I('post.harvest','','addslashes');
            //$data['first_price']=I('post.first_price','','addslashes');
            //$data['first_time']=$first*3600;
            //$data['second_time']=$second*3600;
            //$data['third_time']=$third*3600;
            //$data['harvest_hours']=$harvest*3600;
            //$data['fruit_number']=I('post.fruit_number','','addslashes');
            //$data['varieties']=I('post.varieties','','addslashes');
            //$data['state']=I('post.state','','addslashes');
            $seed->first_price = I('post.first_price','','addslashes');
			//$seed->open_price = I('post.open_price','','addslashes');
            $seed->first_time = I('post.first','','addslashes')*3600;
            $seed->second_time = I('post.second','','addslashes')*3600;
            $seed->third_time = I('post.third','','addslashes')*3600;
            $seed->harvest_hours = I('post.harvest','','addslashes')*3600;
            $seed->fruit_number = I('post.fruit_number','','addslashes');
            $seed->varieties = I('post.varieties','','addslashes');
            $seed->state = I('post.state','','addslashes');
                if($seed->filter('strip_tags')->add()){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加成功！"); </script>';
                    echo "<script> window.location.href='".U('Fruit/index')."';</script>";
                    exit();
                }else{
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("添加失败！"),history.back(); </script>';
                    exit();
                }

        }else{
            creatToken();
            $this->display();
        }
    }
    //果实修改
    public function fruit_edit(){
        if(IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/index');
                return;
            }
            //$id=I('post.id'.''.'');
            //$first=I('post.first','','');
            //$second=I('post.second','','');
            //$third=I('post.third','','');
            //$harvest=I('post.harvest','','');
            //$data['first_price']=I('post.first_price','','');
            //$data['first_time']=$first*3600;
            //$data['second_time']=$second*3600;
            //$data['third_time']=$third*3600;
            //$data['harvest_hours']=$harvest*3600;
            //$data['fruit_number']=I('post.fruit_number','','');
            //$data['varieties']=I('post.varieties','','');
            //$data['state']=I('post.state','','');
            $seed=M('Seeds');
            $seed->first_price = I('post.first_price','','addslashes');
			$seed->open_price = I('post.open_price','','addslashes');
            $seed->first_time = I('post.first','','addslashes')*3600;
            $seed->second_time = I('post.second','','addslashes')*3600;
            $seed->third_time = I('post.third','','addslashes')*3600;
            $seed->harvest_hours = I('post.harvest','','addslashes')*3600;
            $seed->fruit_number = I('post.fruit_number','','addslashes');
            $seed->varieties = I('post.varieties','','addslashes');
            $seed->state = I('post.state','','addslashes');
			//echo 1;die;
            $where['id'] = ':id';
            $bind[':id'] = array(I('post.id'),\PDO::PARAM_INT);
            if($seed->where($where)->bind($bind)->filter('strip_tags')->save() !==false){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改成功！"); </script>';
                echo "<script> window.location.href='".U('Fruit/index')."';</script>";
                exit();
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败！"),history.back(); </script>';
                exit();
            }
        }else{
            creatToken();			
            //$id=I('get.id'.''.'');
            $seed=M('Seeds');
            $where['id'] = ':id';
            $bind[':id'] = array(I('get.id'),\PDO::PARAM_INT);
            $data=$seed->where($where)->bind($bind)->filter('strip_tags')->find();
            $data['first_time']=$data['first_time']/3600;
            $data['second_time']=$data['second_time']/3600;
            $data['third_time']=$data['third_time']/3600;
            $data['harvest_hours']=$data['harvest_hours']/3600;
			/**时间**/
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;111
			$s_data=M('Global_conf')->where('cases="start_time"')->find();
			$e_data=M('Global_conf')->where('cases="end_time"')->find();
			$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
			$start_t=$start-3600*24;
			$time=time();
			//print_r($start);die;
			$end = mktime($e_data['value'],0,0,$m,$d,$y);
			$end_t=$end-3600*24;
			///////////////////////
			$float_conf=M('Global_conf')->where('cases="float"')->find();
			  $zhi=$float_conf['value'];		 
			
			   $cases=''.date('Y-m').'_matching';
				  $data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$data['varieties'].'"')->order('time DESC')->select(); 
				  if(empty($data_max)){					  
					  $money=M('Pay_statistical')->where('seed="'.$data['varieties'].'"')->order('time DESC')->select();
					  if(empty($money)){						   
						  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
						  $end_money=$data['first_price'];
					  }else{
						  $end_money=$money[0]['end_money'];						  
					  }
				  }else{
					  $end_money=$data_max[0]['money'];					 
				  }
				  //开盘前委托最高价格
				  $max_entrust=$end_money+$end_money*$zhi;
				  //最低价
				  $min_entrust=$end_money-$end_money*$zhi;		
			  $max_entrust=round($max_entrust,5);
			  $min_entrust=round($min_entrust,5);
			  $this->assign('max',$max_entrust);
			  $this->assign('min',$min_entrust);
            //print_r($data);die;
            $this->assign('data',$data);
            $this->display();
        }
    }
	public function directional(){
		if(IS_POST){
			$user=I('post.user');
		}else{
			$user=I('get.user');
		}
		if(empty($user)){
			$sql='type=0';
		}else{
			$sql='type=0 AND user='.$user.'';
		}
		$data_s=M('seed_orientation')->where($sql)->order('id DESC')->select();
		$num_l=M('seed_orientation')->where('num = 0')->count();
		$num_all=M('seed_orientation')->count();
		//print_r($data_s);die;
		if(empty($data_s)){
			$state=0;
		}else{
			$state=1;
		}
		$count=count($data_s);//得到数组元素个数
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
		$res =array_slice($data_s,($o-1)*8,8);
		///print_r($res);die;
		$this->assign('data',$res);//分页内容
		$this->assign('user',$user);
		$this->assign('state',$state);
		$this->assign('num_l',$num_l);
		$this->assign('num_all',$num_all);
		//print_r($state);die;
		$this->display();
	}
	public function directional_long(){
		if(IS_POST){
			$user=I('post.user');
			$type=I('post.type');
		}else{
			$user=I('get.user');
			$type=I('get.type');
		}
		$time=time();
		if(empty($user)&&empty($type)){
			$sql='type=1';
		}elseif(!empty($user)&&empty($type)){
			$sql='type=1 AND user='.$user.'';
		}elseif(!empty($user)&&!empty($type)){
			if($type==1){
				$sql='type=1 AND user='.$user.' AND end_time<'.$time.'';
			}else{
				$sql='type=1 AND user='.$user.' AND end_time>'.$time.'';
			}
		}elseif(empty($user)&&!empty($type)){
			if($type==1){
				$sql='type=1 AND end_time<'.$time.'';
			}else{
				$sql='type=1 AND end_time>'.$time.'';
			}
		}
		$data_s=M('seed_orientation')->where($sql)->order('id DESC')->select();
		foreach($data_s as $key=>$val){
			if($val['end_time']<$time){
				$data_s[$key]['ac_state']=1;
			}else{
				$data_s[$key]['ac_state']=0;
			}
		}
		//print_r($data_s);die;
		$num_l=M('seed_orientation')->where('num = 0')->count();
		$num_all=M('seed_orientation')->count();
		//print_r($data_s);die;
		if(empty($data_s)){
			$state=0;
		}else{
			$state=1;
		}
		$count=count($data_s);//得到数组元素个数
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
		$res =array_slice($data_s,($o-1)*8,8);
		///print_r($res);die;
		$this->assign('data',$res);//分页内容
		$this->assign('state',$state);
		$this->assign('type',$type);
		$this->assign('user',$user);
		//print_r($state);die;
		$this->display();
		
	}
	public function directional_del(){
		$num=I('get.num');
		if($num==0){
			if(M('seed_orientation')->where('num>=0 AND type=0')->delete()){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("成功！！"); </script>';
				echo "<script> window.location.href='".U('Fruit/directional')."';</script>";
				exit(); 
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("失败！！"); </script>';
				echo "<script> window.location.href='".U('Fruit/directional')."';</script>";
				exit(); 
			}
		}elseif($num==1){
			if(M('seed_orientation')->where('num=0 AND type=0')->delete()){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("成功！！"); </script>';
				echo "<script> window.location.href='".U('Fruit/directional')."';</script>";
				exit(); 
			}else{
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
				echo '<script> alert("失败！！"); </script>';
				echo "<script> window.location.href='".U('Fruit/directional')."';</script>";
				exit(); 
			}
		}	
	}
	
	public function direction_one(){
		if(IS_AJAX){
			$id=I('post.id');
			if(M('seed_orientation')->where('id='.$id.'')->delete()){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo -1;
		}
	}
	
	public function money_fruit(){
		/**时间**/
		$y = date("Y");
		//获取当天的月份
		$m = date("m");
		//获取当天的号数
		$d = date("d");
		//print_r($m);die;
		//$tm =date("d")+1;111
		$s_data=M('Global_conf')->where('cases="start_time"')->find();
		$e_data=M('Global_conf')->where('cases="end_time"')->find();
		$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
		$start_t=$start-3600*24;
		$time=time();
		//print_r($start);die;
		$end = mktime($e_data['value'],0,0,$m,$d,$y);
		$seed=I('get.seed');
		$case=''.date('Y-m').'_matching';
		$data_m=M($case)->where('time>'.$start.' AND time<'.$end.' AND seed="'.$seed.'"')->order('money ASC')->select();
		$money_num=M($case)->where('time>'.$start.' AND time<'.$end.' AND seed="'.$seed.'"')->sum('num');
		$count=count($data_m);
		$data=array();
		$f=0;
		for($i=0;$i<$count;$i++){
			if($i==0){
				$data[$f]['seed']=$seed;
			   $data[$f]['money']=$data_m[$i]['money'];
			   $data[$f]['num']=$data_m[$i]['num'];
			   $f++;
		   }else{
			   if($data_m[$i]['money']==$data[$f-1]['money']){
				   $data[$f-1]['num']+=$data_m[$i]['num'];
			   }else{
				   $data[$f]['seed']=$seed;
				   $data[$f]['money']=$data_m[$i]['money'];
				   $data[$f]['num']=$data_m[$i]['num'];
				   $f++;
			   }
		   }
		}
		//print_r($data_m);die;
		if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
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
		$this->assign('num',$money_num);
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('o',$o);
		$this->assign('seed',$seed);
        $res =array_slice($data,($o-1)*8,8);
        $this->assign('data',$res);//分页内容
		$this->assign('state',$state);
        $this->display();
	}
    //AJAX果实删除
    public function del(){
        if(IS_AJAX){
            //$id = I('post.id',0,'int');
            $seed=M('Seeds');
            $where['id'] = ':id';
            $bind[':id'] = array(I('post.id'),\PDO::PARAM_INT);
            if($seed->where($where)->bind($bind)->filter('strip_tags')->delete()){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo -1;
        }
    }
	
	public function sell_num(){
		if(IS_POST){
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;
			$s_data=M('Global_conf')->where('cases="start_time"')->find();
			$e_data=M('Global_conf')->where('cases="end_time"')->find();
			$poundage=M('Global_conf')->where('cases="poundage"')->find();
			$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
			$end = mktime($e_data['value'],0,0,$m,$d,$y);
			$time=time();
			if($time>$end||$time<$start){
				echo '请在开盘时间内操作';
			}else{
				$seed=I('post.seed');
				$cases=''.date('Y-m').'_matching';
				$data_today=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state!=2 AND money!=0')->order('time')->select();
				$money=M('Pay_statistical')->field('end_money')->where('seed="'.$seed.'"')->order('time DESC')->select();
				if(empty($data_today)){
					if(empty($money)){				  
					   $money=$data_seed_r['first_price'];
					}else{
					   $money=$money[0]['end_money'];
					}
				}else{
					$money=$data_today[count($data_today)-1]['money'];
				}
				////$seed_data=M('seeds')->where('id='.$id)->find();
				$num=I('post.num');
				$add['seed']=$seed;
				$add['state']=0;
				$add['num']=$num;
				$add['sell_user']=18382077208;
				$add['buy_user']=18228068397;
				$add['total']=$num*$money;
				$add['money']=$money;
				$add['poundage']=$num*$money*$poundage['value'];
				$add['time']=$time;
				$case_m=''.date('Y-m').'_matching';
				if(M($case_m)->add($add)){
					echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
					echo '<script> alert("成功！"); </script>';
					echo "<script> window.location.href='".U('Fruit/money_fruit',array('seed'=>$seed))."';</script>";
					exit();
				}else{
					echo "<meta http-equiv='Content-Type' content='textml; charset=utf-8'>";
					echo '<script> alert("失败！"); </script>';
					echo "<script> window.location.href='".U('Fruit/money_fruit',array('seed'=>$seed))."';</script>";
					exit();
				}
			}
		}
	}
	
	
    //果实详细数据
    public function find_fruit(){
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        $time=time();
        //print_r($start);die;
        $end = mktime($e_data['value'],0,0,$m,$d,$y);

        //$id=1;
		
        $id= intval(I('get.id',1,'addslashes'));
        $this->assign('id',$id);
        //print_r($id);die;
        //print_r($id);die;
        $where['id'] = ':id';
        $bind[':id'] = array(I('get.id'),\PDO::PARAM_INT);
        $data_fruit=M('Seeds')->where($where)->bind($bind)->filter('strip_tags')->find();
        $seed=$data_fruit['varieties'];
		
        $st=M('matching_statistical')->order('id desc')->select();

        
		//echo $case_f;die;
        if(IS_POST){
			$type=I('post.type');     //买入1卖出0
            $start_times=I('post.start_time');
            $end_times=I('post.end_time');
			if($start_times==''){
				$s_y=date('Y');
				$s_m=date('m');
				$s_d=date('d');
				$start_times = mktime(0,0,0,$s_m,$s_d,$s_y);
			}else{
				$s_y=substr($start_times,0,4);
				$s_m=substr($start_times,5,2);
				$s_d=substr($start_times,8,2);
				$s_h=substr($start_times,11,2);
				$s_i=substr($start_times,14,2);
				$s_s=substr($start_times,17,2);
				$start_times = mktime($s_h,$s_i,$s_s,$s_m,$s_d,$s_y);
			}
			if($end_times==''){
				$e_y=date('Y');
				$e_m=date('m');
				$e_d=date('d');
				$e_h=date('h');
				$e_i=date('i');
				$e_s=date('s');
				$end_times = time();
			}else{
				$e_y=substr($end_times,0,4);
				$e_m=substr($end_times,5,2);
				$e_d=substr($end_times,8,2);
				$e_h=substr($end_times,11,2);
				$e_i=substr($end_times,14,2);
				$e_s=substr($end_times,17,2);
				$end_times = mktime($e_h,$e_i,$e_s,$e_m,$e_d,$e_y);
			}
			$money=I('post.money');
			$find= new Find();
            $data=$find->find_fruit($start_times,$end_times,$seed,$type,$money);
		}else{
			if(IS_GET){
				$start_times=I('get.start_time');
				$end_times=I('get.end_time');
				
				$money=I('get.money');
				$type=I('get.type');
				$find= new Find();
				$data=$find->find_fruit($start_times,$end_times,$seed,$type,$money);				
			}
		}
		//print_r($data);die;
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
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
        $this->assign('data',$res);//分页内容
        //总交易数据
		$endtime=I('get.endtime');
		$starttime=I('get.starttime');
        if($endtime==0&&$starttime==0){
            $data_time=M('matching_statistical')->select();
            foreach ($data_time as $k=>$v){
                //$where['seed'] = ':seed';
                //$bind[':seed'] = array($seed,\PDO::PARAM_STR);
                $cases=''.$v['name'].'_matching';
                $data_record['money']+=M(''.$cases.'')->where('seed ="'.$seed.'"')->filter('strip_tags')->sum('total');
                $data_record['num']+=M(''.$cases.'')->where('seed ="'.$seed.'"')->filter('strip_tags')->sum('num');
                $data_record['poundage']+=M(''.$cases.'')->where('seed ="'.$seed.'"')->filter('strip_tags')->sum('poundage');
            }
        }else{
            $total=new Tool();
            $data_record=$total->total($starttime,$endtime,$data_fruit['varieties']);
        }
       
        //数据状态
        $this->assign('state',$state);
        $this->assign('seed',$seed);
		$this->assign('type',$type);
        $this->assign('start_time',$start_times);
        $this->assign('end_time',$end_times);
		$this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('money',$money);
        //交易总数据
        $this->assign('data_record',$data_record);
        //日K线
        creatToken();
        $this->assign('today',$today);
        $this->display();
    }
    public function ajax_k(){
        $seed=I('get.procode');
        $data = $this->k($seed);
        echo json_encode($data);
    }

	//卖种子
	//卖种子
	public function sellzz(){
		if(IS_POST){
			if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Fruit/sellzz');
                return;
            }
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;
			$s_data=M('Global_conf')->where('cases="start_time"')->find();
			$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
			$case_matching=''.date('Y-m').'_matching';
			$user=I('post.user');
			$yes=I('post.yes');
			if(empty($yes)||$yes=0){
				//print_r($user);die;
				$str=M('statistical');
				$data_num=$str->select();
				$data_p=M('Global_conf')->where('cases="poundage"')->find();
				$data_z=M('Global_conf')->where('cases="zhongzi"')->find();
				$data_seed=M('Seeds')->field('open_price,first_price,varieties')->where('varieties="种子"')->find();
				$money=M('Pay_statistical')->where('seed ="'.$data_seed['varieties'].'"')->field('end_money')->order('time DESC')->select();
				if(empty($money)){		  
					$yes=$data_seed['first_price'];
				}else{
					$yes=$money[0]['end_money'];
				}
				$yes=$yes*1.01;
				$yes=round($yes,5);
			}	
			
			//$data=array();
			//$p=0;
			$prop_num=0;
			foreach($data_num as $key=>$val){
				$case=''.$val['name'].'_prop_warehouse';
				$case_member=''.$val['name'].'_members';
				$case_g=''.$val['name'].'_users_gold';
				$prop=M($case)->field('user,num')->where('props="种子" AND num>300000')->select();
				$count=M($case)->field('user,num')->where('props="种子" AND num>300000')->count();
				//print_r($prop);
				for($i=0;$i<$count;$i++){
					//$data[$p]=$prop[$i];
					//$p++;
					$num=300000*$data_z['value'];
					$money=$num*$yes;
					//print_r($num);die;
					//echo $money;echo '<br/>';
					$model=new Model();
					$model->startTrans();
					if(M($case_member)->where('user="'.$prop[$i]['user'].'"')->setInc('coin',$money)){
						if(M($case)->where('props="种子" AND user="'.$prop[$i]['user'].'"')->setDec('num',$num)){
							//echo '3';
							$mat['buy_user']=$user;
							$mat['sell_user']=$prop[$i]['user'];
							$mat['num']=$num;
							$mat['money']=$yes;
							$mat['time']=$start+2;
							$mat['seed']='种子';
							$mat['state']=2;
							$mat['poundage']=$num*$yes*$data_p['value'];
							$mat['total']=$num*$yes;
							$prop_num=$prop_num+$num;
							if(M($case_matching)->add($mat)){
								$sa['user']=$prop[$i]['user'];
								$sa['num']=$num;
								$sa['money']=$yes;
								$sa['time']=$start+2;
								$sa['total']=$num*$yes;
								M('repo_record')->add($sa);
								$data_gold=M(''.$case_g.'')->where('user='.$prop[$i]['user'])->find();
								if(!empty($data_gold)){
									//echo $total;echo
									if(M(''.$case_g.'')->where('user='.$prop[$i]['user'])->setInc('buy_and_sell',$mat['total'])){
										$model->commit();
									}else{
										$model->rollback();
									}
								}else{
									$data_member=M($case_member)->field('num_id')->where('user='.$prop[$i]['user'])->find();
									$save_gold['user']=$prop[$i]['user'];
									$save_gold['num_id']=$data_member['num_id'];
									$save_gold['buy_and_sell']=$mat['total'];
									$save_gold['user_coin']=0;
									$save_gold['user_fees']=0;
									$save_gold['user_top_up']=0;
									//$res['8']=M(''.$case_g.'')->add($save_gold);
									if(M(''.$case_g.'')->add($save_gold)){
										$model->commit();
									}else{
										$model->rollback();
									}
								}
							}else{
								$model->rollback();
							}
						}else{
							//echo '1';
							$model->rollback();
						}
					}else{
						//echo '2';
						$model->rollback();
					}
				}
			}
			echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
			echo '<script> alert("成功！'.$prop_num.'个！"); </script>';
			echo "<script> window.location.href='".U('Fruit/sellzz')."';</script>";
			exit(); 
		}else{
			creatToken();
			$this->display();
		}
	}
	
	
	
     private function k($seed){

        $today_str = date("Y-m-d");

        //print_r($start);die;
        $end = strtotime($today_str) + 3600 * 24;

        $case_m='pay_statistical';

        $data_today=M($case_m)->where(' time <= " '.$end.'" AND seed="'.$seed.'"')->limit()->order('time asc')->select();

        return $data_today;
    }

}
