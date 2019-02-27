<?php
namespace Org\Our;
use Think\Model;
use Org\Our\Chickencommission;

class Buychicken{
	
	  private $id;
	  private $user;
	  private $buynum;
	  private $chickentype;
	  private $chickconvise;
	  private $chickcycle;
	  private $chickprice;
	  private $fruit;
	  private $maintain_table = '_maintain_record';
	  private $farming_table = '_chicken_record';
	  
	  function __construct($id,$user,$buynum) {
          $this->id = $id;
		  $this->user= $user;
		  $this->buynum = $buynum;
      }
      
	  //鸡场维护状态
	  public function chicken_state(){
		   
		 $table = substr($this->user,0,3).$this->maintain_table;	
         //查询维护记录
		 $where['user'] = $this->user;
         $res = M("$table")->order('id desc')->field('due_time')->where($where)->find(); 
         if($res){
			 if($res['due_time']>=time()){
		        $this->conversion();	    	    
			 }else{
				$data['state'] = 99999; 
                $data['content'] = '鸡舍过期了，请先进行维护';	
                echo json_encode($data);			
				exit;				
			 }	 
		 }else{
			 $data['state'] = 99999; 
             $data['content'] = '你还没有鸡舍，请先购买';
			 echo json_encode($data);
             exit;			 
		 }		  
	  }
	  
	  //查询鸡仔比例
	  private function conversion(){
		  
		  //获取正在养的鸡仔比例
		  $table = substr($this->user,0,3).$this->farming_table;
		  $conversion = M("$table")->where('user="'.$this->user.'" and harvest_state=0')->sum('conversion');
		  if(!$conversion){
			  $conversion = 0;
		  }
		  
		  //获取该鸡的转换比例
		  $this_chicken_message = M('chickens')->field('name,price,cycle,conversion,num,fruit')->where('id='.$this->id)->find();
		  //查询是否售完
		  if($this_chicken_message['num']<=0){
			  $data['state'] = 99999; 
              $data['content'] = '商店今日已经售完';	
			  echo json_encode($data);
              exit;
		  }	  
		  //获取总大养殖数配置
		  $farming_message = M('chicken_conf')->where('item="farming_count"')->find();
		  //计算养殖量
		  if($conversion+$this_chicken_message['conversion']*$this->buynum <= $farming_message['value']){
			    $this->chickconvise = $this_chicken_message['conversion'];
			    $this->chickentype = $this_chicken_message['name'];
			    $this->chickcycle = $this_chicken_message['cycle'];
				//$this->chickprice = $this_chicken_message['price'];
				$this->fruit = $this_chicken_message['fruit'];
				
			    //进行金币扣除
			    //$this->deduct_coin($this_chicken_message['price']*$this->buynum);
				//进行果实扣除
				$this->deduct_fruit($this->buynum,$this->fruit);
		  }else{
			  $data['state'] = 99999; 
              $data['content'] = '已经超过最大养殖数量';
              echo json_encode($data);			  
              exit;			  
		  }
	  }
	  
	  //扣除金币
	  private function deduct_coin($coin){
		   
		  //开启事务
		  M()->startTrans();
		  //查询用户金币
		  $table = substr($this->user,0,3).'_members';
		  $user_coin = M("$table")->field('coin')->where('user="'.$this->user.'"')->find();
		  //判断金币是否足够
		  if($user_coin['coin']>=$coin){
			   if(M("$table")->where('user="'.$this->user.'"')->setDec('coin',$coin)){		
                   //扣除商店数量
                   if(M('chickens')->where('id='.$this->id)->setDec('num',$this->buynum)){
					   $this->farming();
				   }else{
					   $data['state'] = 99999; 
					   $data['content'] = '商店数量扣除失败';
                       echo json_encode($data);						   
					   exit;
				   }
			   }else{
				   //回滚
				   M()->rollback();
				   $data['state'] = 99999; 
				   $data['content'] = '金币扣除失败';
                   echo json_encode($data);	
			       exit; 
			   }   
		  }else{
			  $data['state'] = 99999; 
			  $data['content'] = '金币不足';
              echo json_encode($data);	
			  exit; 
		  }		  
	  }
	  
	  
	  private function deduct_fruit($buynum,$fruits){
		  
		  $table = substr(session('user'),0,3).'_seed_warehouse';
		  $need_fruits = explode('|',$fruits);

		  for($i=0;$i<count($need_fruits);$i++){
			    $seed_list = explode(',',$need_fruits[$i]);
				if($i==count($need_fruits)-1){
					 $where.= 'user='.session('user').' and seeds="'.$seed_list[0].'" and num>='.$buynum*$seed_list[1]; 
				}else{
					 $where.= 'user='.session('user').' and seeds="'.$seed_list[0].'" and num>='.$buynum*$seed_list[1].' or ';
				}
				$seednum[$i]['seed'] = $seed_list[0];
				$seednum[$i]['num'] = $buynum*$seed_list[1];
		  }	
	      $seed_data = M("$table")->where($where)->select();
		  
		  //如果满足条件的果实数量与设定的一致，则可以买
		  if(count($seednum)==count($seed_data)){
			  //开启事务
		      M()->startTrans();
              //扣除商店数量			  
			  if(M('chickens')->where('id='.$this->id)->setDec('num',$this->buynum)){
				  $seccuse = 0;
			      //扣除果实
				  for($j=0;$j<count($seednum);$j++){
					  $res = M("$table")->where('user="'.session('user').'" and seeds="'.$seednum[$j]['seed'].'"')->setDec('num',$seednum[$j]['num']);
					  if($res){
						  $seccuse++;
					  }
				  }
				  //如果执行成功次数等于需要扣除的果实总数
				  if($seccuse==count($seednum)){
					   $this->farming();
				  }
			  }else{
				 $data['state'] = 99999; 
			     $data['content'] = '商店数量扣除失败';
			     echo json_encode($data);						   
			     exit;  
			  }
		  }else{
			  $data['state'] = 10001; 
			  $data['content'] = '果实不足';
			  echo json_encode($data);						   
			  exit;  
		  }
	  }
	  
	
	  //生成养殖记录
	  private function farming(){
		  
		  $savedate = array(
		      'user' => $this->user,
			  'chicken_id' => $this->id,
			  'chicken_type' => $this->chickentype,			  
			  'buy_time' => time(),
			  'price'=> 0,
			  'harvest_time' => time()+$this->chickcycle*3600*24,
			  'harvest_state' => 0,
			  'conversion' => $this->chickconvise,
			  'fruit' => $this->fruit
		  );
		    
		  //根据购买数量进行储存
		  $table = substr($this->user,0,3).$this->farming_table;
		  $num = 0;
		  for($i=0;$i<$this->buynum;$i++){  
			  $res = M("$table")->add($savedate);
              if($res){
				  $num++;  
			  }			  
		  }
		  
		  //判断添加成功的次数与购买数量是否相等
		  if($num==$this->buynum){
			  //提交 
			  M()->commit();
			  //$Chickencommission = new Chickencommission($this->user,$this->chickprice*$this->buynum);
			  //$Chickencommission->select_my_team(); 
			  $data['state'] = 10002; 
		      $data['content'] = '抢购成功';
			  //$data['money'] = $this->chickprice*$this->buynum;
			  //$data['num'] = $this->buynum;
              echo json_encode($data);	
			  exit; 
			  
		  }else{
			  M()->rollback();
			  $data['state'] = 99999; 
			  $data['content'] = '抢购失败';
              echo json_encode($data);	
			  exit;   
		  }
	  }
	  
	  public function sell($id,$user){
		  
		  $table = substr($user,0,3).$this->farming_table;
		  $this_farming_message = M("$table")->where('id='.$id)->find();
		  //判断是不是本人操作
		   if($this_farming_message['user']==$user && $this_farming_message['harvest_state']==0){
			   //判断是否可以收获
			   if(time()>=$this_farming_message['harvest_time']){
				    //开启事务
		            M()->startTrans();
					//获取静态资金比例
					$chickens = M("chickens");	
					$this_earnings = $chickens->field('earnings,price')->where('name="'.$this_farming_message['chicken_type'].'"')->find();
					if($this_earnings['earnings']){
						 //分割机率
						 $earnings_list = explode('-',$this_earnings['earnings']);
						 //生成概率
						 $harvest_com = rand($earnings_list[0],$earnings_list[1]);
						 //获取买时花费的果实
						 $buy_fruit = explode('|',$this_farming_message['fruit']);
						 //分别计算收益
						 for($i=0;$i<count($buy_fruit);$i++){
							  $buy_fruit_list = explode(',',$buy_fruit[$i]);
                              $harvest_fruit[$i]['seeds'] = $buy_fruit_list[0];						  
							  $harvest_fruit[$i]['num'] = $buy_fruit_list[1]+$buy_fruit_list[1]*($harvest_com/100);
						 }
						 
						 //将收益存入存库
						 $harvest_fruit_num = 0;
						 $havvest_fruit_str = '';
						 $seed_table = substr($user,0,3).'_seed_warehouse';
						 for($i=0;$i<count($harvest_fruit);$i++){
							 
							  if($i==count($harvest_fruit)-1){
								  $havvest_fruit_str.= $harvest_fruit[$i]['seeds'].','.$harvest_fruit[$i]['num'];
							  }else{
								  $havvest_fruit_str.= $harvest_fruit[$i]['seeds'].','.$harvest_fruit[$i]['num'].'|';
							  }
							 
							  //查看仓库有没有这种果实
							  $res = M("$seed_table")->where('user="'.$user.'" and seeds="'.$harvest_fruit[$i]['seeds'].'"')->find();
							  if($res){
								  $arr = M("$seed_table")->where('user="'.$user.'" and seeds="'.$harvest_fruit[$i]['seeds'].'"')->setInc('num',$harvest_fruit[$i]['num']);
								  if($arr){
									  $harvest_fruit_num++;
								  }
							  }else{
								  $harvest_fruit[$i]['user'] = $user;
								  $arr = M("$seed_table")->add($harvest_fruit[$i]);
								  if($arr){
									  $harvest_fruit_num++;
								  }
							  }	 
						 }
						 
						 //如果成功的次数等于要存入的果实个数
						 if(count($harvest_fruit)==$harvest_fruit_num){
							 //改变收获状态
							  $data['earnings'] = $harvest_com;
							  $data['harvest_fruit'] = $havvest_fruit_str;
							  $data['harvest_state'] = 1;
							  $data['sell_time'] = time();
							  if(M("$table")->where('id='.$id)->save($data)){
								  M()->commit(); 
								  $data['state'] = 10003; 
								  $data['content'] = '已卖出,果实已存入仓库';
								  $data['id'] = $id;
								  echo json_encode($data);	
								  exit; 
							  }else{
								  M()->rollback();
								  $data['state'] = 99999; 
								  $data['content'] = '状态修改失败';
								  echo json_encode($data);	
								  exit; 
							  }
						 }else{
							  M()->rollback();
							  $data['state'] = 99999; 
							  $data['content'] = '存入仓库失败';
							  echo json_encode($data);	
							  exit;    
						 }
						 
					}                  
			   }  
		   } 
	  }
	  
	  
	  //卖金币
	  public function sell_coin($id,$user){

		  $table = substr($user,0,3).$this->farming_table;
		  $this_farming_message = M("$table")->where('id='.$id)->find();
		  //判断是不是本人操作
		  if($this_farming_message['user']==$user && $this_farming_message['harvest_state']==0){
			   //判断是否可以收获
			   if(time()>=$this_farming_message['harvest_time']){
				    //开启事务
		            M()->startTrans();
					//获取静态资金比例
					$chickens = M("chickens");	
					$this_earnings = $chickens->field('earnings,price')->where('name="'.$this_farming_message['chicken_type'].'"')->find();
					if($this_earnings['earnings']){
						  //分割机率
						  $earnings_list = explode('-',$this_earnings['earnings']);
						  //生成概率
						  $harvest_com = rand($earnings_list[0],$earnings_list[1]);
						  //计算收益
                          $user_com = $this_earnings['price']+$this_earnings['price']*($harvest_com/100);				  
                          //改变收获状态
						  $data['harvest_state'] = 1;
						  $data['sell_time'] = time();
                          if(M("$table")->where('id='.$id)->save($data)){							   
							   
							   $user_gold = substr($user,0,3).'_users_gold';							   
							   //查询是否有这个用户
							   if(M("$user_gold")->where('user="'.$user.'"')->find()){
								    //修改静态资金
									if(M("$user_gold")->where('user="'.$user.'"')->setInc('static_gold',$user_com)){
 									     //修改总资金
										 if(M("$user_gold")->where('user="'.$user.'"')->setInc('user_coin',$user_com)){
											   $data['user'] = $user;
											   $data['money'] = $user_com;
											   $data['time'] = time();
											   $data['type'] = 1;  
											   $record_table = date('Y-m').'_chicken_com_record';
											   if(M("$record_table")->add($data)){
												   
												  M()->commit(); 
												  
												  $data['state'] = 99999; 
												  $data['content'] = '已卖出，收益已转入静态资金';
												  echo json_encode($data);	
												  exit; 
											   }else{
												  M()->rollback();
												  
												  $data['state'] = 99999; 
												  $data['content'] = '记录修改失败';
												  echo json_encode($data);	
												  exit; 
											   } 
										 }else{
											 M()->rollback();
											 $data['state'] = 99999; 
										     $data['content'] = '总资金修改有误';
										     echo json_encode($data);	
											 exit; 
										 } 
									}else{
										M()->rollback();
										$data['state'] = 99999; 
										$data['content'] = '静态资金修改有误';
										echo json_encode($data);	
									    exit;
									}
							   }else{
								   							   
								    $arr['user'] = $user;
									$arr['user_fees'] = 0;
									$arr['buy_and_sell'] = 0;
									$arr['user_top_up'] = 0;
									$arr['static_gold'] = 0;
									$arr['static_gold'] = $user_com;
									$arr['user_coin'] = $user_com;
									
									if(M("$user_gold")->add($arr)){
										//存入记录表
										$data['user'] = $user;
									    $data['money'] = $user_com;
									    $data['time'] = time();
										$data['type'] = 1;  
										$record_table = date('Y-m').'_chicken_com_record';
										if(M("$record_table")->add($data)){
											 M()->commit(); 
											 $data['state'] = 99999; 
										     $data['content'] = '已卖出，收益已转入静态资金';
										     echo json_encode($data);	
										     exit; 
										}else{
											 M()->rollback();
											 $data['state'] = 99999; 
										     $data['content'] = '记录修改失败';
										     echo json_encode($data);	
											 exit; 
										} 
									}else{
										M()->rollback();
										$data['state'] = 99999; 
										$data['content'] = '金币修改失败';
										echo json_encode($data);	
								        exit; 
									}
							   } 
						  }else{
							  M()->rollback();
							  $data['state'] = 99999; 
							  $data['content'] = '收获状态修改失败';
							  echo json_encode($data);	
							  exit;
						  }                          
					}else{
						$data['state'] = 99999; 
						$data['content'] = '该鸡不存在';
						echo json_encode($data);	
						exit; 
					}
			   }else{
				  $data['state'] = 99999; 
				  $data['content'] = '还没有成熟';
				  echo json_encode($data);	
				  exit; 
			   }
		  }else{
			 $data['state'] = 99999; 
			 $data['content'] = '请求错误';
			 echo json_encode($data);	
			 exit; 
		  }
	  }  
}

?>