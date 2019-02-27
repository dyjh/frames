<?php

/*返回码*/
/*-2 封号状态*/
/*1 登陆成功*/
/*-1 请求错误*/
/*0 帐号密码有误*/

namespace Org\Our;
use Org\Our\Admin;
use Think\Model;
//用户操作类
class Adxyl{
	
	public function rstj($state){
		$f=0;
        $input=array();
        $data_s=M('statistical')->select();
        foreach ($data_s as $k=>$v){
            $cases=''.$v['name'].'_prop_warehouse';
			$data_m = M($cases)->where('props="种子" AND num<=99')->select();
            for($i=0;$i<count($data_m);$i++){
                $user=$data_m[$i]['user'];
                    $input[$f]=$data_m[$i];
                    $f++;		
            }
        }
		if($state == 1){
		$yi = array();
		foreach($input as $key=>$val){
			$sqluser = substr($val['user'], 0, 3);
			$prop = ''.$sqluser.'_members';
			$dd = M("$prop")->where('user='.$val['user'])->field('user,level,num_id,name,nickname')->find();
			$yi[$dd[level]][] = $dd;
			}
		
		$bhy_jr['yi'] = count($yi[1]);
		$bhy_jr['er'] = count($yi[2]);
		$bhy_jr['san'] = count($yi[3]);
		$bhy_jr['si'] = count($yi[4]);
		$bhy_jr['wu'] = count($yi[5]);
		$bhy_jr['liu'] = count($yi[6]);
		$bhy_jr['qi'] = count($yi[7]);
		$bhy_jr['zongshu'] = count($input);
		return $bhy_jr;
		}else if($state == 3){
		set_time_limit(0);
		$Admin = New Admin();
		
		$res[1] = $Admin->seed_level(1);
		$res[2] = $Admin->seed_level(2);
		$res[3] = $Admin->seed_level(3);
		$res[4] = $Admin->seed_level(4);
		$res[5] = $Admin->seed_level(5);
		$res[6] = $Admin->seed_level(6);
		$res[7] = $Admin->seed_level(7);
		$count = count($res)+1;
		$f = 0;
		$arr = array();
		for($i=1;$i<$count;$i++){
			$arr['tudou'] += $res[$i]['tudou'];
			$arr['caomei'] += $res[$i]['caomei'];
			$arr['yingtao'] += $res[$i]['yingtao'];
			$arr['daomi'] += $res[$i]['daomi'];
			$arr['fanqie'] += $res[$i]['fanqie'];
			$arr['putao'] += $res[$i]['putao'];
			$arr['boluo'] += $res[$i]['boluo'];
			$arr['zhongzi'] += $res[$i]['zhongzi'];
			$arr['zongshu'] += $res[$i]['zongshu'];
            $f++;		
            }
		return $arr;	
		}else{
		$w=0;
        $sl=array();
        foreach ($data_s as $k=>$v){
            $ca=''.$v['name'].'_members';
			$count=M($ca)->order('level')->count();
			$level=M($ca)->order('level')->field('user,level')->select();
			
			foreach($level as $a=>$b){
				$sl[$b['level']][] = $b;
			}
        }
		$jr['yi'] = count($sl[1]);
		$jr['er'] = count($sl[2]);
		$jr['san'] = count($sl[3]);
		$jr['si'] = count($sl[4]);
		$jr['wu'] = count($sl[5]);
		$jr['liu'] = count($sl[6]);
		$jr['qi'] = count($sl[7]);
		$jr['zongshu'] = count($sl[1])+count($sl[2])+count($sl[3])+count($sl[4])+count($sl[5])+count($sl[6])+count($sl[7]);
		return $jr;	
		}
	}
}
