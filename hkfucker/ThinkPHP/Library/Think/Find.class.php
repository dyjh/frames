<?php
namespace Think;

class Find{    
	public function find_fruit($start_times,$end_times,$seed,$type,$money){
		
		$case=''.date('Y-m').'_pay';
		if(empty($money)&&empty($type)){
			//echo $trans_type;die;
			$sql_s='time >= "'.$start_times.'" AND seed = "'.$seed.'" AND time <= "'.$end_times.'"';
		}elseif (!empty($money)&&!empty($type)){
			if($type==2){
				$type=0;
			}
			$sql_s='time >= "'.$start_times.'"  AND time <= "'.$end_times.'" AND money="'.$money.'" AND seed = "'.$seed.'" AND type = "'.$type.'"';
		}elseif (!empty($money)&&empty($type)){
			$sql_s='time >= "'.$start_times.'"  AND time <= "'.$end_times.'" AND money="'.$money.'" AND seed = "'.$seed.'"';
		}elseif (empty($money)&&!empty($type)){
			//echo 1;die;
			if($type==2){
				$type=0;
			}
			$sql_s='time >= "'.$start_times.'" AND time <= "'.$end_times.'" AND seed="'.$seed.'" AND type = "'.$type.'"';
		}
		//echo $sql_s;die;
		$data=M($case)->where($sql_s)->order('time DESC')->select();
		//echo M($case)->getlastsql();die;
		return $data;
	}

    public function total($start_time,$end_time,$seed,$type,$user){
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
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND sell_user="'.$user.'" or time >= "'.$end_start.'"  AND time <= "'.$end_end.'" AND buy_user="'.$user.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND sell_user="'.$user.'" or time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND buy_user="'.$user.'"';
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
                $sql_e='time >= "'.$end_start.'"  AND time <= " '.$end_end.'"  AND sell_user="'.$user.'" AND seed = "'.$seed.'" or time >= "'.$end_start.'"  AND time <= "'.$end_end.'"  AND buy_user="'.$user.'" AND seed = "'.$seed.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= " '.$start_end.'"  AND sell_user="'.$user.'" AND seed = "'.$seed.'" or time >= "'.$start_start.'"  AND time <= "'.$start_end.'"  AND buy_user="'.$user.'" AND seed = "'.$seed.'"';
            }elseif (empty($seed)&&!empty($type)){
                //echo 1;die;
				if($type==1){
					$name='buy_user';
				}else{
					$name='sell_user';
				}
                $sql_total=''.$name.'="'.$user.'"';
                $sql_e='time >= "'.$end_start.'" AND time <= "'.$end_end.'"  AND '.$name.'="'.$user.'"';
                $sql_s='time >= "'.$start_start.'" AND time <= "'.$start_end.'"  AND '.$name.'="'.$user.'"';
            }
            $data_time=M('matching_statistical')->where('id > "'.$data_s['id'].'"  AND id < "'.$data_e['id'].'"')->select();
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
	public function total_pay($start_time,$end_time,$seed,$type,$user){
        //print_r($end_time);die;
        //echo 1;print_r($seed);die;
		//echo $start_time;echo '<br/>';
		//echo $end_time;die;
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
    
        $cases_start=''.$s_y.'-'.$s_m.'_pay';
        $e_y=substr($end_time,0,4);
        $e_m=substr($end_time,5,2);
        $e_d=substr($end_time,8,2);
        $e_d_s=01;
        $end=''.$e_y.'-'.$e_m.'';
        $start=''.$s_y.'-'.$s_m.'';
        $end_start= mktime(0,0,0,$e_m,$e_d_s,$e_y);
        $end_end= mktime(0,0,0,$e_m,$e_d,$e_y);
        $end_end=$end_end+24*3600;
        $cases_end=''.$e_y.'-'.$e_m.'_pay';
        
        if($m>$start_start&&$end_end>time()){
            $data['sstate_t']=1;
			//echo 2;die;
        }else{
//echo 1;die;
            $data_e=M('matching_statistical')->where('name="'.$end.'"')->find();
            $data_s=M('matching_statistical')->where('name="'.$start.'"')->find();

            //判断sql语句
            if(empty($seed)&&empty($type)){
                //echo $trans_type;die;
                $sql_total='user="'.$user.'" AND trans_type = "'.$trans_type.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= "'.$end_end.'" AND user="'.$user.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND user="'.$user.'"';
            }elseif (!empty($seed)&&!empty($type)){
				if($type==2){
					$type=0;
				}
                $sql_total='user="'.$user.'"  AND seed = "'.$seed.'" AND type = "'.$type.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= "'.$end_end.'" AND user="'.$user.'" AND seed = "'.$seed.'" AND type = "'.$type.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND user="'.$user.'" AND seed = "'.$seed.'" AND type = "'.$type.'"';
            }elseif (!empty($seed)&&empty($type)){
                $sql_total='user="'.$user.'"  AND seed = "'.$seed.'"';
                $sql_e='time >= "'.$end_start.'"  AND time <= "'.$end_end.'" AND user="'.$user.'" AND seed = "'.$seed.'"';
                $sql_s='time >= "'.$start_start.'"  AND time <= "'.$start_end.'" AND user="'.$user.'" AND seed = "'.$seed.'"';
            }elseif (empty($seed)&&!empty($type)){
                //echo 1;die;
				if($type==2){
					$type=0;
				}
				//echo $type;die;
                $sql_total='user="'.$user.'" AND type = "'.$type.'"';
                $sql_e='time >= "'.$end_start.'" AND time <= "'.$end_end.'" AND user="'.$user.'" AND type = "'.$type.'"';
                $sql_s='time >= "'.$start_start.'" AND time <= "'.$start_end.'" AND user="'.$user.'" AND type = "'.$type.'"';
            }
			
            $data_time=M('matching_statistical')->where('id > "'.$data_s['id'].'"  AND id < "'.$data_e['id'].'"')->select();
            $data=M('Global_conf')->where('cases="poundage"')->find();
            $poundage=$data['value'];
            //start月
            $data=M($cases_start)->where($sql_s)->select();
			//print_r($data);die;
            
			
            //end月
			if($data_s['id']!=$data_e['id']){
				$c=$data_s['id']+1;
				if($c!=$data_e['id']){
					$i=0;
					foreach ($data_time as $k=>$v){
						$cases=''.$v['name'].'_pay';
						$data_end_t[$k]=M(''.$cases.'')->where($sql_total)->select();
						$i++;
					}
					//print_r($data_end_t);die;
					//中间月
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
		//print_r($data);die;
        return $data;
    }
}
?>
