<?php
namespace Org\Our;
use Org\Our\Consume;
use Org\Our\Record;
use Think\Model;

class hosting{

	 public function buy($data){

			 $model = new Model();
			 $model->startTrans();

			 $table_fix = substr(session('user'),0,3);
			 $user_table = $table_fix."_members";
			 $service_message = M('shop')->field('name,price,seed')->where('id="'.$data['id'].'"')->find();
			 $user_message = M("$user_table")->field('diamond')->where('user="'.$_SESSION['user'].'"')->find();
			 $count = $service_message['price']*$data['count'];
			 
			 //如果果实不为空
			 $where = '';  //定义仓库查询条件
			 $seednum = array();
			 if(!empty($service_message['seed'])){
				  $need_seed = explode('|',$service_message['seed']);
				  for($a=0;$a<count($need_seed);$a++){
					   $seed_list = explode(',',$need_seed[$a]);
					   if($a==count($need_seed)-1){
						  $where.= 'user='.session('user').' and seeds="'.$seed_list[0].'"'; 
					   }else{
						  $where.= 'user='.session('user').' and seeds="'.$seed_list[0].'" or ';
					   }
					   $seednum[$a] = $seed_list[1];
				  }
				  
				  $seed_table = $table_fix."_seed_warehouse";
			      $seed_data = M("$seed_table")->where($where)->select();
				  
				  if($seed_data && count($seednum)==count($seed_data)){
					   for($s=0;$s<count($seed_data);$s++){
						   $need_seed_count = $seednum[$s]*$data['count'];
						   if($seed_data[$s]['num']<$need_seed_count){
							    $data['state'] = 60004;
								$data['content'] = $seed_data[$s]['seeds'].'不足';
								echo json_encode($data);
								exit;
						   }else{
							    M("$seed_table")->where('user="'.$_SESSION['user'].'" and seeds="'.$seed_data[$s]['seeds'].'"')->setDec('num',$need_seed_count);
							    continue;
						   }
					   }
				  }else{
					  $data['state'] = 60004;
					  $data['content'] = '缺少'.(count($seednum)-count($seed_data)).'种果实';
					  echo json_encode($data);
					  exit;
				  }	  
			 }

             if($user_message['diamond']>=$count){
                if(M('shop')->where('id="'.$data['id'].'"')->setDec('num',$data['count'])){
                      if(M("$user_table")->where('user="'.$_SESSION['user'].'"')->setDec('diamond',$count)){
							$endtime = time();
							$addtime = 0;
							for($i=0;$i<$data['count'];$i++){
								if($data['id']==15){
									$addtime+= 48*3600*2;
								    $endtime+= 48*3600*2; 
								}else{
									$addtime+= 48*3600;
								    $endtime+= 48*3600;
								} 
							}

							$array['user'] = $_SESSION['user'];
							$array['service_type'] = $data['id']-10;
							$array['articles'] = '宝石';
							$array['name'] = $service_message['name'];
							$array['price'] = $service_message['price'];
							
							if($data['id']==15){
								$array['num'] = $data['count']*2;
							}else{
								$array['num'] = $data['count'];
							}
							
							$array['buy_time'] = time();
							$array['end_time'] = $endtime;
							$array['state'] = 0;
							$array['type'] = 'b';

							$usr_managed = $table_fix.'_managed_to_record';
							$managed = M("$usr_managed");
							$list = $managed->where('user="'.session('user').'" and service_type="'.$array['service_type'].'" and state=0 and end_time>"'.time().'"')->find();
							//如果已经存在该服务，则叠加
							if($list){
								if($managed->where('user="'.session('user').'" and service_type="'.$array['service_type'].'" and state=0 and end_time>"'.time().'"')->setInc('end_time',$addtime)){
									  $shop_record = $table_fix."_record_shop";
									  if(M("$shop_record")->add($array)){
										  $model->commit();
										  $data['state'] = 70007;
										  $data['content'] = '购买成功';
										  echo json_encode($data);
										  exit;  
									  }else{
										  $model->rollback();
										  $data['state'] = 70006;
										  $data['content'] = '购买失败';
										  echo json_encode($data);
										  exit;
									 }	  
								}else{
									  $model->rollback();
									  $data['state'] = 70006;
									  $data['content'] = '购买失败';
									  echo json_encode($data);
									  exit;
								}
								
							}else{
								
							    if($managed->add($array)){
										$shop_record = $table_fix."_record_shop";
										if(M("$shop_record")->add($array)){
											 $model->commit();
											 $data['state'] = 70007;
											 $data['content'] = '购买成功';
											 echo json_encode($data);
											 exit;
										}else{
											 $model->rollback();
											 $data['state'] = 70006;
											 $data['content'] = '购买失败';
											 echo json_encode($data);
											 exit;
										}
								}else{
									  $model->rollback();
									  $data['state'] = 70008;
									  $data['content'] = '管家记录修改失败';
									  echo json_encode($data);
									  exit;
								}							
							}
					}else{
						 $model->rollback();
						 $data['state'] = 70005;
						 $data['content'] = '宝石修改失败';
						 echo json_encode($data);
						 exit;
					}
				}else{
					 $model->rollback();
					 $data['state'] = 70002;
					 $data['content'] = '系统错误';
					 echo json_encode($data);
					 exit;
				}
			 }else{
				 $model->rollback();
				 $data['state'] = 70003;
				 $data['content'] = '宝石不够';
				 echo json_encode($data);
				 exit;
			 }
	 }
	 
	 public function exchange($data){
		 
		 //需要传入数量，服务ID
		 if($data['id']==15){
			 $data['state'] = 70002;
			 $data['content'] = '稻草人暂未开放';
			 echo json_encode($data);
			 exit;
		 }
		 
		 $model = new Model();
		 $model->startTrans();
		 
		 $table = substr(session('user'),0,3).'_seed_warehouse';
		 $service_message = M('shop')->field('name,exchange')->where('id="'.$data['id'].'"')->find();
		 
		 $exchange_list = explode(',',$service_message['exchange']); 
		 //计算总数
		 $count = $exchange_list[1]*$data['count'];

		 //查找用户种子仓库
		 $user_seeds_count = M("$table")->where('user="'.session('user').'" and seeds="'.$exchange_list[0].'" and num>='.$count)->find();

		 if($user_seeds_count && $user_seeds_count['num']>=$count){
			 if(M('shop')->where('id="'.$data['id'].'"')->setDec('num',$data['count'])){
				  if(M("$table")->where('user="'.session('user').'" and seeds="'.$exchange_list[0].'"')->setDec('num',$count)){
					     $endtime = time();
						 $addtime = 0;
						 for($i=0;$i<$data['count'];$i++){
							 if($data['id']==15){
								$addtime+= 48*3600*2;
								$endtime+= 48*3600*2; 
							 }else{
								$addtime+= 48*3600;
								$endtime+= 48*3600;
							 } 
						 }
						 
						 $array['user'] = $_SESSION['user'];
						 $array['service_type'] = $data['id']-10;
						 $array['articles'] = $exchange_list[0];
						 $array['name'] = $service_message['name'];
						 $array['price'] = $exchange_list[1];
						 
						 if($data['id']==15){
							$array['num'] = $data['count']*2;
						 }else{
							$array['num'] = $data['count'];
						 }
						 
						 $array['buy_time'] = time();
						 $array['end_time'] = $endtime;
						 $array['state'] = 0;
						 $array['type'] = 'h';
						 
						 
						 $user_managed = substr(session('user'),0,3).'_managed_to_record';
						 $list = M("$user_managed")->where('user="'.session('user').'" and service_type="'.$array['service_type'].'" and state=0 and end_time>"'.time().'"')->find();
						 //如果已经存在该服务，则叠加
						 if($list){
							   if(M("$user_managed")->where('user="'.session('user').'" and service_type="'.$array['service_type'].'" and state=0 and end_time>"'.time().'"')->setInc('end_time',$addtime)){
									 $shop_record = substr(session('user'),0,3)."_record_shop";
									 if(M("$shop_record")->add($array)){
										  $model->commit();
										  $data['state'] = 70007;
										  $data['content'] = '兑换成功';
										  echo json_encode($data);
										  exit;
									 }else{
										  $model->rollback();
										  $data['state'] = 70006;
										  $data['content'] = '兑换失败';
										  echo json_encode($data);
										  exit;
									 }
								}else{
									 $model->rollback();
									 $data['state'] = 70006;
									 $data['content'] = '兑换失败';
									 echo json_encode($data);
									 exit;
								}
								
							}else{									
							    if(M("$user_managed")->add($array)){
										$shop_record = substr(session('user'),0,3)."_record_shop";
										if(M("$shop_record")->add($array)){
											 $model->commit();
											 $data['state'] = 70007;
											 $data['content'] = '兑换成功';
											 echo json_encode($data);
											 exit;
										}else{
											 $model->rollback();
											 $data['state'] = 70006;
											 $data['content'] = '兑换失败';
											 echo json_encode($data);
											 exit;
										}
								}else{
									$model->rollback();
									$data['state'] = 70008;
									$data['content'] = '管家记录修改失败';
									echo json_encode($data);
									exit;
								}							
							}
				  }else{
					 $model->rollback();
					 $data['state'] = 70005;
					 $data['content'] = '仓库修改失败';
					 echo json_encode($data);
					 exit;   
				  }
			 }else{
				 $model->rollback();
				 $data['state'] = 70002;
				 $data['content'] = '系统错误';
				 echo json_encode($data);
				 exit;
			 }
		 }else{
			 $data['state'] = 60004;
		     $data['content'] = $exchange_list[0].'不足';
			 echo json_encode($data);
			 exit; 
		 } 
	 } 
}

?>
