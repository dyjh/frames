<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Tool;
use Org\Our\Disasters;
use Org\Our\Matching;
use Org\Our\Automaticservice;
use Org\Our\Adxyl;



class PlanController extends Controller{
	
      /******计划任务相关操作******/

	  /*************************/
	  /*********用户类*********/
	  /************************/
	   
	   
	   public function chaxun(){
		   $member=M('team_relationship')->field('user')->select();
		   $data=array();
		   //echo count($member);die;
		   $k=0;
		   $p=0;
		   foreach($member as $key=>$val){
			   $case=''.substr($val['user'],0,3).'_users_gold';
			   //echo $case;
			   $data_money=M($case)->where('user='.$val['user'].'')->find();
			   $money_1=($data_money['user_fees']+$data_money['buy_and_sell']);
			   if($money_1>100){
				   echo $money_1;echo '<br/>';
			   $money_2=$money_1/100;
			   echo $money_2;echo '<br/>';
			   $k=$k+intval($money_2);
			   
			   $p++;
			   }
			   
		   }
		   echo $k*100;echo '<br/>';
		   echo $p;
	   }
	  public function sedrmei(){
		 
	    file_put_contents('./Log/sedrmei.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND); 
		$shop = M('shop');
		$res = $shop->where('id=6')->find();
		if($res['num'] <= 1000 && $res['num'] == 0){
			//$arr['note'] = '开仓时间 09:30';
			$shop->where('id=6')->setInc('price',10);
			$shop->where('id=6')->setInc('num',1000);
			$shop->where('id=6')->setInc('frequency');
			//$shop->where('id=6')->save($arr);
			$shop->where('id=6')->setField('note','开仓时间 09:30');
			M('global_conf')->where('id=21')->setField('value','34200');
		}
    }
	/***统计每天登陆人数***/
	public function me_num(){
		$y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        $end= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳
		$start=mktime(0,0,0,$m,$d,$y)-86400;
		$p=0;
		$str=M('statistical')->select();
		foreach($str as $key=>$val){
			$case=''.$val['name'].'_members';
			$count_s=M($case)->where('login_time>='.$start.' AND login_time<='.$end.'')->count();
			$p=$p+$count_s;
		}
		$add['time']=$start;
		$add['num']=$p;
		M('me_num_record')->add($add);
	}
	/**重置定向数量**/
	public function directional(){
		$time=time();
		$data=M('seed_orientation')->where('start_time<='.$time.' AND end_time>'.$time.' AND type=1')->select();
		foreach($data as $key=>$val){
			//$save['count']=$val['count']+($val['imm_num']-$val['num']);
			$save['num']=$val['imm_num'];
			M('seed_orientation')->where('id='.$val['id'].'')->save($save);
		}
		//$del=M('seed_orientation')->where('end_time<='.$time.' AND type=1')->delete();
	}
	/**设置开盘价   Dr.耗子**/
	public function kaipj(){
	    file_put_contents('./Log/kaipj.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		$y = date("Y");
		//获取当天的月份
		$m = date("m");
		//获取当天的号数
		$d = date("d");
		//print_r($m);die;
		//$tm =date("d")+1;
		$s_data=M('Global_conf')->where('cases="start_time"')->find();
		$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
		$data_seed=M('Seeds')->field('open_price,varieties')->where('varieties !="'.$name.'" AND state=0')->select();
		foreach($data_seed as $key=>$val){
			if($val['open_price']==0){
				$money=M('Pay_statistical')->where('seed ="'.$val['varieties'].'"')->field('end_money')->order('time DESC')->select();
				$data['money']=$money[0]['end_money'];
			}else{
				$data['money']=$val['open_price'];
			}
			$data['state']=1;
			$data['num']=0;
			$data['seed']=$val['varieties'];
			$data['sell_user']=00000;
			$data['buy_user']=00000;
			$data['total']=0;
			$data['poundage']=0;
			$data['time']=$start+1;
			$case_m=''.date('Y-m').'_matching';
			//print_r($data);
			if(M($case_m)->add($data)){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
  
    //管家任务  第隔1小时一次
    public function housekeeper(){

	       file_put_contents('./Log/managed.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
	  
           $table = '_managed_to_record';
           $time = time();

           $Statistical = M('statistical');
           $Prefix = $Statistical->field('name')->select();
           if($Prefix){
                for($i=0;$i<count($Prefix);$i++){
                     $paln_table = $Prefix[$i]['name'].$table;
                     $user_managed = M("$paln_table");
                     $data['state'] = 1;
                     $res = $user_managed->where('end_time<="'.$time.'"')->save($data);
                     if($res){
                          echo '成功';
                     }else{
                          echo '失败';
                     }
                }
				
           }
      }

      //植物生成   每隔5分钟
      public function Plants_generate(){
		  
		    file_put_contents('./Log/generate.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		  
            $seeds = M('seeds');
            $Statistical = M('statistical');
            $Prefix = $Statistical->field('name')->select();
            $table = '_planting_record';
            $varieties = $seeds->field('varieties,first_time,second_time,third_time')->where('varieties!="种子"')->select();
			//var_dump($varieties);
            if($Prefix && $varieties){
				 $Prefix_count = count($Prefix);	
                 //循环表
                 for($i=0;$i<$Prefix_count;$i++){
                      $paln_table = $Prefix[$i]['name'].$table;
                      $user_managed = M("$paln_table");
                      $varieties_count = count($varieties);
                      //循环种子种类信息
                      for($j=0;$j<$varieties_count;$j++){
                           //查出该表所有对应的种子
                           $table_seeds = $user_managed->where('seed_type="'.$varieties[$j]['varieties'].'" and seed_state<3')->select();
                           //循环查询结果
						   $table_seeds_count = count($table_seeds);
                           for($k=0;$k<$table_seeds_count;$k++){
                                //分析状态(三种)
                                switch ($table_seeds[$k]['seed_state']) {
                                    case 0:
                                        //判断时间发芽阶段时间
                                        if($table_seeds[$k]['time']<=time()-$varieties[$j]['first_time']){
                                             //修改状态与时间
											 //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('time',$varieties[$j]['first_time']);				 
											 $arr['time'] = $table_seeds[$k]['harvest_time']-$varieties[$j]['third_time']-$varieties[$j]['second_time']; 
											 $arr['seed_state'] = 1;
											 $user_managed->where(array('id'=>$table_seeds[$k]['id']))->save($arr);  
                                             //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('seed_state',1);
                                        }else{
                                             continue;
                                        }
                                        break;
                                     case 1:
                                         //判断时间成株阶段时间
                                         if($table_seeds[$k]['time']<=time()-$varieties[$j]['second_time']){
                                              //修改状态与时间
											  //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('time',$varieties[$j]['second_time']);
											  $arr['time'] = $table_seeds[$k]['harvest_time']-$varieties[$j]['third_time']; 
                                              $arr['seed_state'] = 2;											  
                                              $user_managed->where(array('id'=>$table_seeds[$k]['id']))->save($arr);  
                                              //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('seed_state',1);
                                         }else{
                                              continue;
                                         }
                                         break;
                                      case 2:
                                          //判断时间成熟阶段时间
                                          if($table_seeds[$k]['time']<=time()-$varieties[$j]['third_time']){
											   //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('time',$varieties[$j]['third_time']);
                                               //修改状态与时间
											   $arr['time'] = $table_seeds[$k]['harvest_time'];
											   $arr['seed_state'] = 3;
                                               $user_managed->where(array('id'=>$table_seeds[$k]['id']))->save($arr);  
                                               //$user_managed->where(array('id'=>$table_seeds[$k]['id']))->setInc('seed_state',1);
                                          }else{
                                               continue;
                                          }
                                          break;
                                 }
                           }
                      }
                 }
            }
      }

      //灾难生成（分为回本与未回本用户）
      public function Disaster_generate(){

	     file_put_contents('./Log/disaster.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
	  
         $Disasters = New Disasters();
		 $k=0;
		 $zhi=0;
         if($_GET['type']=="new"){
             $Disasters->disaster_new($k,$zhi);
         }else if($_GET['type']=="old"){
             $Disasters->disaster_old($k,$zhi);
         }
      }
	  //清空灾难上限   每天11:30运行
		public function clear_disaster(){
			file_put_contents('./Log/clear_disaster.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
			$statistical=M('Statistical');
			$data_s=$statistical->select();
            foreach ($data_s as $k=> $v){
				$case=''.$v['name'].'_members';
                $member=M($case);
				$save['disasters_num']=0;
				 $data_me=$member->where('disasters_num>0')->save($save);
			}
		}
      //签到记录  每晚12点
      public function signs(){
		  
		 file_put_contents('./Log/signs.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);

		 $statistical = M('statistical');
		 $table_fix = $statistical->field('name')->select();
		 for($i=0;$i<count($table_fix);$i++){
				$table = $table_fix[$i]['name'].'_members';
				//$data['sign_state'] = 0;
				$res = M("$table")->where('sign_state=1')->setDec('sign_state',1);
				if($res){
					echo '成功';
				}else{
					echo '失败';
				}
		 }			 
      }
	  public function error_re(){
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
		$buy=M($case)->where('state<3 AND type = 1')->select();
	  }
	  //每天24点运行   清除昨日开箱数据
	  public function del_list(){
		  
		 file_put_contents('./Log/dellist.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND); 
		  
		 $time=0;
		 M('record_treasure')->where('time="'.$time.'"')->delete();
		 $data['time']=0;
		 M('record_treasure')->where('time= 1')->save($data);
	  }
    
	
	
	/*************************/
	/*********交易类*********/
	/************************/
	
	//自动匹配   在开盘时间内运行   3秒执行  卖 
    public function Auto(){

	        file_put_contents('./Log/sellmatching.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
			$matching=new Matching();
			$data_sell=$matching->time_sell_auto();		      
    }
	
	public function Check_auto(){

	        file_put_contents('./Log/sellmatching.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
			$matching=new Matching();
			$data_sell=$matching->check_auto();		      
    }
	
	public function Auto_buy(){//2秒执行， 买
        //查询买单去匹配卖单
            file_put_contents('./Log/buymatching.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
			$matching=new Matching();
            $data_buy=$matching->time_buy_auto();
	}
	
	
	//交易表自动建表以及统计总数据     每天12点运行
    public function Add(){
		
		file_put_contents('./Log/addtable.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		
        $BeginDate=date('Y-m-01 0:00:00', strtotime(date("Y-m-d")));
        $last_begin=strtotime(date('Y-m-d 0:00:00', strtotime("$BeginDate +1 month -1 day")));
		
		//$last_begin=$last_begin-24*3600;
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        $t_m=''.$y.'-'.$m.'_matching';
		$end=mktime(0,0,0,$m,02,$y);
		$time=time();
        $start= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳
        //if($start>=$last_begin){
		if($time>=$last_begin){
			//echo 1;die;
            $begin=$last_begin+24*3600;
            $begin=date('Y-m',$begin);
            //print_r($begin);
            $Model =  M();
            $Model->execute('
                            CREATE TABLE `'.$begin.'_pay` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `submit_num` int(10) unsigned NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `money` double(20,5) unsigned NOT NULL,
                              `time` int(11) unsigned NOT NULL,
                              `state` int(2) unsigned NOT NULL DEFAULT \'0\',
                              `seed` char(255) NOT NULL,
                              `type` int(10) unsigned NOT NULL COMMENT \'买入1卖出0\',
                              `trans_type` int(10) unsigned NOT NULL COMMENT \'委托0市价1\',
                              `system` int(10) unsigned NOT NULL DEFAULT \'0\',
							  `queue` int(10) unsigned NOT NULL DEFAULT \'0\',
							  `queue_s` int(10) unsigned NOT NULL DEFAULT \'0\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;');
							
	         //添加索引
             $Model->execute('
			                 ALTER TABLE `'.$begin.'_pay`
							 ADD INDEX `user` (`user`) USING BTREE ,
							 ADD INDEX `state` (`state`) USING BTREE ,
							 ADD INDEX `time` (`time`) USING BTREE ,
							 ADD INDEX `money` (`money`) USING BTREE ;
							;');			 
                            
             $Model->execute('               
                            CREATE TABLE `'.$begin.'_rebate_record` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
							  `user` varchar(11) NOT NULL,
							  `money` double(20,5) unsigned NOT NULL,
							  `source` varchar(11) NOT NULL,
							  `time` int(20) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
                            
             $Model->execute('               
                            CREATE TABLE `'.$begin.'_matching` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `sell_user` char(255) NOT NULL,
							  `num` int(10) unsigned NOT NULL,
							  `money` double(20,5) unsigned NOT NULL,
							  `time` int(11) unsigned NOT NULL,
							  `seed` char(255) NOT NULL,
							  `poundage` double(20,5) unsigned NOT NULL,
							  `buy_user` char(255) NOT NULL,
							  `total` double(20,5) unsigned NOT NULL,
							  `state` int(10) unsigned NOT NULL DEFAULT \'0\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
            ');

            $time_record=M('Time_record');
            $data_find=$time_record->select();
            if(empty($data_find)){

            }else{
                $data_seed=M('Seeds')->select();
                foreach ($data_seed as $k=>$v){
                    $total=M('Fruit_record')->where('seed='.$v['varieties'])->find();
                    $data['seed']=$v['varieties'];
                    $data['money']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('total');
                    $data['poundage']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('poundage');
                    $data['num']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('num');
                    if(empty($total)){
                        if(M('Fruit_record')->add($data)){

                        }
                    }else{
                        if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('num',$data['num'])){
                            if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('money',$data['money'])){
                                if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('poundage',$data['poundage'])){

                                }
                            }
                        }
                    }
                }
            }
            $data['time']=date('Y-m-d');
			$s['name']=$begin;
			M('matching_statistical')->add($s);
            if($time_record->add($data)){

            }
        }
    }
	
	//统计每天K线数据      收盘后运行    测试成功
    public function times(){
		
		file_put_contents('./Log/kline.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		
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
		$case_m=''.date('Y-m').'_matching';
		//echo $end;echo '<br/>';
		//echo $time;die;
        if($time>$end){
			$name='摇钱树';
            $data_fruit=M('Seeds')->where('varieties !="'.$name.'" AND state=0')->field('varieties,open_price,first_price')->select();
			
            foreach ($data_fruit as $k=>$v){
				//echo 1;
                $data_c=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->select();
				
                if($data_c){
					echo 1;
                    $count=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->sum('num');
                    $data['num']=$count;
                    $data_min=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->order('money')->select();
                    $data['min_money']=$data_min[0]['money'];
                    $data['max_money']=$data_min[count($data_min)-1]['money'];
                    //print_r($max_money);
                    $data_max=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->order('time')->select();
                    $data['start_money']=$data_max[0]['money'];
                    $data['end_money']=$data_max[count($data_min)-1]['money'];
                    $data['seed']=$v['varieties'];
                    $data['time']=$start;
                    M('Pay_statistical')->add($data);
                }else{	
				
					if($v['open_price']!=0){
						$today['new']=$v['open_price'];
					}else{
						$money=M('Pay_statistical')->where('seed="'.$v['varieties'].'"')->order('time DESC')->select();
						if(!$money){				  
						   $today['new']=$v['first_price'];
						}else{
						   $today['new']=$money[0]['end_money'];
						}
					}
					$data['seed']=$v['varieties'];
					$data['time']=$start;
					$data['start_money']=$today['new'];
					$data['end_money']=$today['new'];
					$data['max_money']=$today['new'];
					$data['min_money']=$today['new'];
					$data['num']=0;
					//print_r($data);
					M('Pay_statistical')->add($data);
				}
            }
			echo 1;
        }else{
            echo 3;
        }
    }
		
	 //自动撤销交易   收盘时运行    测试成功
     public function Auto_return(){
		
		file_put_contents('./Log/return.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		
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

        $case_p=''.date('Y-m').'_pay';
        //实时
		$data=M(''.$case_p.'')->where(' state < 2 AND type=1 AND time <= " '.$end.'"')->order('money')->select();
		//print_r($data);die;
        //$data=M(''.$case_p.'')->where(' state = 3 AND time <= 1502892000 AND time >=1502805600')->order('money')->select();
		//print_r($data);die;
        foreach ($data as $k=>$v){
            $data_p['system']=1;
            $data_p['state']=3;
            $model=new Model;
            $model->startTrans();
            if(M(''.$case_p.'')->where('id='.$v['id'])->save($data_p)){
                $money=$v['num']*$v['money'];
                $table=new Tool();
                $case='members';
                $tel=$v['user'];
                //$tel=13688804;
                $case_m=$table->table($tel,$case);
				$coin_old=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
                if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
                    if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
						$data_c=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
						$coin_record['coin']=$data_c['coin'];
						$coin_record['coin_freeze']=$data_c['coin_freeze'];
						$coin_record['time']=time();
						$coin_record['add']=$money;
						$coin_record['user']=$tel;
						$coin_record['coin_old']=$coin_old['coin'];
						$coin_record['coin_freeze_old']=$coin_old['coin_freeze'];
						M('coin_record')->add($coin_record);
                        $model->commit();
						echo 1;
                    }else{
                        $model->rollback();
						echo $v['id'];echo $tel;echo '<br/>';
						echo 2;
                    }
                }else{
                    $model->rollback();
					echo $v['id'];echo $tel;echo '<br/>';
					echo 3;
                }
            }else{
                $model->rollback();
				echo 4;
            }
        }
		$conf=M('Global_conf')->where('cases="poundage"')->find();
		$poundage=$conf['value'];
        $data=M(''.$case_p.'')->where('state < 2 AND type=0 AND time <= " '.$end.'"')->order('money')->select();
        foreach ($data as $k=>$v){
            $money=$v['num']*$v['money']*$poundage;
            $data_p['system']=1;
            $data_p['state']=3;
            $model=new Model;
            $model->startTrans();
            if(M(''.$case_p.'')->where('id='.$v['id'])->save($data_p)){
                $table=new Tool();
                $case='fruit_record';
                $tel=$v['user'];
                $case_f=$table->table($tel,$case);
                $data=M(''.$case_f.'')->where('user='.$tel.' AND seed="'.$v['seed'].'" AND money='.$v['money'])->find();
               // if($data['num']==$v['num']){
				   
                    if(M(''.$case_f.'')->where('user='.$tel.' AND seed="'.$v['seed'].'" AND money='.$v['money'])->setDec('num',$v['num'])){
                        $num=substr($v['user'],0,3);
						if($v['seed']=='种子'){
							   $ca_seed=''.$num.'_prop_warehouse';
							   $sql='user ='.$tel.' AND props ="'.$v['seed'].'"';
							   $seed_data['prop_id']=6;
							   $seed_data['props']=$v['seed'];
						   }else{
							   $ca_seed=''.$num.'_seed_warehouse';
							   $sql='user='.$tel.' AND seeds="'.$v['seed'].'"';
							   $seed_data['seeds']=$v['seed'];
						   }	
						$data_seed=M($ca_seed)->where($sql)->find();
						if(empty($data_seed)){
							$seed_save['user']=$tel;
							$seed_data['num']=$v['num'];
							if(M($ca_seed)->add($seed_data)){
								$table=new Tool();
								$case='members';
								$tel=$v['user'];
								//$tel=13688804;
								$case_m=$table->table($tel,$case);
								$coin_old=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
								if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
									if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
										$data_c=M(''.$case_m.'')->field('coin,coin_freeze')->where('user='.$tel)->find();
										$coin_record['coin']=$data_c['coin'];
										$coin_record['coin_freeze']=$data_c['coin_freeze'];
										$coin_record['time']=time();
										$coin_record['add']=$money;
										$coin_record['user']=$tel;
										$coin_record['coin_old']=$coin_old['coin'];
										$coin_record['coin_freeze_old']=$coin_old['coin_freeze'];
										M('coin_record')->add($coin_record);
										$model->commit();
										echo 5.1;
									}else{
										$model->rollback();
										echo $v['id'];echo $tel;echo '<br/>';
										echo 5.2;
									}
								}else{
									$model->rollback();
									echo $v['id'];echo $tel;echo '<br/>';
									echo 5.3;
								}
							}else{
								$model->rollback();
								echo 5.5;
							}
						}else{
							if(M($ca_seed)->where($sql)->setInc('num',$v['num'])){
								$table=new Tool();
								$case='members';
								$tel=$v['user'];
								//$tel=13688804;
								$case_m=$table->table($tel,$case);
								if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
									if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
										$model->commit();
										echo 5.1;
									}else{
										$model->rollback();
										echo $v['id'];echo $tel;echo '<br/>';
										echo 5.2;
									}
								}else{
									$model->rollback();
									echo $v['id'];echo $tel;echo '<br/>';
									echo 5.3;
								}
								
							}else{
								$model->rollback();
								echo 5.5;
							}
						}
                    }else{
                        $model->rollback();
						echo 6;
                    }
            }else{
                $model->rollback();
				echo 9;
            }
        }
//		$data_s=M('statistical')->select();
//		foreach($data_s as $key=>$val){
//			$case=''.$val['name'].'_members';
//			$member=M($case)->where('coin_freeze > 0')->select();
//			$count=M($case)->where('coin_freeze > 0')->count();
//			for($i=0;$i<=$count;$i++){
//				$user=$member[$i]['user'];
//				$save_m['coin_freeze']=0;
//				$save_m['coin']=$member[$i]['coin']+$member[$i]['coin_freeze'];
//				if(!empty($user)){
////					M($case)->where('user='.$user)->save($save_m);
//                    M($case)->where('user='.$user)->setInc('coin',$member[$i]['coin_freeze']);
//                    M($case)->where('user='.$user)->setDec('coin_freeze',$member[$i]['coin_freeze']);
//					$da_s=M($case)->getLastsql();
//					file_put_contents('./Log/dyjhlog.log',$da_s.PHP_EOL."\n",FILE_APPEND);
//				}
//			}
//		}
    }
    public function clear_coin(){
        $data_s=M('statistical')->select();
        foreach($data_s as $key=>$val){
            $case=''.$val['name'].'_members';
            $member=M($case)->where('coin_freeze > 0')->select();
            $count=M($case)->where('coin_freeze > 0')->count();
            for($i=0;$i<=$count;$i++){
                $user=$member[$i]['user'];
                $save_m['coin_freeze']=0;
                $save_m['coin']=$member[$i]['coin']+$member[$i]['coin_freeze'];
                if(!empty($user)){
//					M($case)->where('user='.$user)->save($save_m);
                    M($case)->where('user='.$user)->setInc('coin',$member[$i]['coin_freeze']);
                    M($case)->where('user='.$user)->setDec('coin_freeze',$member[$i]['coin_freeze']);
                    $da_s=M($case)->getLastsql();
                    file_put_contents('./Log/coinlog.log',$da_s.PHP_EOL."\n",FILE_APPEND);
                }
            }
        }
	}
	//开盘价
	public function seedprice_clear(){
		
		file_put_contents('./Log/seedprice.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);

		$data['open_price'] = 0;
		$seed_data = M('seeds')->select();
		for($i=0;$i<count($seed_data);$i++){
			M('seeds')->where(array('id'=>$seed_data[$i]['id']))->save($data);
		}
	}
	
	public function coin_clear(){
		
		file_put_contents('./Log/coinclear.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		
		$Model = M();
        $Model->execute('truncate table coin_record');
	}
	
	//清除无用冻结数据
	public function fruit_clear(){
		
		file_put_contents('./Log/fruitclear.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		$str=M('statistical')->select();
		foreach($str as $key=>$val){
			$case=''.$val['name'].'_fruit_record';
			$data=M($case)->where('num>0')->select();
			if(empty($data)){
				$Model = M();
				$Model->execute('truncate table '.$case.'');
			}else{
				
				echo $case;
			}
			//echo $case;
			
		}
	}
	
	public function maticservice(){	
		file_put_contents('./Log/maticservice.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		Automaticservice::trusteeship();  
	}
	
	//自动储存每日统计数据
	public function statistical_data(){
		$Admin = New Adxyl();
		M()->startTrans();
		$res = $Admin->rstj(1);
		$res['time'] = time();
		$res['type'] = 2;
		$ress = $Admin->rstj(2);
		$ress['time'] = time();
		$ress['type'] = 1;
		$ret = $Admin->rstj(3);
		$ret['time'] = time();
		if(M('user_bhy')->add($ress)){
			if(M('user_bhy')->add($res)){
				if(M('seeds_record')->add($ret)){
					M()->commit();
				}
			}else{
				echo 2;
				M()->rollback();
			}
		}else{
			echo 1;
			M()->rollback();
			}
	}
	
}
?>
