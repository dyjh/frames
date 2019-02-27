<?php

namespace Org\Our;
use Org\Our\Pay;
use Org\Our\archive;
use Think\Model;
//交易  返佣
class Deal
{



    public function conductor($tel,$sum){
        $css = new Pay();
        $mend = $css -> manager($tel);
        //return $mend;
        ////手续费
        $commission = M('commission');
        $usql3 = $commission->field('poundage_value')->where('id=12 or id=13')->select();
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
			$str =$mend[0].' '.$poob[0]['coin'].' '.date('Y-m-d H:i:s',time());
			file_put_contents('../log/rebate.log',$str.PHP_EOL."\n",FILE_APPEND);

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
				$cont['type'] = 2;
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
							if($mend[0]=='18048084818'){
								$case=''.$peo1.'_users_gold';
								$tixian=M($case)->where('user='.$mend[0].'')->find();
								$case_m=''.$peo1.'_members';
								$m_coin=M($case_m)->where('user='.$mend[0].'')->find();
								$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
								$str='用户：'.$mend[0].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
								file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
							}
                            return 'success';
                        }else{
                            M(''.$peo1.'_users_gold')->rollback();
                            return 'error';
                        }
                    }else{
                        $dance['user_fees'] = $sbo[0]['user_fees']+$manager_f;
                        if(M(''.$peo1.'_users_gold')->where('user='.$mend[0].'')->setInc('user_fees',$manager_f)!==false){
                            M(''.$peo1.'_users_gold')->commit();
							if($mend[0]=='18048084818'){
								$case=''.$peo1.'_users_gold';
								$tixian=M($case)->where('user='.$mend[0].'')->find();
								$case_m=''.$peo1.'_members';
								$m_coin=M($case_m)->where('user='.$mend[0].'')->find();
								$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
								$str='用户：'.$mend[0].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
								file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
							}
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
                M(''.$peo.'_members') ->startTrans();
                $pooa = M(''.$peo.'_members')->field(true)->where('user='.$mend[1].'')->select();
				$str =$mend[1].' '.$pooa[0]['coin'].' '.date('Y-m-d H:i:s',time());
				file_put_contents('../log/rebate.log',$str.PHP_EOL."\n",FILE_APPEND);
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
				$cont['type'] = 2;
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
					$cong['type'] = 2;
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
								if($mend[0]=='18048084818'){
									$case=''.$peo1.'_users_gold';
									$tixian=M($case)->where('user='.$mend[0].'')->find();
									$case_m=''.$peo1.'_members';
									$m_coin=M($case_m)->where('user='.$mend[0].'')->find();
									$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
									$str='用户：'.$mend[0].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
									file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
								}
								if($mend[1]=='18048084818'){
									$case=''.$peo.'_users_gold';
									$tixian=M($case)->where('user='.$mend[1].'')->find();
									$case_m=''.$peo.'_members';
									$m_coin=M($case_m)->where('user='.$mend[1].'')->find();
									$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
									$str='用户：'.$mend[1].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
									file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
								}
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
								if($mend[0]=='18048084818'){
									$case=''.$peo1.'_users_gold';
									$tixian=M($case)->where('user='.$mend[0].'')->find();
									$case_m=''.$peo1.'_members';
									$m_coin=M($case_m)->where('user='.$mend[0].'')->find();
									$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
									$str='用户：'.$mend[0].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
									file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
								}
								if($mend[1]=='18048084818'){
									$case=''.$peo.'_users_gold';
									$tixian=M($case)->where('user='.$mend[1].'')->find();
									$case_m=''.$peo.'_members';
									$m_coin=M($case_m)->where('user='.$mend[1].'')->find();
									$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
									$str='用户：'.$mend[1].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$manager_f.'';
									file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
								}
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

    //表user type为推荐人ID  //表pay  money为余额    user.id = pay.userid
    /**
     * 交易的程序
     * @ $mid  int  买方的ID
     * @ $tel  string  被买方的tel
     * @ $sum  int  交易手续费的数量
     * **/
    public function deal($tel,$sum){
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
        $usql1 = $commission->field('poundage_value')->where('id=3 or id=4')->select();
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
				$str =$b_sql[0]['referees'].' '.$bf_sql[0]['coin'].' '.date('Y-m-d H:i:s',time());
				file_put_contents('../log/rebate.log',$str.PHP_EOL."\n",FILE_APPEND);
				
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
						$str =$bf_sql[0]['referees'].' '.$by_sql[0]['coin'].' '.date('Y-m-d H:i:s',time());
				        file_put_contents('../log/rebate.log',$str.PHP_EOL."\n",FILE_APPEND);
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
							$cont['type'] = 2;
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
								$cong['type'] = 2;
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
											if($b_sql[0]['referees']=='18048084818'){
												$case=''.$pr2.'_users_gold';
												$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
												$case_m=''.$pr2.'_members';
												$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
												$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
												$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
												file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
											}
											if($bf_sql[0]['referees']=='18048084818'){
												$case=''.$pr3.'_users_gold';
												$tixian=M($case)->where('user='.$bf_sql[0]['referees'].'')->find();
												$case_m=''.$pr3.'_members';
												$m_coin=M($case_m)->where('user='.$bf_sql[0]['referees'].'')->find();
												$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
												$str='用户：'.$bf_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_y.'';
												file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
											}
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
											if($b_sql[0]['referees']=='18048084818'){
												$case=''.$pr2.'_users_gold';
												$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
												$case_m=''.$pr2.'_members';
												$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
												$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
												$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
												file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
											}
											if($bf_sql[0]['referees']=='18048084818'){
												$case=''.$pr3.'_users_gold';
												$tixian=M($case)->where('user='.$bf_sql[0]['referees'].'')->find();
												$case_m=''.$pr3.'_members';
												$m_coin=M($case_m)->where('user='.$bf_sql[0]['referees'].'')->find();
												$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
												$str='用户：'.$bf_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_y.'';
												file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
											}
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
                            $rebate_record ->startTrans();
                            $cont['user'] = $b_sql[0]['referees'];
                            $cont['source'] = $tel;
                            $cont['money'] = $poundage_f;
                            $cont['time'] = time();
							$cont['type'] = 2;
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
										if($b_sql[0]['referees']=='18048084818'){
											$case=''.$pr2.'_users_gold';
											$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
											$case_m=''.$pr2.'_members';
											$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
											$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
											$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
											file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
										}
                                        return 'success';
                                    }else{
                                        M(''.$pr2.'_users_gold')->rollback();
                                        return 'error';
                                    }
                                }else{
                                    $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                    if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                        M(''.$pr2.'_users_gold')->commit();
										if($b_sql[0]['referees']=='18048084818'){
											$case=''.$pr2.'_users_gold';
											$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
											$case_m=''.$pr2.'_members';
											$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
											$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
											$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
											file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
										}
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
						$cont['type'] = 2;
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
									if($b_sql[0]['referees']=='18048084818'){
										$case=''.$pr2.'_users_gold';
										$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
										$case_m=''.$pr2.'_members';
										$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
										$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
										$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
										file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
									}
                                    return 'success';
                                }else{
                                    M(''.$pr2.'_users_gold')->rollback();
                                    return 'error';
                                }
                            }else{
                                $dance['user_fees'] = $sbo[0]['user_fees']+$poundage_f;
                                if(M(''.$pr2.'_users_gold')->where('user='.$b_sql[0]['referees'].'')->setInc('user_fees',$poundage_f)!==false){
                                    M(''.$pr2.'_users_gold')->commit();
									if($b_sql[0]['referees']=='18048084818'){
										$case=''.$pr2.'_users_gold';
										$tixian=M($case)->where('user='.$b_sql[0]['referees'].'')->find();
										$case_m=''.$pr2.'_members';
										$m_coin=M($case_m)->where('user='.$b_sql[0]['referees'].'')->find();
										$coin_tix=$tixian['buy_and_sell']+$tixian['user_fees'];
										$str='用户：'.$b_sql[0]['referees'].' 可提现：'.$coin_tix.' 总金币：'.$m_coin['coin'].' 佣金：'.$poundage_f.'';
										file_put_contents('../log/fanyong.log',$str.PHP_EOL."\n",FILE_APPEND);
									}
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