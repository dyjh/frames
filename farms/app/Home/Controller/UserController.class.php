<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Planting;
use Org\Our\House;
use Org\Our\Prop;
use Org\Our\Pay;
use Org\Our\Material;
use Org\Our\Shop;
use Org\Our\Hosting;
use Org\Our\Treasure;
use Org\Our\Consume;
use Org\Our\Chest;
use Org\Our\Package;
use Org\Our\Activity;
use Org\Our\Exchange;
use Org\Our\Automaticservice;

class UserController extends Controller{

      /******土地相关操作******/

      //升级
      public function upgrade(){
		  
		  if(IS_AJAX && I('post.token')==session('token')){
			  $number = I("post.number");
			  if(is_int($number*1) && strlen($numbe)<13){
				  $user = session('user');
                  $House = new House();
                  $data = $House->exchange($user,$number);
			  }
		  } 
      }

      //种植
      public function planting(){
		  
		  Automaticservice::verify_trusteeship();
     	  $Post = I('post.');
		  if(IS_AJAX && $Post['token']==session('token')){
			  if(is_numeric($Post['number']) && is_numeric($Post['number'])>0 && is_numeric($Post['level']) && is_numeric($Post['level'])>0){
				  $Post['user'] = session('user');
				  $Post['auto'] = 0;
				  $Planting = new Planting($Post);
			  }else{
				  echo '请求错误';
			  }
		  }
      }

      //施肥
      public function fertilization(){
		  	  
		  $Post = I("post.");
		  if(IS_AJAX && $Post['token']==session('token')){ 
			  if(is_numeric($Post['number']) && is_numeric($Post['number'])>0 && is_numeric($Post['level']) && is_numeric($Post['level'])>0){
				  $user_fer = new Prop();
				  $user_fer->fertilization($Post['number']);
			  }
		  } 
      }

      //除灾
      public function disaster(){
		  
		  $Post = I("post.");
		  if(IS_AJAX && $Post['token']==session('token')){
              if(is_numeric($Post['number']) && is_numeric($Post['number'])>0 && is_numeric($Post['level']) && is_numeric($Post['level'])>0){
				  $user_fer = new Prop();
				  $user_fer->disasters($Post['number']);
			  }
		  } 
      }

      //收获
      public function harvest(){
		  
		  Automaticservice::verify_trusteeship(); 
          $Post = I("post.");
		  if(IS_AJAX && $Post['token']==session('token')){
              if(is_numeric($Post['number']) && is_numeric($Post['number'])>0){
				  $user_fer = new Prop();
                  $user_fer->harvest(session('user'),$Post['number'],'manual');
			  }
		  } 
      }


      /******购买相关操作******/

     //购买宝石
     public function diamond(){
				  /*$data['state'] = 70002;
                  $data['content'] = '兑换暂时关闭';
                  echo json_encode($data);
                  eixt;*/
		 if(IS_AJAX){
			$Post = I("post.");
			
			if(is_int($Post['count']*1)){
				if($Post['coins'] > 0 && ($Post['coins'] == 20 || $Post['coins'] == 200)){
					if(is_numeric($Post['coins'])){
						$Consume = new Consume();
						$Consume->Gem_For($Post);
					}
				}
		  }else{
			   $data['state'] = 70002;
			   $data['content'] = '请求错误';
			   echo json_encode($data);
			   exit;
		   } 
		}
     }

     //购买道具
     public function shopbuy(){
          $Post = I("post.");
		  if(is_int($Post['count']*1)){
			  if($Post['count'] > 0 && ($Post['id'] == 1 || $Post['id'] == 2 || $Post['id'] == 3 || $Post['id'] == 4 || $Post['id'] == 6)){
			      new Shop($Post);
			  }
		  }else{
			  $data['state'] = 70002;
			  $data['content'] = '请求错误';
			  echo json_encode($data);
			  exit;
		  }
     }

     //购买宝箱
     public function treasurebuy(){
		 
           $Post = I("post.");
		   if(is_int($Post['count']*1)){
			   if($Post['buy_count'] > 0 && ($Post['id'] == 7 || $Post['id'] == 8 || $Post['id'] == 9 || $Post['id'] == 10)){
				   $shop = M('shop')->where("id='".$Post['id']."'")->field('id,price,buy')->filter('strip_tags')->find();
				   if($Post['buy'] == $shop['buy'] && $Post['counts']%10==0 && $Post['counts']/10==$Post['buy_count']){			
					   $Treasure = new Treasure();
					   $Treasure->buy($Post);
				   }else{
						$data['state'] = 70002;
						$data['content'] = '请求错误';
						echo json_encode($data);
						exit;
				   }
				}
		  }else{
			   $data['state'] = 70006;
			   $data['content'] = '购买失败';
			   echo json_encode($data);
			   exit;
		  }
     }

     //购买服务
     public function servicebuy(){
		 
          $Post = I("post.");
		  if(is_int($Post['count']*1)){
			  if($Post['count'] > 0 && ($Post['id'] == 11 || $Post['id'] == 12 || $Post['id'] == 13 || $Post['id'] == 14 || $Post['id'] == 15)){
				 $Hosting = new Hosting();
				 $Hosting->buy($Post);
			  }
		  }else{
			  $data['state'] = 70002;
			  $data['content'] = '请求错误';
			  echo json_encode($data);
              exit;
		  }
     }

     //购买材料(兑换)
     public function Material(){
         $Post = I("post.");
         $material = new Material();
         $material->exchange($Post['id'],$Post['count'],$Post['cases']);
     }

  
    /**游戏操作类**/
    
    /**查询升级状态**/
    public function upgrade_state(){
	
          if(IS_AJAX && I('post.token')==session('token')){
			  
               $house = M("house");
               $house_material = M("house_material");
			   $level = $_POST['id'];
			   
			   if(is_numeric($level) && $level<13){
				     $res = $house->field('cost')->where(array('level'=>$level))->find();
					 if($res){
						  $res = material_handle_one($res);
						  $need_material = $house_material->field('id,name')->select();
						  $need_material[count($need_material)]['name'] = '升级宝石';
						  $need_material[count($need_material)-1]['id'] = 'price';

						  for($i=0;$i<count($need_material);$i++){
							  foreach($res as $v=>$key){
								  if($v=="cost"){
									   continue;
								  }else{
									  if($need_material[$i]['id']==$v){
										  $need_material[$i]['num'] = $key;
									  }
								  }
							  }
						  }
						  
						  $str = '';
						  $str.= '<div class="level_box">
								   <div class="level_level"><img src="/farms/Public/Home/images/index/LV_'.$level.'.png"></div>
								   <div class="level_center">
									   <img src="/farms/Public/Home/images/index/upgrade.png">
									   <button id="a_qd" onclick="User_action(\'upgrade\')"></button>
								   </div>
								   <div class="level_words">
									   <span>所需材料</span>
								   </div>
								   <div class="level_data">';

									  for($i=0;$i<count($need_material);$i++){
											$str.='<div class="level_list">';
											$str.='<div class="level_list_img"><img src="/farms/Public/Home/images/index/'.image_name_icover($need_material[$i]['name']).'.png"></div>';
											$str.='<div class="level_list_words"><span>';
											if(isset($need_material[$i]['num'])){
												  $str.=$need_material[$i]['num'];
											}else{
												  $str.=0;
											}
											$str.='</span></div>';
											$str.='</div>';
									  }
									  
					      echo json_encode($str);	  
					  }
			     }
           }
    }

    //签到
    public function sign(){
		
		  if(IS_AJAX && I('post.token')==session('token')){
			  
			  $table_fix = substr(session('user'),0,3);		  
			  $user_table = $table_fix."_members";
			  $sign = M("$user_table")->field('sign_state')->where(array('user'=>session('user')))->find();
			  if($sign['sign_state']==1){
				  echo '';
				  exit;
			  }else{
				  $table = $table_fix."_prop_warehouse";
				  $user_table = $table_fix."_members";
				  $prop = array('除草剂','除虫剂','水壶','肥料');
				  shuffle($prop);
				  
				  M()->startTrans();
				  
				  if($prop[0]){
					   $shop = M('shop');
					   $res = $shop->field('id')->where(array('name'=>$prop[0]))->find();
					   if($res){
							$array['user'] = session('user');
							$array['props'] = $prop[0];
							$array['prop_id'] = $res['id'];
							$array['num'] = 1;
							$list = M("$table")->where('user="'.$_SESSION['user'].'" and props="'.$prop[0].'"')->find();

							if($list==null){
								  $arr['sign_state'] = 1;
								  $rss = M("$user_table")->where('user="'.$_SESSION['user'].'"')->save($arr);
								  if(M("$table")->add($array) && $rss){
									  M()->commit();
									  echo $prop[0];
								  }else{
									  echo '';
									  M()->rollback();
								  }
							}else{
								 $arr['sign_state'] = 1;
								 $rss = M("$user_table")->where('user="'.$_SESSION['user'].'"')->save($arr);
								 if(M("$table")->where('user="'.$_SESSION['user'].'" and props="'.$prop[0].'"')->setInc('num',1) && $rss){
									   M()->commit();
									  echo $prop[0];
								 }else{
									  echo '';
									  M()->rollback();
								 }
							}
					   }
				  }  
			  }
		  }
      }

	  //头像选择
      public function sel_head(){

           if(IS_AJAX){
				$user=$_SESSION['user'];
				$user_num=substr($user,8,4);
			    $member_count = M('total_station')->field('member_num')->where(array('id'=>1))->find();
			    $num = $member_count['member_num']+100+1;
				$num .=rand(100,999);
				$num .=$user_num;
				$data['num_id']=$this->num_id($num,$member_count);


                $data['headimg'] = '/farms/Public/Home/images/headimg/head'.$_POST['headnumber'].'.png';
                $data['nickname'] = $_POST['nickname'];
                $table_fix = substr(session('user'),0,3);
                $table = $table_fix."_members";              
				$res = M("$table")->where("user='%s'",$_SESSION['user'])->save($data);
				//$res = M("$table")->where('user="'.$_SESSION['user'].'"')->save($data);
				if($res){
                     echo 1;
                }else{
                     echo 0;
                }
           }
      }
	  
	  
	  public function num_id($num,$member_count){
            $user=$_SESSION['user'];
            $user_num=substr($user,8,4);
            $for=M('statistical')->select();
            
			/*foreach($for as $k=>$v){
                $case=''.$v['name'].'_members';
                $data=M($case)->where('num_id='.$num)->find();
                if($data){
                	unset($num);
                    $num = $member_count['member_num']+10000+1;
                    $num .=rand(100,999);
                    $num .=$user_num;
                    $this->num_id($num);
                }else{
                    return $num;
                }
            }*/
			
			foreach($for as $k=>$v){
                $case=''.$v['name'].'_members';
                $data=M($case)->where('num_id='.$num)->find();
                if($data){
					$temp = true;	
                }
            }
			
			if($temp){
				 $num = $member_count+100+1;
                 $num .=rand(100,999);
                 $num .=$user_num;
                 return $this->num_id($num,$member_count);
			}
			
			return $num;
		}
		
	  //开宝箱
      public function openbox(){
		  
		 if(IS_AJAX){
			 
			 $Post = I('post.');
             $rate_number = intval($Post['rate_number']);
			 if(is_string($Post['boxname']) && strlen($Post['boxname'])==12 && is_numeric($rate_number) && $rate_number>0 && $rate_number<=1000){		
				  $Chest = new Chest();
                  $Chest->Chest($Post['boxname'],$Post['rate_number']);
			}else{
				echo '请求错误';
			}
		 }  
      }
	   
	  //大礼包
	  public function spree(){  
  
		 if(IS_AJAX && I('post.token')==session('token')){
			 $spree = new Package();
		     $spree->get($_SESSION['user']);
		 }
	  }
	  
	  //公告
	  public function notice(){
		  
		  if(IS_AJAX){
			  M('users_behavior')->where(array('user'=>$_SESSION['user']))->setInc('content_num',$_POST['length']);
		  }
	  }
	  
	  //手机碎片活动
	  public function synthetic(){
		  if(IS_AJAX){
			  $Activity = New activity();
			  $Acti_message = $Activity->synthetic();
			  echo $Acti_message;
		  }
	  }
	  
	  
	  //催熟(时间差导致)
	  public function lateripe(){
		  if(IS_AJAX && is_numeric($_POST['number']) && $_POST['number']>0){
               $table = substr(session('user'),0,3).'_planting_record';
			   $res = M("$table")->where('user="'.$_SESSION['user'].'" and seed_state=2 and harvest_state=0 and number='.$_POST['number'])->find();
			   if($res && time()>=$res['harvest_time']){
				    $data['time'] = $res['harvest_time'];
					$data['seed_state'] = 3;
				    M("$table")->where('user="'.$_SESSION['user'].'" and seed_state=2 and harvest_state=0 and number='.$_POST['number'])->save($data);
			   }
		  }
	  }
	  
	  
	//果实重生  
	public function reborn(){ 
		if(IS_AJAX){
			//
			//S('reborn',null);die;
			
			if(S('reborn')==false){
				//echo 'hello';
				S('reborn',0);
			}

			if(S('reborn')==0){
				//开始处理
				S('reborn',null);
				S('reborn',1);
				//echo '开始处理';
				//处理流程。。。。。。     
				//if($_SESSION['user'] == '15802858094'){
				$config = M('global_conf')->field('value')->where('id=25')->find();
				//var_dump($config['status']);die;
					if($config['value'] == 1){
						$user = $_SESSION['user'];
						$num  = intval(I('post.number'));
						$type = I('post.seeds');
						$crit = intval(I('post.crit'));
						//echo '处理完了';
						$exchange = new Exchange();
						$exchange_message = $exchange->test_exchange($user,$num,$type,$crit);						
						return $exchange_message;											
					}else{
						$data['state'] = 30000;
						$data['content'] = '种子已经超出限制';
						//清除缓存
						S('reborn',null);
						S('reborn',0);
						echo json_encode($data);
						exit;
					}
				//}
					
				//处理完毕后
				
			}else{
				$data['state'] = 3003;
				$data['content'] = '当前重生人数过多请稍后再试';
				echo json_encode($data);
				S('reborn',null);
				S('reborn',0);
				exit;
			}		  
		}
	} 
	
	public function exchange_service(){
		
		if(IS_AJAX){
			$Post = I('post.');
			if($Post['id']>10 && $Post['id']<16 && $Post['count']>0 && is_numeric($Post['count'])){
			    $Hosting = new Hosting();
				$Hosting->exchange($Post);	
			}else{
				echo '请求错误';
			}
		}
	}
}
?>
