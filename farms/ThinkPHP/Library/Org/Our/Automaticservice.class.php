<?php
namespace Org\Our;
use Think\Model;
use Org\Our\Prop;
use Org\Our\Planting;

class Automaticservice{

	  //验证是否处于托管状态
	  static public function verify_trusteeship(){
		  
		  $tabel =  substr(session('user'),0,3).'_managed_to_record';
		  $res = M("$tabel")->where('service_type=5 and state=0 and user="'.$_SESSION['user'].'" and end_time>='.time())->find();
		  if($res){
				$data['state'] = 10007;
				$data['content'] = '正在托管中';
				echo json_encode($data);
				exit;
		   }
	  }



	  static public function trusteeship(){
		  
		  $table_fix = M('statistical')->field('name')->select();
		  $fix_count = count($table_fix);
		  $array = array();
		  //取得所有托管服务用户(包括等级)
		  for($i=0;$i<$fix_count;$i++){
			   $table = $table_fix[$i]['name'].'_managed_to_record';
			   $res = M("$table")->field($table.'.user,team_relationship.level')->join('team_relationship ON team_relationship.user = '.$table.'.user')->where('service_type=5 and state=0 and end_time>='.time())->select();
			   if($res){
				   $datacount = count($res);
				   for($j=0;$j<$datacount;$j++){
						array_push($array,$res[$j]);
				   }
			   }
		   }
		   		      	   		   
		   //查看种植记录
		   $service_users = count($array);   
		   for($k=0;$k<$service_users;$k++){
			   //种植表
			   $planting_table = substr($array[$k]['user'],0,3).'_planting_record';
			   //获取正在种植的记录
			   $planting_data = M("$planting_table")->field('seed_state,harvest_time,harvest_state,number')->where('user="'.$array[$k]['user'].'" and seed_state<4 and harvest_state=0')->select();
			   
	           //var_dump($planting_data);
	
			   //统计种植条数
			   $planting_count = count($planting_data);
               //遍历等级，等同于共有多少块土地
			   $landnum = array();
			   for($s=1;$s<$array[$k]['level']+1;$s++){
					array_push($landnum,$s);
			   } 
			   //echo $array[$k]['user'].'目前'.$array[$k]['level'].'级<br/>';
			   //遍历种植记录...
			   for($y=0;$y<$planting_count;$y++){
				   //echo $array[$k]['user'].$planting_data[$y]['number'].'<br/>'; 
				   //查看正在种植土地是否在等级范围内,并返回下标
				   $key = array_search($planting_data[$y]['number'],$landnum); 
				   //如果存在				   
				   if($key!==false){
					    //先进行清除
						array_splice($landnum,$key,1); 
						//查看目前该地是否是收获状态
						if($planting_data[$y]['seed_state']==3 && $planting_data[$y]['harvest_time']<=time()){
							  //进行自动收获，调用收获			
					          $prop = new Prop();
							  $state = $prop->harvest($array[$k]['user'],$planting_data[$y]['number'],'auto');
							  //如果收获成功，则立即需要种上
							  if($state==40003){
								   $data['user'] = $array[$k]['user'];
								   $data['level'] = $array[$k]['level'];
								   $data['number'] = $planting_data[$y]['number'];
								   $data['auto'] = 1;
								   new Planting($data);
							  }
						}  
				   }		
			   } 
			   //echo $array[$k]['user'].'级别是'.$array[$k]['level'].'级，共有'.$array[$k]['level'].'地，种了'.($array[$k]['level']-count($landnum)).'块，还剩下';
			   //var_dump($landnum).'<br/>';
			   
			    echo '<br/><br/>'.$array[$k]['user'].'<br/>';
			   
			   //如果剩余土地不为空
			   if(!empty($landnum)){
				    $landnum_count = count($landnum);
				    //遍历剩余土地块数
					for($x=0;$x<$landnum_count;$x++){
					     //进行自动播种
						 $data['user'] = $array[$k]['user'];
						 $data['level'] = $array[$k]['level'];
						 $data['number'] = $landnum[$x];
						 $data['auto'] = 1;
						 new Planting($data); 
					}
			   }
		   } 
	  }
 
}

?>
