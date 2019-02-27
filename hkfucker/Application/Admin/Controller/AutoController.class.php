<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29 0029
 * Time: 下午 4:21
 */

namespace Admin\Controller;
use Think\Tool;
use Think\Model;
use Think\Matching;
header('content-type:text/html; charset=utf-8');
class AutoController
{

    //每天24点运行   清除昨日开箱数据
    public function del_list(){
        $time=0;
        M('record_treasure')->where('time="'.$time.'"')->delete();
    }

    //交易表自动建表以及统计总数据     每天12点运行
    public function Add(){
        $BeginDate=date('Y-m-01 0:00:00', strtotime(date("Y-m-d")));
        $last_begin=strtotime(date('Y-m-d 0:00:00', strtotime("$BeginDate +1 month -1 day")));
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        $t_m=''.$y.'-'.$m.'_matching';
        $start= mktime(0,0,0,$m,$d,$y);//即是当天零点的时间戳
        if($start==$last_begin){
            $begin=$last_begin+24*3600;
            $begin=date('Y-m',$begin);
            //print_r($begin);
            $Model =  M();
            $Model->execute('DROP TABLE IF EXISTS `'.$begin.'_pay`;
                            CREATE TABLE `'.$begin.'_pay` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) unsigned NOT NULL,
                              `submit_num` int(10) unsigned NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `money` float(10,4) unsigned NOT NULL,
                              `time` int(11) unsigned NOT NULL,
                              `state` int(2) unsigned NOT NULL DEFAULT \'0\',
                              `seed` char(255) NOT NULL,
                              `type` int(10) unsigned NOT NULL COMMENT \'买入1卖出0\',
                              `trans_type` int(10) unsigned NOT NULL COMMENT \'委托0市价1\',
                              `system` int(10) unsigned NOT NULL DEFAULT \'0\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
                            
                            DROP TABLE IF EXISTS `'.$begin.'_rebate_record`;
                            CREATE TABLE `'.$begin.'_rebate_record` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
							  `user` varchar(11) NOT NULL,
							  `money` float(11,4) unsigned NOT NULL,
							  `source` varchar(11) NOT NULL,
							  `time` int(20) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                            
                            DROP TABLE IF EXISTS `'.$begin.'_matching`;
                            CREATE TABLE `'.$begin.'_matching` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `sell_user` char(255) NOT NULL,
							  `num` int(10) unsigned NOT NULL,
							  `money` float(10,4) unsigned NOT NULL,
							  `time` int(11) unsigned NOT NULL,
							  `seed` char(255) NOT NULL,
							  `poundage` float(10,4) unsigned NOT NULL,
							  `buy_user` char(255) NOT NULL,
							  `total` float(11,4) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
            ');

            $time_record=M('Time_record');
            $data_find=$time_record->select();
            if(empty($data_find)){

            }else{
                $data_seed=M('Seeds')->select();
                foreach ($data_seed as $k=>$v){
                    $total=M('Fruit_record')->where('seed='.$v['varieties'])->find();
                    $data['seed']=$v['varieties'];
                    $data['money']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('total');
                    $data['poundage']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('poundage');
                    $data['num']=M(''.$t_m.'')->where('seed="'.$v['varieties'].'"')->sum('num');
                    if(empty($total)){
                        if(M('Fruit_record')->add($data)){

                        }
                    }else{
                        if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('num',$data['num'])){
                            if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('money',$data['money'])){
                                if(M('Fruit_record')->where('seed='.$v['varieties'])->setInc('poundage',$data['poundage'])){

                                }
                            }
                        }
                    }
                }
            }
            $data['time']=date('Y-m-d');
            if($time_record->add($data)){

            }
        }
    }

    //统计每天K线数据      收盘后运行
    public function time(){
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        $time=time();
        //print_r($start);die;
        $end = mktime($e_data['value'],0,0,$m,$d,$y);

        if($time>$end){
            $data_fruit=M('Seeds')->select();
            foreach ($data_fruit as $k=>$v){
                $case_m=''.date('Y-m').'_matching';
                $data_c=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->select();
                if(!empty($data_c)){
                    $count=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->sum('num');
                    $data['num']=$count;
                    $data_min=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->order('money')->select();
                    $data['min_money']=$data_min[0]['money'];
                    $data['max_money']=$data_min[count($data_min)-1]['money'];
                    //print_r($max_money);
                    $data_max=M(''.$case_m.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$v['varieties'].'"')->order('time')->select();
                    $data['start_money']=$data_max[0]['money'];
                    $data['end_money']=$data_max[count($data_min)-1]['money'];
                    $data['seed']=$v['varieties'];
                    $data['time']=$start;
                    M('Pay_statistical')->add($data);
                }
            }
        }else{
            echo 3;
        }
    }
    //自动撤销交易   收盘时运行
    public function Auto_return(){
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        $time=time();
        //print_r($start);die;
        $end = mktime($e_data['value'],0,0,$m,$d,$y);

        $case_p=''.date('Y-m').'_pay';
        //实时
        $data=M(''.$case_p.'')->where('time >= "'.$start.'" AND state < 2 AND type=1 AND time <= " '.$end.'"')->order('money')->select();
        foreach ($data as $k=>$v){
            $data_p['system']=1;
            $data_p['state']=3;
            $model=new Model;
            $model->startTrans();
            if(M(''.$case_p.'')->where('id='.$v['id'])->save($data_p)){
                $money=$v['num']*$v['money'];
                $table=new Tool();
                $case='members';
                $tel=$v['user'];
                //$tel=13688804;
                $case_m=$table->table($tel,$case);
                if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
                    if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
                        $model->commit();
                    }else{
                        $model->rollback();
                    }
                }else{
                    $model->rollback();
                }
            }else{
                $model->rollback();
            }
        }
        $data=M(''.$case_p.'')->where('time >= "'.$start.'" AND state < 2 AND type=0 AND time <= " '.$end.'"')->order('money')->select();
        foreach ($data as $k=>$v){
            //$money=$v['num']*$v['money'];
            $data_p['system']=1;
            $data_p['state']=3;
            $model=new Model;
            $model->startTrans();
            if(M(''.$case_p.'')->where('id='.$v['id'])->save($data_p)){
                $table=new Tool();
                $case='fruit_record';
                $tel=$v['user'];
                $case_f=$table->table($tel,$case);
                $data=M(''.$case_f.'')->where('user='.$tel.' AND seed="'.$v['seed'].'" AND money='.$v['money'])->find();
                if($data['num']==$v['num']){
                    if(M(''.$case_f.'')->where('user='.$tel.' AND seed="'.$v['seed'].'" AND money='.$v['money'])->delete()){
                        $model->commit();
                    }else{
                        $model->rollback();
                    }
                }else{
                    if(M(''.$case_f.'')->where('user='.$tel.' AND seed="'.$v['seed'].'" AND money='.$v['money'])->setDec('num',$v['num'])){
                        $model->commit();
                    }else{
                        $model->rollback();
                    }
                }
            }else{
                $model->rollback();
            }
        }
    }
    //针对未回本用户  发放灾难
    public function disaster_new(){
        $data=M('disaster_time')->where('type=0')->find();
        if(empty($data)){

        }else{
            $zhi=0;
            $i=0;
            $k=$data['k'];
            $res=$this->Disasters($k,$i,$zhi);
            if($res==1){
                M('disaster_time')->where('type=0')->setDec('times',1);
                $data_new=M('disaster_time')->where('type=0')->find();
                if($data_new['times']==0){
                    M('disaster_time')->where('type=0')->delete();
                }
            }
        }
    }

    //针对回本用户  发放灾难
    public function disaster_old(){
        $data=M('disaster_time')->where('type=1')->find();
        if(empty($data)){

        }else{
            $zhi=0;
            $i=0;
            $k=$data['k'];
            $res=$this->Disasters($k,$i,$zhi);
            if($res==1){
                M('disaster_time')->where('type=1')->setDec('times',1);
                $data_new=M('disaster_time')->where('type=1')->find();
                if($data_new['times']==0){
                    M('disaster_time')->where('type=1')->delete();
                }
            }
        }
    }

    //灾难自动分配    什么时候运行你随意，开心就好。。。
    public function Disasters($k,$i,$zhi){
        if($k==$i){
            return 1;
        }else{
            $statistical=M('Statistical');
            $count_s =$statistical->count();
            $number_s=mt_rand(1,$count_s);
            $tel=$statistical->select();
            $num=$tel[$number_s-1]['name'];
            $case=''.$num.'_members';
            $m = new Model();
            if($m->query('show tables like "'.$case.'"')){
                //echo 1;
                //随机会员
                $member=M(''.$case.'');
                if($zhi==0){
                    $cases='disasters_new';
                    $data_old=M('global_conf')->where('cases ="'.$cases.'"')->find();
                    $count =$member->where('id > 4 AND disasters_num < '.$data_old['value'].' AND cost_state=0')->count();
                    $data=$member->where('id > 4 AND disasters_num < '.$data_old['value'].' AND cost_state=0')->select();
                }elseif ($zhi==1){
                    $cases='disasters_old';
                    $data_old=M('global_conf')->where('cases ="'.$cases.'"')->find();
                    $count =$member->where('id > 4 AND disasters_num < '.$data_old['value'].' AND cost_state=1')->count();
                    $data=$member->where('id > 4 AND disasters_num < '.$data_old['value'].' AND cost_state=1')->select();
                }
                //print_r($count);
                $number=mt_rand(1,$count);
                //print_r($data[$number-1]);
                if(empty($data)){
                    $this->Disasters($k,$i,$zhi);
                }else{
                    $user=$data[$number-1]['user'];
                    $case_plant=''.$num.'_planting_record';
                    //随机灾难
                    $disaster=M('Disasters')->select();
                    $dis_num=mt_rand(0,2);
                    $dis_rand=$disaster[$dis_num];
                    $case_mamaged=''.$num.'_managed_to_record';
                    $mamaged=M(''.$case_mamaged.'')->where('user ='.$user.' AND service_type ='.$dis_rand['d_id'].' AND state = 0')->find();
                    //判断是否有对应的管家服务
                    if(empty($mamaged)){
                        $case_seed=''.$num.'_seed_warehouse';
                        $data_seed=M(''.$case_seed.'')->where('user ='.$user)->order('num DESC')->select();
                        //print_r($data_seed);die;
                        //判断仓库是否有果实
                        if(empty($data_seed)){
                            $planting=M(''.$case_plant.'')->where('disasters_state not regexp "[[:<:]]'.$dis_rand['d_id'].'" AND seed_state < 3 AND user ='.$user)->select();
                            $count_plant=M(''. $case_plant.'')->where('disasters_state not regexp "[[:<:]]'.$dis_rand['d_id'].'" AND seed_state < 3 AND user ='.$user)->count();
                            if(empty($planting)){
                                $this->Disasters($k,$i,$zhi);
                            }else{
                                $number_p=mt_rand(1,$count_plant);
                                $plant=$planting[$number_p-1];
                                if(empty($plant['disasters_state'])){
                                    $data_n['disasters_state']=$dis_rand['d_id'];
                                    $data_n['disasters_time']=time();
                                    $data=M('global_conf')->where('cases ="disasters_number"')->find();
                                    //print_r($data['value']);die;
                                    //echo 1;
                                    //print_r($plant['id']);die;
                                    M(''.$case_plant.'')->where('id ='.$plant['id'])->setInc('disasters_value',$data['value']);
                                    M(''.$case_plant.'')->where('id ='.$plant['id'])->save($data_n);
                                    M(''.$case.'')->where('user ='.$user)->setInc('disasters_num',1);
                                    $i++;
                                    $this->Disasters($k,$i,$zhi);
                                }else{
                                    //$str=$plant['disasters_state'].' '.$dis_rand['d_id'].'';
                                    //$data_n['disasters_state']=$str;
                                    $this->Disasters($k,$i,$zhi);
                                }
                            }
                        }else{
                            $seed=$data_seed[0]['seeds'];
                            $planting=M(''.$case_plant.'')->where('disasters_state not regexp "[[:<:]]'.$dis_rand['d_id'].'" AND harvest_state = 0 AND user ='.$user.' AND seed_type='.$seed)->select();
                            $count_plant=M(''. $case_plant.'')->where('disasters_state not regexp "[[:<:]]'.$dis_rand['d_id'].'" AND harvest_state = 0 AND user ='.$user.' AND seed_type='.$seed)->count();
                            if(empty($planting)){
                                $this->Disasters($k,$i,$zhi);
                            }else{
                                $number_p=mt_rand(1,$count_plant);
                                $plant=$planting[$number_p-1];
                                if(empty($plant['disasters_state'])){
                                    $data_n['disasters_state']=$dis_rand['d_id'];
                                    $data_n['disasters_time']=time();
                                    $data=M('Global_conf')->where('cases="disasters_number"')->find();
                                    //print_r($plant['id']);die;
                                    //
                                    M(''.$case_plant.'')->where('id ='.$plant['id'])->setInc('disasters_value',$data['value']);
                                    M(''.$case_plant.'')->where('id ='.$plant['id'])->save($data_n);
                                    M(''.$case.'')->where('user ='.$user)->setInc('disasters_num',1);
                                    $i++;
                                    $this->Disasters($k,$i,$zhi);
                                }else{
                                    //$str=$plant['disasters_state'].' '.$dis_rand['d_id'].'';
                                    //$data_n['disasters_state']=$str;
                                    $this->Disasters($k,$i,$zhi);
                                }
                            }
                        }
                    }else{
                        $this->Disasters($k,$i,$zhi);
                    }
                }
            }else{
                $this->Disasters($k,$i,$zhi);
            }
        }
    }

    //自动匹配   在开盘时间内运行
    public function Auto(){
        if(IS_AJAX){
            $case=''.date('Y-m').'_pay';
            //卖出
            $sell=M($case)->where('state < 2 AND type =0 AND trans_type =0')->order('time')->select();
			if($type==0){
				$t=1;
			}else{
				$t=0;
			}
			$i=0;
			$data_sell=$matching->find_sell($sell,$i,$t);
            //实时
            /* $sell=M(''.$case.'')->where('state < 2 AND type =0 AND trans_type =1')->order('time')->select();
             $i=0;
             $data_s=$matching->find($sell,$i);*/
            //print_r($data);die;
            $buy=M($case)->where('state < 2 AND type =1 AND trans_type =0')->order('time')->select();
			 if($type==0){
				 $t=1;
			 }else{
				 $t=0;
			 }
			 $i=0;
			 //echo $sell[0]['num'];die;
			 $matching=new Matching();
			 $data_buy=$matching->find_buy($buy,$i,$t);

        }
    }

}