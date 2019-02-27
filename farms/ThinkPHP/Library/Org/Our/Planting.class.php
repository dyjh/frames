<?php
namespace Org\Our;
use Org\Our\Tool;
use Think\Model;
/***0  无对应等级  ***/
/***1   种值失败  ***/
/***2  种植成功  ***/
/***3  种子扣有误  ***/
/***4 种子数量不够  ***/

class planting{

			 private $land_number;
			 private $user_level;
             private $user;
			 private $table_fix;
			 private $type;

			 function __construct($data){
				 $this->land_number = $data['number'];
				 $this->user_level = $data['level'];
				 $this->user = $data['user'];
				 $this->type = $data['auto'];
				 $this->table_fix = substr($data['user'],0,3);
				 $this->land_level();	 
			 }


			private function land_level(){

				  $user_planting_record = $this->table_fix.'_planting_record';
				  $user_members = $this->table_fix.'_members';
				  			  
                  $now_level = M("$user_members")->field('level')->where(array('user'=>$this->user))->find();				  
				  if($this->land_number>$this->user_level || $now_level['level']!==$this->user_level){
					    $error = true;
						$data['state'] = 10000;
						$data['content'] = '用户级别错误！';
						echo json_encode($data);
				  }		

				  if(M("$user_planting_record")->where('user='.$this->user.' and number="'.$this->land_number.'" and harvest_state=0')->select()){
					    $error = true;
						$data['state'] = 10007;
						$data['content'] = $this->land_number.'号地正在种植中！';
						echo json_encode($data);
				  };
				  
				  if($error!==true){
					   $this->clearing();
				  } 
			}

           //判断种子数量是否足够
			private function clearing(){

					 $user_prop_warehouse = $this->table_fix."_prop_warehouse";
					 //获取用户种子数量
					 $user_seeds_count = M("$user_prop_warehouse")->field('num')->where('user="'.$this->user.'" and props="种子"')->find();
					 //获取播种种子比例
					 if($user_seeds_count['num']>=100){
							 $this->Probability();
					 }else{
						   $data['state'] = 10001;
						   $data['content'] = '种子数量不足';
						   echo json_encode($data);
					 }
			}


			//等级对应种植类型
			private function Probability(){
				
				    //查看定向
					$orientation = M('seed_orientation')->where('user="'.$this->user.'" and num>0')->find();
					//如果有定向并且次数大于0
					if($orientation && $orientation['num']>0){
						 //if(M('seed_orientation')->where('user="'.$this->user.'" and seed="'.$orientation['seed'].'"')->setDec('num',1)){
					     if(M('seed_orientation')->where('id='.$orientation['id'])->setDec('num',1)){
							  //if(M('seed_orientation')->where('user="'.$this->user.'" and seed="'.$orientation['seed'].'"')->setInc('count',1)){
							  if(M('seed_orientation')->where('id='.$orientation['id'])->setInc('count',1)){	  
								  $this->Harvest($orientation['seed']);
							  } 
						 }else{
							 $data['state'] = 10001;
						     $data['content'] = '数据错误';
						     echo json_encode($data);
						 }
					}else{
						 //是否作了回本限制种植
						 $allow_message = M("backmoney_user")->field('allow_seed,ban_cycle')->where(array('user'=>$this->user))->find();
						 $today = date('w');
						 $cycle = strpos($allow_message['ban_cycle'],$today);

						 if($allow_message && $cycle>=0){
							$res[0]['seed_level'] = $allow_message['allow_seed'];
						 }else{
							$res = M('seed_level')->field('seed_level')->where(array('level'=>$this->user_level))->select();
						 }

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
								/*if($this->user_level>=6){
									  //六级以上处理
									  $this->Special($Sow_probability,0);
								}else{
									  //计算生成
									$this->Harvest($Sow_probability[0]);
								}*/
							$this->Harvest($Sow_probability[0]);
						}else{
							$data['state'] = 10002;
							$data['content'] = '帐户等级有误';
							echo json_encode($data);
							exit;
						}
					}
			}


			//六级以上处理
			private function Special($Sow_probability,$pointer){
				
				    //开启缓存
				    //$mem=new \Memcached;
                    //$mem->addServer('localhost',11211);

                    $Tool = New Tool;
					//获取当天时间零点，24点
					$starttime = $Tool->time(time());
                    //结束时间
                    $endtime = $starttime+86400;
					//获取数量机率最多，也是需要做限制的种子
					$seed_most = $this->seed_most($Sow_probability);
					//获取用户种植表
					$user_planting_record = $this->table_fix.'_planting_record';
				    $planting_record = M("$user_planting_record");
                    if($this->user_level==6){
						    //六级
                           if($Sow_probability[$pointer]==$seed_most[0]){
                                $count = $planting_record->where('time>="'.$starttime.'" and time<="'.$endtime.'" and seed_type="'.$seed_most[0].'"')->count();
								if($count<2){
									 //计算收成
									 $this->Harvest($Sow_probability[$pointer]);
								 }else{
									 $pointer++;
									 return $this->Special($Sow_probability,$pointer);
								 }
							}else{
								//计算收成
								$this->Harvest($Sow_probability[$pointer]);
							}
				    }else if($this->user_level==7){
							//七级
                            if($Sow_probability[$pointer]==$seed_most[0]){
								 $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
								 if($count<3){
									  //计算收成
									 $this->Harvest($Sow_probability[$pointer]);
								 }else{
									 $pointer++;
									 return $this->Special($Sow_probability,$pointer);
								 }
							}else if($Sow_probability[$pointer]==$seed_most[1]){
                                    $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
                                    if($count<1){
										  //计算收成
										  $this->Harvest($Sow_probability[$pointer]);
									}else{
										  $pointer++;
										  return $this->Special($Sow_probability,$pointer);
									}
							}else{
								  //计算收成
								  $this->Harvest($Sow_probability[$pointer]);
							}
					}else if($this->user_level==8){
							 //八级
							 if($Sow_probability[$pointer]==$seed_most[0]){
									$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
									if($count<10){
										//计算收成
									    $this->Harvest($Sow_probability[$pointer]);
									}else{
										$pointer++;
										return $this->Special($Sow_probability,$pointer);
									}
							 }else if($Sow_probability[$pointer]==$seed_most[1]){
									$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
									if($count<3){
											//计算收成
											$this->Harvest($Sow_probability[$pointer]);
									 }else{
											$pointer++;
											return $this->Special($Sow_probability,$pointer);
									 }
							 }else{
								   //计算收成
								  $this->Harvest($Sow_probability[$pointer]);
							 }
					}else if($this->user_level==9){
							//九级
							if($Sow_probability[$pointer]==$seed_most[0]){
								  $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
								  if($count<10){
										 //计算收成
										 $this->Harvest($Sow_probability[$pointer]);
								   }else{
										 $pointer++;
										 return $this->Special($Sow_probability,$pointer);
								   }
							}else if($Sow_probability[$pointer]==$seed_most[1]){
										 $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
										 if($count<6){
												//计算收成
												$this->Harvest($Sow_probability[$pointer]);
										 }else{
											    $pointer++;
												return $this->Special($Sow_probability,$pointer);
										 }
							}else if($Sow_probability[$pointer]==$seed_most[2]){
										$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[2].'"')->count();
										if($count<1){
											//计算收成
										    $this->Harvest($Sow_probability[$pointer]);
										}else{
											$pointer++;
											return $this->Special($Sow_probability,$pointer);
										}
							}else{
							    //计算收成
								$this->Harvest($Sow_probability[$pointer]);
							}
					}else if($this->user_level==10 || $this->user_level==11 || $this->user_level==12){
						    		
						    //$mem->add($this->user.$this->user_level.$seed_most[0],1,600);
							
							//十级									
							if($Sow_probability[$pointer]==$seed_most[0]){
								        $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[0].'"')->count();
										if($count<10){
											//计算收成
										    $this->Harvest($Sow_probability[$pointer]);
										}else{
											$pointer++;
										    return $this->Special($Sow_probability,$pointer);
										}
							}else if($Sow_probability[$pointer]==$seed_most[1]){
										$count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[1].'"')->count();
										if($count<6){
											//计算收成
											$this->Harvest($Sow_probability[$pointer]);
									    }else{
											$pointer++;
											return $this->Special($Sow_probability,$pointer);
									    }
							}else if($Sow_probability[$pointer]==$seed_most[2]){
										 $count = $planting_record->where('time>="'.$time['start'].'" and time<="'.$time['end'].'" and seed_type="'.$seed_most[2].'"')->count();
										 if($count<3){
												//计算收成
												$this->Harvest($Sow_probability[$pointer]);
										 }else{
											 $pointer++;
											 return $this->Special($Sow_probability,$pointer);
										}
							 }else{
									 //计算收成
									 $this->Harvest($Sow_probability[$pointer]);
							 }
						}
			}


			//获取种子数量最多的
	    function seed_most($data){
	        $array = array_count_values($data);
	        arsort($array);
	        //如果是六级，稻米需要做限制
	        if($this->user_level==6){
	            $seed_most = array_slice($array,0,1,true);   //取一位
	        }else if($this->user_level==7){
	            $seed_most = array_slice($array,0,2,true);    //取二位
	        }else if($this->user_level==8){
	            $seed_most = array_slice($array,0,2,true);     //取二位
	        }else if($this->user_level==9){
	            $seed_most = array_slice($array,0,3,true);    //取三位
	        }else if($this->user_level==10 || $this->user_level==11 || $this->user_level==12){
	            $seed_most = array_slice($array,0,3,true);    //取三位
	        }
	        //键值对换
	        $seed_most = array_flip($seed_most);
	        //重置索引
	        $seed_most = array_values($seed_most);
	        //返回
	        return $seed_most;
	    }


			//计算收成
			private function Harvest($sow_seeds){
				//获取收成机率
				$harvest_probability_data = array();
				//获取果实数量区间
				$seeds = M('seeds');

				$harvest_probability = $seeds->where(array('varieties'=>$sow_seeds))->select();

				if(!$harvest_probability){
					 $data['state'] = 10003;
					 $data['content'] = '种子不存在';
					 echo json_encode($data);
					 exit;
				}
					
				   //抽取果实数量
			    $harvest_hours = $harvest_probability[0]['harvest_hours'];
				
				if($sow_seeds=="摇钱树"){
					//播种
				    $this->Sow($sow_seeds,1,$harvest_hours);			
				}else{
					$harvest_probability = explode('-',$harvest_probability[0]['fruit_number']);
					for($i=$harvest_probability[0];$i<=$harvest_probability[1];$i++){
						array_push($harvest_probability_data,$i);
					}
					shuffle($harvest_probability_data);
					
					$this->Sow($sow_seeds,$harvest_probability_data[0],$harvest_hours);
				} 
			}

			//播种
			private function Sow($sow_seeds,$harvest_probability,$harvest_hours){

                   $Tool = New Tool;
    			   $Sow_data = array(
                        'user' =>$this->user,
					    'seed_type'=>$sow_seeds,
						'seed_img_name'=>image_name_icover($sow_seeds),
						'time'=> time(),
						'harvest_time'=>time()+$harvest_hours,
						'seed_state'=>0,
						'disasters_state'=>0,
						'disasters_value'=>0,
						'housekeeper'=>0,
						'harvest_num'=>$harvest_probability,
						'harvest_state'=>0,
						'number'=>$this->land_number,
						'auto'=>$this->type,
					);
                    $user_planting_record = $Tool->table($this->user,'planting_record');
					$planting_record = M("$user_planting_record");
					//开启事务
					$planting_record->startTrans();
					$res = $planting_record->add($Sow_data);
					if($res){
						  //事务成功
					      $planting_record->commit();
                          $this->deducted_seed();
					}else{
						  //事务失败
							$planting_record->rollback();
							$data['state'] = 10004;
							$data['content'] = '种植失败';
							echo json_encode($data);
					}
			}

			//减掉仓库种子数量
            public function deducted_seed(){
				 $Tool = New Tool;
				 $user_prop_warehouse = $Tool->table($this->user,'prop_warehouse');
				 $prop_warehouse = M("$user_prop_warehouse");
				 //获取播种种子比例
				 $conf = M('global_conf');
				 $harvest_probability = $conf->where(array('cases'=>'planting_proportion'))->select();
				 //开启事务
				 $prop_warehouse->startTrans();
				 $res = $prop_warehouse->where('user="'.$this->user.'" and props="种子"')->setDec('num',$harvest_probability[0]['value']);
				 if($res){
					  if($this->type==0){
						  $str = $this->user.' '.date('Y-m-d H:i:s',time()).' 手动';
					  }else{
						  $str = $this->user.' '.date('Y-m-d H:i:s',time()).' 自动';
					  }  
					  file_put_contents('../log/planting.log',$str.PHP_EOL."\n",FILE_APPEND);
					  //事务成功
					  $prop_warehouse->commit();
					  $data['state'] = 10006;
					  $data['content'] = '种植成功';
					  echo json_encode($data);
				 }else{
					  //事务失败
					  $prop_warehouse->rollback();
						$data['state'] = 10005;
						$data['content'] = '仓库种子扣除失败';
						echo json_encode($data);
				 }
		}
}

?>
