<?php

namespace Org\Our;
use Think\Model;

use Org\Our\Deal;
use Org\Our\archive;
class Matching
{
	public $number=0;
	
	
    public function time_buy($number,$poundage,$buy,$k,$t,$state){
        //S('states',null); S('start',null);S('end',null);die; //清除所有缴存

        if(S('end')==false && S('end')!==0){
            S('end',0);
        }
        //离上单时间间隔10秒，则可以进行处理。

        if(S('states')==0 && (S('end')==0 || time()-S('end')>=3)){
			$str='匹配中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            //标记处理状态
            S('states',1);
            $data=$this->find_buy($number,$poundage,$buy,$k,$t,$state);
			$str='处理完毕'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            //echo $zhi;
            S('states',null);
            S('states',0);
            S('start',null); //清除初始时间
            S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
            S('end',time()); // 重新记录本单处理完的时间
            return $data;
        }else{
			$str='排队中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            // echo 'die';
            if($state==0){
                $id=$buy[0]['id'];
                //$save['queue_s']=1;
				$save['trans_type']=0;
                $day_t=date('Y-m');
                $case=''.$day_t.'_pay';
                M($case)->where('id='.$id)->save($save);
            }
            //如果存在等待初始时间，则计算下等待的秒数;
			if(S('start')){
                $d_time = time()-S('start');
               // echo '有单正在处理，等待了'.$d_time.'秒';
                if($d_time>20){
					S('states',null);
					S('states',0);
					S('start',null); //清除初始时间
					S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
					S('end',time()); // 重新记录本单处理完的时间
				}
            }else{
                //同一时间另一请求，则记录等待初始时间
               // echo '有单正在处理,开始等待....';
                S('start',time());
            }
			$data['zhi']=4;
            return $data;
        }
    }
    public function time_buy_auto(){
        //S('states',null); S('start',null);S('end',null);die; //清除所有缴存
		
        if(S('end')==false && S('end')!==0){
            S('end',0);
        }
        //离上单时间间隔10秒，则可以进行处理。

        if(S('states')==0 && (S('end')==0|| time()-S('end')>=3)){
			$str='匹配中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
			$data=M('Global_conf')->where('cases="poundage"')->find();
			$poundage=$data['value'];
            //标记处理状态
            S('states',1);
            //$str='11';
            //file_put_contents('../log/log.log',$str.PHP_EOL."\n",FILE_APPEND);
            $case=''.date('Y-m').'_pay';
            $data=M('Seeds')->limit(0,6)->select(); //获取果实种类
            foreach ($data as $key=>$v){
                //echo 1;
                $d=date('d');
                //$v['varieties']='土豆';
				//echo $v['varieties'];
				$buy=M($case)->where('state < 2 AND type =1 AND queue=0 AND num>0 AND seed="'.$v['varieties'].'"')->order('money DESC')->select();
				//$buy_qu=M($case)->where('state < 2 AND type =1 AND trans_type =0 AND queue_s=1 AND seed="'.$v['varieties'].'"')->limit(0,50)->order('time asc')->select();
                //array_multisort(i_array_column($buy,'money'),SORT_DESC,$buy);//排序
				$orderBy = array('money'=>'desc','submit_num'=>'asc');
				$buy = resultOrderBy($buy,$orderBy);
                $t=0;
                $k=0;
                //$matching=new Matching();
                $state=1;
				//print_r($buy);
                if(!empty($buy)){
                   // S('seed','1');
				   //echo 5;
				   $number=0;
					$data_buy=$this->find_buy($number,$poundage,$buy,$k,$t,$state);
                }else{
                    echo 6;
                }
            }
            //$seed=S('seed')+1;
           // S('seed',$seed);
            //echo $zhi;
			$str='处理完毕'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            S('states',null);
            S('states',0);
            S('start',null); //清除初始时间
            S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
            S('end',time()); // 重新记录本单处理完的时间
            //return $zhi;
        }else{
			$str='排队中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            // echo 'die';
            //如果存在等待初始时间，则计算下等待的秒数;
            if(S('start')){
                $d_time = time()-S('start');
                echo '有单正在处理，等待了'.$d_time.'秒';
                if($d_time>20){
					 S('states',null);
					S('states',0);
					S('start',null); //清除初始时间
					S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
					S('end',time()); // 重新记录本单处理完的时间
				}
            }else{
                //同一时间另一请求，则记录等待初始时间
                //echo '有单正在处理,开始等待....';
                S('start',time());
            }

        }
    }
    public function time_sell($number,$poundage,$sell,$i,$t,$state){
        // S('states',null); S('start',null);S('end',null);die; //清除所有缴存

        if(S('end')==false && S('end')!==0){
            S('end',0);
        }
        //离上单时间间隔10秒，则可以进行处理。
        if(S('states')==0 && (S('end')==0|| time()-S('end')>=3)){
			$str='匹配中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
			//$data=M('Global_conf')->where('cases="poundage"')->find();
			//$poundage=$data['value'];
            //标记处理状态
            //echo S('seed');
            S('states',1);
            //开始处理
            //$str='10';
            //file_put_contents('../log/log.log',$str.PHP_EOL."\n",FILE_APPEND);
			$str='处理完毕'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            $data=$this->find_sell($number,$poundage,$sell,$i,$t,$state);
            //echo $zhi;
            S('states',null);
            S('start',null); //清除初始时间
            S('states',0);
            S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
            S('end',time()); // 重新记录本单处理完的时间
            return $data;
        }else{
			$str='排队中'.date('Y-m-d H:i:s');
			file_put_contents('../log/paidui.log',$str.PHP_EOL."\n",FILE_APPEND);
            //echo 'die';
            $seed=S('seed')+1;
            S('seed',$seed);
            //echo S('seed');echo '<br/>';
            if($state==0){
                $id=$sell[0]['id'];
               // $save['queue_s']=1;
				$save['trans_type']=0;
                $day_t=date('Y-m');
                $case=''.$day_t.'_pay';
                M($case)->where('id='.$id)->save($save);
            }
            //如果存在等待初始时间，则计算下等待的秒数;
			if(S('start')){
                $d_time = time()-S('start');
               // echo '有单正在处理，等待了'.$d_time.'秒';
                if($d_time>20){
					 S('states',null);
					S('states',0);
					S('start',null); //清除初始时间
					S('end',null); //如果存在上单结束时间，则清除上单的的处理完毕时间
					S('end',time()); // 重新记录本单处理完的时间
				}
            }else{
                //同一时间另一请求，则记录等待初始时间//
               // echo '有单正在处理,开始等待....';
                S('start',time());
            }
			$data['zhi']=4;
            return $data;
        }
    }
    public function find_sell($number,$poundage,$sell,$i,$t,$state){
		//$number=0;
		
        //echo 'die';die;
        $y = date("Y");
        $m = date("m");

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
        $start= mktime($s_data,0,0,$m,$d,$y);//即是当天零点的时间戳
		$start_pay=$start+900;
        //print_r($start);die;
        $end = mktime($e_data,0,0,$m,$d,$y);
        $time=time();
        if($time>$end||$time<$start){
            //echo 2;die;
			//echo $this->number;
			$data['zhi']=2;
			$data['number']=$this->number;
            return $data;
        }
        if($time>$start&&$time<$end){
			if($time>=$start_pay){
				if(empty($sell)){
				//echo $this->number;
				$data['zhi']=3;
				$data['number']=$this->number;
				return $data;

				}else{
					if(count($sell)==$i){
						//echo $this->number;echo 1;
						$data['zhi']=1;
						$data['number']=$this->number;
						return $data;
					}else{
						//echo $this->number;			
						$d=date('d');
						//$money=$sell[$i]['money'];
						$case=''.date('Y-m').'_pay';
						//switch ($state){
							//case '1':
								//echo $i;	
								$buy=M(''.$case.'')->where('state < 2 AND type ='.$t.' AND queue=0 AND money='.$sell[$i]['money'].' AND seed="'.$sell[$i]['seed'].'" AND num>0 AND user!="'.$sell[$i]['user'].'"')->limit(0,50)->order('submit_num asc')->select();
								//break;
							//case '0':
								
								//$buy=M(''.$case.'')->where('state < 2 AND type ='.$t.' AND queue=0 AND money='.$sell[$i]['money'].' AND seed="'.$sell[$i]['seed'].'" AND num>0 AND user!="'.$sell[$i]['user'].'"')->limit(0,50)->order('submit_num asc')->select();
								
								//break;
						//}
						//array_multisort(i_array_column($buy,'money'),SORT_DESC,$buy);
						$orderBy = array('money'=>'desc','submit_num'=>'asc');
						$buy = resultOrderBy($buy,$orderBy);
						//print_r($buy);
						if(empty($buy)){

							$i++;
						}else{
							//echo 1;print_r($sell);print_r($buy);die;
							$k=0;
							$zhi='2';
	//							S('status',1);
							$sell=$this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
	//							S('status',null);
							//$this->find($sell,$i);
							//echo time();
						}
						return $this->find_sell($number,$poundage,$sell,$i,$t,$state);
					}
				}
			}else{
				$data['zhi']=1;
				$data['number']=$this->number;
				return $data;
			}
        }

    }
	
    public function find_buy($number,$poundage,$buy,$k,$t,$state){
		//$number=0;
		//echo 2;
        //print_r($buy);
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $data_g=M('Global_conf')->where('cases="start_time" or cases="end_time"')->select();  //查询开盘时间和结束时间
        foreach ($data_g as $key=>$v){
            switch ($v['cases']) {
                case 'start_time':
                    $s_data=$data_g[$key]['value'];
                    break;
                case 'end_time':
                    $e_data=$data_g[$key]['value'];
                    break;
            }
        }
        $start= mktime($s_data,0,0,$m,$d,$y);//即是当天开盘的时间戳
        $end = mktime($e_data,0,0,$m,$d,$y);//即是当天收盘的时间戳
		$start_pay=$start+900;
        $time=time();
        if($time>$end||$time<$start){
			//echo -3;
			$data['zhi']=2;
			$data['number']=$this->number;
            return $data;
        }
        if($time>$start&&$time<$end){
			if($time>=$start_pay){
				if(empty($buy)){
				//echo $this->number;
				$data['zhi']=3;
				$data['number']=$this->number;
                return $data;
            }else{
                if(count($buy)==$k){
					//echo $this->number;
					$data['zhi']=1;
					$data['number']=$this->number;
                    return $data;
                }else{
					//echo $this->number;
                    $money=$buy[$k]['money'];
                    $d=date('d');
                    $case=''.date('Y-m').'_pay';
                    //switch ($state){//$state交易类型 1 计划任务 0 限价交易 游戏市场进入
                        //case '1':
                            
							$sell=M(''.$case.'')->where('state < 2 AND type ='.$t.' AND queue=0 AND money='.$buy[$k]['money'].' AND seed="'.$buy[$k]['seed'].'" AND num>0 AND user !="'.$buy[$k]['user'].'"')->limit(0,50)->order('submit_num asc')->select();
                            

                           // break;
                       // case '0':
							//$sell=M(''.$case.'')->where('state < 2 AND type ='.$t.' AND queue=0 AND num>0 AND money='.$buy[$k]['money'].' AND seed="'.$buy[$k]['seed'].'" AND user !="'.$buy[$k]['user'].'"')->limit(0,50)->order('submit_num asc')->select();
                           // break;
						//}
						//array_multisort(i_array_column($sell,'money'),SORT_ASC,$sell);
						//print_r($sell);die;
						$orderBy = array('money'=>'asc','submit_num'=>'asc');
						$sell = resultOrderBy($sell,$orderBy);
						//print_r($sell);echo 1;
						if(empty($sell)){
							$k++;
						}else{
							$i=0;
							$zhi='1';
	//							S('status',1);
							$buy=$this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
	//							S('status',null);
							//S('status',0);
						}
						return $this->find_buy($number,$poundage,$buy,$k,$t,$state);
					}
				}
			}else{
				$data['zhi']=1;
				$data['number']=$this->number;
				return $data;
			}
            
        }

    }
    public function Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number){
		 $mou=date('Y-m');
            $path='../log/matching/'.$mou.'';
            if (!file_exists($path)){
                mkdir($path,0777,true);
            }
            $day=date('Y-m-d');
        //生成表名
        $day_t=date('Y-m');
        $case=''.$day_t.'_pay';
        $cases=''.$day_t.'_matching';
        $pay=M(''.$case.'');
        $check_buy=$pay->where('id='.$buy[$k]['id'])->find();
		$check_sell=$pay->where('id='.$sell[$i]['id'])->find();
		$buy[$k]=$check_buy;
		$sell[$i]=$check_sell;
        switch ($zhi) {    //判断是以买方为主 还是卖方为主
            case '1':    //买方为主
                if(empty($sell[$i])){
                    return $buy;
                }
				if(empty($buy[$k])){
                    return $buy;
                }
                $dec['money']=$sell[$i]['money'];
				if($buy[$k]['money']!=$sell[$i]['money']){
					 //删除数组元素
                    unset($buy[$k]);
                    //重置索引，重新进入循环
                    $buy = array_values($buy);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    //return $buy;
				}
                if($buy[$k]['num']<=0){
                    //删除数组元素
                    unset($buy[$k]);
                    //重置索引，重新进入循环
                    $buy = array_values($buy);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    //return $buy;
                }
				break;
            case '2':   //卖方为主
			//echo 'bb';
                if(empty($buy[$k])){
                    //  echo 1;
                    return $sell;
                }
				if(empty($sell[$i])){
                    return $sell;
                }
				if($buy[$k]['money']!=$sell[$i]['money']){
					//删除数组元素
                    unset($sell[$i]);
                    //重置索引，重新进入循环
                    $sell = array_values($sell);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    //return $sell;
				}
                $dec['money']=$buy[$k]['money'];
                if($sell[$i]['num']<=0){
                    //删除数组元素
                    unset($sell[$i]);
                    //重置索引，重新进入循环
                    $sell = array_values($sell);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    //return $sell;
                }
				break;
        }
        //生成表头
        $num_sell=substr($sell[$i]['user'],0,3);
        $num_buy=substr($buy[$k]['user'],0,3);
        if($sell[$i]['num']>$buy[$k]['num']){   //判断交易数量   取双方最小值
            $dec['num']=$buy[$k]['num'];
            $state=1;
        }
		if($sell[$i]['num']<$buy[$k]['num']){
            $dec['num']=$sell[$i]['num'];
            $state=2;  //生成对应状态    大于取1   小于 取2  等于取3
        }
		if($sell[$i]['num']==$buy[$k]['num']){
			$dec['num']=$sell[$i]['num'];
			$state=3;
		}
        //事物开始
        $model=new Model();
        $model->startTrans();
		$start['queue']=1;
        $r['sell']=$pay->where('id='.$sell[$i]['id'])->save($start);     //开始队列
        $r['buy']=$pay->where('id='.$buy[$k]['id'])->save($start);
        //$data_s['state']=$state==3?2:1;
        //$check_buy=$pay->where('id='.$buy[$k]['id'])->find();
		//$check_sell=$pay->where('id='.$sell[$i]['id'])->find();
        $res['2']='1';
		switch($state){    //卖
			case '1':
				$data_s['state']=1;     //卖方数量多
				$data_b['state']=2;			//卖方数量多
				break;
			case '2':
				$data_s['state']=2;		//卖方数量少
				$data_b['state']=1;			//卖方数量少
				break;
			case '3':
				$data_s['state']=2;		//数量相当
				$data_b['state']=2;			//数量相当	
				break;
		}
        if($data_s['state']!=$check_sell['state']){
            if($pay->where('id='.$sell[$i]['id'])->save($data_s)!==false){
				$res['2']='1';
			}else{
				$res['2']='';
			}
        }
        $res['4']='1';
        if($data_b['state']!=$check_buy['state']){
            //$res['4']=$pay->where('id='.$buy[$k]['id'])->save($data_b);
			if($pay->where('id='.$buy[$k]['id'])->save($data_b)!==false){
				$res['4']='1';
			}else{
				$res['4']='';
			}
        }
        $res['1']='';
        if($check_sell['num']>=$dec['num']){
            $res['1']=$pay->where('id='.$sell[$i]['id'])->setDec('num',$dec['num']);
        }else{
			 $model->rollback();
			$start['queue']=2;
			$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
			unset($sell[$i]);
			//重置索引，重新进入循环
			$sell = array_values($sell);
			return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
			exit;
               
		}
		$res['3']='';
        if($check_buy['num']>=$dec['num']){
            $res['3']=$pay->where('id='.$buy[$k]['id'])->setDec('num',$dec['num']);
        }else{
			 $model->rollback();
			$start['queue']=2;
			//$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
			$r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
			unset($buy[$k]);
			//重置索引，重新进入循环
			$buy = array_values($buy);
			return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
			//break;
		}
        $data_new['sell_user']=$sell[$i]['user'];
        $data_new['buy_user']=$buy[$k]['user'];
        $data_new['poundage']=$dec['num']*$dec['money']*$poundage;
        $data_new['money']=$dec['money'];
        $data_new['time']=time();
		$this->number+=$dec['num'];
        $data_new['seed']=$sell[$i]['seed'];
        $data_new['num']=$dec['num'];
        $money=$dec['money']*$dec['num'];
        $total=$money;
        $data_new['total']=$money;
        $res['5']=M('total_station')->where('id=1')->setInc('income',$data_new['poundage']);
        $res['6']=M(''.$cases.'')->add($data_new);
        $case_m=''.$num_sell.'_members';
        $case_g=''.$num_sell.'_users_gold';
		
        //$res['7']=M(''.$case_m.'')->where('user='.$sell[$i]['user'])->setInc('coin',$total);
		if(M(''.$case_m.'')->where('user='.$sell[$i]['user'])->setInc('coin',$total)!==false){
			$res['7']=1;
		}else{
			$model->rollback();
				$start['queue']=2;
				$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
				
				unset($sell[$i]);
				//重置索引，重新进入循环
				$sell = array_values($sell);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
				
		}
        $data_gold=M(''.$case_g.'')->where('user='.$sell[$i]['user'])->find();
        if(!empty($data_gold)){            
			if(M(''.$case_g.'')->where('user='.$sell[$i]['user'])->setInc('buy_and_sell',$total)){
				$res['8']=1;
			}else{
				$model->rollback();
					$start['queue']=2;
					$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
					
					unset($sell[$i]);
					//重置索引，重新进入循环
					$sell = array_values($sell);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
			}
        }else{
            $data_member=M(''.$case_m.'')->field('num_id')->where('user='.$sell[$i]['user'])->find();
            $save_gold['user']=$sell[$i]['user'];
            $save_gold['num_id']=$data_member['num_id'];
            $save_gold['buy_and_sell']=$total;
			$save_gold['user_coin']=0;
			$save_gold['user_fees']=0;
			$save_gold['user_top_up']=0;
            //$res['8']=M(''.$case_g.'')->add($save_gold);
			if(M(''.$case_g.'')->add($save_gold)){
				$res['8']=1;
			}else{
				$model->rollback();
					$start['queue']=2;
					$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
					unset($sell[$i]);
					//重置索引，重新进入循环
					$sell = array_values($sell);
					return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);					
			}            
        }      
        $case_f=''.$num_sell.'_fruit_record';
        $fruit_check=M(''.$case_f.'')->where('user='.$sell[$i]['user'].' AND seed ="'.$data_new['seed'].'" AND money='.$sell[$i]['money'])->find();        
        if ($fruit_check['num']<$data_new['num']){
            $model->rollback();
			$start['queue']=2;
			$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
			unset($sell[$i]);
			//重置索引，重新进入循环
			$sell = array_values($sell);
			return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
        }else if($fruit_check['num']==$data_new['num']){
            if(M(''.$case_f.'')->where('user='.$sell[$i]['user'].' AND seed ="'.$data_new['seed'].'" AND money='.$sell[$i]['money'])->setDec('num',$data_new['num']) ==false){
                $model->rollback();
				$start['queue']=2;
				$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
				unset($sell[$i]);
				//重置索引，重新进入循环
				$sell = array_values($sell);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
            }
        }else{
            //$str='12';
            //file_put_contents('../log/log.log',$str.PHP_EOL."\n",FILE_APPEND);
            if(M(''.$case_f.'')->where('user='.$sell[$i]['user'].' AND seed ="'.$data_new['seed'].'" AND money='.$sell[$i]['money'])->setDec('num',$data_new['num']) ==false){
                $model->rollback();
				$start['queue']=2;
				$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
				unset($sell[$i]);
				//重置索引，重新进入循环
				$sell = array_values($sell);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);              
            }
        }
        $case_m_b=''.$num_buy.'_members';
        $case_m_s=''.$num_sell.'_members';
        $coin_check_b=M(''.$case_m_b.'')->where('user='.$buy[$k]['user'])->find();
        $coin_check_s=M(''.$case_m_s.'')->where('user='.$sell[$i]['user'])->find();
        $coinf_save['coin_freeze']=$coin_check_s['coin_freeze']-$data_new['poundage'];
        $coin_save['coin_freeze']=$coin_check_b['coin_freeze']-$money;
        if(M(''.$case_m_b.'')->where('user='.$buy[$k]['user'])->save($coin_save)!==false){
			$res['9']=1;
		}else{
			$model->rollback();
			$start['queue']=2;
			$r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
			unset($buy[$k]);
			//重置索引，重新进入循环
			$buy = array_values($buy);
			return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
		}
        //echo M(''.$case_m_b.'')->getLastSql();
        if(M(''.$case_m_s.'')->where('user='.$sell[$i]['user'])->save($coinf_save)!==false){
			$res['9']=1;
		}else{
			$model->rollback();
				$start['queue']=2;
				$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
				unset($sell[$i]);
				//重置索引，重新进入循环
				$sell = array_values($sell);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
		}
        $case_s_b=''.$num_buy.'_seed_warehouse';
        $data=M(''.$case_s_b.'')->where('user ='.$buy[$k]['user'].' AND seeds ="'.$data_new['seed'].'"')->find();
        if(!$data){
            $data_s_b['seeds']=$data_new['seed'];
            $data_s_b['user']=$buy[$k]['user'];
            $data_s_b['num']=$data_new['num'];
            if(M(''.$case_s_b.'')->add($data_s_b)==false){
                $model->rollback();
				$start['queue']=2;
				$r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
				unset($buy[$k]);
				//重置索引，重新进入循环
				$buy = array_values($buy);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
            }
        }else{
            if(M(''.$case_s_b.'')->where('user ='.$buy[$k]['user'].' AND seeds ="'.$data_new['seed'].'"')->setInc('num',$data_new['num'])==false){
                $model->rollback();
				$start['queue']=2;
				$r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
				unset($buy[$k]);
				//重置索引，重新进入循环
				$buy = array_values($buy);
				return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
            }
        }
        $case_m_r=''.$num_buy.'_member_record';
        $res['11']=M(''.$case_m_r.'')->where('user='.$buy[$k]['user'])->setInc('order_number',1);
        $case_m_r=''.$num_sell.'_member_record';
        $case_m=''.$num_sell.'_members';
        $data=M($case_m)->where('user="'.$sell[$i]['user'].'"')->find();
        $lv=$data['level'];
        $data_cost=M('cost')->where('level="'.$lv.'"')->find();
        $data_record=M($case_m_r)->where('user="'.$sell[$i]['user'].'"')->find();
        if($data_cost['cost']<=$data_record['income']){
            $data_s['cost_state']=1;
        }else{
            $data_s['cost_state']=0;
        }
        $case_m_s=''.$num_sell.'_member_record';
        if($data['cost_state']==$data_s['cost_state']){
            $res['12']='1';
        }else{
            if(M($case_m)->where('user='.$sell[$i]['user'])->save($data_s)!==false){
				$res['12']='1';
			}else{
				$res['12']='';
			}
        }
        $res['13']=M(''.$case_m_s.'')->where('user='.$sell[$i]['user'])->setInc('order_number',1);
        $res['14']=M(''.$case_m_r.'')->where('user='.$sell[$i]['user'])->setInc('income',$total);
        //echo 1;
        $res=array_filter($res);
        if(count($res)==13){
			$str='7';
        file_put_contents('../log/log.log',$str.PHP_EOL."\n",FILE_APPEND);
            $model->commit();
            $start['queue']=0;
            $r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
            $r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
            //print_r($res);echo 1 ;echo '<br/>';
            $mou=date('Y-m');
            $path='../log/matching/'.$mou.'';
            if (!file_exists($path)){
                mkdir($path,0777,true);
            }
            $day=date('Y-m-d');
            $str='进行：买入用户:'.$buy[$k]['user'].'卖出用户;'.$sell[$i]['user'].';果实:'.$data_new['seed'].'交易数量：'.$data_new['num'].'交易单价:'.$data_new['money'].'交易总额:'.$data_new['total'].'手续费:'.$data_new['poundage'].'交易时间：'.$data_new['time'].'';
            file_put_contents('../log/matching/'.$mou.'/'.$day.'matching.log',$str.PHP_EOL."\n",FILE_APPEND);
			$str=json_encode($res);
			file_put_contents('../log/matching/'.$mou.'/'.$day.'matching.log',$str.PHP_EOL."\n",FILE_APPEND);
            $deal=new Deal();
            $btel=$sell[$i]['user'];
            $sum=$money;
            $result=$deal->deal($btel,$sum);
            //统计业绩
            $archive=new archive();
            $tel=$sell[$i]['user'];
            $type=3;
            $num=$money;
            $archive->store($tel,$type,$num);
            switch ($state) {
                case '1':
                    switch ($zhi) {
                        case '1':
                            unset($buy[$k]);
                            //重置索引，重新进入循环
                            $buy = array_values($buy);
                            return $buy;
                            break;
                        case '2':
                            $sell[$i]['num']=$sell[$i]['num']-$buy[$k]['num'];
                            $k++;
                            return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                            break;
                    }
                    break;
                case '3':
                    switch ($zhi) {
                        case '1':
                            unset($buy[$k]);
                            //重置索引，重新进入循环
                            $buy = array_values($buy);
							return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                            //return $buy;
                            break;
                        case '2':
                            unset($sell[$i]);
                            //重置索引，重新进入循环
                            $sell = array_values($sell);
							return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                            //return $sell;
                            break;
                    }
                    break;
                case '2':
                    switch ($zhi) {
                        case '1':
                            $buy[$k]['num']=$buy[$k]['num']-$sell[$i]['num'];
                            $i++;
                            return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                            break;
                        case '2':
                            unset($sell[$i]);
                            //重置索引，重新进入循环
                            $sell = array_values($sell);
							return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                            //return $sell;
                            break;
                    }
                    break;
            }
        }else{
            $model->rollback();
			$start['queue']=2;
			$r['14']=$pay->where('id='.$sell[$i]['id'])->save($start);
			$r['15']=$pay->where('id='.$buy[$k]['id'])->save($start);
            switch ($zhi) {
                case '1':
                    unset($sell[$i]);
                    //重置索引，重新进入循环
                    $sell = array_values($sell);
                    return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    break;
                case '2':
                    unset($buy[$k]);
                    //重置索引，重新进入循环
                    $buy = array_values($buy);
                    return $this->Matchin($poundage,$sell,$buy,$k,$i,$zhi,$number);
                    break;
            }
        }
    }
}
