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
                              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
							  `num_id` char(20) NOT NULL,
                               `user` char(11) NOT NULL,
                               UNIQUE INDEX `user` USING BTREE (`user`) ,
                              `nickname` char(255) NOT NULL,
                              `name` char(255) NOT NULL,
                              `tel` char(255) NOT NULL,
                              `headimg` char(255) NOT NULL,
                               `id_card` char(255) NOT NULL,
                               UNIQUE INDEX `id_card` USING BTREE (`id_card`) ,
                              `password` char(255) NOT NULL,
                              `referees` char(255) NOT NULL,
                              `level` int(10) unsigned NOT NULL,
                              `team` longtext(0) NOT NULL,
                              `disasters_num` int(2) unsigned NOT NULL,
                              `coin` double(20,5) unsigned NOT NULL,
                              `coin_freeze` double(20,5) unsigned NOT NULL,
                              `diamond` int(10) unsigned NOT NULL,
                              `login_time` int(10) unsigned NOT NULL,
                              `cost_state` int(10) unsigned NOT NULL,                            
                              `gift_state` int(10) unsigned NOT NULL,
                              `real_name_state` int(10) unsigned NOT NULL,
                              `freeze_state` int(10) unsigned NOT NULL,
                              `sign_state` int(10) unsigned NOT NULL,
							  `mac` char(100) NOT NULL,
							  `bank_name` char(255) NOT NULL,
							  `bank_num` char(20) NOT NULL,
							  `pay_password` char(32) NOT NULL,
							  `identity` varchar(20) NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                        ');

             $Model->execute('CREATE TABLE `'.$first.'_meterial_warehouse` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `props` int(10) NOT NULL,
                              `prop_name` varchar(40) DEFAULT NULL,
                              `num` int(10) unsigned DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
                         ');

            $Model->execute('CREATE TABLE `'.$first.'_record_shop` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `name` char(255) NOT NULL,
							  `articles` char(255) NOT NULL,
                              `price` int(10) unsigned NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `buy_time` int(11) unsigned NOT NULL,
                              `type` char(255) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_treasure_warehouse` (
                              `id` int(10) NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `name` char(255) NOT NULL,
                              `num` int(10) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_record_conversion` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(255) NOT NULL,
                              `coin` double(20,5) unsigned NOT NULL,
                              `diamond` int(10) unsigned NOT NULL,
                              `name` char(255) NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `buy_time` int(11) unsigned NOT NULL,
							  `attach` varchar(255) NULL,
                              `type` char(255) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
                         ');

             $Model->execute('CREATE TABLE `'.$first.'_fruit_record` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `seed` char(255) NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `time` int(11) unsigned NOT NULL,
                              `money` double(20,5) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ');

             $Model->execute('CREATE TABLE `'.$first.'_managed_to_record` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `service_type` char(255) NOT NULL,
                              `end_time` int(10) unsigned NOT NULL,
                              `state` int(10) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          ');

              $Model->execute('CREATE TABLE `'.$first.'_order` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
							  `user` char(11) NOT NULL,
							  `order_num` varchar(255) NOT NULL,
							  `money` double(20,5) unsigned NOT NULL,
							  `add_time` int(11) unsigned NOT NULL,
							  `state` int(10) unsigned NOT NULL,
							  `pay_time` tinyint(3) unsigned NOT NULL,
							  `pay_bank` varchar(80) NOT NULL,
							  `pay_cash` tinyint(5) unsigned NOT NULL,
                               PRIMARY KEY (`id`)
                             ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                           ');

              $Model->execute('CREATE TABLE `'.$first.'_planting_record` (
                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                `user` char(11) NOT NULL,
                                `seed_type` char(255) NOT NULL,
                                `seed_img_name` char(255) NOT NULL,
                                `time` int(10) unsigned NOT NULL,
								`steal_num` int(10) unsigned NOT NULL,
                                `harvest_time` int(10) NOT NULL,
                                `seed_state` int(10) unsigned NOT NULL,
                                `disasters_state` char(255) NOT NULL,
                                `disasters_time` int(10) NOT NULL,
                                `disasters_value` int(10) unsigned NOT NULL,
                                `housekeeper` char(255) NOT NULL,
                                `harvest_num` int(10) unsigned NOT NULL,
                                `harvest_state` int(10) unsigned NOT NULL,
                                `number` int(11) unsigned NOT NULL,
								`auto` int(11) NULL,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;
                          ');

              $Model->execute('CREATE TABLE `'.$first.'_prop_warehouse` (
                                 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  `user` char(11) NOT NULL,
                                  `props` char(255) NOT NULL,
                                  `prop_id` int(10) NOT NULL,
                                  `num` int(10) unsigned NOT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                           ');

               $Model->execute('CREATE TABLE `'.$first.'_member_record` (
                                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                  `top_record` int(10) unsigned NOT NULL,
                                  `deposit_record` int(10) unsigned NOT NULL,
                                  `order_number` int(10) unsigned NOT NULL,
                                  `user` char(255) NOT NULL,
                                  `income` double(20,5) NOT NULL,
								  `top_money` double(20,5) NOT NULL,
								  `deposit_money` double(20,5) NOT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                             ');

               $Model->execute('CREATE TABLE `'.$first.'_seed_warehouse` (
                                     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `user` char(11) NOT NULL,
                                      `seeds` char(255) NOT NULL,
                                      `num` int(10) unsigned NOT NULL,
                                      PRIMARY KEY (`id`)
                                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                              ');

                $Model->execute('CREATE TABLE `'.$first.'_share_out_bonus` (
                                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                    `user` char(11) NOT NULL,
                                    `money` double(20,5) NOT NULL,
                                    `time` int(10) unsigned NOT NULL,
                                    PRIMARY KEY (`id`)
                                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                              ');
							  
				 //鸡场表
                 $Model->execute('CREATE TABLE `'.$first.'_chicken_record` (
								`id`  int(11) NOT NULL AUTO_INCREMENT ,
								`user`  char(20) NOT NULL ,
								`chicken_id`  int(11) NOT NULL ,
								`chicken_type`  char(10) NOT NULL ,
								`buy_time`  int(11) NOT NULL ,
								`price`  int(11) NOT NULL ,
								`fruit`  varchar(255) NOT NULL ,
								`harvest_time`  int(11) NOT NULL ,
								`harvest_state`  int(11) NOT NULL ,
								`sell_time`  int(11) NOT NULL ,
								`harvest_fruit`  varchar(255) NOT NULL ,
								`earnings` int(11) NOT NULL ,
								`conversion`  int(11) NOT NULL ,
								PRIMARY KEY (`id`)
							  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                 ');              
   
   
                 $Model->execute('CREATE TABLE `'.$first.'_maintain_record` (
								`id`  int(11) NOT NULL AUTO_INCREMENT ,
								`user`  char(20) NOT NULL ,
								`maintain_time`  int(11) NOT NULL ,
								`due_time`  int(11) NOT NULL ,
								`cost_coin`  int(11) NOT NULL ,
								`cost_fruit`  char(100) NOT NULL ,
								`name`  char(10) NOT NULL ,
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
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` int(10) unsigned NOT NULL,
                              `submit_num` int(10) unsigned NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `money` double(20,4) unsigned NOT NULL,
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
                              `user` varchar(11) NOT NULL COMMENT \'获得返佣人的用户\',
                              `money` double(20,4) NOT NULL COMMENT \'返佣的数量金额\',
                              `time` int(20) NOT NULL COMMENT \'返佣时间\',
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

                            DROP TABLE IF EXISTS `'.$begin.'_matching`;
                            CREATE TABLE `'.$begin.'_matching` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `sell_user` char(255) NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `money` double(20,4) unsigned NOT NULL,
                              `time` int(11) unsigned NOT NULL,
                              `seed` char(255) NOT NULL,
                              `poundage` int(10) unsigned NOT NULL,
                              `buy_user` char(255) NOT NULL,
                              `total` int(11) unsigned NOT NULL,
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
