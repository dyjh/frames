<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
/**
 * 配置模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class PlantRModel extends Model {

    public $trueTableName = "";

    public function __init($trueTableName = '') {

        if($trueTableName){
            $this->trueTableName = $trueTableName;
        }
    }

    /**
     * @param $user_info  用户信息
     * @return array      返回的用户土地数据
     */
    function one_user_planting_record($user_info){
        //  查询 用户土地的情况

		$table_prefix= substr($user_info['user'],0,3);

		$land_count  =  M($table_prefix. "_members")->where("user='".$user_info['user']."'")->getField("level");

		// 拼接 sql；
		for($q=1; $q <= $land_count ;$q++){
			$where   =  "user='".$user_info['user']."'";

			$where  .=  " and number = ". $q;

			$land_info[] =  $this->union($union,true)->where($where)->limit(1)->order("id desc")->find();	
		}

        $all_land_info =array();

        $disasters = M("disasters")->select();
        foreach( $disasters as $key=>$val){
            $all_disasters[$val['id']] = $val['type'];
        }

        for($i=0;$i<12;$i++){
            $j = $i+1;
            if($j > $user_info['level']){
                $all_land_info[$j]['land_note'] = "（暂未开拓）";
            }else{
                $land_number = $land_info[$i]['number'];

                if(!$all_land_info[$j]){
                    $all_land_info[$j]['number'] = $j;
                    $all_land_info[$j]['status_note'] = "未种植";
                }
                if($land_info[$i]){

                    $all_land_info[$land_number] = $land_info[$i];

                    if($land_info[$i]['seed_type']){
                        $seeds_info = M("seeds")->where("varieties='".$land_info[$i]['seed_type']."'")->field("fruit_number,id")->find();
                        $seeds_num = explode("-",$seeds_info['fruit_number']) ;
                        $all_land_info[$land_number]['seeds_min'] =  $seeds_num[0];
                        $all_land_info[$land_number]['seeds_max'] =  $seeds_num[1];
                        $all_land_info[$land_number]['seeds_id']  =  $seeds_info['id'];
                    }

                    switch($land_info[$i]['seed_state']){
                        case 0 :  $all_land_info[$land_number]['status_note'] = "种子";break;
                        case 1 :  $all_land_info[$land_number]['status_note'] = "发芽";break;
                        case 2 :  $all_land_info[$land_number]['status_note'] = "成株";break;
                        case 3 :  $all_land_info[$land_number]['status_note'] = "成熟";break;
                    }

                    switch($land_info[$i]['harvest_state']){
                        case 0 :  $all_land_info[$land_number]['harvest_note'] = "未收获";break;
                        case 1 :  $all_land_info[$land_number]['harvest_note'] = "已收获";break;
                    }

                    if($land_info[$i]['disasters_state']){
                        $all_land_info[$land_number]['disasters_note'] = $all_disasters[$land_info[$i]['disasters_state']];
                    }else{
                        $all_land_info[$land_number]['disasters_note'] = "未受灾";
                    }
                }
            }
        }

        return $all_land_info;
    }

    function users_planting_harvest($time=array(),$user_info){
        if(empty($time)){
            $time['start'] = strtotime(date('Y-m-d'));
            $time['end'] = $time['start'] + 3600 *24;
        }

        if($user_info){
            $where['user'] = $user_info['user'];

        }
       $where['time'] = array("BETWEEN",array($time['start'],$time['end']));

        $all_data = $this->where($where)->select();

        $seed_list_info =array();

        foreach($all_data as $key=>$val){

            $harvest_num = 0;
            $disasters_value= 0;
            $plan_harvest_num= 0;
            $plan_disasters_value= 0;

            // 判断是否 收获
            if($val['harvest_state']){
                $harvest_num     =  (int)$val['harvest_num'];
                $disasters_value =  (int)$val['disasters_value'];
            }else{
                $plan_harvest_num = (int)$val['harvest_num'];
                $plan_disasters_value = (int)$val['disasters_value'];
            }
            $seed_list_info[$val['seed_type']]['get_harvest_num']      +=  $harvest_num;
            $seed_list_info[$val['seed_type']]['get_disasters_value'] +=  (int)$disasters_value;
            $seed_list_info[$val['seed_type']]['plan_harvest_num'] += (int)$plan_harvest_num;
            $seed_list_info[$val['seed_type']]['plan_disasters_value'] += (int)$plan_disasters_value;
        }

        return $seed_list_info;
    }


}
