<?php
namespace Org\Our;
use Org\Our\Record;

use Think\Model;

class Treasure{
	
	         public $limit = 20;

			 public function buy($arr){

					 $model = new Model();
					 $model->startTrans();

					 $table_fix = substr(session('user'),0,3);
                     $seed_table = $table_fix.'_seed_warehouse';
					 //获取商店对应数据
					 $treasura_message =  M('shop')->where(array('id'=>$arr['id']))->find();
					 
					 //判断宝箱限制
					 $y = date("Y");
					 $m = date("m");
					 $d = date("d");
					 $start = mktime(0,0,0,$m,$d,$y);
					 
					 $shop_record = $table_fix.'_record_shop';
					 $buy_count = M("$shop_record")->where('user="'.$_SESSION['user'].'" and name="'.$treasura_message['name'].'" and buy_time>='.$start.' and buy_time<='.time())->sum('num');	
					 
					 if(!$buy_count){
						 $buy_count = 0;
					 }
					 
					 if($buy_count+$arr['buy_count']>$this->limit){
						 $ke_buy = $this->limit-$buy_count;
						 $data['state'] = 70012;
						 $data['content'] = '超过当日限购，还可购买'.$ke_buy.'个';
						 echo json_encode($data);
						 exit;
					 }
					 
					 $res = M("$seed_table")->field('num')->where('user="'.$_SESSION['user'].'" and seeds="'.$treasura_message['buy'].'"')->find();

					 if($res!==null){
                        if($res['num']>=$arr['counts']){
                           if(M("$seed_table")->where('user="'.$_SESSION['user'].'" and seeds="'.$treasura_message['buy'].'"')->setDec('num',$arr['counts'])){
                               if(M('shop')->where(array('id'=>$arr['id']))->setDec('num',$arr['counts'])){
										$treasura_message =  M('shop')->where(array('id'=>$arr['id']))->find();
										 $treasure_table = $table_fix.'_treasure_warehouse';
										 $array['user'] = session('user');
										 $array['name'] = $treasura_message['name'];
										 $array['num'] = $arr['buy_count'];
										 $array['price'] = $treasura_message['price'];
										 $array['type'] = 'b';
										 $array['buy_time'] = time();

										 $res = M("$treasure_table")->where('user="'.$_SESSION['user'].'" and name="'.$treasura_message['name'].'"')->select();
										 if($res==null){
												$list = M("$treasure_table")->add($array);
										 }else{
												$list = M("$treasure_table")->where('user="'.$_SESSION['user'].'" and name="'.$treasura_message['name'].'"')->setInc('num',$arr['buy_count']);
										 }

										 if($list){
										 $Reco = new Record();
										 $record_treasure = M('record_treasure');
										 $open_treasure['get_seed'] = $arr['buy'];
										 $open_treasure['get_seed_num'] = $arr['counts'];
										 $open_treasure['type'] = 'b';												 
										 $open_treasure['time'] = 1;
										 $treasure_res = $record_treasure->add($open_treasure);
													 if($Reco->Record_Shop($array) && $treasure_res){
																 $model->commit();
																 $data['state'] = 70007;
																 $data['content'] = '购买成功';
																 echo json_encode($data);
																 eixt;
													 }else{
																 $model->rollback();
																 $data['state'] = 70006;
																 $data['content'] = '购买失败';
																 echo json_encode($data);
																 eixt;
													 }
										 }else{
												 $data['state'] = 70012;
												 $data['content'] = '宝箱仓库修改失败';
												 echo json_encode($data);
												 exit;
										 }
								 }else{
										 $data['state'] = 70002;
										 $data['content'] = '商店修改失败';
										 echo json_encode($data);
										 exit;
								 }
							}else{
							   $data['state'] = 70011;
							   $data['content'] = '种子仓库修改失败';
							   echo json_encode($data);
							   exit;
							}
						}else{
							  $data['state'] = 70010;
							  $data['content'] = $arr['buy'].'不足';
							  echo json_encode($data);
							  exit;
					     }
					  }else{
						 $data['state'] = 70009;
						 $data['content'] = $arr['buy'].'不足';
						 echo json_encode($data);
						 exit;
					 }
		  }
}







?>
