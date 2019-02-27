<?php
namespace Think;

class Find{
//获取当天零点和24点的时间戳
    public function time($data){
        $time = date("Y-m-d",$data);
        $y = substr($time, 0, 4);
        $m = substr($time,6, 2);
        $d = substr($time,9, 2);
        $ed = $d+1;
        $times['start'] = mktime(0,0,0,$m,$d,$y);
        $times['end'] = mktime(0,0,0,$m,$ed,$y);
        return $times;
    }
//获取随机会员数据    
    public function table($tel,$case){
        $prefix=substr($tel, 0, 3);
        switch ($case) {
          case 'members':
            return $prefix."_members";    //会员表
            break;
          case 'member_record':
            return $prefix."_member_record";    //会员统计表
            break;
          case 'share_out_bonus':
            return $prefix."_share_out_bonus";    //分红宝
            break;
          case 'fruit_record':
            return $prefix."_fruit_record";    //会员表
            break;
          case 'managed_to_record':
            return $prefix."_managed_to_record";   //服务记录
            break;
          case 'order':
            return $prefix."_order";    //订单
            break;
          case 'planting_record':
            return $prefix."_planting_record";  //种植记录
             break;
          case 'prop_warehouse':
            return $prefix."_prop_warehouse";   //道具仓库
            break;
          case 'seed_warehouse':
            return $prefix."_seed_warehouse";   //种子仓库
            break;
        }
    }
    
    public function total($start_time,$end_time,$seed,$type,$user,$c,$trans_type){
        //print_r($start_time);print_r($end_time);//die;
        //echo 1;print_r($seed);die;
        $data_mou=M('matching_statistical')->select();
        $mou=$data_mou[0]['name'];
        $m_y=substr($mou,0,4);
        $m_m=substr($mou,5,2);
        $m_d=01;
        $m= mktime(0,0,0,$m_m,$m_d,$m_y);

        $s_y=substr($start_time,0,4);
        $s_m=substr($start_time,5,2);
        $s_d=substr($start_time,8,2);
        $thismonth = $s_m;
        $thisyear = $s_y;
        if ($thismonth == 12) {
            $s_m_e = 1;
            $s_y_e = $thisyear + 1;
        } else {
            $s_m_e = $thismonth + 1;
            $s_y_e = $thisyear;
        }
        $s_d_e=01;
        $start_end= mktime(0,0,0,$s_m_e,$s_d_e,$s_y_e);
        $start_start= mktime(0,0,0,$s_m,$s_d,$s_y);
    
        $cases_start=''.$s_y.'-'.$s_m.'_'.$c.'';
        $e_y=substr($end_time,0,4);
        $e_m=substr($end_time,5,2);
        $e_d=substr($end_time,8,2);
        $e_d_s=01;
        $end=''.$e_y.'-'.$e_m.'';
        $start=''.$s_y.'-'.$s_m.'';
        $end_start= mktime(0,0,0,$e_m,$e_d_s,$e_y);
        $end_end= mktime(0,0,0,$e_m,$e_d,$e_y);
        $end_end=$end_end+24*3600;
        $cases_end=''.$e_y.'-'.$e_m.'_'.$c.'';
        if($m>$start_start&&$end_end>time()){
            $data['sstate_t']=1;
        }else{

            $data_e=M('matching_statistical')->where('name="'.$end.'"')->find();
            $data_s=M('matching_statistical')->where('name="'.$start.'"')->find();

            //判断sql语句
            if(empty($seed)&&empty($type)){
                //echo $trans_type;die;
                $sql_total='user="'.$user.'"';
                $sql_e='time >= "'.$end_start.'" AND time <="'.$end_end.'" AND user="'.$user.'"';
                $sql_s='time >= "'.$start_start.'" AND time <="'.$start_end.'" AND user="'.$user.'"';
            }elseif (!empty($seed)&&!empty($type)){
				if($type==2){
					$type=0;
				}
                $sql_total='user="'.$user.'"  AND seed = "'.$seed.'" AND type = "'.$type.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND user="'.$user.'" AND seed = "'.$seed.'" AND type = "'.$type.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND user="'.$user.'" AND seed = "'.$seed.'" AND type = "'.$type.'"';
            }elseif (!empty($seed)&&empty($type)){
                $sql_total='user="'.$user.'"  AND trans_type = "'.$trans_type.'" AND seed = "'.$seed.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= "'.$end_end.'"  AND user="'.$user.'" AND seed = "'.$seed.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND user="'.$user.'" AND seed = "'.$seed.'"';
            }elseif (empty($seed)&&!empty($type)){
                //echo 1;die;
				if($type==2){
					$type=0;
				}
                $sql_total='user="'.$user.'" AND type = "'.$type.'"';
                $sql_e='time >= "'.$end_start.'" AND time <= "'.$end_end.'" AND user="'.$user.'" AND type = "'.$type.'"';
                $sql_s='time >= "'.$start_start.'" AND time <= "'.$start_end.'" AND user='.$user.' AND type = "'.$type.'"';
            }
            $data_time=M('matching_statistical')->where('id > "'.$data_s['id'].'"  AND id < " '.$data_e['id'].'"')->select();
            $data=M('Global_conf')->where('cases="poundage"')->find();
            $poundage=$data['value'];
            //start月
            $data=M($cases_start)->where($sql_s)->select();
			//print_r($sql_s);die;
            foreach ($data as $k=>$v){
                if($v['type']==0){
                    $data[$k]['poundage']=$v['money']*$v['num']*$poundage;
                    $data[$k]['total']=$v['money']*$v['num']-$data[$k]['poundage'];
                    $data[$k]['num']=$data[$k]['submit_num']-$data[$k]['num'];
                }else{
                    $data[$k]['poundage']='';
                    $data[$k]['total']=$v['money']*$v['num'];
                    $data[$k]['num']=$data[$k]['submit_num']-$data[$k]['num'];
                }
            }
            $i=0;
            foreach ($data_time as $k=>$v){
                $cases=''.$v['name'].'_'.$c.'';
                $data_end_t[$k]=M(''.$cases.'')->where($sql_total)->select();
                foreach ($data as $key=>$val){
                    if($v['type']==0){
                        $data_end_t[$k][$key]['poundage']=$val['money']*$val['num']*$poundage;
                        $data_end_t[$k][$key]['total']=$val['money']*$val['num']-$data_end_t[$k][$key]['poundage'];
                        $data_end_t[$k][$key]['num']=$data_end_t[$k][$key]['submit_num']-$data_end_t[$k][$key]['num'];
                    }else{
                        $data_end_t[$k][$key]['poundage']='';
                        $data_end_t[$k][$key]['total']=$val['money']*$val['num'];
                        $data_end_t[$k][$key]['num']=$data_end_t[$k][$key]['submit_num']-$data_end_t[$k][$key]['num'];
                    }
                }
                $i++;
            }
            //中间月
            for($k=0;$k<$i;$k++){
                if(empty($data)&&!empty($data_end_t[$k])){
                    $data=$data_end_t[$k];
                }elseif (!empty($data)&&empty($data_end_t[$k])){
                    $data=$data;
                }elseif (!empty($data)&&!empty($data_end_t[$k])){
                    $data = array_merge($data,$data_end_t[$k]);
                }
            }

            //end月
            $data_end=M($cases_end)->where($sql_e)->select();
			//echo M($cases_end)->Getlastsql();die;
            foreach ($data_end as $k=>$v){
                if($v['type']==0){
                    $data_end[$k]['poundage']=$v['money']*$v['num']*$poundage;
                    $data_end[$k]['total']=$v['money']*$v['num']-$data_end[$k]['poundage'];
                    $data_end[$k]['num']=$data_end[$k]['submit_num']-$data_end[$k]['num'];
                }else{
                    $data_end[$k]['poundage']='';
                    $data_end[$k]['total']=$v['money']*$v['num'];
                    $data_end[$k]['num']=$data_end[$k]['submit_num']-$data_end[$k]['num'];
                }
            }
			//print_r($data_e['id']);
			//print_r($data_end);die;
			if($data_s['id']!=$data_e['id']){
				if(empty($data)&&!empty($data_end)){
					$data=$data_end;
				}elseif (!empty($data)&&empty($data_end)){
					$data=$data;
				}elseif (!empty($data)&&!empty($data_end)){
					$data = array_merge($data,$data_end);
				}
			}
        }
		
        return $data;
    }
	public function total_pay($start_time,$end_time,$seed,$type,$user){
        //print_r($end_time);die;
        //echo 1;print_r($seed);die;
        $data_mou=M('matching_statistical')->select();
        $mou=$data_mou[0]['name'];
        $m_y=substr($mou,0,4);
        $m_m=substr($mou,5,2);
        $m_d=01;
        $m= mktime(0,0,0,$m_m,$m_d,$m_y);

        $s_y=substr($start_time,0,4);
        $s_m=substr($start_time,5,2);
        $s_d=substr($start_time,8,2);
        $thismonth = $s_m;
        $thisyear = $s_y;
        if ($thismonth == 12) {
            $s_m_e = 1;
            $s_y_e = $thisyear + 1;
        } else {
            $s_m_e = $thismonth + 1;
            $s_y_e = $thisyear;
        }
        $s_d_e=01;
        $start_end= mktime(0,0,0,$s_m_e,$s_d_e,$s_y_e);
        $start_start= mktime(0,0,0,$s_m,$s_d,$s_y);
    
        $cases_start=''.$s_y.'-'.$s_m.'_matching';
        $e_y=substr($end_time,0,4);
        $e_m=substr($end_time,5,2);
        $e_d=substr($end_time,8,2);
        $e_d_s=01;
        $end=''.$e_y.'-'.$e_m.'';
        $start=''.$s_y.'-'.$s_m.'';
        $end_start= mktime(0,0,0,$e_m,$e_d_s,$e_y);
        $end_end= mktime(0,0,0,$e_m,$e_d,$e_y);
        $end_end=$end_end+24*3600;
        $cases_end=''.$e_y.'-'.$e_m.'_matching';
		
        if($m>$start_start&&$end_end>time()){
            $data['sstate_t']=1;
        }else{
//echo 100;echo $type;echo $seed;die;
            $data_e=M('matching_statistical')->where('name="'.$end.'"')->find();
            $data_s=M('matching_statistical')->where('name="'.$start.'"')->find();
            //判断sql语句
            if(empty($seed)&&empty($type)){
				//echo 2;die;
                //echo $trans_type;die;
                $sql_total='sell_user="'.$user.'" or buy_user="'.$user.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND sell_user="'.$user.'" or time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND buy_user="'.$user.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND sell_user="'.$user.'" or time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND buy_user="'.$user.'"';
            }elseif (!empty($seed)&&!empty($type)){
				if($type==1){
					$name='buy_user';
				}else{
					$name='sell_user';
				}
                $sql_total=''.$name.'="'.$user.'"  AND seed = "'.$seed.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'"  AND '.$name.'="'.$user.'" AND seed = "'.$seed.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND '.$name.'="'.$user.'" AND seed = "'.$seed.'"';
            }elseif (!empty($seed)&&empty($type)){
				//echo 3;die;
				$sql_total='sell_user="'.$user.'"  AND seed = "'.$seed.'" or buy_user="'.$user.'"  AND seed = "'.$seed.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'"  AND sell_user="'.$user.'" AND seed = "'.$seed.'" or time >= "'.$end_start.'"  AND time <= " '.$end_end.'"  AND buy_user="'.$user.'" AND seed = "'.$seed.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= " '.$start_end.'"  AND sell_user="'.$user.'" AND seed = "'.$seed.'" or time >= "'.$start_start.'"  AND time <= " '.$start_end.'"  AND buy_user="'.$user.'" AND seed = "'.$seed.'"';
            }elseif (empty($seed)&&!empty($type)){
                //echo 1;die;
				if($type==1){
					$name='buy_user';
				}else{
					$name='sell_user';
				}
                $sql_total=''.$name.'="'.$user.'"';
                $sql_e='time >= "'.$end_start.'" AND time <= " '.$end_end.'"  AND '.$name.'="'.$user.'"';
                $sql_s='time >= "'.$start_start.'" AND time <= " '.$start_end.'"  AND '.$name.'="'.$user.'"';
            }
            $data_time=M('matching_statistical')->where('id > "'.$data_s['id'].'"  AND id < " '.$data_e['id'].'"')->select();
            //start月
            $data=M($cases_start)->where($sql_s)->select();
			//echo M($cases_start)->getLastsql();die;
            //print_r($data_e['id']);die;
            //中间月
			if($data_s['id']!=$data_e['id']){
				$c=$data_s['id']+1;
				if($c!=$data_e['id']){
					$i=0;
					foreach ($data_time as $k=>$v){
						$cases=''.$v['name'].'_matching';
						$data_end_t[$k]=M(''.$cases.'')->where($sql_total)->select();
						$i++;
					}
					for($k=0;$k<$i;$k++){
						if(empty($data)&&!empty($data_end_t[$k])){
							$data=$data_end_t[$k];
						}elseif (!empty($data)&&empty($data_end_t[$k])){
							$data=$data;
						}elseif (!empty($data)&&empty($data_end_t[$k])){
							$data = array_merge($data,$data_end_t[$k]);
						}
					}
				}
				
            //end月
			
				$data_end=M($cases_end)->where($sql_e)->select();
				//print_r($data_end);die;
				if(empty($data)&&!empty($data_end)){
					$data=$data_end;
				}elseif (!empty($data)&&empty($data_end)){
					$data=$data;
				}elseif (!empty($data)&&!empty($data_end)){
					$data = array_merge($data,$data_end);
				}
			}
        }
        return $data;
    }
}
?>
