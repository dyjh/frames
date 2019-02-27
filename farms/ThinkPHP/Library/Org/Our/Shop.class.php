<?php
namespace Org\Our;
use Think\Model;
use Org\Our\Consume;
use Org\Our\Record;


/**返回值**/
/* 1 成功*/
/* -1 余额不足*/
/* -2  失败*/
/* 0  库存不足 */

//商店购买类
class Shop{

    private $arr;

    function __construct($arr){
        $this->arr = $arr;
        $this->Shop_Buy($this->arr);
    }

    private function Shop_Buy($list){

        $model = new Model();
        $model->startTrans();

		//$y = date('Y');
		//$m = date('m');
		//$d = date('d');
		
		//$today = mktime(0,0,0,$m,$d,$y);

		//$tomorrow = $today+86400;
		
        $user = session('user');
        $table_fix = substr($user,0,3);
        $sel = M('shop')->where('id='.$list['id'])->find(); //查询当前道具情况
		//$sel_seeds = M('shop')->where('id=6')->find(); //查询当前种子售价情况
        $user_table = $table_fix.'_members';
		//$user_shop = $table_fix.'_record_shop';
        $user_message = M("$user_table")->field('diamond')->where('user="'.$user.'"')->find();
		//$shop_record = M("$user_shop")->where('user="'.$user.'" AND name="种子" AND buy_time>="'.$today.'" AND buy_time<="'.$tomorrow.'"')->sum('num');
		
		//$shop_count = ($shop_record/1000)*$sel_seeds['price'];
		
        if($user_message['diamond']<$list['count']*$sel['price']){
            $data['state'] = 70003;
            $data['content'] = '宝石不够';
            echo json_encode($data);
            exit;
        }
		
		//$user_seed = $table_fix.'_prop_warehouse';
		//$user_zhongzi = M("$user_seed")->where('user="'.$user.'" AND props="种子"')->find();
		
        /*if($list['id']==6 && $user_zhongzi['num']>= 50000){
            $data['state'] = 70001;
            $data['content'] = '已超过最大拥有量';
            echo json_encode($data);
            exit;
        }*/
		
		/*$edu = M('global_conf')->where('id=22')->find();
		if($list['id']==6 && (($shop_count+($list['count']*$sel['price']))>$edu['value'])){
			$data['state'] = 70001;
            $data['content'] = '已超过今日购买限额';
            echo json_encode($data);
            exit;
		}*/
	
		$shji = time();
		$today = 1504617428;
		//$tomorrows = $today+34200; //早上9点半
		//$tomorrows = $today+48600;//下午1点半
		//$tomorrows = $today+73800;//晚上8点半
		//$time_shop = M('global_conf')->where('id=21')->find();
		//$tomorrows = $today+$time_shop['value'];
		if($list['id']==6 && $shji>$today){
			 $data['state'] = 70001;
			 $data['content'] = '种子已售罄';
			 echo json_encode($data);
			 exit;
		}
		
		/*if($list['id']==4 && $list['count']>777){
			$data['state'] = 70001;
			$data['content'] = '单次购买数量不可大于777';
			echo json_encode($data);
			exit;
		}*/
	

        if($sel['num']>=$list['count']){  //如果商店数量大于或等于购买数量
              //商店减去相应的数量
              if(M('shop')->where('id='.$list['id'])->setDec('num',$list['count'])){
                   $table = $table_fix.'_prop_warehouse';
                   $condition['user'] = $user;
                   $condition['props'] = $sel['name'].'';
                   $set = M("$table")->where($condition)->find();
                   //如果用户不存在，则添加，否则修改
				   
				/*   if($list['id']==4){
					   $data['state'] = 70007;
                       $data['content'] = '化肥暂停购买，约十五分钟后开放';
                       echo json_encode($data);
                       exit;
				   }*/
				   

                   $array['user'] = $user;
				   if($list['id']==6){
					   $array['num'] = $list['count']*1000;
					   
				   //化肥活动，结束后取消elseif
				   /*}else if($list['id']==4){
					    $start = 1503898200;
					   $end = 1504071000;
					   $count = M("$user_shop")->where('user='.$_SESSION['user'].' and buy_time>='.$start.' and buy_time<='.$end.' and name="肥料"')->sum('num');
					   if(777-$count/2>=$list['count']){
						   $array['num'] = $list['count']*2;
						   $temp = 0;
					   }else if(777-$count/2<=0){
						   $array['num'] = $list['count'];
						   $temp = 1;
					   }else{
						   $array['num'] = $list['count']+777-$count/2;
						   $temp = 0;
					   }*/

				   }else{
					   $array['num'] = $list['count'];
				   }  
				   
                   $array['price'] = $sel['price'];
                   $array['name'] =  $sel['name'];
                   $array['props'] = $sel['name'];
                   $array['prop_id'] = $list['id'];
                   $array['type'] = 'b';

                   if($set!==null){
					   if($list['id']==6){
						   $res = M("$table")->where($condition)->setInc('num',$array['num']);
					   }else{
						   $res = M("$table")->where($condition)->setInc('num',$array['num']);
					   }
                   }else{
                       $res = M("$table")->add($array);
                   }

				   
				   
                   if($res){
                        $Reco = new Record();
                        if($Reco->Record_Shop($array)){                             
							  /*if($list['id']==4 && $temp!==1){
					               $array['num'] = $list['count'];  
				              }*/						  
							  $Cons = new Consume();
                              if($Cons->Gem_Consume($array,$list['id'])){
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
                            $data['state'] = 70005;
                            $data['content'] = '宝石修改失败';
                            echo json_encode($data);
                            exit;
                        }
                   }else{
                       $data['state'] = 70004;
                       $data['content'] = '道具仓库修改失败';
                       echo json_encode($data);
                       exit;
                   }
              }else{
                  $data['state'] = 70002;
                  $data['content'] = '系统错误';
                  echo json_encode($data);
                  exit;
              }
        }else if($sel['num']==0 || $sel['num']<0){
            $data['state'] = 70001;
            $data['content'] = '商店已经卖完';
            echo json_encode($data);
            exit;
       }else{
		    $data['state'] = 70001;
            $data['content'] = '可购买仅剩'.$sel['num'];
            echo json_encode($data);
            exit;
	   }
    }
}
