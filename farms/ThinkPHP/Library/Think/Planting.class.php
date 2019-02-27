<?php
namespace Think;
use Think\Autoadd;
use Think\Tool;

class planting{

			 private $user;

			 function __construct($user){
					 $this->user = $user;
           $this->Select_Level($this->user);
			 }

       //查询用户等级
			 private function Select_Level($user){

				  $Tool = New Tool;
					$user_table = $Tool->table($user,'members');
          $members = M("$user_table");
					$res = $members->field('level')->where(array('user'=>$user))->select();
					if($res){
               //判断用户是否可以种植(空地，种子)
               $this->clearing($user,$res[0]['level']);
					}else{
							 echo '该用户不存在';
					}
			}

      //是否有空地，即等级大于当前正在种植的数量,是否有种子，够不够播种
			private function clearing($user,$level){
				    $Tool = New Tool;
            $user_planting_record = $Tool->table($user,'planting_record');
            $planting_record = M("$user_planting_record");
						$count = $planting_record->where('user="'.$user.'" and harvest_state=0')->count();
						if($level>$count){
                $user_seed_warehouse = $Tool->table($user,'seed_warehouse');
								//获取用户种子数量
								$seed_warehouse = M("$user_seed_warehouse");
								$user_seeds_count = $seed_warehouse->field('num')->where(array('user'=>$user))->select();
                //获取播种种子比例
								$conf = M('global_conf');
								$harvest_probability = $conf->where(array('cases'=>'planting_proportion'))->select();

                if($user_seeds_count[0]['num']>=$harvest_probability[0]['value']){
									   $this->Probability($user,$level);
								}else{
									  echo '种子数量不够，只有'.$user_seeds_count[0]['num'];
								}
						}else{
							  echo '你只有'.$level.'块地,不能再种了';
						}
			}

			//等级对应种植类型
			private function Probability($user,$level){
          $seed_level = M('seed_level');
					$res = $seed_level->field('seed_level')->where(array('level'=>$level))->select();
					if($res){
              //概率处理
							$Sow_allow = explode('|',$res[0]['seed_level']);
							for($i=0;$i<count($Sow_allow);$i++){
								   $Sow_allow[$i] = explode(',',$Sow_allow[$i]);
							}

              $Sow_probability = array();
							for($j=0;$j<count($Sow_allow);$j++){
								   for($k=0;$k<$Sow_allow[$j][1];$k++){
										    array_push($Sow_probability,$Sow_allow[$j][0]);
									 }
							}
              //生成种子
							shuffle($Sow_probability);

              //判断级别，6级以上要特殊处理
              if($level>=6){
								  //六级以上处理
									$this->Special($user,$level,$Sow_probability,0);
							}else{
								  //计算生成
							    $this->Harvest($user,$Sow_probability[0]);
							}
					}else{
						  echo '该等级不存在';
					}
			}


			//六级以上处理
			private function Special($user,$level,$Sow_probability,$pointer){
            $Tool = New Tool;
						//获取当天时间零点，24点
						$time = time();
						$time = $Tool->time($time);
						//获取数量机率最多，也是需要做限制的种子
						$seed_most = $Tool->seed_most($Sow_probability,$level);
						//获取用户种植表
						$user_planting_record = $Tool->table($user,'planting_record');
				    $planting_record = M("$user_planting_record");
            if($level==6){
							    //六级
                  if($Sow_probability[$pointer]==$seed_most[0]){
                       $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
											 if($count<2){
												    //计算收成
														$this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
												    $pointer++;
												    return $this->Special($user,$level,$Sow_probability,$pointer);
											 }
								 }else{
									  //计算收成
									  $this->Harvest($user,$Sow_probability[$pointer]);
								 }
						}else if($level==7){
							   //七级
                 if($Sow_probability[$pointer]==$seed_most[0]){
									    $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
											if($count<3){
												 //计算收成
												 $this->Harvest($user,$Sow_probability[$pointer]);
											}else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else if($Sow_probability[$pointer]==$seed_most[1]){
                       $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
                       if($count<1){
												  //计算收成
												  $this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
												  $pointer++;
												  return $this->Special($user,$level,$Sow_probability,$pointer);
											 }
								 }else{
									  //计算收成
									  $this->Harvest($user,$Sow_probability[$pointer]);
								 }
						}else if($level==8){
									//八级
									if($Sow_probability[$pointer]==$seed_most[0]){
											$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
											if($count<10){
												 //计算收成
												 $this->Harvest($user,$Sow_probability[$pointer]);
											}else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else if($Sow_probability[$pointer]==$seed_most[1]){
												$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
												if($count<3){
													//计算收成
													$this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
													$pointer++;
													return $this->Special($user,$level,$Sow_probability,$pointer);
											 }
								 }else{
										//计算收成
										$this->Harvest($user,$Sow_probability[$pointer]);
								 }
						}else if($level==9){
									//九级
									if($Sow_probability[$pointer]==$seed_most[0]){
											$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
											if($count<10){
												 //计算收成
												 $this->Harvest($user,$Sow_probability[$pointer]);
											}else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else if($Sow_probability[$pointer]==$seed_most[1]){
												$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
												if($count<6){
													//计算收成
													$this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
													$pointer++;
													return $this->Special($user,$level,$Sow_probability,$pointer);
											 }
								 }else if($Sow_probability[$pointer]==$seed_most[2]){
											 $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[2].'"')->count();
											 if($count<1){
												  //计算收成
												  $this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else{
									   //计算收成
									   $this->Harvest($user,$Sow_probability[$pointer]);
								 }
						}else if($level==10){
									//十级
									if($Sow_probability[$pointer]==$seed_most[0]){
											$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
											if($count<10){
												 //计算收成
												 $this->Harvest($user,$Sow_probability[$pointer]);
											}else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else if($Sow_probability[$pointer]==$seed_most[1]){
												$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
												if($count<6){
													//计算收成
													$this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
													$pointer++;
													return $this->Special($user,$level,$Sow_probability,$pointer);
											 }
								 }else if($Sow_probability[$pointer]==$seed_most[2]){
											 $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[2].'"')->count();
											 if($count<3){
													//计算收成
													$this->Harvest($user,$Sow_probability[$pointer]);
											 }else{
												 $pointer++;
												 return $this->Special($user,$level,$Sow_probability,$pointer);
											}
								 }else{
										 //计算收成
										 $this->Harvest($user,$Sow_probability[$pointer]);
								 }
						}
			}

			//计算收成
			private function Harvest($user,$seeds){
           //获取收成机率
					 $harvest_probability_data = array();
           //获取果实数量区间
					 $conf = M('global_conf');
					 $harvest_probability = $conf->where(array('case'=>'fruit_numbe'))->select();
					 //抽取果实数量
           $harvest_probability = explode('-',$harvest_probability[0]['value']);
					 for($i=$harvest_probability[0];$i<=$harvest_probability[1];$i++){
                 array_push($harvest_probability_data,$i);
					 }
					 shuffle($harvest_probability_data);
					 //播种
					 $this->Sow($user,$seeds,$harvest_probability_data[0]);
			}

			//播种
			private function Sow($user,$seeds,$harvest_probability){
          $Tool = New Tool;
    			$Sow_data = array(
              'user' =>$user,
							'seed_type'=>$seeds,
							'time'=> time(),
							'seed_state'=>0,
							'disasters_state'=>0,
							'disasters_value'=>0,
							'housekeeper'=>0,
							'harvest_num'=>$harvest_probability,
							'harvest_state'=>0,
					);

          $user_planting_record = $Tool->table($user,'planting_record');
					$planting_record = M("$user_planting_record");
					//开启事务
					$planting_record->startTrans();
					$res = $planting_record->add($Sow_data);

					if($res){
						  //事务成功
							$planting_record->commit();
						  echo '春天来了，我种了'.$seeds;
							$this->deducted_seed($user);
					}else{
						  //事务失败
							$planting_record->rollback();
						  echo '种植失败';
					}
			}

			//减掉仓库种子数量
      public function deducted_seed($user){
           $Tool = New Tool;
					 $user_seed_warehouse = $Tool->table($user,'seed_warehouse');
					 $seed_warehouse = M("$user_seed_warehouse");
					 //获取播种种子比例
					 $conf = M('global_conf');
					 $harvest_probability = $conf->where(array('cases'=>'planting_proportion'))->select();
					 //开启事务
 					 $seed_warehouse->startTrans();
					 $res = $seed_warehouse->where(array('user'=>$user))->setInc('num',-($harvest_probability[0]['value']));
					 if($res){
						  //事务成功
						  $seed_warehouse->commit();
							echo '减掉种子了';
					 }else{
						  //事务失败
						  $seed_warehouse->rollback();
						  echo '种植失败';
					 }
			}

}







?>
