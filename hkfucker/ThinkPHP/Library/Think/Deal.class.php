<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 9:45
 */
namespace Home\Controller;
namespace Think;
use Think\Controller;
//交易  返佣
class Deal
{

    //表user type为推荐人ID  //表pay  money为余额    user.id = pay.userid
    /**
     * 交易的程序
     * @ $mid  int  买方的ID
     * @ $tel  string  被买方的tel
     * @ $sum  int  交易手续费的数量
     * **/
     static function deal($btel,$sum){
         //print_r($btel);die;
         $css = new Pay();
         $attrr = $css -> manager($btel);
        //卖方的表前缀
        $pr1 = substr($btel,0,3);
        //查询卖方有没有推荐人
        $seller_members = M(''.$pr1.'_members');
        $b_sql=$seller_members->field('referees,coin')->where('user='.$btel.'')->select();
        ////手续费
        $commission = M('commission');
        $usql1 = $commission->field('poundage_value')->where('id=3')->select();
        $usql2 = $commission->field('poundage_value')->where('id=4')->select();
        $usql3 = $commission->field('poundage_value')->where('id=12')->select();
        $usql4 = $commission->field('poundage_value')->where('id=13')->select();
        $poundage_f  = $sum * $usql1[0]['poundage_value'];//父级推荐人的推荐费
        $poundage_y = $sum * $usql2[0]['poundage_value'];//爷爷级获得的推荐费
        $manager_f = $sum * $usql3[0]['poundage_value'];
        $manager_y = $sum * $usql4[0]['poundage_value'];

        if($b_sql[0]['referees']==''){//没有父级推荐人
            //return '没有推荐人';
            return 'success';
        }else{//有父级推荐人//
            //卖方的表前缀//
            $pr2 = substr($b_sql[0]['referees'],0,3);
            //查询卖方有没有推荐人//
            $seller_members_f = M(''.$pr2.'_members');
            $bf_sql=$seller_members_f->field('referees,coin')->where('user='.$b_sql[0]['referees'].'')->select();
            //
            $institutions = M('institutions');
            $ksql = $institutions->where('user='.$b_sql[0]['referees'].'')->select();
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
                    $cont['source'] = $btel;
                    $cont['money'] = $poundage_f;
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
                    ///
                    $ksql_y = $institutions->where('user='.$bf_sql[0]['referees'].'')->select();
                    if($ksql_y[0]==null){//爷爷级不是机构
                        //$seller_members_f = M(''.$pr2.'_members');
                        $cond['coin'] = $bf_sql[0]['coin'] + $poundage_f;
                        //存入返佣记录表
                        $th = date('Y-m-d H:i:s',time());
                        $tms = substr($th,0,7);
                        $rebate_record = M(''.$tms.'_rebate_record');
                        $cont['user'] = $b_sql[0]['referees'];
                        $cont['source'] = $btel;
                        $cont['money'] = $poundage_f;
                        $cont['time'] = time();
                        if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
                            //$pr3 = substr($bf_sql[0]['referees'],0,3);
                            $conk['coin'] =$by_sql[0]['coin'] + $poundage_y;
                            //存入返佣记录表
                            $cong['user'] = $bf_sql[0]['referees'];
                            $cong['source'] = $btel;
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
                        $cont['source'] = $btel;
                        $cont['money'] = $poundage_f + $manager_f;
                        $cont['time'] = time();
                        if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
                            //$pr3 = substr($bf_sql[0]['referees'],0,3);
                            $conk['coin'] =$by_sql[0]['coin'] +  $poundage_y + $manager_y;
                            //存入返佣记录表
                            $cong['user'] = $bf_sql[0]['referees'];
                            $cong['source'] = $btel;
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
                    $cont['source'] = $btel;
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
                    //
                    //$seller_members_f = M(''.$pr2.'_members');
                    $cond['coin'] =$bf_sql[0]['coin'] +  $poundage_f + $manager_f;
                    //存入返佣记录表
                    $th = date('Y-m-d H:i:s',time());
                    $tms = substr($th,0,7);
                    $rebate_record = M(''.$tms.'_rebate_record');
                    $cont['user'] = $b_sql[0]['referees'];
                    $cont['source'] = $btel;
                    $cont['money'] = $poundage_f + $manager_f;
                    $cont['time'] = time();
                    if($seller_members_f->where('user='.$b_sql[0]['referees'].'')->save($cond)!==false && $rebate_record->data($cont)->add()!==false){
                        //$pr3 = substr($bf_sql[0]['referees'],0,3);
                        $conk['coin'] =$by_sql[0]['coin'] +  $poundage_y + $manager_y;
                        //存入返佣记录表
                        $cong['user'] = $bf_sql[0]['referees'];
                        $cong['source'] = $btel;
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

            }

        }
         //print_r($btel);die;
     }

}