<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 9:44
 */

namespace Org\Our;
use Org\Our\Pay;
//充值  返佣
class Pay
{

    /**
     * 找到充值用户不大于两个的经理的数据
     * @ $tel     string  充值用户电话
     * **/
	public function manager($tel){
        //表前缀
        $pr = substr($tel,0,3);
        //空数组
        $arrTemp = array();
        //查询有没有推荐人
        $members1 = M(''.$pr.'_members');
        $gsql=$members1->field('referees,team')->where('user='.$tel.'')->select();
        //
        if($gsql[0]['referees']!=='' && $gsql[0]['referees']!==$tel){//有推荐人
            $br =  substr($gsql[0]['referees'],0,3);
            $mema = M(''.$br.'_members');
            $kong = $mema->where('user='.$gsql[0]['referees'].'')->select();
            if($kong[0]!==null){
                //查询有多少个用户表
                $statistical=M('Statistical');
                $dsql = $statistical->field('id,name')->select();

                //查询机构 人数条件 等级条件
                $global_conf = M('global_conf');
                $csql1 = $global_conf->field('value')->where('cases="z_number"')->select();//直接推荐人数
                $csql2 = $global_conf->field('value')->where('cases="j_number"')->select();//团队推荐人数
                $csql3 = $global_conf->field('value')->where('cases="z_level"')->select();//直接推荐等级
                $csql4 = $global_conf->field('value')->where('cases="j_level"')->select();//团队推荐等级
                //包不包含直推
                $contain_sql = $global_conf->field('value')->where('cases="contains"')->select();
                $fznum = 0;
                $ftnum = 0;
                for($i=0;$i<count($dsql);$i++){
                    $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
                    //return $rsql;
                    //父级直接推荐了多少人
                    $members2 = M(''.$rsql[0]['name'].'_members');
                    $cond['referees'] = $gsql[0]['referees'];
                    $cond['level'] = array('egt',$csql3[0]['value']);
                    $fzsql = $members2->where($cond)->count();
                    $fznum += $fzsql;
                    //查询父级团队有多少人
                    $members3 = M(''.$rsql[0]['name'].'_members');
                    $conr['level'] = array('egt',$csql4[0]['value']);
                    $conr['team'] = array("LIKE", "%".$gsql[0]['team']."%");
                    $ftsql = $members3->where($conr)->count();
                    $ftnum += $ftsql;
                }
                $ftt = $ftnum - $fznum;

                if($contain_sql[0]['value']==0){////////////////////////////////////////////////////////////////判断包含直推
                    $institutions = M('institutions');
                    $zsql = $institutions->where('user='.$gsql[0]['referees'].'')->select();
                    //////////
                    $id1 = 0;
                    if($zsql[0]!== null){//判断此号码是原定经理

                        $id1++;
                        $arrTemp[$id1] = $gsql[0]['referees'];
                        self::manager($gsql[0]['referees']);
                        return $arrTemp;
                    }else{//不是原定经理
                        //return $zsql;
                        $pra = substr($gsql[0]['referees'],0,3);
                        $membersa = M(''.$pra.'_members');
                        $hsql=$membersa->field(true)->where('user='.$gsql[0]['referees'].'')->select();
                        if($fznum >= $csql1[0]['value'] && $ftnum >= $csql2[0]['value']){//判断是经理
                            $conv['user'] = $gsql[0]['referees'];
                            $conv['name'] = $hsql[0]['name'];
                            $conv['card'] = $hsql[0]['id_card']; 
                            $conv['level'] = $hsql[0]['level'];
                            $conv['time'] = time();
                            if($institutions->data($conv)->add()!== false){
                                $arrTemp[] = $gsql[0]['referees'];
                                if($arrTemp!=''){
                                    self::manager($arrTemp[0]);
                                }
                                return $arrTemp;
                            }else{
                                return 'error';
                            }
                        }else{//判断不是经理
                            self::manager($gsql[0]['referees']);
                        }
                    }
                }else{/////////////////////////////////////////////////////////////////判断不包含直推
                    $institutions = M('institutions');
                    $zsql = $institutions->where('user='.$gsql[0]['referees'].'')->select();

                    //////////
                    $id1 = 0;
                    if($zsql[0]!== null){//判断此号码是原定经理
                        $id1++;
                        $arrTemp[$id1] = $gsql[0]['referees'];
                        self::manager($gsql[0]['referees']);
                        return $arrTemp;
                    }else{//不是原定经理
                        $pra = substr($gsql[0]['referees'],0,3);
                        $membersa = M(''.$pra.'_members');
                        $hsql=$membersa->field(true)->where('user='.$gsql[0]['referees'].'')->select();
                        //return $hsql[0]['name'];
                        if($fznum >= $csql1[0]['value'] && $ftt >= $csql2[0]['value']){//判断是经理
                            $conv['user'] = $gsql[0]['referees'];
                            $conv['name'] = $hsql[0]['name'];
                            $conv['card'] = $hsql[0]['card'];
                            $conv['level'] = $hsql[0]['level'];
                            $conv['time'] = time();
                            if($institutions->data($conv)->add()!== false){
                                $arrTemp[] = $gsql[0]['referees'];
                                if($arrTemp!=''){
                                    self::manager($arrTemp[0]);
                                }
                                return $arrTemp;
                            }else{
                                return 'error';
                            }
                        }else{//判断不是经理
                            self::manager($gsql[0]['referees']);
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
    }
    
    
    
    
    /**
     * 换宝石返佣的程序
     * @ $tel  string   兑换人的ID
     * @ $sum  int     兑换的金额
     * **/
    public function recharge($tel,$sum){
        $css = new Pay();
        $attrr = $css -> manager($tel);
        //表前缀
        $pr = substr($tel,0,3);
        //查询有没有父级推荐人
        $members_b = M(''.$pr.'_members');
        $b_sql=$members_b->field('referees,coin')->where('user='.$tel.'')->select();
        ////手续费
        $commission = M('commission');
        $usql1 = $commission->field('poundage_value')->where('id=1')->select();
        $usql2 = $commission->field('poundage_value')->where('id=2')->select();
        $usql3 = $commission->field('poundage_value')->where('id=5')->select();
        $usql4 = $commission->field('poundage_value')->where('id=6')->select();
        $poundage_f = $sum * $usql1[0]['poundage_value'];//父级推荐人的推荐费
        $poundage_y = $sum * $usql2[0]['poundage_value'];//爷爷级获得的推荐费
        $manager_f = $sum * $usql3[0]['poundage_value'];//父级推荐人的机构推荐费
        $manager_y = $sum * $usql4[0]['poundage_value'];//爷爷级推荐人的机构推荐费

		
        if($b_sql[0]['referees']=='' || $b_sql[0]['referees']==$tel){//没有父级推荐人
            return 'success';
        }else{//有父级推荐人//
            //的表前缀//
            $pr2 = substr($b_sql[0]['referees'],0,3);
            //查询有没有推荐人//
            $seller_members_f = M(''.$pr2.'_members');
            $bf_sql=$seller_members_f->field('referees,coin')->where('user='.$b_sql[0]['referees'].'')->select();
            //查询是不是机构账户
            $institutions = M('institutions');
            $ksql = $institutions->where('user='.$b_sql[0]['referees'].'')->select();
			if($bf_sql[0] !== null && $bf_sql[0]['referees']!==$b_sql[0]['referees']){
				if($ksql[0]==null){//不是机构账户
					if($bf_sql[0]['referees']==''){//没有爷爷级推荐人
						//$seller_members_f = M(''.$pr2.'_members');
						$cond['coin'] = $b_sql[0]['coin'] + $poundage_f;
						//return $poundage_f ;
						//存入返佣记录表
						$th = date('Y-m-d H:i:s',time());
						$tms = substr($th,0,7);
						$rebate_record = M(''.$tms.'_rebate_record');
						$cont['user'] = $b_sql[0]['referees'];
						$cont['source'] = $tel;
						$cont['money'] = $poundage_f;
						$cont['time'] = time();
						if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
							return 'success';
						}else{
							return 'error';
						}

					}else{//    有爷爷级推荐人

						//的表前缀
						$pr3 = substr($bf_sql[0]['referees'],0,3);
						//查询有没有推荐人
						$seller_members_y = M(''.$pr3.'_members');
						$by_sql=$seller_members_y->field('referees,coin')->where('user='.$bf_sql[0]['referees'].'')->select();
						///
						$ksql_y = $institutions->where('user='.$bf_sql[0]['referees'].'')->select();
						//return $ksql_y[0];
						if($by_sql[0] !== null && $by_sql[0]['referees']!==$bf_sql[0]['referees']){
							if($ksql_y[0]==null){//爷爷级不是机构
								//$seller_members_f = M(''.$pr2.'_members');
								$cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
								//存入返佣记录表
								$th = date('Y-m-d H:i:s',time());
								$tms = substr($th,0,7);
								$rebate_record = M(''.$tms.'_rebate_record');
								$cont['user'] = $b_sql[0]['referees'];
								$cont['source'] = $tel;
								$cont['money'] = $poundage_f;
								$cont['time'] = time();
								if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
									//$pr3 = substr($bf_sql[0]['referees'],0,3);
									$conk['coin'] =$by_sql[0]['coin'] + $poundage_y;
									//存入返佣记录表
									$cong['user'] = $bf_sql[0]['referees'];
									$cong['source'] = $tel;
									$cong['money'] = $poundage_y;
									$cong['time'] = time();
									if($seller_members_y->where('user='.$bf_sql[0]['referees'].'')->save($conk)!==false && $rebate_record->data($cong)->add()!==false){
										return 'success';
									}else{
										return 'error';
									}

								}else{
									return 'error';
								}

							}else{//爷爷级是机构
								//$seller_members_f = M(''.$pr2.'_members');
								$cond['coin'] =$bf_sql[0]['coin'] +  $poundage_f + $manager_f;
								//存入返佣记录表
								$th = date('Y-m-d H:i:s',time());
								$tms = substr($th,0,7);
								$rebate_record = M(''.$tms.'_rebate_record');
								$cont['user'] = $b_sql[0]['referees'];
								$cont['source'] = $tel;
								$cont['money'] = $poundage_f + $manager_f;
								$cont['time'] = time();
								if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
									//$pr3 = substr($bf_sql[0]['referees'],0,3);
									$conk['coin'] =$by_sql[0]['coin'] +  $poundage_y + $manager_y;
									//存入返佣记录表
									$cong['user'] = $bf_sql[0]['referees'];
									$cong['source'] = $tel;
									$cong['money'] = $poundage_y + $manager_y;
									$cong['time'] = time();
									if($seller_members_y->where('user='.$bf_sql[0]['referees'].'')->save($conk)!==false && $rebate_record->data($cong)->add()!==false){
										return 'success';
									}else{
										return 'error';
									}
								}else{
									return 'error';
								}
							}
						}else{
							return 'success';
						}
						
					}
				}else{//是机构账户
					//return 2005948;
					if($bf_sql[0]['referees']==''){//没有爷爷级推荐人
						//$seller_members_f = M(''.$pr2.'_members');
						$cond['coin'] =$bf_sql[0]['coin'] + $poundage_f + $manager_f;
						//存入返佣记录表
						$th = date('Y-m-d H:i:s',time());
						$tms = substr($th,0,7);
						$rebate_record = M(''.$tms.'_rebate_record');
						$cont['user'] = $b_sql[0]['referees'];
						$cont['source'] = $tel;
						$cont['money'] = $poundage_f + $manager_f;
						$cont['time'] = time();
						if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
							return 'success';
						}else{
							return 'error';
						}
					}else{//    有爷爷级推荐人
						
						//卖方的表前缀
						$pr3 = substr($bf_sql[0]['referees'],0,3);
						//查询卖方有没有推荐人
						$seller_members_y = M(''.$pr3.'_members');
						$by_sql=$seller_members_y->field('coin')->where('user='.$bf_sql[0]['referees'].'')->select();
						//////
						if($by_sql[0] !== nul && $by_sql[0]['referees']!==$bf_sql[0]['referees']){
							//$seller_members_f = M(''.$pr2.'_members');
							$cond['coin'] =$bf_sql[0]['coin'] +  $poundage_f + $manager_f;
							//存入返佣记录表
							$th = date('Y-m-d H:i:s',time());
							$tms = substr($th,0,7);
							$rebate_record = M(''.$tms.'_rebate_record');
							$cont['user'] = $b_sql[0]['referees'];
							$cont['source'] = $tel;
							$cont['money'] = $poundage_f + $manager_f;
							$cont['time'] = time();
							if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
								//$pr3 = substr($bf_sql[0]['referees'],0,3);
								$conk['coin'] =$by_sql[0]['coin'] +  $poundage_y + $manager_y;
								//存入返佣记录表
								$cong['user'] = $bf_sql[0]['referees'];
								$cong['source'] = $tel;
								$cong['money'] = $poundage_y + $manager_y;
								$cong['time'] = time();
								if($seller_members_y->where('user='.$bf_sql[0]['referees'].'')->save($conk)!==false && $rebate_record->data($cong)->add()!==false){
									return 'success';
								}else{
									return 'error';
								}

							}else{
								return 'error';
							}
						}else{
							return 'success';
						}
						

					}

				}
			}else{
				return 'success';
			}
            

        }

    }
}