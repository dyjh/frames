<?php
namespace Org\Our;
use Think\Model;

class activity{
	
	//生产碎片
	public function production($user){
		
	    $num = rand(1,10);
		
		if($num==1){
		
            //查询有无定向		
		    $debris_privilege = M('debris_privilege')->find();
			//如果存在定向
			if($debris_privilege){
				//查看定向是否是本人
				if($user==$debris_privilege['user']){ 
					 //echo '今天有定向，并且是你本人，定向物品是'.$debris_privilege['type']; 
					 //则为设置定向物品
				     $num = $debris_privilege['type'];
				}else{					
					//echo '今天有定向，但是不是本人，只能抽1-5。必须先等定向的出了才有6<br/>';
					//如果存在定向，并且不是本人
					$array = array(6,2,5);
					shuffle($array);
					$num = $array[0];	
				}
			}else{				
                //echo '今天没有定向，你可以抽1-6<br/>';	
				//$array = array(1,2,3,4,5,6); //如果不存在定向
				$array = array(6,2,5);
				shuffle($array);
				$num = $array[0];	
			}
				
			//如果是碎片6
			if($num==3){
				
				//查看今日是否已经发放完毕
				$limit_num = M('global_conf')->where(array('cases'=>'activity'))->find();
				//如果当天还有
				if($limit_num['value']>0){
					
					$table = substr($user,0,3).'_activity_warehouse';	
					//查询是否中过奖
					$list = M('activities_winning')->where(array('user'=>$_SESSION['user']))->find();
					//查询仓库是否有
					$name = '手机碎片'.$num;
					$res = M("$table")->where('name="'.$name.'" and user='.$_SESSION['user'])->find(); 
					//如果满足任何一项，则重新生成
					if($list || $res){	
						//echo '<br/>你已经中过奖或者有碎片6了，重新生成';	
						$array = array(6,2,5); 
						shuffle($array);
						$num = $array[0];
                    //否则当天数额减1						
					}else{						
						//echo '<br/>可以领取碎片6，系统将会减1';
						M('global_conf')->where(array('cases'=>'activity'))->setDec('value',1);
					}
				}else{
					//echo '<br/>今日超过限制，重新生成';
					$array = array(6,2,5); 
					shuffle($array);
					$num = $array[0];	  
				}
			}
						    
			$data['name'] = '手机碎片'.$num;
			/*for($i=0;$i<count($array);$i++){
				 echo $array[$i];
			}
			echo '<br/>你抽到了'.$data['name'];
			die;*/
			$data['user'] = $user;
            $data['num'] = 1;
			$data['time'] = 1508083200;

			$table = substr($user,0,3).'_activity_warehouse';			
			$res = M("$table")->where('name="'.$data['name'].'" and user='.$user)->find();			
			if($res){
				 if(M("$table")->where('name="'.$data['name'].'" and user='.$user)->setInc('num',1)){
					return $data['name'];
				 }else{
					return '';
				 }
			}else{	             
				if(M("$table")->add($data)){
					return $data['name'];
				}else{
					return '';
				}
			}
		}else{
			return '';
		}
	}
	
	//合成
	public function synthetic(){
		
		return '碎片数量不足，加油哟';
		break;
		
		//验证碎片
		/*$synthetic_list = array('手机碎片1','手机碎片2','手机碎片3','手机碎片4','手机碎片5','手机碎片6');
        
		$table = substr(session('user'),0,3).'_activity_warehouse';
        $res = 	M("$table")->where('user='.$_SESSION['user'])->select();
		$count = count($res);
		$temp = 0;
        if($res && $count==6){
			for($i=0;$i<$count;$i++){
			    if($res[$i]['num']>=1 && in_array($res[$i]['name'],$synthetic_list)){
					$temp++;
					continue;
				}else{
					return '碎片还没凑齐哟';
					break;
				}  
			}
			
            //是否有领取过
			if(M('activities_winning')->where(array('user'=>$_SESSION['user']))->find()){
			     return '你已经领取过了哟';
				 exit;
			}else{
				
				M()->startTrans();			
				if(M("$table")->where('name like "%手机碎片%" and user='.$_SESSION['user'])->setDec('num',1)){
					
					//消息滚动条
				    $members = substr(session('user'),0,3).'_members';
				    $nickname = M("$members")->field('nickname')->where(array('user'=>$_SESSION['user']))->find();
					
					//随机手机
					// $phone_list = array('IPHONE7','MATE9','OPPOR11','VIVOx9');
					$phone_list = array('VIVO X9');
					shuffle($phone_list);
					$data['nickname'] = $nickname['nickname'];
					$data['user'] = session('user'); 
					$data['prize'] = $phone_list[0];
					if(M('activities_winning')->add($data)){
						  M()->commit(); 
						 
						  //定义一个数组
						  $array = array();
						  //查看是否有缓存
						  $treasure_message = S('treasure_message');
						  $treasure_num = S('treasure_num');
						   
						  $level['user'] = $nickname['nickname'];
						  $level['activity'] = $phone_list[0];

						  //设置过期时间
						  $time=mktime(0,0,0,date('m'),date('d'),date('y'))+24*3600-time();
						  //如果不存在缓存
						  if($treasure_num==false){
							   Array_push($array,$level);  //将新添加数据加入空数组
							   S('treasure_message',$array,$time);  //新数组开启缓存
							   S('treasure_num',1,$time);  //计数从1开始
						  }else{
							   //如果存在缓存
							   Array_push($treasure_message,$level); //将新添加数据加入已有的缓存数组
							   $treasure_num = S('treasure_num')+1;   //计数加1
							   S('treasure_message',null);  //删除以前的缓存
							   S('treasure_num',null);    //删除以前的计数
							   S('treasure_message',$treasure_message,$time); //重新生成缓存
							   S('treasure_num',$treasure_num,$time);  //重新生成计数
						   }

						 return '恭喜你获得'.$phone_list[0].'一部';
						 exit;
					 }else{
						 M()->rollback();
						 return '合成失败了';
						 exit;
					 }					
				}else{
					 M()->rollback();
					 return '合成失败了';
					 exit;
				}
			}
		}else{
			return '碎片还没凑齐哟';
			exit;
		}*/
	}
}

?>



