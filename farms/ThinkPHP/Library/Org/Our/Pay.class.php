<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 9:44
 */

namespace Org\Our;
use Think\Model;
use Org\Our\archive;

//充值  返佣
class Pay
{

    /**
     * 找到充值用户不大于两个的经理的数据
     * @ $tel     string  充值用户电话
     * **/
    public function manager($tel,$arrTemp = array()){
        if(count($arrTemp)<2){
            //表前缀
            $pr = substr($tel,0,3);
            //查询有没有推荐人
            $members1 = M(''.$pr.'_members');
            $gsql=$members1->field('referees,team')->where('user='.$tel.'')->select();
            //return $gsql[0]['referees'];
            //
            if($gsql[0]['referees']!=='' && $gsql[0]['referees']!==$tel){//有推荐人
                $br =  substr($gsql[0]['referees'],0,3);
                $mema = M(''.$br.'_members');
                $kong = $mema->where('user='.$gsql[0]['referees'].'')->select();

                if($kong[0]!==null){
                   
                    //查询机构 人数条件 等级条件
                    $global_conf = M('global_conf');
                    $csql1 = $global_conf
                        ->field('value')
                        ->where('cases="z_number" or cases="j_number" or cases="z_level" or cases="j_level" or cases="contains"')
                        ->select();//直接推荐人数
                    //包不包含直推
                    //$contain_sql = $global_conf->field('value')->where('cases="contains"')->select();

                    $fznum = 0;
                    $ftnum = 0;
                    
                    //return $rsql;
                    //父级直接推荐了多少人
                    $team_relationship = M('team_relationship');
                    $cond['referees'] = $gsql[0]['referees'];
                    $cond['level'] = array('egt',$csql1[2]['value']);//$csql1[2]['value']
                    $fzsql = $team_relationship->where($cond)->count();
                    $fznum += $fzsql;
                    //查询父级团队有多少人
                    $conr['level'] = array('egt',$csql1[3]['value']);//$csql1[3]['value']
                    $conr['team'] = array("LIKE", "%".$gsql[0]['referees']."%");
                    $ftsql = $team_relationship->where($conr)->count();
                    $ftnum += $ftsql;
					
					
                    $ftt = $ftnum - $fznum;
                    //return $ftnum.'bhfdghftgh'.$fznum;
                    $institutions = M('institutions');
                    $zsql = $institutions->where('user='.$gsql[0]['referees'].'')->select();
                    //////////
                    if($csql1[4]['value']==0){////////////////////////////////////////////////////////////////判断包含直推
                        if($zsql[0]!== null){//判断此号码是原定经理
                            $arrTemp[] = $gsql[0]['referees'];
                            return self::manager($gsql[0]['referees'],$arrTemp);
                            //$arrTemp;
                        }else{//不是原定经理
                            //return $zsql;
                            $pra = substr($gsql[0]['referees'],0,3);
                            $membersa = M(''.$pra.'_members');
                            $hsql=$membersa->field(true)->where('user='.$gsql[0]['referees'].'')->select();
                            if($fznum>=$csql1[1]['value'] || ($fznum>=$csql1[0]['value'] && $ftnum>=$csql1[1]['value'])){//判断是经理
                                $conv['user'] = $gsql[0]['referees'];
                                $conv['name'] = $hsql[0]['name'];
                                $conv['card'] = $hsql[0]['id_card'];
                                $conv['level'] = $hsql[0]['level'];
                                $conv['time'] = time();
                                if($institutions->data($conv)->add()!== false){
                                    $arrTemp[] = $gsql[0]['referees'];
                                    if($arrTemp!==''){
                                        return self::manager($arrTemp[0],$arrTemp);

                                    }
                                }else{
                                    return 'error';
                                }
                            }else{//判断不是经理
                                return self::manager($gsql[0]['referees'],$arrTemp);
                            }
                        }
                    }else{/////////////////////////////////////////////////////////////////判断不包含直推
                        if($zsql[0]!== null){//判断此号码是原定经理
                            $arrTemp[] = $gsql[0]['referees'];
                            return self::manager($gsql[0]['referees'],$arrTemp);
                        }else{//不是原定经理
                            $pra = substr($gsql[0]['referees'],0,3);
                            $membersa = M(''.$pra.'_members');
                            $hsql=$membersa->field(true)->where('user='.$gsql[0]['referees'].'')->select();
                            if($fznum>=$csql1[1]['value'] || ($fznum>=$csql1[0]['value'] && $ftt>=$csql1[1]['value'])){//判断是经理
                                $conv['user'] = $gsql[0]['referees'];
                                $conv['name'] = $hsql[0]['name'];
                                $conv['card'] = $hsql[0]['id_card'];
                                $conv['level'] = $hsql[0]['level'];
                                $conv['time'] = time();
                                if($institutions->data($conv)->add()!== false){
                                    $arrTemp[] = $gsql[0]['referees'];
                                    if($arrTemp!==''){
                                        return self::manager($arrTemp[0],$arrTemp);

                                    }
                                }else{
                                    return 'error';
                                }
                            }else{//判断不是经理
                                return self::manager($gsql[0]['referees'],$arrTemp);
                            }
                        }
                    }
                }else{
                    if($arrTemp!==''){
                        return $arrTemp;
                    }else{
                        return 'error';
                    }
                }
            }else{
                if($arrTemp!==''){
                    return $arrTemp;
                }else{
                    return 'error';
                }
            }
        }else{
            return $arrTemp;
        }

    }


    public function conductor($tel,$sum){
        $mend = $this->manager($tel);
        //return $mend;
        ////手续费
        $commission = M('commission');
        $usql3 = $commission->field('poundage_value')->where('id=5 or id=6')->select();
        $manager_f = $sum * $usql3[0]['poundage_value'];//父级推荐人的机构推荐费
        $manager_y = $sum * $usql3[1]['poundage_value'];//爷爷级推荐人的机构推荐费
        //return $manager_f.$manager_y;
		$pu_record = new archive();
        if($mend[0]==null){//一级没有人
            return 'success';
        }else{
            $peo1 = substr($mend[0],0,3);
            M(''.$peo1.'_members')->startTrans();
            $poob = M(''.$peo1.'_members')->field(true)->where('user='.$mend[0].'')->select();
            M(''.$peo1.'_users_gold')->startTrans();
            $sbo =  M(''.$peo1.'_users_gold')->field(true)->where('user='.$mend[0].'')->select();
            if($mend[1]==null){
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
				$cont['type'] = 1;
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
                            return 'error';
                        }
                    }else{
                        $dance['user_fees'] = $sbo[0]['user_fees']+$manager_f;
                        if(M(''.$peo1.'_users_gold')->where('user='.$mend[0].'')->setInc('user_fees',$manager_f)!==false){
                            M(''.$peo1.'_users_gold')->commit();
                            return 'success';
                        }else{
                            M(''.$peo1.'_users_gold')->rollback();
                            return 'error';
                        }
                    }
                }else{
                    M(''.$peo1.'_members')->rollback();
                    $rebate_record->rollback();
                    return 'error';
                }

            }else{
                $peo = substr($mend[1],0,3);
                M(''.$peo.'_members')->startTrans();
                $pooa = M(''.$peo.'_members')->field(true)->where('user='.$mend[1].'')->select();
                M(''.$peo.'_users_gold')->startTrans();
                $sao =  M(''.$peo.'_users_gold')->field(true)->where('user='.$mend[1].'')->select();
                //////////
                //$seller_members_f = M(''.$pr2.'_members');
                $cond['coin'] = $poob[0]['coin'] + $manager_f;
                //return $cond['coin'];
                //存入返佣记录表
                $th = date('Y-m-d H:i:s',time());
                $tms = substr($th,0,7);
                $rebate_record = M(''.$tms.'_rebate_record');
                $rebate_record->startTrans();
                $cont['user'] = $mend[0];
                $cont['source'] = $tel;
                $cont['money'] = $manager_f;
                $cont['time'] = time();
				$cont['type'] = 1;
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
                    $cong['type'] = 1;
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
                            //return $sao;
                            if(M(''.$peo1.'_users_gold')->where('user='.$mend[0].'')->setInc('user_fees',$manager_f)!==false && M(''.$peo.'_users_gold')->where('user='.$mend[1].'')->setInc('user_fees',$manager_y)!==false){
                                M(''.$peo1.'_users_gold')->commit();
                                M(''.$peo.'_users_gold')->commit();
                                return 'success';
                            }else{
                                M(''.$peo1.'_users_gold')->rollback();
                                M(''.$peo.'_users_gold')->rollback();
                                return 'error';
                            }
                        }
                    }else{
                        M(''.$peo.'_members')->rollback();
                        $rebate_record->rollback();
                        return 'error';
                    }

                }else{
                    M(''.$peo1.'_members')->rollback();
                    $rebate_record->rollback();
                    return 'error';
                }
            }
        }
    }



    /**
     * 换宝石返佣的程序
     * @ $tel  string   兑换人的ID
     * @ $sum  int     兑换的金额
     * **/ 
    public function recharge($tel,$sum){
        $wpd = $this->conductor($tel,$sum);
        //return $wpd;
        //表前缀
        $pr = substr($tel,0,3);
        //查询有没有父级推荐人
        $members_b = M(''.$pr.'_members');
        $b_sql=$members_b->field('referees,coin')->where('user='.$tel.'')->select();
        ////手续费
        $commission = M('commission');
        $usql1 = $commission->field('poundage_value')->where('id=1 or id=2')->select();
        $poundage_f = $sum * $usql1[0]['poundage_value'];//父级推荐人的推荐费
        $poundage_y = $sum * $usql1[1]['poundage_value'];//爷爷级获得的推荐费
		$pu_record = new archive();
        if($wpd=='success'){
            if($b_sql[0]['referees']!=='' && $b_sql[0]['referees']!==$tel){//有父级推荐人且不是兑换人
                //的表前缀//
                $pr2 = substr($b_sql[0]['referees'],0,3);
                //查询有没有推荐人//
                $seller_members_f = M(''.$pr2.'_members');
                $seller_members_f->startTrans();
                $bf_sql=$seller_members_f->field('num_id,referees,coin')->where('user='.$b_sql[0]['referees'].'')->select();

                M(''.$pr2.'_users_gold')->startTrans();
                $sbo =  M(''.$pr2.'_users_gold')->field(true)->where('user='.$b_sql[0]['referees'].'')->select();
                if($bf_sql[0] !== null){//判断有没有这个人
                    if($bf_sql[0]['referees']!=='' && $bf_sql[0]['referees']!==$b_sql[0]['referees'] && $bf_sql[0]['referees']!==$tel){
                        //有爷爷级推荐人且不等于前父级推荐人也不等于兑换的人
                        //爷爷级的表前缀
                        $pr3 = substr($bf_sql[0]['referees'],0,3);
                        //查询有没有推荐人
                        $seller_members_y = M(''.$pr3.'_members');
                        $seller_members_y->startTrans();
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
                            $rebate_record->startTrans();
                            $cont['user'] = $b_sql[0]['referees'];
                            $cont['source'] = $tel;
                            $cont['money'] = $poundage_f;
                            $cont['time'] = time();
							$cont['type'] = 1;
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
								$cong['type'] = 1;
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
                                            return 'error';
                                        }
                                    }
                                }else{
                                    $seller_members_y->rollback();
                                    $rebate_record->rollback();
                                    return 'error';
                                }

                            }else{
                                $seller_members_f->rollback();
                                $rebate_record->rollback();
                                return 'error';
                            }
                        }else{//只有父级推荐人
                            //$seller_members_f = M(''.$pr2.'_members');
                            $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                            //return $poundage_f ;
                            //存入返佣记录表
                            $th = date('Y-m-d H:i:s',time());
                            $tms = substr($th,0,7);
                            $rebate_record = M(''.$tms.'_rebate_record');
                            $rebate_record->startTrans();
                            $cont['user'] = $b_sql[0]['referees'];
                            $cont['source'] = $tel;
                            $cont['money'] = $poundage_f;
                            $cont['time'] = time();
							$cont['type'] = 1;
							$pu_record->store($b_sql[0]['referees'],4,$poundage_f);
                            if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->setInc('coin',$poundage_f)!==false && $rebate_record->data($cont)->add()!==false){
                                $seller_members_f->commit();
                                $rebate_record->commit();//
                                if($sbo[0]==null){
                                    $dance['user'] = $b_sql[0]['referees'];
                                    $dance['num_id'] = $bf_sql[0]['num_id'];
                                    $dance['user_fees'] = $poundage_f;
                                    if(M(''.$pr2.'_users_gold')->data($dance)->add()!==false){
                                        M(''.$pr2.'_users_gold')->commit();
                                        return 'success';
                                    }else{
                                        M(''.$pr2.'_users_gold')->rollback();
                                        return 'error';
                                    }
                                }else{
                                    $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                    if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                        M(''.$pr2.'_users_gold')->commit();
                                        return 'success';
                                    }else{
                                        M(''.$pr2.'_users_gold')->rollback();
                                        return 'error';
                                    }
                                }

                            }else{
                                $seller_members_f->rollback();
                                $rebate_record->rollback();
                                return 'error';
                            }
                        }
                    }else{//只有父级推荐人
                        //$seller_members_f = M(''.$pr2.'_members');
                        $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                        //return $poundage_f ;
                        //存入返佣记录表
                        $th = date('Y-m-d H:i:s',time());
                        $tms = substr($th,0,7);
                        $rebate_record = M(''.$tms.'_rebate_record');
                        $rebate_record->startTrans();
                        $cont['user'] = $b_sql[0]['referees'];
                        $cont['source'] = $tel;
                        $cont['money'] = $poundage_f;
                        $cont['time'] = time();
						$cont['type'] = 1;
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
                                    return 'error';
                                }
                            }else{
                                $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                    M(''.$pr2.'_users_gold')->commit();
                                    return 'success';
                                }else{
                                    M(''.$pr2.'_users_gold')->rollback();
                                    return 'error';
                                }
                            }
                        }else{
                            $seller_members_f->rollback();
                            $rebate_record->rollback();
                            return 'error';
                        }
                    }

                }else{
                    return 'success';
                }

            }else{//没有父级推荐人//
                return 'success';
            }
        }else{
            return 'error';
        }


    }

}