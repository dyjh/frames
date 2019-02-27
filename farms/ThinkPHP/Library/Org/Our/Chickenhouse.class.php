<?php

namespace Org\Our;
use Think\Model;

class Chickenhouse
{
	/**
	**新注册赠送鸡舍**
	**$user:用户电话**
	**$id:鸡舍ID**
	**By 耗子**
	**/
	public function regist($user,$id=1){
		$add['buy_time']=time();
		$add['systems_give']=0;
		$add['maintenance_day']=0;
		$add['user']=$user;
		$data=M('chicken_shop')->where('id='.$id)->find();
		$conf=M('chicken_conf')->where('item="maintain_time"')->find();
		$record['name']=$data['name'];
		$day=$conf['value'];
		$add['end_time']=time()+($day*86400);
		//$BeginDate=date('Y-m-d', time());         //获取当天时间的日期格式
		//$add['end_time']=strtotime("$BeginDate +1 month");       //获取一个月后的时间戳
		if(M('chicken_house')->add($add)){
			return 'success';
		}else{
			return 'error';
		}
	}
	
	/**
	*****购买鸡舍*****
	**$user:用户电话**
	**$state:维护方式  2:果实  1：金币**
	**$id:鸡舍ID**
	*****By 耗子*****
	**/
	public function buy($user,$id,$state){
		$case_record=''.substr($user,0,3).'_maintain_record';
		if($state==1){
			return '金币维护尚未开通，敬请期待  sorry, Gold coin maintenance has not been opened, please look forward';
		}else{
			$data=M('chicken_shop')->where('id='.$id)->find();
			$member=M('chicken_house')->where('user='.$user.'')->find();
			if($member){
				$data['state'] = 99999;
				$data['content'] = '只能拥有一个鸡舍';
			    echo json_encode($data);
				exit;
			}
			$need_seed = explode('|',$data['fruit']);
			//print_r($need_seed);
			$i=0;
			for($a=0;$a<count($need_seed);$a++){
				$seed_list = explode(',',$need_seed[$a]);
				if($a==count($need_seed)-1){
					$where.= 'user='.$user.' and seeds="'.$seed_list[$i].'" AND num>='.$seed_list[$i+1].''; 
				}else{
					$where.= 'user='.$user.' and seeds="'.$seed_list[$i].'" AND num>='.$seed_list[$i+1].' or ';
				}
			}
			$case=''.substr($user,0,3).'_seed_warehouse';
			$fruit=M($case)->where($where)->select();
			$model=new Model();
			$model->startTrans();
			if(count($fruit)==count($need_seed)){
				for($a=0;$a<count($need_seed);$a++){
					$seed_list = explode(',',$need_seed[$a]);
					$save['num']=array('exp','num-'.$seed_list[1].'');
					if(M($case)->where('user='.$user.' AND seeds="'.$seed_list[0].'"')->save($save)==false){
						$model->rollback();
						$data['state'] = 99999;
					    $data['content'] = '扣除果实失败';
					    echo json_encode($data);
					    exit;        //扣除果实失败   回滚//
					}
				}
				$data=M('chicken_shop')->where('id='.$id)->find();
				$add['buy_time']=time();
				$add['systems_give']=1;
				
				$conf=M('chicken_conf')->where('item="maintain_time"')->find();
				$day=$conf['value'];
				$end=time()+($day*86400);
				$add['maintenance_day']=date('d',$end);
				
				//$BeginDate=date('Y-m-d', time());         //获取当天时间的日期格式
				//$add['maintenance_day']=date('d',strtotime("$BeginDate +1 month"));//获取一个月后的时间戳
				$add['user']=$user;
				$add['name']=$data['name'];
				$add['cost_fruit']=$data['fruit'];
				$add['end_time']=0;       
				if(M('chicken_house')->add($add)){
					$record['maintain_time']=time();
					//$record['due_time']=date('d',$end);
					$record['due_time']=$end;
					//$BeginDate=date('Y-m-d', time());         //获取当天时间的日期格式
					//$add['due_time']=date('d',strtotime("$BeginDate +1 month"));//获取一个月后的时间戳
					$record['user']=$user;
					$record['name']=$data['name'];
					$record['cost_fruit']=$data['fruit'];
					$record['cost_coin']=0; 
					if(M($case_record)->add($record)){
						$model->commit();
						$data['state'] = 10002;
					    $data['content'] = '购买鸡舍成功';
					    echo json_encode($data);
					    exit; 
					}else{
						$model->rollback();
						$data['state'] = 99999;
					    $data['content'] = '添加记录失败 ';
					    echo json_encode($data);
					    exit;   //添加记录失败   回滚
					}
				}else{
					$model->rollback();
					$data['state'] = 99999;
					$data['content'] = '添加记录失败 ';
					echo json_encode($data);
					exit; //添加记录失败   回滚
				}
			}else{
				$data['state'] = 10001;
				$data['content'] = '果实不足';
				echo json_encode($data);
				exit;
			}
		}
		
	}
	
	/**
	*****维护鸡舍*****
	**$user:用户电话**
	**$state:维护方式  2:果实  1：金币**
	**$id:鸡舍ID**
	*****By 耗子*****
	**/
	
	public function maintenance($user,$state){
		
		$house_new=M('chicken_house')->where('user='.$user.' AND systems_give=1')->order('id desc')->find();
		if($state==1){
			return '金币维护尚未开通，敬请期待  sorry, Gold coin maintenance has not been opened, please look forward';
		}else{
			$case_seed=''.substr($user,0,3).'_seed_warehouse';
			$case_record=''.substr($user,0,3).'_maintain_record';
			$maintain_record=M($case_record)->where('user='.$user.'')->order('id desc')->find();
			$data=M('chicken_conf')->where('item="maintain_cost_fruit"')->find();		
			$house=M('chicken_house')->where('user='.$user.' AND name="'.$data['name'].'" AND systems_give=1')->order('id desc')->find();
			if(empty($maintain_record)){
				if(empty($house)||date('d')!==$house['maintenance_day']||($house['buy_time']+86400)>time()){
					$data['state'] = 99999;
					$data['content'] = '今日购买的不需要维护';
					echo json_encode($data);
					exit;
				}
			}else{
				if(time()<$maintain_record['due_time']||($maintain_record['maintain_time']+86400)>time()){
					$data['state'] = 99999;
					$data['content'] = '今日购买的不需要维护';
					echo json_encode($data);
					exit;
				}
			}
		
			//echo ($house['buy_time']+86400);die;
			$today_end=mktime(0,0,0,date('m'),date('d'),date('y'))+86400;
			$today_start=mktime(0,0,0,date('m'),date('d'),date('y'));
			//判断当日是否已经维护过
			$record=M($case_record)->where('user='.$user.' AND name="'.$data['name'].'" AND maintain_time<'.$today_end.' AND maintain_time>'.$today_start.'')->find();
			if($record){
				$data['state'] = 99999;
				$data['content'] = '今天已经维护过了';
				echo json_encode($data);
				exit;
			}
			$need_seed = explode('|',$data['value']);
			//print_r($need_seed);
			$i=0;
			for($a=0;$a<count($need_seed);$a++){
				$seed_list = explode(',',$need_seed[$a]);
				if($a==count($need_seed)-1){
					$where.= 'user='.$user.' and seeds="'.$seed_list[$i].'" AND num>='.$seed_list[$i+1].''; 
				}else{
					$where.= 'user='.$user.' and seeds="'.$seed_list[$i].'" AND num>='.$seed_list[$i+1].' or ';
				}
			}
			$case=''.substr($user,0,3).'_seed_warehouse';
			$fruit=M($case)->where($where)->select();
			$model=new Model();
			$model->startTrans();
			if(count($fruit)==count($need_seed)){
				for($a=0;$a<count($need_seed);$a++){
					$seed_list = explode(',',$need_seed[$a]);
					$save['num']=array('exp','num-'.$seed_list[1].'');
					if(M($case)->where('user='.$user.' AND seeds="'.$seed_list[0].'"')->save($save)==false){
						$model->rollback();
						$data['state'] = 99999;
						$data['content'] = '扣除果实失败';
						echo json_encode($data);
						exit;       //扣除果实失败  // 回滚
					}
				}
				$data=M('chicken_shop')->where('name="'.$house_new['name'].'"')->find();
				$data_conf=M('chicken_conf')->where('item="maintain_cost_fruit"')->find();	
				$add['maintain_time']=time();
				$conf=M('chicken_conf')->where('item="maintain_time"')->find();
				$day=$conf['value'];
				$end=time()+($day*86400);
				$add['due_time']=$end;
				//$BeginDate=date('Y-m-d', time());         //获取当天时间的日期格式
				//$add['due_time']=date('d',strtotime("$BeginDate +1 month"));//获取一个月后的时间戳
				$add['user']=$user;
				$add['name']=$data['name'];
				$add['cost_fruit']=$data_conf['value'];
				$add['cost_coin']=0;  
				
				if(M($case_record)->add($add)){
					$model->commit();
					$data['state'] = 10002;
					$data['content'] = '鸡舍维护成功';
					echo json_encode($data);
					exit;
				}else{
					$model->rollback();
					$data['state'] = 99999;
					$data['content'] = '添加记录失败';
					echo json_encode($data);
					exit;    //添加记录失败   回滚
				}
			}else{
				$data['state'] = 10001;
				$data['content'] = '果实不足';
				echo json_encode($data);
				exit;
			}
		}
	}
}
