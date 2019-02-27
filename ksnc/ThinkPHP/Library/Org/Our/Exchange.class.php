<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 11:22
 */

namespace Org\Our;
use Org\Our\Pay;
//use Think\Deal;
use Org\Our\archive;
//use Think\archive;
use Think\Model;
//交换种子  重生
class Exchange
{


	/**
     * seeds_num()   仓库种子总数
     **/
    public function seeds_num(){
        $statistical=M('Statistical');
        $dsql = $statistical->field('name')->select();
        //return $dsql;
        $number = count($dsql);
        $or = array();
        for($i=0;$i<$number;$i++){
            $prop_num = M(''.$dsql[$i]['name'].'_prop_warehouse');
            $num_sql = $prop_num->where('prop_id=6')->sum('num');
            $or[] =$num_sql;
        }
        $sum = 0;
        $sum_or = count($or);
        for($j=0;$j<$sum_or;$j++){
            $sum += $or[$j];
			if($sum > 300000000){
				return $sum;
			}
        }
        return $sum;
    }
		
	
    /**
     * conductor()   交换种子   机构返佣
     * @ $tel   string     电话
     * @ $sum  string     数量（电话）
     * **/

    public function conductor($tel,$sum){
        $css = new Pay();
        $mend = $css -> manager($tel);
        //return $mend;
        ////手续费
        $commission = M('commission');
        $usql3 = $commission->field('poundage_value')->where('id=9 or id=10')->select();
        $manager_f = $sum * $usql3[0]['poundage_value'];//父级推荐人的机构推荐费
        $manager_y = $sum * $usql3[1]['poundage_value'];//爷爷级推荐人的机构推荐费
        //return $manager_f.$manager_y;
        $pu_record = new archive();
        if($mend[0]==''){//一级没有人
            return 'success';
        }else{
            $peo1 = substr($mend[0],0,3);
            M(''.$peo1.'_members') ->startTrans();
            $poob = M(''.$peo1.'_members')->field(true)->where('user='.$mend[0].'')->select();
            M(''.$peo1.'_users_gold')->startTrans();
            $sbo =  M(''.$peo1.'_users_gold')->field(true)->where('user='.$mend[0].'')->select();
            if($mend[1]==''){
                //$seller_members_f = M(''.$pr2.'_members');
                $cond['coin'] = $poob[0]['coin'] + $manager_f;
                //存入返佣记录表
                $th = date('Y-m-d H:i:s',time());
                $tms = substr($th,0,7);
                $rebate_record = M(''.$tms.'_rebate_record');
                $rebate_record ->startTrans();
                $cont['user'] = $mend[0];
                $cont['source'] = $tel;
                $cont['money'] = $manager_f;
                $cont['time'] = time();
				$cont['type'] = 3;
                $pu_record->store($mend[0],4,$manager_f);
                if(M(''.$peo1.'_members')->where('user='.$mend[0].'')->setInc('coin',$manager_f)!==false && $rebate_record->data($cont)->add()!==false){
                    M(''.$peo1.'_members')->commit();
                    $rebate_record->commit();
                    if($sbo[0]==null){
                        $dance['user'] = $mend[0];
                        $dance['num_id'] = $poob[0]['num_id'];
                        $dance['user_fees'] = $manager_f;
                        if(M(''.$peo1.'_users_gold')->data($dance)->add()!==false){
                            M(''.$peo1.'_users_gold')->commit();
                            return 'success';
                        }else{
                            M(''.$peo1.'_users_gold')->rollback();
                            return 'error6';
                        }
                    }else{
                        $dance['user_fees'] = $sbo[0]['user_fees']+$manager_f;
                        if(M(''.$peo1.'_users_gold')->where('user='.$mend[0].'')->setInc('user_fees',$manager_f)!==false){
                            M(''.$peo1.'_users_gold')->commit();
                            return 'success';
                        }else{
                            M(''.$peo1.'_users_gold')->rollback();
                            return 'error5';
                        }
                    }
                }else{
                    M(''.$peo1.'_members')->rollback();
                    $rebate_record->rollback();
                    return 'error4';
                }

            }else{
                $peo = substr($mend[1],0,3);
                M(''.$peo.'_members') ->startTrans();
                $pooa = M(''.$peo.'_members')->field(true)->where('user='.$mend[1].'')->select();
                M(''.$peo.'_users_gold')->startTrans();
                $sao =  M(''.$peo.'_users_gold')->field(true)->where('user='.$mend[1].'')->select();
                //////////
                //return $poob[0]['coin'];
                //$seller_members_f = M(''.$pr2.'_members');
                $cond['coin'] = $poob[0]['coin'] + $manager_f;
                //return $cond['coin'];
                //存入返佣记录表
                $th = date('Y-m-d H:i:s',time());
                $tms = substr($th,0,7);
                $rebate_record = M(''.$tms.'_rebate_record');
                $rebate_record ->startTrans();
                $cont['user'] = $mend[0];
                $cont['source'] = $tel;
                $cont['money'] = $manager_f;
                $cont['time'] = time();
				$cont['type'] = 3;
                $pu_record->store($mend[0],4,$manager_f);
                if(M(''.$peo1.'_members')->where('user='.$mend[0].'')->setInc('coin',$manager_f)!==false && $rebate_record->data($cont)->add()!==false){
                    M(''.$peo1.'_members')->commit();
                    $rebate_record->commit();
                    //$pr3 = substr($bf_sql[0]['referees'],0,3);
                    $conk['coin'] =$pooa[0]['coin'] + $manager_y;
                    //return $conk['coin'];
                    //存入返佣记录表
                    $cong['user'] = $mend[1];
                    $cong['source'] = $tel;
                    $cong['money'] = $manager_y;
                    $cong['time'] = time();
					$cong['type'] = 3;
                    $pu_record->store($mend[1],4,$manager_y);
                    if(M(''.$peo.'_members')->where('user='.$mend[1].'')->setInc('coin',$manager_y)!==false && $rebate_record->data($cong)->add()!==false){
                        M(''.$peo.'_members')->commit();
                        $rebate_record->commit();
                        if($sbo[0]==null){
                            $dance['user'] = $mend[0];
                            $dance['num_id'] = $poob[0]['num_id'];
                            $dance['user_fees'] = $manager_f;
                            /////////////////////
                            $dence['user'] = $mend[1];
                            $dence['num_id'] = $pooa[0]['num_id'];
                            $dence['user_fees'] = $manager_y;
                            if(M(''.$peo1.'_users_gold')->data($dance)->add()!==false && M(''.$peo.'_users_gold')->data($dence)->add()!==false){
                                M(''.$peo1.'_users_gold')->commit();
                                M(''.$peo.'_users_gold')->commit();
                                return 'success';
                            }else{
                                M(''.$peo1.'_users_gold')->rollback();
                                M(''.$peo.'_users_gold')->rollback();
                                return 'error';
                            }
                        }else{
                            $dance['user_fees'] = $sbo[0]['user_fees']+$manager_f;
                            /////////
                            $dence['user_fees'] = $sao[0]['user_fees']+$manager_y;
                            if(M(''.$peo1.'_users_gold')->where('user='.$mend[0].'')->setInc('user_fees',$manager_f)!==false && M(''.$peo.'_users_gold')->where('user='.$mend[1].'')->setInc('user_fees',$manager_y)!==false){
                                M(''.$peo1.'_users_gold')->commit();
                                M(''.$peo.'_users_gold')->commit();
                                return 'success';
                            }else{
                                M(''.$peo1.'_users_gold')->rollback();
                                M(''.$peo.'_users_gold')->rollback();
                                return 'error3';
                            }
                        }
                    }else{
                        M(''.$peo.'_members')->rollback();
                        $rebate_record->rollback();
                        return 'error2222';
                    }

                }else{
                    M(''.$peo1.'_members')->rollback();
                    $rebate_record->rollback();
                    return 'error1111';
                }
            }
        }
    }


    /**
     * exchange()   交换种子
     * @ $tel   string     电话
     * @ $sum  string     数量
     *
     * **/
    public function exchange($tel,$sum){
        $wpd = $this->conductor($tel,$sum);
        //return $wpd;
        //卖方的表前缀
        $pr1 = substr($tel,0,3);
        //查询卖方有没有推荐人
        //$seller_members = M(''.$pr1.'_members');
        $b_sql=M(''.$pr1.'_members')->field('referees,coin')->where('user='.$tel.'')->select();
        //return $b_sql[0]['coin'];
        ////手续费
        $commission = M('commission');
        $usql1 = $commission->field('poundage_value')->where('id=7 or id=8')->select();
        $poundage_f  = $sum * $usql1[0]['poundage_value'];//父级推荐人的推荐费
        $poundage_y = $sum * $usql1[1]['poundage_value'];//爷爷级获得的推荐费
        //return $poundage_f;
        $pu_record = new archive();
        if($wpd=='success'){
            if($b_sql[0]['referees']!=='' && $b_sql[0]['referees']!==$tel){//有父级推荐人且不是兑换人

                //的表前缀//
                $pr2 = substr($b_sql[0]['referees'],0,3);
                //查询有没有推荐人//
                $seller_members_f = M(''.$pr2.'_members');
                $seller_members_f ->startTrans();
                $bf_sql=$seller_members_f->field('referees,coin,num_id')->where('user='.$b_sql[0]['referees'].'')->select();

                M(''.$pr2.'_users_gold')->startTrans();
                $sbo =  M(''.$pr2.'_users_gold')->field(true)->where('user='.$b_sql[0]['referees'].'')->select();
                if($bf_sql[0] !== null){//判断有没有这个人

                    if($bf_sql[0]['referees']!=='' && $bf_sql[0]['referees']!==$b_sql[0]['referees'] && $bf_sql[0]['referees']!==$tel){
                        //有爷爷级推荐人且不等于前父级推荐人也不等于兑换的人
                        //爷爷级的表前缀

                        $pr3 = substr($bf_sql[0]['referees'],0,3);
                        //查询有没有推荐人
                        $seller_members_y = M(''.$pr3.'_members');
                        $seller_members_y ->startTrans();
                        $by_sql=$seller_members_y->field('referees,coin,num_id')->where('user='.$bf_sql[0]['referees'].'')->select();
                        ///
                        M(''.$pr3.'_users_gold')->startTrans();
                        $sao =  M(''.$pr3.'_users_gold')->field(true)->where('user='.$bf_sql[0]['referees'].'')->select();
                        if($by_sql[0] !==''){//判断有爷爷级这个人
                            //$seller_members_f = M(''.$pr2.'_members');
                            $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                            //存入返佣记录表
                            $th = date('Y-m-d H:i:s',time());
                            $tms = substr($th,0,7);
                            $rebate_record = M(''.$tms.'_rebate_record');
                            $rebate_record ->startTrans();
                            $cont['user'] = $b_sql[0]['referees'];
                            $cont['source'] = $tel;
                            $cont['money'] = $poundage_f;
                            $cont['time'] = time();
							$cont['type'] = 3;
                            $pu_record->store($b_sql[0]['referees'],4,$poundage_f);
                            if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->setInc('coin',$poundage_f)!==false && $rebate_record->data($cont)->add()!==false){
                                $seller_members_f->commit();
                                $rebate_record->commit();
                                //$pr3 = substr($bf_sql[0]['referees'],0,3);
                                $conk['coin'] =$by_sql[0]['coin'] + $poundage_y;
                                //存入返佣记录表
                                $cong['user'] = $bf_sql[0]['referees'];
                                $cong['source'] = $tel;
                                $cong['money'] = $poundage_y;
                                $cong['time'] = time();
								$cong['type'] = 3;
                                $pu_record->store($bf_sql[0]['referees'],4,$poundage_y);
                                if($seller_members_y->where('user='.$bf_sql[0]['referees'].'')->setInc('coin',$poundage_y)!==false && $rebate_record->data($cong)->add()!==false){
                                    $seller_members_y->commit();
                                    $rebate_record->commit();
                                    if($sbo[0]==null){
                                        $dance['user'] = $b_sql[0]['referees'];
                                        $dance['num_id'] = $bf_sql[0]['num_id'];
                                        $dance['user_fees'] = $poundage_f;
                                        /////////////////////
                                        $dence['user'] = $bf_sql[0]['referees'];
                                        $dence['num_id'] = $by_sql[0]['num_id'];
                                        $dence['user_fees'] = $poundage_y;
                                        if(M(''.$pr2.'_users_gold')->data($dance)->add()!==false && M(''.$pr3.'_users_gold')->data($dence)->add()!==false){
                                            M(''.$pr2.'_users_gold')->commit();
                                            M(''.$pr3.'_users_gold')->commit();
                                            return 'success';
                                        }else{
                                            M(''.$pr2.'_users_gold')->rollback();
                                            M(''.$pr3.'_users_gold')->rollback();
                                            return 'error';
                                        }
                                    }else{
                                        $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                        /////////
                                        $dence['user_fees'] = $sao[0]['user_fees']+$poundage_y;
                                        if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false && M(''.$pr3.'_users_gold')->where('user='.$bf_sql[0]['referees'].'')->setInc('user_fees',$poundage_y)!==false){
                                            M(''.$pr2.'_users_gold')->commit();
                                            M(''.$pr3.'_users_gold')->commit();
                                            return 'success';
                                        }else{
                                            M(''.$pr2.'_users_gold')->rollback();
                                            M(''.$pr3.'_users_gold')->rollback();
                                            return 'error0';
                                        }
                                    }
                                }else{
                                    $seller_members_y->rollback();
                                    $rebate_record->rollback();
                                    return 'error9';
                                }

                            }else{
                                $seller_members_f->rollback();
                                $rebate_record->rollback();
                                return 'error8';
                            }
                        }else{//只有父级推荐人
                            //$seller_members_f = M(''.$pr2.'_members');
                            $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                            //return $poundage_f ;
                            //存入返佣记录表
                            $th = date('Y-m-d H:i:s',time());
                            $tms = substr($th,0,7);
                            $rebate_record = M(''.$tms.'_rebate_record');
                            $rebate_record ->startTrans();
                            $cont['user'] = $b_sql[0]['referees'];
                            $cont['source'] = $tel;
                            $cont['money'] = $poundage_f;
                            $cont['time'] = time();
							$cont['type'] = 3;
                            $pu_record->store($b_sql[0]['referees'],4,$poundage_f);
                            if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->setInc('coin',$poundage_f)!==false && $rebate_record->data($cont)->add()!==false){
                                $seller_members_f->commit();
                                $rebate_record->commit();
                                if($sbo[0]==null){
                                    $dance['user'] = $b_sql[0]['referees'];
                                    $dance['num_id'] = $bf_sql[0]['num_id'];
                                    $dance['user_fees'] = $poundage_f;
                                    if(M(''.$pr2.'_users_gold')->data($dance)->add()!==false){
                                        M(''.$pr2.'_users_gold')->commit();
                                        return 'success';
                                    }else{
                                        M(''.$pr2.'_users_gold')->rollback();
                                        return 'error7';
                                    }
                                }else{
                                    $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                    if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                        M(''.$pr2.'_users_gold')->commit();
                                        return 'success';
                                    }else{
                                        M(''.$pr2.'_users_gold')->rollback();
                                        return 'error6';
                                    }
                                }
                            }else{
                                $seller_members_f->rollback();
                                $rebate_record->rollback();
                                return 'error5';
                            }
                        }
                    }else{//只有父级推荐人
                        //$seller_members_f = M(''.$pr2.'_members');
                        $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                        //return $bf_sql[0]['coin'];
                        //return $poundage_f ;
                        //存入返佣记录表
                        $th = date('Y-m-d H:i:s',time());
                        $tms = substr($th,0,7);
                        $rebate_record = M(''.$tms.'_rebate_record');
                        $rebate_record ->startTrans();
                        $cont['user'] = $b_sql[0]['referees'];
                        $cont['source'] = $tel;
                        $cont['money'] = $poundage_f;
                        $cont['time'] = time();
						$cont['type'] = 3;
                        $pu_record->store($b_sql[0]['referees'],4,$poundage_f);
                        if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->setInc('coin',$poundage_f)!==false && $rebate_record->data($cont)->add()!==false){
                            $seller_members_f->commit();
                            $rebate_record->commit();
                            if($sbo[0]==null){
                                $dance['user'] = $b_sql[0]['referees'];
                                $dance['num_id'] = $bf_sql[0]['num_id'];
                                $dance['user_fees'] = $poundage_f;
                                if(M(''.$pr2.'_users_gold')->data($dance)->add()!==false){
                                    M(''.$pr2.'_users_gold')->commit();
                                    return 'success';
                                }else{
                                    M(''.$pr2.'_users_gold')->rollback();
                                    return 'error4';
                                }
                            }else{
                                $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                    M(''.$pr2.'_users_gold')->commit();
                                    return 'success';
                                }else{
                                    M(''.$pr2.'_users_gold')->rollback();
                                    return 'error3';
                                }
                            }
                        }else{
                            $seller_members_f->rollback();
                            $rebate_record->rollback();
                            return 'error2';
                        }
                    }

                }else{
                    return 'success';
                }

            }else{//没有父级推荐人//
                return 'success';
            }
        }else{
            return 'error1';
        }

    }

	/**
	 *随机小数的方法
	 **/
	public function randFloat($min,$max){
		return $min + mt_rand()/mt_getrandmax() * ($max-$min);
	}
	
	
    /**
     * exchange()   交换种子
     * @ $pop   string     交换种子的数量
     * @ $user  string     交换种子的用户名（电话）
     *  200--成功 400--失败 300--你的钱不够  500--不足1000果子
     * **/
    public function test_exchange($user,$num,$type,$crit){
		if($num != 1000 && $num != 10000){//判断数量是否大于1000  且是100的整数倍
            $data['state'] = 30005;
            $data['content'] = '输入数量有误';
			//清除缓存
			S('reborn',null);
			S('reborn',0);
            echo json_encode($data);
            exit;
        }
		
		if($crit < 1 || $crit > 2){
			$data['state'] = 30008;
            $data['content'] = '不要搞事';
			//清除缓存
			S('reborn',null);
			S('reborn',0);
            echo json_encode($data);
            exit;
		}
		//只能判断开关重生   种子是否超过限制
        $prop_sum = $this->seeds_num();
        if($prop_sum >= 300000000){            
            $data['state'] = 30000;
            $data['content'] = '种子已经超出限制';
			//清除缓存
			S('reborn',null);
			S('reborn',0);
            echo json_encode($data);
            exit;   
        }
        ////////////////////
        $pu_record = new archive();
        ////手续费
        $commission = M('commission');
        $u_sql = $commission->field('poundage_value')->where('id=11 or id=14 or id=15')->select();//直接推荐重生利润

        //当前单价交易费
        $tms = substr(date('Y-m-d H:i:s',time()),0,7);
        $matching = M(''.$tms.'_matching');
        $seed['seed'] = $type;
        $price_sql = $matching->field('money')->where($seed)->order('time desc')->limit(1)->find();//当前单价交易费
		//
		$pay_statistical = M('pay_statistical');
		$pay_sql = $pay_statistical->field('end_money')->where($seed)->order('time desc')->find();

        $pr1 = substr($user,0,3);
        //查询现有的费用
        $members1 = M(''.$pr1.'_members');
        $members1 ->startTrans();
        $g_sql=$members1->field('coin,diamond')->where('user='.$user.'')->find();

        //果实详情
        $prop_warehouse = M(''.$pr1.'_prop_warehouse');
        $prop_warehouse ->startTrans();
        $prop_cond['user'] = $user;
        $prop_cond['props'] = '种子';
        $prop_sql = $prop_warehouse->where($prop_cond)->find();

        //果实详情
        $seed_warehouse = M(''.$pr1.'_seed_warehouse');
        $seed_warehouse ->startTrans();
        $seed_cond['user'] = $user;
        $seed_cond['seeds'] = $type;
        $seed_sql = $seed_warehouse->where($seed_cond)->find();
        //return $seed_sql;
		
		//果实够不够
		if($seed_sql['num'] < $num){
			$data['state'] = 30007;
            $data['content'] = '果实不足';
			//清除缓存
			S('reborn',null);
			S('reborn',0);
            echo json_encode($data);
            exit;
		}
		
        //需要多少费用
		if($price_sql['money']!=null){
			$cost = round($num * $price_sql['money'] * $u_sql[0]['poundage_value'],5);
			$sum = $num * $price_sql['money'];
		}else{
			$cost = round($num * $pay_sql['end_money'] * $u_sql[0]['poundage_value'],5);
			$sum = $num * $pay_sql['end_money'];
		}
        
        if($g_sql['coin'] < $cost){//判断钱是不是够支付重生费用
            $data['state'] = 30003;
            $data['content'] = '金币不足';
			//清除缓存
			S('reborn',null);
			S('reborn',0);
            echo json_encode($data);
            exit;
        }
		
		
		if($crit == 1){
			if($num == 1000){
				$diamond = 100;
			}else{
				$diamond = 600;
			}
			
            if($g_sql['diamond'] < $diamond){//判断是不是够支付暴击重生费用
                $data['state'] = 30003;
                $data['content'] = '钻石不足';
				//清除缓存
				S('reborn',null);
				S('reborn',0);
                echo json_encode($data);
                exit;

            }
        }
        
        //暴击随机数
		if($crit == 1){
			$cal = $num * (number_format($this->randFloat($u_sql[1]['poundage_value'],$u_sql[2]['poundage_value']),2));
		}
        
		//减少重生费后的金额
        /*if($crit == 1){
            $cong['coin'] = sprintf("%.3f",($g_sql['coin'] - $cost));
			$cong['diamond'] = $g_sql['diamond'] - $diamond;
        }else{
            $cong['coin'] = sprintf("%.3f",($g_sql['coin'] - $cost));           
        }*/
		
        //增加种子
        if($crit == 1){
            $augment['num'] = $num + $cal;
            $augment_cond['user'] = $user;
            $augment_cond['props'] = '种子';
        }else{
            $augment['num'] = $num;
            $augment_cond['user'] = $user;
            $augment_cond['props'] = '种子';
        }
        //减少果实
        $lessen['num']  = $seed_sql['num'] - $num;
        $lessen_cond['user']  = $user;
        $lessen_cond['seeds']  = $type;
		//重生记录
		$record = M(''.$pr1.'_record_conversion');
		$record->startTrans();
		$record_cond['user'] = $user;
		$record_cond['coin'] = $cost;
		if($crit == 1){
			$record_cond['diamond'] = $diamond;
		}
		$record_cond['name'] = $type;
		$record_cond['num'] = $num;
		$record_cond['buy_time'] = time();
		$record_cond['type'] = 'c';
		$record_cond['attach'] = ($num + $cal);
		//
        $pu_record->store($user,5,$sum);
		if($crit == 1){
			if($members1->where('user='.$user.'')->setDec('coin',$cost)!==false
				&& $members1->where('user='.$user.'')->setDec('diamond',$diamond)!==false
				&& $prop_warehouse->where($augment_cond)->setInc('num',$augment['num'])!==false
				&& $seed_warehouse->where($lessen_cond)->setDec('num',$num)!==false
				&& $record->add($record_cond) !== false
			){
				$this->exchange($user,$sum);
				$members1->commit();
				$prop_warehouse->commit();
				$seed_warehouse->commit();
				$record->commit();
				$data['state'] = 30002;
				$data['money'] = $cost;
				$data['num'] = $num;
				$data['crit'] = $cal;
				$data['diamond'] = $diamond;
				//$data['test'] = $num.'/'.$price_sql['money'].'/'.$u_sql[0]['poundage_value'];
				$data['content'] = '重生成功';
				//清除缓存
				S('reborn',null);
				S('reborn',0);
				echo json_encode($data);
				exit;
			}else{
				$members1->rollback();
				$prop_warehouse->rollback();
				$seed_warehouse->rollback();
				$record->rollback();
				$data['state'] = 30004;
				$data['content'] = '重生失败';
				//清除缓存
				S('reborn',null);
				S('reborn',0);
				echo json_encode($data);
				exit;
			}
		}else{
			if($members1->where('user='.$user.'')->setDec('coin',$cost)!==false
				&& $prop_warehouse->where($augment_cond)->setInc('num',$augment['num'])!==false
				&& $seed_warehouse->where($lessen_cond)->setDec('num',$num)!==false
				&& $record->add($record_cond) !== false
			){
				$this->exchange($user,$sum);
				$members1->commit();
				$prop_warehouse->commit();
				$seed_warehouse->commit();
				$record->commit();
				$data['state'] = 30002;
				$data['money'] = $cost;
				$data['num'] = $num;
				$data['crit'] = $cal;
				$data['diamond'] = $diamond;
				//$data['test'] = $num.'/'.$price_sql['money'].'/'.$u_sql[0]['poundage_value'];
				$data['content'] = '重生成功';
				//清除缓存
				S('reborn',null);
				S('reborn',0);
				echo json_encode($data);
				exit;
			}else{
				$members1->rollback();
				$prop_warehouse->rollback();
				$seed_warehouse->rollback();
				$record->rollback();
				$data['state'] = 30004;
				$data['content'] = '重生失败';
				//清除缓存
				S('reborn',null);
				S('reborn',0);
				echo json_encode($data);
				exit;
			}
		}
        
    }


   

}
