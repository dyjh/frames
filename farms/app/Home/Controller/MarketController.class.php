<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Tool;
use Org\Our\Matching;
use Org\Our\Sweeping;
header("content-type:text/html;charset=utf8");
class MarketController extends Controller{

    public function index(){
         $data= M('Seeds')->where('varieties!="摇钱树" AND state=0')->order('ord')->select();
         $this->assign('data',$data);
         $this->display();
     }
	 
    /*public function price_now($seed){
        
		$mem=new \Memcached;

		$mem->addServer('localhost', 11211);
		
		$mem->add('add',13,300);
		
		echo $mem->get('add');
		
		
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
        $cases=''.date('Y-m').'_matching';
        $data_today=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
        $data_y=M(''.$cases.'')->where('time >= "'.$start_t.'"  AND time <= " '.$end_t.'" AND seed="'.$seed.'"')->order('time DESC')->select();
		
        if(empty($data_today)&&empty($data_y)){
            $data=M('Seeds')->where('varieties="'.$seed.'"')->find();
            $new=$data['first_price'];	
        }else{
            $new=$data_today[count($data_today)-1]['money'];
        }
        echo $new;
    }*/
	
	
    public function market_dynamic(){
		 
       if(IS_AJAX){
            $y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");
            //print_r($m);die;
            //$tm =date("d")+1;
            $s_data=M('Global_conf')->where('cases="start_time"')->find();
            $e_data=M('Global_conf')->where('cases="end_time"')->find();
            $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
            $start_t=$start-3600*24;
            $time=time();
            //print_r($start);die;
            $end = mktime($e_data['value'],0,0,$m,$d,$y);
            $end_t=$end-3600*24;
            $data_seed=M('Seeds')->field('open_price,first_price,varieties')->where('varieties!="摇钱树" AND state=0')->order('ord')->select();
            //Print_r($start_t);die;
            foreach ($data_seed as $k=>$v){
				// 2017年10月6日11:05:08
				// QHP 增加 交易市场查询时， 价格必须存在的数据，
				// 过滤 增加的交易量数据
                $case_m=''.date('Y-m').'_matching';
                $first=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'" AND state!=2 and money>0')->find();
                $today['start']=$first['money'];
				$end_money=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'" AND state!=2 and money>0')->order('id desc')->find();
                //$data[$k]['num']=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->count();    //成交数量
                //$data_money=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'" AND state!=2 and money>0')->order('money')->select();
                //$today['start']=$data_today[0]['money'];
                //$data[$k]['new']=$data_today[count($data_today)-1]['money'];
                $today['new']=$end_money['money'];
                $money=M('Pay_statistical')->where('seed ="'.$v['varieties'].'"')->field('end_money')->order('time DESC')->find();
                if(empty($first)){
                    //$today['start']=0;
							//$data_seed_r=M('Seeds')->field('open_price,first_price')->where('varieties="'.$v['varieties'].'"')->find();
					$today['start']=0; 
					//$money=M('Pay_statistical')->where('seed="'.$v['varieties'].'"')->order('time DESC')->select();
					if(empty($money)){
					   $today['new']=$v['first_price'];
					}else{
					   $today['new']=$money['end_money'];
					}
					
                }
				  if(empty($money)){
					  $data_seed_r=M('Seeds')->where('varieties="'.$v['varieties'].'"')->find();
					  $yes=$data_seed_r['first_price'];
				  }else{
					  $yes=$money['end_money'];
				  } 
				if(empty($first)){
					$data[$k]['gaines']=$today['new']-$yes; 
					$data[$k]['gains']=(($today['new']-$yes)/$yes)*100;
					
				}else{
					$data[$k]['gaines']=$today['new']-$first['money']; 
					$data[$k]['gains']=(($today['new']-$first['money'])/$first['money'])*100;
                   
				}
				 /*if($v['varieties']=='种子'){
					if($data[$k]['gains']>1){
						$data[$k]['gains']=1;
					}
					if($data[$k]['gains']<-1){
						$data[$k]['gains']=-1;
					}
				}elseif($v['varieties']=='番茄'||$v['varieties']=='葡萄'||$v['varieties']=='菠萝'){
					
				}else{
					if($data[$k]['gains']>10){
						$data[$k]['gains']=10;
					}
					if($data[$k]['gains']<-10){
						$data[$k]['gains']=-10;
					}
				}*/
                $data[$k]['gains']=round($data[$k]['gains'],2);                        //涨跌
				//number_format($today['ga'], 5, '.', '');
				//$data[$k]['seed']=$v['varieties'];
                $data[$k]['new']=$today['new'];                                 //最新成交价
                //$data[$k]['gains']=(($today['new']-$yes)/$yes)*100;              ////涨跌幅
				//print_r($data);die;
            }					
            echo json_encode($data);          
        }
    }


    public function pay(){
		
		
		
		/**时间**/
		//die;
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        $data_g=M('Global_conf')->where('cases="start_time" or cases="end_time" or cases="float" or cases="poundage" or cases="start_end"')->select();
        foreach ($data_g as $k=>$v){
            switch ($v['cases']) {
                case 'start_time':
                    $s_data=$data_g[$k]['value'];
                    break;
                case 'poundage':
                    $poundage=$data_g[$k]['value'];
                    break;
                case 'end_time':
                    $e_data=$data_g[$k]['value'];
                    break;
                case 'float':
                    $zhi=$data_g[$k]['value'];
                    break;
				case 'start_end':
                    $start_end=$data_g[$k]['value'];
                    break;
            }
        }
        //$e_data=M('Global_conf')->where('cases="end_time"')->find();
        $start= mktime($s_data,0,0,$m,$d,$y);//即是当天开盘的时间戳
        //$start_t=$start-3600*24;
        $time=time();
        $end = mktime($e_data,0,0,$m,$d,$y);
		//$end=mktime($e_data,0,0,$m,$d,$y)+24*3600;
		$end_end =$end+3600;
		$start_pay=$start+900;
        //$end_t=$end-3600*24;
    if(IS_AJAX){
		// $arr['content'] = '市场正在更新中，请稍等。';
			// echo json_encode($arr);
			// exit;	
		if($time>=$end||$time<=$start){
			if($_SESSION['user']=='18228068397'){
				
			}else{
				$arr['state'] = -2;
				$arr['content'] = '收盘后不能挂单';  
				echo json_encode($arr);
				exit;
			}
		}
		$check_pay=M('user_freeze')->where('user='.$_SESSION['user'].'')->find();
		if($check_pay['pay']==1){
			$arr['state'] = 2;
				$arr['content'] = '系统繁忙，稍后再试';
				echo json_encode($arr);
				exit;
		}
		if($start_end==0){
			if($_SESSION['user']=='18228068397'||$_SESSION['user']=='18382050570'||$_SESSION['user']=='15802858094'||$_SESSION['user']=='18584084806'||$_SESSION['user']=='18780164595'){
				
			}else{
				$arr['state'] = 2;
				$arr['content'] = '交易暂时关闭';
				echo json_encode($arr);
				exit;
			}
		}
		/*$arr['state'] = -2;
		$arr['content'] = '暂时无法交易';  
		echo json_encode($arr);
		exit;*/
        $cases=''.date('Y-m').'_matching';
		$table_fix = substr(session('user'),0,3);
		$table = $table_fix."_members";	
		$level = M("$table")->field('level')->where(array('user'=>session('user')))->find();				
        $seed=_safe(I('post.seed'));
		if($seed=='种子'){
			$zhi=0.01;
		}
        ///////////////////////
        $money=M('Pay_statistical')->field('end_money,time')->where('seed="'.$seed.'"')->order('time DESC')->select();
        $data_seed_r=M('Seeds')->field('first_price,open_price,state')->where('varieties="'.$seed.'"')->find();
		if($data_seed_r['state']==1){
			$arr['state'] = -2;
			$arr['content'] = '该果实暂时无法交易';  
			echo json_encode($arr);
			exit;
		}
		/*if($time<$start||$time>$end){
			  if(empty($money)){
				  $end_money=$data_seed_r['first_price'];
			  }else{
				  $end_money=$money[0]['end_money'];
			  }
          }else{
              //$cases=''.date('Y-m').'_matching';
              $data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
			  if(empty($data_max)){
				  //$money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
				  if(empty($money)){
						  //$data_seed_r=M('Seeds')->field('first_price')->where('varieties="'.$seed.'"')->find();
						  $end_money=$data_seed_r['first_price'];
					  }else{
						  $end_money=$money[0]['end_money'];
					  }
			  }else{
				  $end_money=$data_max[0]['money'];
			  }
          }*/
		  $cases=''.date('Y-m').'_matching';
              $data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
			  if(empty($data_max)){
				  //$money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
				  if(empty($money)){
						  //$data_seed_r=M('Seeds')->field('first_price')->where('varieties="'.$seed.'"')->find();
						  $end_money=$data_seed_r['first_price'];
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

        $model=new Model();
        $model->startTrans();
		$submit_num=I('post.num',0,'addslashes')*1;
        $data['num']=intval($submit_num);
		$num=$data['num'];
		///echo $num  ;die;
        $data['type']=intval(I('post.type','','addslashes'))*1; //买卖
        $data['user']=$_SESSION['user'];
        $data['submit_num']=$data['num'];  //数量

		$sweeping=intval(I('post.sweeping',2,'addslashes'));
        $data['seed']=$seed;   //种子
        $data['money']=I('post.money',0,'addslashes')*1;  //金额

		$fruit_name = array('土豆','草莓','樱桃','稻米','葡萄','番茄','种子','菠萝','蓝莓','榴莲');
		$num_check=$data['num']/100;
		$data['time']=time();
		$max_entrust=round($max_entrust,5);
		  $min_entrust=round($min_entrust,5);
		if($sweeping>=2||$sweeping<0||$data['num']<=0||!is_int($num_check)||$data['submit_num']<=0||$data['money']<=0||!in_array($seed,$fruit_name)){
			$arr['state'] = -2;
			$arr['content'] = '错误';  
			echo json_encode($arr);
			exit;
		}
		/*if($seed=='番茄'||$seed=='葡萄'){
			if($money>100||$money<0.001){
				$arr['state'] = -2;
				$arr['content'] = '请输入涨停和跌停之间的价格';  
				echo json_encode($arr);
				exit;
			}
		}*/
		if($seed=='蓝莓'||$seed=='榴莲'){
			$arr['state'] = -2;
			$arr['content'] = '该果实暂时不开放交易';  
			echo json_encode($arr);
			exit;
		}
		/**判断 限价（1）委托（0）**/
        if($data['time']>$start&&$data['time']<$end){
			if($sweeping==1){
				$data['trans_type']=2;  //扫荡 2
			}else{
				$matching = date('Y-m').'_matching';
				$matching_message = M($matching);
				$today_matching = $matching_message->where('time>="'.$start.'" and time<="'.$end.'" AND seed="'.$seed.'"')->find();
				if($today_matching){
                    $data_now=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time DESC')->select();
                    if(empty($data_now)){
						//$money=M('Pay_statistical')->where('seed="'.$v['varieties'].'"')->order('time DESC')->select();
						if(empty($money)){
							$now=$data_seed_r['first_price'];
						}else{
							$now=$money[0]['end_money'];
						}
                    }else{
                        $now=$data_now[0]['money'];
                    }

					if($data['money']==$now){
						$data['trans_type']=1;  //委托 0
						
					}else{
						$data['trans_type']=0;  //委托 0
					}
				}else{
					$data['trans_type']=0;  //委托 0
				}
			}
        }else{
            $data['trans_type']=0; 
			//委托 0
        }
		//$data['trans_type']=1; 
        $case=''.date('Y-m').'_pay';
        $tel=$_SESSION['user'];
        $num_tel=substr($tel,0,3);
		//var_dump($data);
		if($data['type']==0){
			if($level['level']<5){
				$model->rollback();
				$arr['state'] = -2;
				$arr['content'] = '达到5级才能交易，你当前为'.$level['level'].'级，努力加油升级哟';
				echo json_encode($arr);
				exit;
			}
		}
        if(M(''.$case.'')->add($data)){
			$id=M(''.$case.'')->GetlastinsID();
			/*$model->commit();
						  $check=M($case)->where('id='.$id)->find();
						  var_dump($check);die;*/
            if($data['type']==1){
                //$case_m=$table->table($tel,$case);
				$case_m=''.$num_tel.'_members';
                $data_m['coin_freeze']=$data['money']*$data['num'];
                //$data_m['coin_freeze']=$data['money'];
				$data_coin=M(''.$case_m.'')->where('user ='.$tel)->find();
				if($data_m['coin_freeze']>$data_coin['coin']){
					$model->rollback();
					$arr['state'] = -2;
					$arr['content'] = '金币不足';  
					//$arr['money'] = $data_coin[0]['user'];  
					echo json_encode($arr);
					exit;
				}
				//echo $data_m['coin_freeze'];die;
				$data_m['coin_freeze']=(float)$data_m['coin_freeze'];
				$coin=M(''.$case_m.'')->where('user ='.$tel)->find();
				$save['coin_freeze']=$coin['coin_freeze']+$data_m['coin_freeze'];
				$save['coin']=$coin['coin']-$data_m['coin_freeze'];
				$coin_record['coin']=$save['coin'];
				$coin_record['coin_freeze']=$save['coin_freeze'];
				$coin_record['add']=0-$data_m['coin_freeze'];
				$coin_record['time']=time();
				$coin_record['user']=$tel;
				$coin_record['coin_freeze_old']=$data_coin['coin_freeze'];
				$coin_record['coin_old']=$data_coin['coin'];
				if(M('coin_record')->add($coin_record)){
					M($case_m)->where('user ='.$tel)->setInc('coin_freeze',$data_m['coin_freeze']);
					M($case_m)->where('user ='.$tel)->setDec('coin',$data_m['coin_freeze']);
					//if(M(''.$case_m.'')->where('user ='.$tel)->save($save)/*setInc('coin_freeze',$data_m['coin_freeze'])*/){
							 $model->commit();
							$mou=date('Y-m');
							$path='../log/order/'.$mou.'';
							if (!file_exists($path)){
								mkdir($path,0777,true);
							}
							$day=date('Y-m-d');
							$str='用户;'.$data['user'].';买入果实:'.$data['seed'].'买入数量：'.$data['num'].'买入单价:'.$data['money'].'';
							file_put_contents('../log/order/'.$mou.'/'.$day.'buyorder.log',$str.PHP_EOL."\n",FILE_APPEND);
							//die;
							 if($data['trans_type']==1){
								 //查询今天是否已经有交易，没有则不能进行实时买入
								 //$id= $addid;  //存入的id
								 //echo $id;die;

								 $buy=M($case)->where('id='.$id)->select();
								 if($data['type']==0){
									 $t=1;
								 }else{
									 $t=0;
								 }
								 $k=0;
								
								//echo $sell[0]['num'];die;
								$matching=new Matching();
								$state=0;
								$data=M('Global_conf')->where('cases="poundage"')->find();
								$poundage=$data['value'];
								$number=0;
								$data=$matching->time_buy($number,$poundage,$buy,$k,$t,$state);
								//die;
								//判断返回值
								$s=$data['zhi'];
								 switch ($s) {
									 case '4':
										 $arr['state'] = 1;
										 $arr['content'] = '交易排队中';
										 echo json_encode($arr);
										 exit;
										 break;
									 case '3':
										 $arr['state'] = 1;
										 $arr['content'] = '已购买'.$seed.'x'.$num;
										 echo json_encode($arr);
										 exit;
										 break;
									 case '2':
										 //不在开盘时间
										 $arr['state'] = 2;
										 $arr['content'] = '当日还未开盘';
										 echo json_encode($arr);
										 exit;
										 break;
									 case '1':
										 $save['trans_type']=0;
										 M($case)->where('id='.$id)->save($save);
										 $arr['state'] = 1;
										 if($data['number']==0){
											$arr['content'] = "当前无单匹配，转为委托";
										}else{
											$arr['content'] = "已交易".$data['number']."，剩余转为委托";
										}
										 echo json_encode($arr);
										 exit;
										 break;
								 }
							 }else{
								 $arr['state'] = 1;
								 $arr['content'] = '系统已委托';  
								 echo json_encode($arr);
								 exit;  
							 }
					  
				}else{
					$model->rollback();
					$arr['state'] = 5;
					$arr['content'] = '系统故障，交易暂停';  
					echo json_encode($arr);
					exit;
				}
            }else{
                
					//卖出
                //$table=new Tool();
                $data_g=M('Global_conf')->where('cases="poundage"')->find();
                $case_m=''.$num_tel.'_members';
                $data_m['coin_freeze']=$data['money']*$data['num']*$data_g['value'];
                $data_coin=M(''.$case_m.'')->where('user ='.$tel)->find();
                if($data_m['coin_freeze']>$data_coin['coin']){
                    $model->rollback();
                    $arr['state'] = 1;
                    $arr['content'] = '金币不足（手续费：'.$data_m['coin_freeze'].'）';
                    //$arr['money'] = $data_coin[0]['user'];
                    echo json_encode($arr);
                    exit;
                }

                //echo $data_m['coin_freeze'];die;
                //$data_m['coin_freeze']=(float)$data_m['coin_freeze'];
                $coin=M(''.$case_m.'')->where('user ='.$tel)->find();
                $save['coin_freeze']=$coin['coin_freeze']+$data_m['coin_freeze'];
                $save['coin']=$coin['coin']-$data_m['coin_freeze'];
				$coin_record['coin']=$save['coin'];
				$coin_record['coin_freeze']=$save['coin_freeze'];
				$coin_record['add']=0-$data_m['coin_freeze'];
				$coin_record['time']=time();
				$coin_record['user']=$tel;
				$coin_record['coin_freeze_old']=$data_coin['coin_freeze'];
				$coin_record['coin_old']=$data_coin['coin'];
				M('coin_record')->add($coin_record);             //添加金币冻结记录
                //M(''.$case_m.'')->where('user ='.$tel)->save($save);        //冻结手续费
				M($case_m)->where('user ='.$tel)->setInc('coin_freeze',$data_m['coin_freeze']);
				M($case_m)->where('user ='.$tel)->setDec('coin',$data_m['coin_freeze']);
                $case_f=''.$num_tel.'_fruit_record';
                if($data['seed']=='种子'){
					$case_s=''.$num_tel.'_prop_warehouse';
					$sql='user ='.$tel.' AND props ="'.$data['seed'].'"';
				}else{
					$case_s=''.$num_tel.'_seed_warehouse';
					$sql='user ='.$tel.' AND seeds ="'.$data['seed'].'"';
				}
                //$case_s=$table->table($tel,$case);
                $data_num=M(''.$case_s.'')->where($sql)->find();
				//echo $data_num['num'];die;	
                if($data_num['num']<$num){
					$model->rollback();
                    $arr['state'] = 6;
					$arr['content'] = '果实数量不足'; 
					echo json_encode($arr);
					exit;     
                }else{
                    if(M(''.$case_s.'')->where($sql)->setDec('num',$num)){
                        $data_s['seed']=$data['seed'];
                        $data_s['num']=$data['num'];
                        $data_s['time']=time();
                        $data_s['user']=$_SESSION['user'];
                        $data_s['money']=$data['money'];
						//$data['money']=0.001;
						$data_record_s=M($case_f)->where('user="'.$tel.'" AND money='.$data_s['money'].' AND seed="'.$data_s['seed'].'"')->find();
						if(empty($data_record_s)){
							if(M(''.$case_f.'')->add($data_s)){
								//echo 11;die;
                                $model->commit();
							}else{
								$model->rollback();
								$arr['state'] = 5;
								$arr['content'] = '系统故障，交易暂停1';  
								echo json_encode($arr);
								exit;
							}
						}else{
							if(M(''.$case_f.'')->where('user="'.$tel.'" AND money='.$data['money'].' AND seed="'.$data['seed'].'"')->setInc('num',$data_s['num'])){
                            $model->commit();
							}else{
								$model->rollback();
								$arr['state'] = 5;
								$arr['content'] = '系统故障，交易暂停2';  
								echo json_encode($arr);
								exit;           
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
						//die;
						if($data['trans_type']==1){
							//echo $num;die;
							$matching=new Matching();
							$state=0;
							$sell=M($case)->where('id='.$id)->select();
							//$sell=M(''.$case.'')->where('state < 2 AND type =0 AND trans_type =1')->order('time')->select();
							if($data['type']==0){
								$t=1;
							}else{
								$t=0;
							}
							$i=0;
							$data=M('Global_conf')->where('cases="poundage"')->find();
							$poundage=$data['value'];
							$number=0;
							$data=$matching->time_sell($number,$poundage,$sell,$i,$t,$state);
							///die;
							$s=$data['zhi'];
                            switch ($s) {
                                case '4':
                                    $arr['state'] = 1;
                                    $arr['content'] = '交易排队中';
                                    echo json_encode($arr);
                                    exit;
                                    break;
                                case '3':
                                    $arr['state'] = 1;
                                    $arr['content'] = '已卖出'.$seed."x".$num;
                                    echo json_encode($arr);
                                    exit;
                                    break;
                                case '2':
                                    //不在开盘时间
                                    $arr['state'] = 2;
                                    $arr['content'] = '当日还未开盘';
                                    echo json_encode($arr);
                                    exit;
                                    break;
                                case '1':
                                    $save['trans_type']=0;
                                    M($case)->where('id='.$id)->save($save);
                                    $arr['state'] = 1;
									if($data['number']==0){
										$arr['content'] = "当前无单匹配，转为委托";
									}else{
										$arr['content'] = "已交易".$data['number']."，剩余转为委托";
									}
                                    echo json_encode($arr);
                                    exit;
                                    break;
                            }
						}else{
							 $arr['state'] = 1;
							 $arr['content'] = '系统已委托';  
							 echo json_encode($arr);
							 exit;
						}

                    }else{
                        $model->rollback();
						$arr['state'] = 5;
						$arr['content'] = '系统故障，交易暂停3';
						echo json_encode($arr);
						exit;           
                    }
                }
				
            }
        }else{
			$model->rollback();
			$arr['state'] = 5;
			$arr['content'] = '系统故障，交易暂停8';
			echo json_encode($arr);
			exit;           
			
        }

    }else{
          $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);
          $this->assign("OpenCloseTime",array('Open'=>date("H",$GetOpenCloseTime['start']),'Close'=>date("H",$GetOpenCloseTime['end'])));

          //判断是否允许交易
          //$table=new Tool();
          //$case='members';
          $tel=$_SESSION['user'];
		  $num_tel=substr($tel,0,3);
          $case_m=''.$num_tel.'_members';
          //$data=M(''.$case_m.'')->where('user='.$tel)->find();
          /*if($data['gift_state']==0){
              $pay_state=0;
          }elseif ($data['gift_state']==1&&$data['level']>=5){
              $pay_state=0;
          }elseif ($data['gift_state']==1&&$data['level']<5){
              $pay_state=1;
          }*/

          $cases=''.date('Y-m').'_matching';
          //接受传过来的水果id
          $id=intval(I('get.id',0,'addslashes'));//增加过滤判断
		  
		  if(M('Seeds')->where('id="'.$id.'"')->filter('strip_tags')->find()){
			  $seed_data = M('Seeds')->where('varieties!="摇钱树" AND state=0')->order('ord')->select();

          for($i=0;$i<count($seed_data);$i++){
              if($seed_data[$i]['id']==$id){
                  $seed = $seed_data[$i]['varieties'];
                  $state = $seed_data[$i]['state'];
              }
          }
		  $data_record=M(''.$cases.'')->where('seed="'.$seed.'"')->select();
          $float_conf=M('Global_conf')->where('cases="float"')->find();
          //$data_fruit=M('Seeds')->where('id ='.$id)->find();
          //$seed=$data_fruit['varieties'];
          //判断果实是否可交易
          //$state=$data_fruit['state'];
          //果子库存
          //$table=new Tool();
          if($seed=='种子'){
			  $case_fruit_w=''.$num_tel.'_prop_warehouse';
			  $sql='user ='.$tel.' AND props ="'.$seed.'"';
			  $zhi=0.01;
		  }else{
			  $case_fruit_w=''.$num_tel.'_seed_warehouse';
			  $sql='user ='.$tel.' AND seeds ="'.$seed.'"';
			  $zhi=$float_conf['value'];
		  }
          
          //$tel=$_SESSION['user'];
          //$case_fruit_w=$table->table($tel,$case);
          $data_ware_fruit=M(''.$case_fruit_w.'')->where($sql)->find();
          if(!$data_ware_fruit){
              $fruit_ware=0;
          }else{
              $fruit_ware=$data_ware_fruit['num'];
          }
          //历史行情数据
          
          //$zhi=$float_conf['value'];
          $money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
          if($time<$start || $time>$end){
			  //echo 3;die;
			  if(empty($money)){
				  $data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
				  $end_money=$data_seed_r['first_price'];
			  }else{
				  $end_money=$money[0]['end_money'];
			  }
              //开盘前委托最高价格
              $max_entrust=$end_money+$end_money*$zhi;
              //最低价
              $min_entrust=$end_money-$end_money*$zhi;
          }else{
              //$cases=''.date('Y-m').'_matching';
              $data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
			  if(empty($data_max)){
				  $money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
				  $end_money=$money[0]['end_money'];
				  if(empty($money)){
					  $data=M('Seeds')->where('varieties="'.$seed.'"')->find();
				      $end_money=$data['first_price'];
				  }
			  }else{
				  $end_money=$data_max[0]['money'];
			  }
              //开盘前委托最高价格
              $max_entrust=$end_money+$end_money*$zhi;
              //最低价
              $min_entrust=$end_money-$end_money*$zhi;
          }
		$cases=''.date('Y-m').'_matching';
		$data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
		if(empty($data_max)){
			$money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
			$end_money=$money[0]['end_money'];
			if(empty($money)){
				$data=M('Seeds')->where('varieties="'.$seed.'"')->find();
				$end_money=$data['first_price'];
			}
		}else{
			$end_money=$data_max[0]['money'];
		}
		if($seed=='番茄'||$seed=='葡萄'||$seed=='菠萝'){
			$max_entrust=100.00000;
			$min_entrust=0.001;
		}else{
			//开盘前委托最高价格
			$max_entrust=$end_money+$end_money*$zhi;
			//最低价
			$min_entrust=$end_money-$end_money*$zhi;
		}
			$max_entrust=100.00000;
			$min_entrust=0.001;
		
          //果实冻结信息
          $case_fruit_r=''.$num_tel.'_fruit_record';
          $tel=$_SESSION['user'];
          //$case_fruit_r=$table->table($tel,$case);
          $data_record_fruit=M(''.$case_fruit_r.'')->where('user ='.$tel.' AND seeds ="'.$seed.'"')->sum('num');
          $this->assign('data_record_fruit',$data_record_fruit);
          //当天蔬菜信息
          $cases=''.date('Y-m').'_matching';
          $data_today=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state!=2 AND money!=0')->order('time')->select();
          //var_dump($data_today);die;
          $today['start']=$data_today[0]['money'];
          $today['count']=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state=0')->sum('num');
          $today['new']=$data_today[count($data_today)-1]['money'];
          $data_seed_r=M('Seeds')->field('open_price,first_price')->where('varieties="'.$seed.'"')->find();
          $money=M('Pay_statistical')->field('end_money')->where('seed="'.$seed.'"')->order('time DESC')->select();
		  if(empty($money)){
			  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
			  $yes=$data_seed_r['first_price'];
		  }else{
			  $yes=$money[0]['end_money'];
		  }
		if(empty($data_today)){
			//$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
			$today['start']=0; 
			//$money=M('Pay_statistical')->field('end_money')->where('seed="'.$seed.'"')->order('time DESC')->select();
			if(empty($money)){
			   $today['new']=$data_seed_r['first_price'];
			}else{
			   $today['new']=$money[0]['end_money'];
			}
		}			  
          //$today['ga']=(float)($today['new']-$yes);
		  // var_dump($today['new']);
		  // echo "\n";
		   // var_dump($yes);
		  // echo "\n";
//		    var_dump($today['ga']);
		  // echo "\n";
		  // die;
          //$today['gains']=round((($today['new']-$yes)/$yes)*100,2);
		  if(empty($data_today)){
				$today['new']=(float)$today['new'];
				$yes=(float)$yes;
				 $today['ga']=(float)(($today['new']*1)-($yes*1))*1; 
				 $today['gains']=(($today['new']-$yes)/$yes)*100;
              
			}else{
				$today['new']=(float)$today['new'];
				$data_today[0]['money']=(float)$data_today[0]['money'];
				 $today['ga']=(float)(($today['new']*1)-($data_today[0]['money']*1))*1;
				 $today['gains']=(($today['new']-$data_today[0]['money'])/$data_today[0]['money'])*100;
              
			}
			 /*if($seed=='种子'){
				if($today['gains']>1){
					$today['gains']=1;
				}else if($today['gains']<-1){
					$today['gains']=-1;
				}
			}elseif($seed=='番茄'||$seed=='葡萄'||$seed=='菠萝'){
					
			}else{
				if($today['gains']>10){
					$today['gains']=10;
				}else if($today['gains']<-10){
					$today['gains']=-10;
				}
			}*/
			$today['gains']=round($today['gains'],2);
			//$today['ga']=round($today['ga'],5);
			$today['ga']= number_format($today['ga'], 5, '.', '');
          $data_money=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND money!=0')->order('money')->select();
          $today['max']=$data_money[count($data_money)-1]['money'];
          $today['min']=$data_money[0]['money'];
		  //$today['sell_height']=substr(((float)$today['new']*1)*1.01,0,7);
		  //$today['buy_low']=substr(((float)$today['new']*1)*0.99,0,7);
		  // print_r($today);
		  //var_dump($today);
		  // die;
          //查询个人可用金额
          //$data_m=M(''.$case_m.'')->where('user='.$tel)->find();

			
			//查询冻结金额
			 $data_m=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
              $money=$data_m['coin'];
          $money_freeze=$data_m['coin_freeze'];
			$this->assign('money_freeze',$money_freeze);
			
          //$pay=M('pay_statistical');
          //$to=$pay->where('seed="'.$seed.'"')->order('time')->select();
          //$count=$pay->where('seed="'.$seed.'"')->count();

          //果实是否可交易
          $this->assign('state',$state);
          //个人果子库存
          $this->assign('fruit_ware',$fruit_ware);
          //可否交易 0可以1不可以
          //$this->assign('pay_state',$pay_state);
          //个人可用余额
          $this->assign('money',$money);
          //种子类型
          $this->assign('seed',$seed);
          //最高价格
		  $max_entrust=round($max_entrust,5);
		  $min_entrust=round($min_entrust,5);
          $this->assign('max_entrust',$max_entrust);
          //最低价
		  
          $this->assign('min_entrust',$min_entrust);
          //历史行情数据
          $this->assign('data_record',$data_record);
          //当日行情
          $this->assign('today',$today);
		  //var_dump($today);
          //果实种类
          $this->assign('seeddata',$seed_data);

          $this->display();
		  }else{
			echo '请求错误';
			exit;
		  }

          
      }
   }


   //AJAX实委托
    public function find_per(){
      //if(IS_AJAX){
          $user = session('user');
          $cases=''.date('Y-m').'_pay';
          $data=M(''.$cases.'')->where('user="'.$user.'" AND state < 2 AND trans_type=0')->order('time DESC')->select();

          var_dump($data);
      //}
  }

   //果实市场动态
    public function friut_dynamic(){

        if(IS_AJAX){

            $seed = $_POST['seed'];
			//$seed = '土豆';
            $y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");

            $s_data=M('Global_conf')->where('cases="start_time"')->find();
            $e_data=M('Global_conf')->where('cases="end_time"')->find();
            $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
            //$start_t=$start-3600*24;
            //$time=time();
            //print_r($start);die;
            $end = mktime($e_data['value'],0,0,$m,$d,$y);
            //$end_t=$end-3600*24;
            $cases=''.date('Y-m').'_matching';
			$first=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state!=2 and money>0')->find();
			$today['start']=$first['money'];
			$end_money=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state!=2 and money>0')->order('id desc')->find();
            //$data_today=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND state!=2 AND money!=0')->order('time')->select();
            $today['count']=M(''.$cases.'')->where('time >= "'.$start.'" AND time <= " '.$end.'" AND seed="'.$seed.'" AND state=0')->sum('num');
            $today['new']=$end_money['money'];  //最新
			//print_r($start);die;
			 $data_seed_r=M('Seeds')->field('open_price,first_price')->where('varieties="'.$seed.'"')->find();
            $money=M('Pay_statistical')->field('end_money')->where('seed="'.$seed.'"')->order('time DESC')->find();
			  if(empty($money)){
				  //$data_seed_r=M('Seeds')->where('varieties="'.$seed.'"')->find();
				  $yes=$data_seed_r['first_price'];
			  }else{
				  $yes=$money['end_money'];
			  } 
			if(empty($first)){
				//$today['start']=0;
				//$data_seed_r=M('Seeds')->field('open_price,first_price')->where('varieties="'.$seed.'"')->find();
				$today['start']=0; 
				//$money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
				if(empty($money)){				  
				   $today['new']=$data_seed_r['first_price'];
				}else{
				   $today['new']=$money['end_money'];
				}
			}
			
            //$today['gains']=round((($today['new']-$yes)/$yes)*100,2);//涨幅
			//$today['gains'].='%';
            if(empty($first)){
				 $today['ga']=($today['new']-$yes)*1; 
				// $today['ga']=round($today['ga'],5);
				 $today['gains']=(($today['new']-$yes)/$yes)*100;
                
			}else{
				 $today['ga']=($today['new']-$first['money'])*1;
				// $today['ga']=round($today['ga'],5);//
				 $today['gains']=(($today['new']-$first['money'])/$first['money'])*100;
			}
			/*if($seed=='种子'){
				if($today['gains']>1){
					$today['gains']=1;
				}else if($today['gains']<-1){
					$today['gains']=-1;
				}
			}elseif($seed=='番茄'||$seed=='葡萄'||$seed=='菠萝'){
					
			}else{
				if($today['gains']>10){
					$today['gains']=10;
				}else if($today['gains']<-10){
					$today['gains']=-10;
				}
			}*/
			
			
			$today['gains']=round($today['gains'],2);
            $min=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND money!=0')->order('money')->find();
			$max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'" AND money!=0')->order('money desc')->find();
            $today['max']=$max['money'];  //最高
            $today['min']=$min['money'];   //最低
			//$today['count']=count($data_money);
			if(empty($min)){
				$today['count']=0;
				$today['max']=0;
				$today['min']=0;
			}
			$today['ga']= number_format($today['ga'], 5, '.', '');
			//print_r($today);die;
            $data=json_encode($today);
            echo $data;
        }
    }

     //K线图
    public function ajax_k(){

         $seed=I('get.procode','','');

         $data = $this->k($seed);

         echo json_encode($data);
     }

    private function k($seed){

		$today_str = date("Y-m-d");

		//print_r($start);die;
		$end = strtotime($today_str) + 3600 * 24;

		$case_m='pay_statistical';

		$data_today=M($case_m)->where(' time <= " '.$end.'" AND seed="'.$seed.'"')->limit()->order('time asc')->select();

		return $data_today;
	}

    //分时图
    public function ajax_min(){

        $seed = I('post.procode','','');
        $data = $this->market_f($seed);
		//
        echo json_encode($data);
    }

    private function market_f($seed){

        $all_data = $this->GetMarketInfo($seed);

        $all_data['SysDT'] = time();
		
        return array("data"=>$all_data);

    }

     // 获取某产品的 当日销售量
    private function GetMarketInfo($seed){

        $today_str = date("Y-m-d");                     //  今日零点的

        $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);

        extract($GetOpenCloseTime);

        // 记录表
        $case_m=''.date('Y-m').'_matching';

        /***********************************************************/
//        $aaaa = M($case_m)->order('time desc')->find();
//        $aaaa['time'] += 60 * 5;
//        $aaaa['num']  = rand(500,1000);
//        $aaaa['money']  = rand(10000,11000)/100000;
//        unset($aaaa['id']);
//        M($case_m)->add($aaaa);
        /***********************************************************/

        $ClosingQuotationTime = strtotime($today_str_end) - 3600 * 24; //  设置前一天的收盘时间

        //  获得前一天的收盘价格
        if(! $LastDayProductInfo = S("LastDayProductInfo_$seed")  ){
            $LastDayProductInfo = M("pay_statistical")->where(' seed="'.$seed.'"')->order('time desc')->find();
            if(empty($LastDayProductInfo)){
                $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
            }
            $LastDayProductInfo['end_money'] = $seeds_info['first_price'];
            S("LastDayProductInfo_$seed",$LastDayProductInfo,(strtotime($today_str)-time()));
        }

        //  获取当前 今天的交易数据
        //  规定 每 5 分钟 刷新一次数据

        $interval  = 60 * 5;  //  设置需要间隔的秒数

        //  获取当前 今天的交易数据
        $seed_info = S("SeedSalesInfo_$seed".$today_str);
        $seed_info = $seed_info ? $seed_info : array();
		// $seed_info = array();
        // 得到  当前缓存了多少条数据
        $ListLength = count($seed_info);
		$start_time = strtotime($today_str_begin);

        $NowTime = time() > strtotime($today_str_end) ? strtotime($today_str_end) : time();

        $ShouldHaveLength = floor(($NowTime - $start_time) / $interval);

        $where['seed'] = $seed;

        if($ShouldHaveLength > $ListLength){
            for ($time_arr = $start_time + $ListLength * $interval ; $time_arr <= $NowTime; $time_arr += $interval) {
				// echo $time_arr;
				// echo "\n";
                $where["time"] = array("BETWEEN",array($time_arr,$time_arr + $interval));
                $list = array();
                $list = M($case_m)->where($where)->field(" * , sum(num) as total_num")->order('time asc')->find();
                $list['time'] = $time_arr;
                $seed_info[] = $list;
            }	
            unset($list);
            S("SeedSalesInfo_$seed".$today_str,$seed_info);
        }

        foreach($seed_info as $key=>$val){
            $TimeShare['Time']       = $val['time'];
            $TimeShare['Price']     = number_format($val['money'],6);
            $TimeShare['Volume']     = (int)$val['total_num'];
            $data['TimeShare'][] = $TimeShare;
        }

        $MarketInfo = $this->GetFlushEntrust(0,$seed);

        $data = array_merge($data,$MarketInfo);

        return $data;

    }


    protected function GetOpenCloseTime($today_str){
        // 获取系统的开收盘时间
        if( S("SALE_START_TIME") && S("SALE_END_TIME")  ){
			
			$start =  S("SALE_START_TIME");

            $end   =  S("SALE_END_TIME");
			
			$today_str_begin = ($today_str ." " .  $start . ":00:00");
			
			$today_str_end   = ($today_str ." " .  $end   . ":00:00");
			
        }else{
			
            $Global_conf =  M('Global_conf')->where('cases="start_time" or cases="end_time"')->select();

            foreach($Global_conf as $val){
                if($val['cases'] == 'start_time') { $today_str_begin = $today_str ." " .$val['value'] . ":00:00"; S("SALE_START_TIME",$val['value'],24*3600); $start =$val['value'];}
                if($val['cases'] == 'end_time'  ) { $today_str_end   = $today_str ." " .$val['value'] . ":00:00"; S("SALE_END_TIME",$val['value'],24*3600);	 $end =$val['value'];}			
			}
			
        }

        return array("start"=>$start,"end"=>$end,"today_str_begin"=>$today_str_begin,"today_str_end"=>$today_str_end);

    }
	
    public function GetFlushEntrust($isget_BUY_AND_SALE = 1,$seed=''){

        $today_str = date("Y-m-d");                     //  今日零点的

        $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);

        extract($GetOpenCloseTime);

        // 记录表
        $case_m=''.date('Y-m').'_matching';

        $ClosingQuotationTime = strtotime($today_str_end) - 3600 * 24; //  设置前一天的收盘时间

        //  获得前一天的收盘价格

        if(! $LastDayProductInfo = S("LastDayProductInfo_$seed") ){
            $LastDayProductInfo = M("pay_statistical")->where(' seed="'.$seed.'"')->order('time desc')->find();
            if( empty($LastDayProductInfo)){
                $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
                $LastDayProductInfo['end_money'] = $seeds_info['first_price'];
            }
            S("LastDayProductInfo_$seed",$LastDayProductInfo,(strtotime($today_str)-time()));
        }

		
		
		/***
		    2017-07-23 
		    修改交易信息的查询功能。
			每5分钟计算一次，
		*/
		// $interval = 60 * 5;
		
		// $info_count = floor(($end - $start) / ($interval));     //  今日交易信息总条数
		
		// $seed_info = S("SeedSalesInfo_$seed");
		
		// $seed_info = array();
		
		$now_info_count  =  count($seed_info);
		
		// $need_info_count =  floor((time() - $start) / ($interval));
		
		// for($i = $now_info_count ; $i < $need_info_count ; $i++ ){
			// $where = "" ; 
			// $where =  'time >= "'.($start+($i*$interval)).'"  AND time <= " '.($start+($i*$interval)+$interval).'" AND seed="'.$seed.'"';
			// $info = M($case_m)->where($where)->order('time desc')->field(" * , sum(num) as total_num")->find();
			// echo M($case_m)->getLastSql();echo "\n";
			// $seed_info[] = $info;
		// }
		// die;
		// print_r($seed_info);die;
		
		// S("SeedSalesInfo_$seed",$seed_info,array('type'=>'file','expire'=>($end - $start)));
		$start = strtotime($today_str_begin);
		$end = strtotime($today_str_end);
		
		
		$where_seed_info = 'time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"';
		
        if(! $seed_info = S("SeedSalesInfo_$seed") ){
			$field = " *  ";
            $seed_info = M($case_m)->where($where_seed_info)->order('time asc')->select();
            S("SeedSalesInfo_$seed",$seed_info,array('type'=>'file','expire'=>300));
        }
		
		$TodayProductSum = M($case_m)->where($where_seed_info)->field(" max(money) as highestprice, min(money) as lowestprice ,  sum(num) as total_num")->find();

        //  取得 最新的交易数据
        $NowProductInfo = $seed_info[ count($seed_info)-1];
// print_r($seed_info);die;
        if(!$NowProductInfo ){

            $NowProductInfo = $this->GetNowProductInfo($start,$end,$seed);

        }

        // 取得今天第一笔交易 数据
        $FirstProductSaleInfo = $seed_info[0] ? $seed_info[0] : array();

        $data  = array();

        $data['MarketInfo']['ProductName'] = $seed;
        // 计算 当前产品的  涨幅额度
        $data['MarketInfo']['Increase']           =  ($NowProductInfo['money'] - ( $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $LastDayProductInfo['end_money'])) ;
        $data['MarketInfo']['IncreaseRate']       =  number_format($data['MarketInfo']['Increase'] / ( $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $LastDayProductInfo['end_money']) , 4 ) ;
        //  计算产品的 涨跌停价格
        $data['MarketInfo']['OpenPrice']          =  $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['open_price']  ;
        $data['MarketInfo']['LimitUp']            =  round( (($FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['money']) * 1.1 ) ,5 );
        $data['MarketInfo']['LimitDown']          =  round( (($FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['money']) * 0.9 ) ,5);
        $data['MarketInfo']['Price']              =  $NowProductInfo['money']  ;
        $data['MarketInfo']['ProductId']          =  null;
        $data['MarketInfo']['ProductImage']       =  null;
        $data['MarketInfo']['ProductCode']        =  null;
        $data['MarketInfo']['HighestPrice']       =  round($TodayProductSum['highestprice'],5) ;
        $data['MarketInfo']['LowestPrice']        =  round($TodayProductSum['lowestprice'],5) ;
        $data['MarketInfo']['Volume']             =  $TodayProductSum['total_num'];
  
// print_r($data);die;
        //  获取 产品正在进行的买入卖出信息
        if($isget_BUY_AND_SALE){

            $buy_list  = $this->find_buy(5,$seed);

            foreach ($buy_list as $key=>$val){
                if($val['money']){
                    $List['Price']  = $val['money'];
                    $List['Number'] = $val['num'];

                    $data['BuyList'][] = $List;
                }

            }

            $sale_list = $this->find_sell(5,$seed);

            foreach ($sale_list as $key=>$val){
                if($val['money']){
                    $List['Price']  = $val['money'];
                    $List['Number'] = $val['num'];
                    $data['SaleList'][] = $List;
                }

            }
        }

        return $data;
    }

    /**
     * 获取产品 最新的交易信息
     * @param $start  开盘时间
     * @param $end    收盘时间
     * @param $seed   种子名称
     * @return array   返回数组
     */
    private function GetNowProductInfo($start,$end,$seed){

	
	
	
	
	/****
				TODO:
				20717-07-19
			 更改为吧不需要查询上一天收盘价格作为现价
			 若新一天不存在交易，以数据库中设置的的 open_price 作为 开盘价 及 现价
	*/
	
	  
        // $time = 60 * 60 * 24;

        // if($NowProductInfo = S($seed."NowProductInfo") ){
            // return $NowProductInfo;
        // }

        // $start_str = date("H",$start);
        // $end_str   = date("H",$end);

        // $all_match_sta =  M("matching_statistical")->select();

        // foreach ( $all_match_sta as $val){

            // $case_m = $val['name'].'_matching';

      //    // FROM_UNIXTIME(add_time,"%X年%m月")

            // $NowProductInfo = M($case_m)->where(' FROM_UNIXTIME(time,"%H") >= "'.$start_str.'"  AND  FROM_UNIXTIME(time,"%H") <= "'.$end_str.'" AND seed="'.$seed.'"')->order('time desc')->find();

            // if($NowProductInfo){
                // break;
            // }

        // }

        // if(empty($NowProductInfo)){
            $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
            $NowProductInfo['money'] 		= $seeds_info['open_price']>0 ? $seeds_info['open_price'] : $seeds_info['first_price'];
			$NowProductInfo['open_price']   = $seeds_info['open_price'];
        // }

        S($seed."NowProductInfo",$NowProductInfo,$time);

        return $NowProductInfo;

    }


    //买卖排队
    public function find_buy(){

        if(IS_AJAX){
            //接收种子类别
			//接收种子类别
			$y = date("Y");
			//获取当天的月份
			$m = date("m");
			//获取当天的号数
			$d = date("d");
			//print_r($m);die;
			//$tm =date("d")+1;
			$e_data=M('Global_conf')->where('cases="end_time"')->find();
			$time=time();
			//print_r($start);die;
			$end = mktime($e_data['value'],0,0,$m,$d,$y);
			if($time<=$end){
				$seed=I('post.seed','','');
				$case_p=''.date('Y-m').'_pay';
				$str = "";
				//卖方数据
				$data_p_s=M(''.$case_p.'')->order('money asc')->where('type= 0 AND state<2 AND num>0 AND seed="'.$seed.'"')->select();
				$count_s=M(''.$case_p.'')->order('money asc')->where('type= 0 AND state<2 AND num>0 AND seed="'.$seed.'"')->count();
				$data_s=array();
				$f=0;
				for($i=0;$i<$count_s;$i++){
				   if($f>5){
					   break;
				   }
				   if($i==0){
					   $data_s[$f]['money']=$data_p_s[$i]['money'];
					   $data_s[$f]['num']=$data_p_s[$i]['num'];
					   $f++;
				   }else{
					   if($data_p_s[$i]['money']==$data_s[$f-1]['money']){
						   $data_s[$f-1]['num']+=$data_p_s[$i]['num'];
					   }else{
						   $data_s[$f]['money']=$data_p_s[$i]['money'];
						   $data_s[$f]['num']=$data_p_s[$i]['num'];
						   $f++;
					   }
				   }
				}
				if(count($data_s)>5){
				   $sell_block = 5;
				}else{
				   $sell_block = count($data_s);
				}
				$zh_num=array('一','二','三','四','五');
				if($sell_block){
				   for($i=0;$i<$sell_block;$i++){
					   $k=$sell_block-$i-1;
					   $str.= '<div class="entrust_list" style="color:#5B910B">
								   <div class="entrust_list_center"><span>卖'.$zh_num[$k].'</span></div>
								   <div id="sell_'.($k+1).'" class="entrust_list_center"><span>'.$data_s[$k]['money'].'</span></div>
								   <div class="entrust_list_center"><span>'.$data_s[$k]['num'].'</span></div>
								</div>';
				   }
				}else{
				   $str.= '<div class="entrust_list" style="text-align:center"><span style="display:block;margin-top:2%">暂无卖方数据<span></div>';
				}


				//maifang
				$data_p_b=M(''.$case_p.'')->order('money desc')->where('type= 1 AND num>0 AND state<2 AND seed="'.$seed.'"')->select();
				$count_b=M(''.$case_p.'')->order('money desc')->where('type= 1 AND num>0 AND state<2 AND seed="'.$seed.'"')->count();
				$data_b=array();
				$k=0;
				for($i=0;$i<$count_b;$i++){
					if($k>5){
					   break;
				   }
				   if($i==0){
					   $data_b[$k]['money']=$data_p_b[$i]['money'];
					   $data_b[$k]['num']=$data_p_b[$i]['num'];
					   $k++;
				   }else{
					   if($data_p_b[$i]['money']==$data_b[$k-1]['money']){
						   $data_b[$k-1]['num']+=$data_p_b[$i]['num'];
					   }else{
						   $data_b[$k]['money']=$data_p_b[$i]['money'];
						   $data_b[$k]['num']=$data_p_b[$i]['num'];
						   $k++;
					   }
				   }
				}
				//print_r($data_b);die;

				if(count($data_b)>5){
				   $buy_block = 5;
				}else{
				   $buy_block = count($data_b);
				}


				if($buy_block){
				   for($i=0;$i<$buy_block;$i++){
					   $str.='<div class="entrust_list" style="color:#FF6600">
								 <div class="entrust_list_center"><span>买'.$zh_num[$i].'</span></div>
								 <div class="entrust_list_center"><span>'.$data_b[$i]['money'].'</span></div>
								 <div class="entrust_list_center"><span>'.$data_b[$i]['num'].'</span></div>
							  </div>';
				   }
				}else{
				   $str.='<div class="entrust_list" style="text-align:center;"><span style="display:block;margin-top:2%">暂无买方数据<span></div>';
				}

			}else{
				$str.= '<div class="entrust_list" style="text-align:center"><span style="display:block;margin-top:2%">暂无卖方数据<span></div>';
				$str.='<div class="entrust_list" style="text-align:center;"><span style="display:block;margin-top:2%">暂无买方数据<span></div>';
			}

           echo $str;

        }
    }

    //当前委托记录
    public function entrust(){
        if(IS_AJAX){
            $seed=I('post.seed');
            $user = session('user');
            $cases=''.date('Y-m').'_pay';
			$d=date('d');
			if($d==01){
				$matching_statistical=M('matching_statistical')->order('id desc')->select();
				$num=$matching_statistical[2]['name'];
				$cases_la=''.$num.'_pay';
				$data_la=M(''.$cases_la.'')->where('user="'.$user.'" AND num>0 AND state < 2 AND trans_type=0 AND seed="'.$seed.'"')->order('time DESC')->select();
				//$data_to=M(''.$cases.'')->where('user="'.$user.'" AND state < 2 AND trans_type=0 AND seed="'.$seed.'"')->order('time DESC')->select();
				
			}
			/*
			 *   取消委托单  不显示
			 *   2017年8月27日14:59:35
			 *   QHP
			 */
			$data_to=M(''.$cases.'')->where('user="'.$user.'" AND num>0 AND state < 2 ' ./* 'AND trans_type=0 ' . */' AND seed="'.$seed.'"')->order('time DESC')->select();
			if($data_la && $data_to){
				$data = array_merge($data_la,$data_to);
			}else{
				if($data_la){
					$data = $data_la;
				}else if($data_to){
				    $data = $data_to;
				}
			}
            if(count($data)){
               for($i=0;$i<count($data);$i++){
				   //$data[$i]['remaining']=$data[$i]['submit_num']-$data[$i]['num'];
                   echo '<div id="entrust_'.$data[$i]['id'].'" class="history_entrust_list" style="margin-top:3%">
                       <div class="history_entrust_title" style="width:14%;"><span style="margin-left:-10%;">';
                           //$time = date('Y-m-d',$data[$i]['time']);
                           //$y = substr($time,0,4);
                           //$m = substr($time,6,1);
                           //$d = substr($time,8,2);						   
					     $y = date('Y',$data[$i]['time']);
                         $m = date('n',$data[$i]['time']);
                         $d = date('j',$data[$i]['time']);
                   echo $y.'.'.$m.'.'.$d;				
                   echo '</span></div>';
                   echo '<div class="history_entrust_title" style="margin-left:4%"><span>'.$data[$i]['seed'].'</span></div>';
                   echo '<div class="history_entrust_title">';
                         if($data[$i]['type']==1){
                             echo '<span style="color:blue">买</span>';
                         }else{
                             echo '</span>卖</span>';
                         }
                   echo '</div>';
                   echo '<div class="history_entrust_title" style="margin-left:-2%"><span>'.$data[$i]['money'].'</span></div>';
                   echo '<div class="history_entrust_title" style="margin-left:8%"><span>'.$data[$i]['num'].'</span></div>';
                   echo '<div class="history_entrust_title" style="margin-left:1%"><span class="history_width">';
                        if($data[$i]['state']==0){
                             echo '待交易';
                        }else if($data[$i]['state']==1){
                             echo '待交易';
                        }else if($data[$i]['state']==2){
                             echo '已完成';
                        }else{
                             echo '已撤销';
                        }
                   echo '</span></div>';
                   echo '<div class="history_entrust_title" style="margin-left:3%"><span style="color:red" onclick="undo('.$data[$i]['time'].','.$data[$i]['id'].')">撤销</span></div></div>';
               }

            }else{
                echo '<div class="history_entrust_list" style="margin-top:3%;text-align:center"><span style="line-height:36px">暂无该产品的委托记录</span></div>';
            }
        }
    }

  public function undo(){
          if(IS_AJAX){
			  $data=M('Global_conf')->where('cases="poundage"')->find();
				$poundage=$data['value'];
				$time=I('post.time');
			   $time=date('Y-m',$time);
			   $user=$_SESSION['user'];
			   $case_p=''.$time.'_pay';
			   $id=I('post.id');
			   $data=M(''.$case_p.'')->where('id='.$id.' AND user ='.$user.' AND queue !=1 AND state<2')->find();
			   $data_p['state']=3;
			   $model=new Model();
			   $model->startTrans();
			   if(M(''.$case_p.'')->where('id='.$id.' AND user ='.$user.' AND queue !=1 AND state<2')->save($data_p)){
				   if($data['type']==1){
					   $money=$data['num']*$data['money'];
					   $table=new Tool();
					   $case='members';
					   $tel=$data['user'];
					   $case_m=$table->table($tel,$case);
					  //print_r($money);die;
					  $coin_old=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
					   if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
						   if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
							   $data_c=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
								$coin_record['coin']=$data_c['coin'];
								$coin_record['coin_freeze']=$data_c['coin_freeze'];
								$coin_record['coin_old']=$coin_old['coin'];
								$coin_record['coin_freeze_old']=$coin_old['coin_freeze'];
								$coin_record['add']=$money;
								$coin_record['time']=time();
								$coin_record['user']=$tel;
								if(M('coin_record')->add($coin_record)){
									$model->commit();							   
									$datas['money']='已退回金币'.$money;
									$datas['state']=1;
									$datas=json_encode($datas);
									echo $datas;
								}else{
									$model->rollback();
									$datas['money']='金币解冻失败';
									$datas['state']=-6;//撤销冻结金额失败////
									$datas=json_encode($datas);
									echo $datas;
								}
						   }else{
							   $model->rollback();
							   $datas['money']='金币解冻失败';
							   $datas['state']=0;//撤销冻结金额失败
								$datas=json_encode($datas);
								echo $datas;
							   //echo 0;//撤销冻结金额失败
						   }
					   }else{
						   $model->rollback();
						   $datas['money']='金币解冻失败';
						   $datas['state']=-1;//金额回滚失败
							$datas=json_encode($datas);
							echo $datas;
						   //echo -1;//金额回滚失败
					   }
				   }else{
					   $seed=$data['seed'];
					   $num=$data['num'];
					   $money=$data['money'];
					   $table=new Tool();
					   $case='fruit_record';
					   $tel=$data['user'];
					   $case_f=$table->table($tel,$case);
					   $table=new Tool();
					   $case='members';
					   
					   $case_m=$table->table($tel,$case);
					   if(M(''.$case_f.'')->where('user='.$tel.' AND seed ="'.$seed.'" AND money='.$money)->setDec('num',$num)){
						   //$data_record=M(''.$case_f.'')->where('user='.$tel.' AND seed ="'.$seed.'" AND money='.$money)->find();
						   if($seed=='种子'){
							   $case='prop_warehouse';
							   $sql_find='user ='.$tel.' AND props ="'.$seed.'"';
							   $data_s_b['props']=$seed;
							   $data_s_b['prop_id']=6;
							   $data_s_b['user']=$tel;//
							   $data_s_b['num']=$num;
						   }else{
							   $case='seed_warehouse';
							   $sql_find='user ='.$tel.' AND seeds ="'.$seed.'"';
							   $data_s_b['seeds']=$seed;
							   $data_s_b['user']=$tel;
							   $data_s_b['num']=$num;
						   }						   
						   $case_s_b=$table->table($tel,$case);
						   $data_seed=M(''.$case_s_b.'')->where($sql_find)->find();
						   if(empty($data_seed)){
							   if(M(''.$case_s_b.'')->add($data_s_b)){
								   $money_t=$num*$money*$poundage;
								   $coin_old=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
								   if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money_t)){
									   if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money_t)){
										   $data_c=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
											$coin_record['coin']=$data_c['coin'];
											$coin_record['coin_freeze']=$data_c['coin_freeze'];
											$coin_record['add']=$money_t;
											$coin_record['time']=time();
											$coin_record['user']=$tel;
											$coin_record['coin_old']=$coin_old['coin'];
											$coin_record['coin_freeze_old']=$coin_old['coin_freeze'];
											if(M('coin_record')->add($coin_record)){
												$model->rollback();
												$datas['money']='已退回手续费'.$money_t;
												$datas['state']=2;
												$datas=json_encode($datas);
												echo $datas;
											}else{
												$model->rollback();
												$datas['money']='金币解冻失败1';
												$datas['state']=-6;//撤销冻结金额失败/
												$datas=json_encode($datas);
												echo $datas;
											}
									   }else{
										    $model->rollback();
										    $datas['money']='金币解冻失败'.$money_t;
											$datas['state']=0;//撤销冻结金额失败
											$datas=json_encode($datas);
											echo $datas;
										   //echo 0;//撤销冻结金额失败//
									   }
								   }else{
									   $model->rollback();
									   $datas['money']='金币解冻失败';
										$datas['state']= -1;//金额回滚失败
										$datas=json_encode($datas);
										echo $datas;
									   //echo -1;//金额回滚失败
								   }
							   }else{
								   $model->rollback();
								   $datas['money']='果实解冻失败';
									$datas['state']= -2;   //果实回滚失败
									$datas=json_encode($datas);
									echo $datas;
								   //echo -2;   //果实回滚失败
							   }
						   }else{
							   if(M(''.$case_s_b.'')->where($sql_find)->setInc('num',$num)){
								   $money_t=$num*$money*$poundage;
								   //echo $money_t;die;
								   $coin_old=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
								   if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money_t)){
									   if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money_t)){
										    $data_c=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
											$coin_record['coin']=$data_c['coin'];
											$coin_record['coin_freeze']=$data_c['coin_freeze'];
											$coin_record['time']=time();
											$coin_record['add']=$money_t;
											$coin_record['user']=$tel;
											$coin_record['coin_old']=$coin_old['coin'];
											$coin_record['coin_freeze_old']=$coin_old['coin_freeze'];
											if(M('coin_record')->add($coin_record)){
												$model->commit();
												$datas['money']='已退回手续费'.$money_t; 
												$datas['state']=2;
												$datas=json_encode($datas);
												echo $datas;
											}else{
												$model->rollback();
												$datas['money']='金币解冻失败';
												$datas['state']= -6;//撤销冻结金额失败
												$datas=json_encode($datas);
												echo $datas;
												//echo -6;//撤销冻结金额失败
											}
									   }else{
										   $model->rollback();
										   $datas['money']='金币解冻失败';
											$datas['state']= 0;//撤销冻结金额失败
											$datas=json_encode($datas);
											echo $datas;
										   //echo 0;//撤销冻结金额失败
									   }
								   }else{
									   $model->rollback();
									   $datas['money']='金币解冻失败';
										$datas['state']= -1;//金额回滚失败
										$datas=json_encode($datas);
										echo $datas;
									   //echo -1;//金额回滚失败
								   }
							   }else{
								   $model->rollback();
								   $datas['money']='果实解冻失败';
									$datas['state']= -3;     //果实回滚失败
									$datas=json_encode($datas);
									echo $datas;
								   
							   }
						   }
					   }else{
						   $model->rollback();
						   $datas['money']='果实解冻失败';
							$datas['state']= -4;      //冻结记录消除失败
							$datas=json_encode($datas);
							echo $datas;
						   //echo -4;      //冻结记录消除失败
					   }
				   }
			   }else{
				   $model->rollback();
				   $datas['money']='撤销订单失败';
					$datas['state']= 8;//撤销订单失败
					$datas=json_encode($datas);
					echo $datas;
				   //echo 8;//撤销订单失败
			   }
         }
    }

    //交易记录
    public function trading(){

        $seed=I('post.seed','','');
        $user = session('user');
        $count_time=M('matching_statistical')->order()->count();
        $time=M('matching_statistical')->order('id')->select();
        $k=$count_time-1;
        $length=10;
        $t=0;
        $data=array();
        $data=$this->find_old_d($time,$user,$seed,$k,$data,$length,$t);
        if(count($data)){
            for($i=0;$i<count($data);$i++){
                echo '<div class="history_entrust_list" style="margin-top:1%">
					  <div class="history_entrust_title color2" style="width:14%"><span style="margin-left: -10%;">';
						   //$time = date('Y-m-d',$data[$i]['time']);
                           $y = date('Y',$data[$i]['time']);
                           $m = date('n',$data[$i]['time']);
                           $d = date('j',$data[$i]['time']);
                           echo $y.'.'.$m.'.'.$d;
				echo '</span></div>
					  <div class="history_entrust_title color2" style="margin-left:4%"><span>'.$data[$i]['seed'].'</span></div>
					  <div class="history_entrust_title color2"><span>';
                        if($data[$i]['sell_user']==session('user')){
                            echo '卖';
                        }else{
                            echo '<span style="color:red">买</span>';
                        }
                        echo '</span></div>
								<div class="history_entrust_title color2" style="margin-left:0%"><span>'.$data[$i]['money'].'</span></div>
								<div class="history_entrust_title color2" style="margin-left:8%"><span>'.$data[$i]['num'].'</span></div>
								<div class="history_entrust_title color2"><span class="history_width">已成交</span></div>
							</div>';
             }

             //S('data',$data,3600);//
        }else{
             echo '<div class="history_entrust_list" style="margin-top:1%;text-align:center"><span style="line-height:45px">暂无历史数据<span></div>';
        }
    }

    public function find_old_d($time,$user,$seed,$k,$data,$length,$t){
        $cases=''.$time[$k]['name'].'_matching';
        $data_m=M(''.$cases.'')->where('sell_user="'.$user.'" AND seed="'.$seed.'" AND state=0 or buy_user="'.$user.'" AND seed="'.$seed.'" AND state=0')->order('time DESC')->select();
        $count=M(''.$cases.'')->where('sell_user="'.$user.'" AND seed="'.$seed.'" AND state=0 or buy_user="'.$user.'" AND seed="'.$seed.'" AND state=0')->order('time DESC')->count();
        if($count>=$length){
            for($i=0;$i<$length;$i++){
				//$t=$t+$i;
                $data[$t]=$data_m[$i];
				$t++;
            }
            return $data;
        }else{
            $length=10-$count;
            for($i=$t;$i<$count;$i++){
                $data[$i]=$data_m[$i];
                $t++;
            }
            if($k==0){
                return $data;
            }else{
                if($length==0){
                    return $data;
                }else{
                    $k--;
                   $data=$this->find_old_d($time,$user,$seed,$k,$data,$length,$t);
                   return $data;
                }
            }
        }
    }
	
	//刷新金币和果实数量
	public function brush(){
		 if(IS_AJAX){
			 if($_POST['type']=='fruit'){
				 if($_POST['seed']=='种子'){
					 $table = substr(session('user'),0,3).'_prop_warehouse';
				     $arr['props'] = $_POST['seed'];
				 }else{
					 $table = substr(session('user'),0,3).'_seed_warehouse';
				     $arr['seeds'] = $_POST['seed'];
				 }
				   
				   $arr['user'] = session('user');
				   $num = M("$table")->field('num')->where($arr)->find();
				   if($num['num']!==0 || !empty($num)){
					    echo $num['num'];
						exit;
				   }else{
					    echo 0;
				   } 
			 }else{
				   $table = substr(session('user'),0,3).'_members';
				   $coin = M("$table")->field('coin')->where(array('user'=>$_SESSION['user']))->find();
				   if($coin['coin']!==0 || !empty($coin)){
					    echo $coin['coin'];
						exit;
				   }else{
					    echo 0;
				   } 
			 }
		 }
	}
}
?>
