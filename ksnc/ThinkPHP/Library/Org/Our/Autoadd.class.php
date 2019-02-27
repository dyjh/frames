<?php
namespace Org\Our;
use Think\Model;
header('content-type:text/html; charset=utf-8');

class Autoadd{
	
    public function Autoadd($tel){
		
        $first=substr($tel, 0, 3);
        $name=''.$first.'_members';

        $m = new Model();
        if($m->query('show tables like "'.$name.'"')){

        }else{
            
			$Model =  M();
            $Model->execute(' CREATE TABLE `'.$first.'_members` (
                              `id` int(11) unsigned  AUTO_INCREMENT,
							  `num_id` char(20) ,
                              `user` char(11) ,
		                       UNIQUE INDEX `user` USING BTREE (`user`) ,
                              `nickname` char(255) ,
                              `name` char(255) ,
                              `tel` char(255) ,
                              `headimg` char(255) ,
                               `id_card` char(255) ,
                               UNIQUE INDEX `id_card` USING BTREE (`id_card`) ,
                              `password` char(255) ,
                              `referees` char(255) ,
                              `level` int(10) unsigned ,
                              `team` longtext(0) ,
                              `disasters_num` int(2) unsigned ,
                              `coin` double(20,5) unsigned ,
                              `coin_freeze` double(20,5) unsigned ,
                              `diamond` int(10) unsigned ,
                              `login_time` int(10) unsigned ,
                              `cost_state` int(10) unsigned ,                            
                              `gift_state` int(10) unsigned ,
                              `real_name_state` int(10) unsigned ,
                              `freeze_state` int(10) unsigned ,
                              `sign_state` int(10) unsigned ,
                              `pwd` int(6) unsigned ,
							  `mac` char(100) ,
							  `bank_name` char(255) ,
							  `bank_num` char(20) ,
							  `pay_password` char(32) ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                        ');

             $Model->execute('CREATE TABLE `'.$first.'_meterial_warehouse` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` char(11) ,
                              `props` int(10) ,
                              `prop_name` varchar(40) DEFAULT NULL,
                              `num` int(10) unsigned DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
                         ');

            $Model->execute('CREATE TABLE `'.$first.'_record_shop` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` char(11) ,
                              `name` char(255) ,
                              `price` int(10) unsigned ,
                              `num` int(10) unsigned ,
                              `buy_time` int(11) unsigned ,
                              `type` char(255) ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_treasure_warehouse` (
                              `id` int(10)  AUTO_INCREMENT,
                              `user` char(11) ,
                              `name` char(255) ,
                              `num` int(10) ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_record_conversion` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` char(255) ,
                              `coin` double(20,5) unsigned ,
                              `diamond` int(10) unsigned ,
                              `name` char(255) ,
                              `num` int(10) unsigned ,
                              `buy_time` int(11) unsigned ,
                              `type` char(255) ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_fruit_record` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` char(11) ,
                              `seed` char(255) ,
                              `num` int(10) unsigned ,
                              `time` int(11) unsigned ,
                              `money` double(20,5) unsigned ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ');

             $Model->execute('CREATE TABLE `'.$first.'_managed_to_record` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` char(11) ,
                              `service_type` char(255) ,
                              `end_time` int(10) unsigned ,
                              `state` int(10) unsigned ,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ');

              $Model->execute('CREATE TABLE `'.$first.'_order` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
							  `user` char(11) ,
							  `order_num` varchar(255) ,
							  `money` double(20,5) unsigned ,
							  `add_time` int(11) unsigned ,
							  `state` int(10) unsigned ,
							  `pay_time` tinyint(3) unsigned ,
							  `pay_bank` varchar(80) ,
							  `pay_cash` tinyint(5) unsigned ,
                               PRIMARY KEY (`id`)
                             ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                           ');

              $Model->execute('CREATE TABLE `'.$first.'_planting_record` (
                                `id` int(10) unsigned  AUTO_INCREMENT,
                                `user` char(11) ,
                                `seed_type` char(255) ,
                                `seed_img_name` char(255) ,
                                `time` int(10) unsigned ,
								`steal_num` int(10) unsigned ,
                                `harvest_time` int(10) ,
                                `seed_state` int(10) unsigned ,
                                `disasters_state` char(255) ,
                                `disasters_time` int(10) ,
                                `disasters_value` int(10) unsigned ,
                                `housekeeper` char(255) ,
                                `harvest_num` int(10) unsigned ,
                                `harvest_state` int(10) unsigned ,
                                `number` int(11) unsigned ,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;
                          ');

              $Model->execute('CREATE TABLE `'.$first.'_prop_warehouse` (
                                 `id` int(10) unsigned  AUTO_INCREMENT,
                                  `user` char(11) ,
                                  `props` char(255) ,
                                  `prop_id` int(10) ,
                                  `num` int(10) unsigned ,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                           ');

               $Model->execute('CREATE TABLE `'.$first.'_member_record` (
                                  `id` int(11) unsigned  AUTO_INCREMENT,
								  `top_record` int(10) unsigned ,
								  `deposit_record` int(10) unsigned ,
								  `order_number` int(10) unsigned ,
								  `user` char(255) ,
								  `income` double(20,5) ,
								  `top_money` double(20,5) ,
								  `deposit_money` double(20,5) ,
								  PRIMARY KEY (`id`),
								  UNIQUE KEY `user` (`user`)
								) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
                             ');

               $Model->execute('CREATE TABLE `'.$first.'_seed_warehouse` (
                                     `id` int(10) unsigned  AUTO_INCREMENT,
                                      `user` char(11) ,
                                      `seeds` char(255) ,
                                      `num` int(10) unsigned ,
                                      PRIMARY KEY (`id`)
                                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                              ');

                $Model->execute('CREATE TABLE `'.$first.'_share_out_bonus` (
                                    `id` int(10) unsigned  AUTO_INCREMENT,
                                    `user` char(11) ,
                                    `money` double(20,5) ,
                                    `time` int(10) unsigned ,
                                    PRIMARY KEY (`id`)
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              ');

            $statistical=M('Statistical');
            $data['name']=$first;
            $statistical->add($data);
        }
    }


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
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `user` int(10) unsigned ,
                              `submit_num` int(10) unsigned ,
                              `num` int(10) unsigned ,
                              `money` double(20,4) unsigned ,
                              `time` int(11) unsigned ,
                              `state` int(2) unsigned  DEFAULT \'0\',
                              `seed` char(255) ,
                              `type` int(10) unsigned  COMMENT \'买入1卖出0\',
                              `trans_type` int(10) unsigned  COMMENT \'委托0市价1\',
                              `system` int(10) unsigned  DEFAULT \'0\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

                            DROP TABLE IF EXISTS `'.$begin.'_rebate_record`;
                            CREATE TABLE `'.$begin.'_rebate_record` (
                              `id` int(11)  AUTO_INCREMENT,
                              `user` varchar(11)  COMMENT \'获得返佣人的用户\',
                              `money` double(20,4)  COMMENT \'返佣的数量金额\',
                              `time` int(20)  COMMENT \'返佣时间\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

                            DROP TABLE IF EXISTS `'.$begin.'_matching`;
                            CREATE TABLE `'.$begin.'_matching` (
                              `id` int(10) unsigned  AUTO_INCREMENT,
                              `sell_user` char(255) ,
                              `num` int(10) unsigned ,
                              `money` double(20,4) unsigned ,
                              `time` int(11) unsigned ,
                              `seed` char(255) ,
                              `poundage` int(10) unsigned ,
                              `buy_user` char(255) ,
                              `total` int(11) unsigned ,
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
}

?>
