<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Buychicken;
use Org\Our\Chickenhouse;


class PastureController extends Controller{

     //牧场主页
     public function index(){
		  
	    //先查看有无鸡舍
		$user_chicken_house = M("chicken_house")->field('systems_give,end_time')->where('user="'.session('user').'"')->find();
		//如果有鸡舍
		if($user_chicken_house){
			//有鸡舍
			$user_chicken_house_state = 1;
			//如果是系统赠送
			if($user_chicken_house['systems_give']==0){
				//那么到期时间就是赠送结束时间
				$end_time = '到期时间：'.date('m',$user_chicken_house['end_time']).'月'.date('d',$user_chicken_house['end_time']).'日';
			}else{
				//如果是自行购买的，则需要查询最近一条维护记录
				$maintain_table = substr(session('user'),0,3).'_maintain_record';
		        $res = M("$maintain_table")->order('id desc')->field('due_time')->where('user="'.session('user').'"')->find();
			    //如果过期时间小于了当前时间，说明该维护了
				if($res['due_time']<time()){
					$end_time = '已经过期了';
				}else{
					//到期时间等到最后一条到期时间
					$end_time = '到期时间：'.date('m',$res['due_time']).'月'.date('d',$res['due_time']).'日';
				}
			}
		}else{
			//没有鸡舍，需要购买
			$user_chicken_house_state = 0;
		}
		
	    //养殖记录
		$table = substr(session('user'),0,3).'_chicken_record';
		$res = M("$table")->where('user="'.session('user').'" and harvest_state=0')->select();
		if($res){
			$num = count($res);
		}else{
			$num = 0;
		}	
		
		$this->assign('user_chicken_house_state',$user_chicken_house_state);
		$this->assign('end_time',$end_time);
		$this->assign('res',$res);
		$this->assign('num',$num); 
        $this->display();			  
     }
	 

	 //菜单
	 public function menus(){
		  
		if(IS_AJAX){
			
		   $Post = I('post.');	
		   $menus_list = array('panic','manage','sell','income','maintain','house');
		   if(in_array($Post['type'],$menus_list) && is_numeric($Post['page']) && $Post['page']>0){
			   //抢购
			   if($Post['type']=='panic'){
				   
				    //获取用户的金币数
					//$members = substr(session('user'),0,3).'_members';
					//$user_coin = M("$members")->where('user="'.session('user').'"')->find();
								   
				    //获取小时
					$hour = date('H',time());
					//鸡仔抢购时间
					if($hour>=10 && $hour<12){
						$index = 0;	
					//小鸡抢购时间	
					}else if($hour>=12 && $hour<17){
						$index = 1;
					//母鸡抢购时间		
					}else if($hour>=17 && $hour<20){
						$index = 2;
					//公鸡抢购时间		
					}else if($hour>=20 && $hour<24){
						$index = 3;	
					}else{
						$index = 999;
					}	
										
					$time_data = array('10:00','12:00','17:00','20:00');
					
				    $res = M('chickens')->select();
				    if($res){	   	 

                       $str.= '<div class="panic_ji_num">商店剩余数量：'.$res[$index]['num'].'</div>';		
					
					   for($i=0;$i<count($res);$i++){
						   
						   if($index==999){
							   $str.= '<div class="pop_list" style="filter:alpha(opacity=20);-moz-opacity:0.2;-khtml-opacity:0.2;opacity:0.2;">';	
						   }else{
							   if($i==$index){
							       $str.= '<div class="pop_list">';	  
						       }else{
							       $str.= '<div class="pop_list" style="filter:alpha(opacity=20);-moz-opacity:0.2;-khtml-opacity:0.2;opacity:0.2;">';	
						       } 
						   }
                           $str.= '<div class="panic_time">'.$time_data[$i].'</div>';						  
						   $str.= '<div class="panic_type"><img src="/farms/Public/Home/images/ji/shop_'.$res[$i]['id'].'.png"></div>';  
						   $str.= '</div>';
					    }
	
						$need_fruit = explode('|',$res[$index]['fruit']);
						
						if(count($need_fruit)<=2){
							$top = 9;
						}else if(count($need_fruit)<=4){
							$top = 6;
						}else if(count($need_fruit)<=6){
							$top = 3;
						}else if(count($need_fruit)<=8){
							$top = 0;
						}

						$str.= '<div class="panic_firult" style="margin-top:'.$top.'%">';	
						if($index!==999){
							for($i=0;$i<count($need_fruit);$i++){
								$need_fruit_list = explode(',',$need_fruit[$i]);
								if($i%2==0){
								   $str.= '<div class="panic_firult_list">';
								   $str.= '<div class="firult_list"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($need_fruit_list[0]).'.png"><span>x'.$need_fruit_list[1].'</span></div>';
								 }else{
								   $str.= '<div class="firult_list"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($need_fruit_list[0]).'.png"><span>x'.$need_fruit_list[1].'</span></div>';
								   $str.= '</div>';
								 }
						    }
							
						}
			
					    $str.= '</div>';
						
                        //$str.= '<div class="pop_list_price">'.$user_coin['coin'].'</div>';						
				    }
					
			   //维护
			   }else if($Post['type']=='maintain'){
				    
				    $str = ''; 
				    //查询用户目前是什么鸡舍
					$chicken_house = M('chicken_house')->field('name')->where('user="'.session('user').'"')->find();
					//查询该鸡舍维护需要的东西
					//$need = M("chicken_shop")->where('name="'.$chicken_house['name'].'"')->find();
					$need = M("chicken_conf")->where('item="maintain_cost_fruit"')->find();
					//如果存在
					if($need){						
						$need_data = explode('|',$need['value']);
						for($i=0;$i<count($need_data);$i++){
							$need_list = explode(',',$need_data[$i]);
							$str.= '<div class="maintain_list_box">';
							$str.= '<div class="maintain_img"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($need_list[0]).'.png"></div>';
							$str.= '<div class="maintain_gold">'.$need_list[1].'个</div></div>';
							
						}
					}
			   //日志
		       }else if($Post['type']=='manage'){
  
                   $str = '';
				   $table = substr(session('user'),0,3).'_chicken_record';
				   $start_time = time()-3600*24*30;
                   $log_data = M("$table")->order('sell_time desc')->where('user="'.session('user').'" and buy_time>='.$start_time.' and buy_time<='.time())->select();
                   if($log_data){
					    $page_count = ceil(count($log_data)/7);
				        $res = array_slice($log_data,($Post['page']*7)-7,7);
						//组装div
						$str.= '<div class="manage_list_box">';
					    for($i=0;$i<count($res);$i++){
						    if($res[$i]['harvest_state']==0){
							    $str.= '<div class="manage_list"><span>'.date('m-d H:i:s',$res[$i]['buy_time']).'&nbsp;&nbsp;&nbsp;购买了'.$res[$i]['chicken_type'].'x1</span></div>';             
							}else{
							    $str.= '<div class="manage_list"><span>'.date('m-d H:i:s',$res[$i]['sell_time']).'&nbsp;&nbsp;&nbsp;出售了'.$res[$i]['chicken_type'].'x1</span></div>';
							}
						}	
						$str.= '</div>';
                        $str.= '<div class="manage_page_box">';
						if($Post['page']>1){
						   $str.= '<div class="manage_lastpage" onclick="pages(\'manage\',\'pre\')"></div>';
						}else{
						   $str.= '<div class="manage_lastpage"></div>';
						}
						$str.= '<div class="manage_page_text"><span>'.$Post['page'].'/'.$page_count.'</span></div>';
						if($Post['page']<$page_count){
						   $str.='<div class="manage_nextpage" onclick="pages(\'manage\',\'next\')"></div></div>';
						}else{
						   $str.='<div class="manage_nextpage"></div></div>';
						}
				   }else{
					   $str.='<div class="manage_list"><span>暂无日志</span></div>';   
				   }
				   
               //出售				
			   }else if($Post['type']=='sell'){
				   
				   $str = '';
				   
				   $table = substr(session('user'),0,3).'_chicken_record';
				   $sell_data = M("$table")->where('user="'.session('user').'" and harvest_state=0')->select();
				   if($sell_data){
					   
					    $page_count = ceil(count($sell_data)/7);
				        $res = array_slice($sell_data,($Post['page']*7)-7,7);
					    for($i=0;$i<count($res);$i++){
							$str.= '<div class="sell_list_box" id="sell_chicken_'.$res[$i]['id'].'">';
							    $str.= '<div class="sell_list_left">';
							    $str.= '<div class="chicken_img"><img src="/farms/Public/Home/images/ji/sell_'.$res[$i]['chicken_id'].'.png"></div>';
							    $str.= '<div class="chicken_type">'.$res[$i]['chicken_type'].'</div>';
							if($res[$i]['harvest_time']<=time()){
								$str.= '<div class="chicken_state">已经成熟</div>';
								$str.= '</div>';
								$str.= '<div class="chicken_shop" onclick="sell('.$res[$i]['id'].')"><span style="color:red">我要出售</span></div>';
							}else{
								$str.= '<div class="chicken_state">还有';
								   $day = ($res[$i]['harvest_time']-time())/(3600*24);
								   $day = floor($day);
								   $hour = (($res[$i]['harvest_time']-time())-($day*3600*24))/3600;
								   $hour = floor($hour);
								   $str.= $day.'天'.$hour.'小时成熟</div>';
								$str.= '</div>';
								$str.= '<div class="chicken_shop"><span>不能出售</span></div>';
							}
							$str.= '</div>';
						}
					    $str.= '<div class="sell_page_box">';
						if($Post['page']>1){
							$str.= '<div class="sell_lastpage" onclick="pages(\'sell\',\'pre\')"></div>';
						}else{
							$str.= '<div class="sell_lastpage" ></div>';
						}
					    $str.= '<div class="sell_page_text">'.$Post['page'].'/'.$page_count.'</div>';
					    if($Post['page']<$page_count){
							$str.= '<div class="sell_nextpage" onclick="pages(\'sell\',\'next\')"></div>';
						}else{
							$str.= '<div class="sell_nextpage"></div>';
						}
						$str.='</div>';
					   
				   }else{
					   $str.= '<div class="sell_list_box" style="color:#fff;text-indent:28%">鸡舍还没有养鸡</div>';
				   }
			   //收入
			   }else if($Post['type']=='income'){
					
                   $str = '';
				   
				   $table = date('Y-m').'_chicken_com_record';
				   $year = date('Y',time());
				   $mouth = date('m',time());
				   $start_time = strtotime($year.'-'.$mouth.'-01 00:00:00');
				   
				   $farming_table = substr(session('user'),0,3).'_chicken_record';
				   
				   $harvest_data = M("$farming_table")->field('sell_time,chicken_type,earnings')->where('user="'.session('user').'" and harvest_state=1 and sell_time>='.$start_time.' and sell_time<='.time())->select();
				   
				   if($harvest_data){
					   $page_count = ceil(count($harvest_data)/12);
				       $res = array_slice($harvest_data,($Post['page']*12)-12,12);
					   for($i=0;$i<count($res);$i++){
					       $str.= '<div class="income_list_sum">';
						   $str.= date('m/d',$res[$i]['sell_time']).'&nbsp;&nbsp;&nbsp出售了'.$res[$i]['chicken_type'].'，收益率 '.$res[$i]['earnings'].'%';
						   $str.= '</div>';
					   }
					   
					   $str.= '<div class="income_page_box">';
					   if($Post['page']>1){
							$str.= '<div class="income_lastpage" onclick="pages(\'income\',\'pre\')"></div>';
					   }else{
							$str.= '<div class="income_lastpage"></div>';
					   }
					   $str.= '<div class="income_page_text">'.$Post['page'].'/'.$page_count.'</div>';   
					   if($Post['page']<$page_count){
							$str.= '<div class="income_nextpage" onclick="pages(\'income\',\'next\')"></div>'; 
					   }else{
							$str.= '<div class="income_nextpage"></div>'; 
					   }
				   }else{
					   $str.= '<div class="income_list_sum" style="text-align:center">暂无收入记录</div>';
				   }
				   
				   
				   
				   
				   
				   //获取用户的静动态资金
				   /*$gold_table = substr(session('user'),0,3).'_users_gold';
				   $user_glod = M("$gold_table")->field('static_gold,dynamic_gold,user_coin')->where('user="'.session('user').'"')->find();
                   //获取资金明细
				   $income_data = M("$table")->order('id desc')->where('user="'.session('user').'" and time>='.$start_time.' and time<='.time())->select();
				   
				   $str.= '<div class="income_list_sum">';
				   $str.= '<div class="income_trends">'.$user_glod['dynamic_gold'].'</div>';
				   $str.= '<div class="income_statics">'.$user_glod['static_gold'].'</div>';
				   $str.= '<div class="income_all_sum">'.$user_glod['user_coin'].'</div>';
				   $str.= '</div>';
				   
				   if($income_data && $user_glod){
					
    					$page_count = ceil(count($income_data)/7);
				        $res = array_slice($income_data,($Post['page']*7)-7,7);
						
						for($i=0;$i<count($res);$i++){
							$str.= '<div class="income_list_sum">';
							if($res[$i]['type']==0){
							   $str.= date('m/d',$res[$i]['time']).'&nbsp;&nbsp;&nbsp;动态收入&nbsp;&nbsp;'.$res[$i]['money'];
						    }else{
							   $str.= date('m/d',$res[$i]['time']).'&nbsp;&nbsp;&nbsp;静态收入&nbsp;&nbsp;'.$res[$i]['money'];
						    }
							$str.= '</div>';
						}
						
						$str.= '<div class="income_page_box">';
						if($Post['page']>1){
							$str.= '<div class="income_lastpage" onclick="pages(\'income\',\'pre\')"></div>';
						}else{
							$str.= '<div class="income_lastpage"></div>';
						}
						$str.= '<div class="income_page_text">'.$Post['page'].'/'.$page_count.'</div>';   
						if($Post['page']<$page_count){
							$str.= '<div class="income_nextpage" onclick="pages(\'income\',\'next\')"></div>'; 
						}else{
							$str.= '<div class="income_nextpage"></div>'; 
						}
						
				   }else{
					   $str.= '<div class="income_list_sum">暂无收入记录</div>';   
				   }*/
				   
               //鸡舍				   
			   }else if($Post['type']=="house"){
				   
                      $str = '';				    
					  $need = M('chicken_shop')->field('fruit')->find();
					  if($need){
						  $need_data = explode('|',$need['fruit']);
						  for($i=0;$i<count($need_data);$i++){
								$need_list = explode(',',$need_data[$i]);
								$str.= ' <div class="maintain_list_box">';
								$str.= '<div class="maintain_img"><img src="/farms/Public/Home/images/fruit/'.image_name_icover($need_list[0]).'.png"></div>';
								$str.= '<div class="maintain_gold">'.$need_list[1].'个</div></div>';
								$str.= '</div>';
							  
						  }
					  }else{
						  echo '没有鸡舍可以卖';
					  }   
			   }

			   //返回数据
			   echo json_encode($str);
			   die;
			   
		   }else{
			  echo '非法数据'; 
		   }
		}else{
		   echo '请求错误';
		}  
	 }
	 
	 
	 
	 
	 //抢购
	 public function buy(){
		 
		if(IS_AJAX){
			
			$Post = I('post.');
				
			if(is_numeric($Post['buynum']) && $Post['buynum']>0){
				
				//获取小时
				$hour = date('H',time());
				//鸡仔抢购时间
				if($hour>=10 && $hour<12){
					$id = 1;	
				//小鸡抢购时间	
				}else if($hour>=12 && $hour<17){
				    $id = 2;
				//母鸡抢购时间		
				}else if($hour>=17 && $hour<20){
				    $id = 3;
				//公鸡抢购时间		
				}else if($hour>=20 && $hour<24){
				    $id = 4;	
				}else{
					$data['state'] = 99999; 
				    $data['content'] = '现在不在售卖时间';
				    echo json_encode($data);			  
				    exit;
				}	
				//调用购买类
				$buychicken = new Buychicken($id,session('user'),$Post['buynum']);
                $buychicken->chicken_state();		
				
			}else{
				echo '数据错误';
			}
		}else{
		   echo '请求错误';	
		}  
	 }
     
	 //出售
	 public function sell(){
       
        if(IS_AJAX){
			
			$Post = I('post.');
			if(is_numeric($Post['id']) && $Post['id']>0){
               //调用买卖类
               $buychicken = new Buychicken();
               $buychicken->sell($Post['id'],session('user')); 								
			}
		}else{
			echo '请求错误';
		}	   

	 }
	 
	 //维护
	 public function maintain(){
		 
		if(IS_AJAX){
			
		    $Chickenhouse = new Chickenhouse();
			$Chickenhouse->maintenance(session('user'),2);
			
		}else{
			echo '请求错误';
		}  
	 }
	 
	 //购买鸡舍
	 public function house(){
		 
		 if(IS_AJAX){
			
		    $Chickenhouse = new Chickenhouse();
			$Chickenhouse->buy(session('user'),1,2);
			
		}else{
			echo '请求错误';
		}  
		 
	 }
	 
}	

?>
