<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

class UserTableInfoModel extends Model{


    public $trueTableName = "";

    public function __init($trueTableName = '') {

        if($trueTableName){
            $this->trueTableName = $trueTableName;
        }
    }

    /**
     * 查询用户充值,提现信息
     * @param array $need_parameter
     * @return mixed
     */
    function GetUserEntrust($need_parameter = array("pay_cash"=>1)){

		$where['user']  = _USERTEL_;
		if(! _USERTEL_ ){
			$back_array['err'] = 1;
            $back_array['msg'] = "查询信息用户不能为空";
            return $back_array;
		}

        if( ! $need_parameter['pay_cash'] ){
            $back_array['err'] = 1;
            $back_array['msg'] = "查询信息参数不能为空";
            return $back_array;
        }

        $limit = $need_parameter['limit'] ? $need_parameter['limit'] : 10;
        $order = $need_parameter['order'] ? $need_parameter['order'] : "add_time desc";

        $where['pay_cash'] = $need_parameter['pay_cash'];
        $where['state'] = array("not in","2");

        $All_Entrust = $this->where($where)->limit($limit)->order($order)->select();

        foreach($All_Entrust as $key=>$val){
            switch($val['state']){
                case 0:
                    $All_Entrust[$key]['state'] = "未完成";
                    break;
                case 1:
                    $All_Entrust[$key]['state'] = "已完成  ";
					IF( $val['is_gift'] == 1 && $val['get_gift'] == 1 ){
						$All_Entrust[$key]['state'] = "已领取礼包";
					}elseif( $val['is_gift'] == 1 && $val['get_gift'] == 0 ){
						$All_Entrust[$key]['state'] = "暂未领取礼包";
					}
                    break;
                case 2:
                    $All_Entrust[$key]['state'] = "取消订单";
                    break;
				case 9:
                    $All_Entrust[$key]['state'] = "已完成";
                    break;
                default:
                    $All_Entrust[$key]['state'] = "未知状态";
                    break;
            }
			
			switch($val['is_gift']){
				case 1:				
					if( (int)$val['money'] == 5700 ){
						$All_Entrust[$key]['money'] =  "小礼包";
					}	elseif((int)$val['money'] == 11400 ){
						$All_Entrust[$key]['money'] =  "大礼包";
					}	
					break;
				default:
					$All_Entrust[$key]['money'] = number_format($val['money'],2);				
                    break;
			}
        }

        $back_array['All_Entrust'] = $All_Entrust;

        return $back_array;

    }


    function IsBindBankInfo($member_where){

        $field = " bank_name , bank_num , name";

        $where['user'] = $member_where;

        $bankinfo = $this->where($where)->field($field)->find();

        return $bankinfo;
    }

    function IsBindUsercashInfo($member_where){

        $where['user'] = $member_where;
        $where['pay_cash'] = 2;
        $where['state']    = array("in","0,1,2");
        $bankinfo = $this->where($where)->order('id desc')->limit(10)->select();
        foreach($bankinfo as $key=>$val){
            switch($val['state']){
                case 0:
                    $bankinfo[$key]['state'] = "未完成";
                    break;
                case 1:
                    $bankinfo[$key]['state'] = "已完成  ";
                    break;
                case 2:
                    $bankinfo[$key]['state'] = "因姓名与账户信息不对应、支付宝账户不存在、银行网点信息出错，取消订单。";
                    break;
                default:
                    $bankinfo[$key]['state'] = "未知状态";
                    break;
            }
			
			$bankinfo[$key]['pay_bank']   =  explode("-",$val['pay_bank'])[0];

			if($val['money'] < 500){
				$bankinfo[$key]['charge'] = $val['money'] * 0.029;
			}elseif($val['money'] < 1000 && $val['money'] >= 500){
				$bankinfo[$key]['charge'] = $val['money']*0.025;
			}elseif($val['money'] >= 1000){
				$bankinfo[$key]['charge'] = $val['money']*0.02;
			}

        }

        return $bankinfo;
    }

    function BindBankInfo($user){
		
		$update_data['bank_name'] = $_POST['bank_name'];
		
		$update_data['bank_num']  = $_POST['bank_num'] ;
		// $update_data['bank_num']  = $_POST['bank_num'] . "_" . $_POST['bank_name_branch'];
		
        $member_where['user'] = $user;

        $result = $this->where($member_where)->save($update_data);

        if($result==1){
            $back_array['state'] = 1;
        }elseif($result==0){
            $back_array['state'] = 2;
        }else{
            $back_array['state'] = -1;
        }
        $back_array['bankinfo'] = $update_data;

        return $back_array;

    }

}
