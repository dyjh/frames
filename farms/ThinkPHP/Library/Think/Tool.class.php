<?php
namespace Think;

class Tool{

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

    //查询表
    public function table($tel,$case){
        $prefix=substr($tel,0,3);
        switch ($case) {
          case 'members':
            return $prefix."_members";    //会员表
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


    //获取种子数量最多的
    function seed_most($data,$level){
        $array = array_count_values($data);
        arsort($array);
        //如果是六级，稻米需要做限制
        if($level==6){
            $seed_most = array_slice($array,0,1,true);   //取一位
        }else if($level==7){
            $seed_most = array_slice($array,0,2,true);    //取二位
        }else if($level==8){
            $seed_most = array_slice($array,0,2,true);     //取二位
        }else if($level==9){
            $seed_most = array_slice($array,0,3,true);    //取三位
        }else if($level==10){
            $seed_most = array_slice($array,0,3,true);    //取三位
        }
        //键值对换
        $seed_most = array_flip($seed_most);
        //重置索引
        $seed_most = array_values($seed_most);
        //返回
        return $seed_most;
    }

    //分时段查询
    public function total($start_time,$end_time,$seed){

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
          $end_start= mktime(0,0,0,$e_m,$e_d_s,$e_y);
          $end_end= mktime(0,0,0,$e_m,$e_d,$e_y);
          $end_end=$end_end+24*3600;
          $cases_end=''.$e_y.'-'.$e_m.'_matching';
          $num_end_t=M(''.$cases_end.'')->where('seed="'.$seed.'"')->sum('num');
          $money_end_t=M(''.$cases_end.'')->where('seed="'.$seed.'"')->sum('total');
          $poundage_end_t=M(''.$cases_end.'')->where('seed="'.$seed.'"')->sum('poundage');
          $num_start_t=M(''.$cases_start.'')->where('seed="'.$seed.'"')->sum('num');
          $money_start_t=M(''.$cases_start.'')->where('seed="'.$seed.'"')->sum('total');
          $poundage_start_t=M(''.$cases_start.'')->where('seed="'.$seed.'"')->sum('poundage');
          $num_end=M(''.$cases_end.'')->where('time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND seed="'.$seed.'"')->sum('num');
          $money_end=M(''.$cases_end.'')->where('time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND seed="'.$seed.'"')->sum('total');
          $poundage_end=M(''.$cases_end.'')->where('time >= "'.$end_start.'"  AND time <= " '.$end_end.'" AND seed="'.$seed.'"')->sum('poundage');
          $num_start=M(''.$cases_start.'')->where('time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND seed="'.$seed.'"')->sum('num');
          $money_start=M(''.$cases_start.'')->where('time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND seed="'.$seed.'"')->sum('total');
          $poundage_start=M(''.$cases_start.'')->where('time >= "'.$start_start.'"  AND time <= " '.$start_end.'" AND seed="'.$seed.'"')->sum('poundage');

          //查询时间段
          $time_s = strtotime(''.$start_time.''); // 自动为00:00:00 时分秒
          $time_e = strtotime(''.$end_time.'');
          $monarr = array();
          $monarr[] = $end; // 当前月;
          while( ($time_s = strtotime('+1 month', $time_s)) <= $time_e){
              $monarr[] = date('Y-m',$time_s); // 取得递增月;
          }
          //print_r($monarr);
          //
          $num=0;
          $money=0;
          $poundage=0;
          for($i=0;$i<=count($monarr);$i++){
              $cases=''.$monarr[$i].'_matching';
              $num+=M(''.$cases.'')->where('seed="'.$seed.'"')->sum('num');
              $money+=M(''.$cases.'')->where('seed="'.$seed.'"')->sum('total');
              $poundage+=M(''.$cases.'')->where('seed="'.$seed.'"')->sum('poundage');
          }
          $num=$num+$num_end+$num_start-$num_end_t-$num_start_t;
          $poundage=$poundage+$poundage_end+$poundage_start-$poundage_end_t-$poundage_start_t;
          $money=$money+$money_end+$money_start-$money_end_t-$money_start_t;
          $data['money']=$money;
          $data['num']=$num;
          $data['poundage']=$poundage;


          return $data;
      }

}
?>
