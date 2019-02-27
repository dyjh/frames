<?php
namespace Think;
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
            $Model->execute('DROP TABLE IF EXISTS `'.$first.'_members`;
                            CREATE TABLE `'.$first.'_members` (
                              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `mac` char(100) NOT NULL,
                              `nickname` char(255) NOT NULL,
                              `name` char(255) NOT NULL,
                              `tel` char(255) NOT NULL,
                              `headimg` char(255) NOT NULL,
                              `id_card` char(255) NOT NULL,
                              `password` char(255) NOT NULL,
                              `referees` char(255) NOT NULL,
                             `level` int(10) unsigned NOT NULL DEFAULT \'1\',
                              `team` char(255) NOT NULL,
                              `disasters_num` int(2) unsigned NOT NULL,
                              `coin` int(10) unsigned NOT NULL,
                              `coin_freeze` float(10,4) unsigned NOT NULL,
                              `diamond` int(10) unsigned NOT NULL,
                              `login_time` int(11) unsigned NOT NULL,
                              `cost_state` int(10) unsigned NOT NULL,
                             `bank_name` char(255) NOT NULL,
                                  `bank_num` char(20) NOT NULL,
                              `gift_state` int(10) unsigned NOT NULL,
                              `real_name_state` int(10) unsigned NOT NULL,
                              `freeze_state` int(10) unsigned NOT NULL,
                              `sign_state` int(10) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

                            DROP TABLE IF EXISTS `'.$first.'_meterial_warehouse`;
                            CREATE TABLE `'.$first.'_meterial_warehouse` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `props` int(10) NOT NULL COMMENT \'道具\',
                              `prop_name` varchar(40) DEFAULT NULL,
                              `num` int(10) unsigned DEFAULT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

                            CREATE TABLE `'.$first.'_record_shop` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `name` char(255) NOT NULL,
                              `price` int(10) unsigned NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `buy_time` int(11) unsigned NOT NULL,
                              `type` char(255) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

                            
                            DROP TABLE IF EXISTS `'.$first.'_treasure_warehouse`;
                            CREATE TABLE `'.$first.'_treasure_warehouse` (
                              `id` int(10) NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `name` char(255) NOT NULL,
                              `num` int(10) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                            
                            CREATE TABLE `'.$first.'_record_conversion` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(255) NOT NULL,
                              `coin` int(10) unsigned NOT NULL,
                              `diamond` int(10) unsigned NOT NULL,
                              `name` char(255) NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `buy_time` int(11) unsigned NOT NULL,
                              `type` char(255) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


                            DROP TABLE IF EXISTS `'.$first.'_fruit_record`;
                            CREATE TABLE `'.$first.'_fruit_record` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `seed` char(255) NOT NULL,
                              `num` int(10) unsigned NOT NULL,
                              `time` int(11) unsigned NOT NULL,
                              `money` int(10) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                    
                            DROP TABLE IF EXISTS `'.$first.'_managed_to_record`;
                            CREATE TABLE `'.$first.'_managed_to_record` (
                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `user` char(11) NOT NULL,
                              `service_type` char(255) NOT NULL,
                              `end_time` int(10) unsigned NOT NULL,
                              `state` int(10) unsigned NOT NULL,
                              PRIMARY KEY (`id`)
                          
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        
                               DROP TABLE IF EXISTS `'.$first.'_order`;
                                CREATE TABLE `'.$first.'_order` (
                                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  `user` char(11) NOT NULL,
                                  `order_num` char(255) NOT NULL,
                                  `money` int(10) unsigned NOT NULL,
                                  `time` int(11) unsigned NOT NULL,
                                  `state` int(10) unsigned NOT NULL,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

                        
                               DROP TABLE IF EXISTS `'.$first.'_planting_record`;
                                CREATE TABLE `'.$first.'_planting_record` (
                                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  `user` char(11) NOT NULL,
                                  `seed_type` char(255) NOT NULL COMMENT \'种子类型\',
                                  `time` int(10) unsigned NOT NULL,
                                  `seed_img_name` char(255) NOT NULL,
                                  `harvest_time` int(10) NOT NULL,
                                  `seed_state` int(10) unsigned NOT NULL,
                                  `disasters_state` char(255) NOT NULL,
                                  `disasters_time` int(10) NOT NULL,
                                  `disasters_value` int(10) unsigned NOT NULL,
                                  `housekeeper` char(255) NOT NULL COMMENT \'..\',
                                  `harvest_num` int(10) unsigned NOT NULL,
                                  `harvest_state` int(10) unsigned NOT NULL,
                                  `number` int(11) unsigned NOT NULL,
                                  PRIMARY KEY (`id`)                             
                                ) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;
                        
                                DROP TABLE IF EXISTS `'.$first.'_prop_warehouse`;
                                CREATE TABLE `'.$first.'_prop_warehouse` (
                                 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  `user` char(11) NOT NULL,
                                  `props` char(255) NOT NULL COMMENT \'道具\',
                                  `prop_id` int(10) NOT NULL,
                                  `num` int(10) unsigned NOT NULL,
                                  PRIMARY KEY (`id`)
                                 
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                          
                                DROP TABLE IF EXISTS `'.$first.'_member_record`;
                            CREATE TABLE `'.$first.'_member_record` (
                              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                              `top_record` int(11) unsigned NOT NULL,
                              `deposit_record` int(11) unsigned NOT NULL,
                              `order_number` int(11) unsigned NOT NULL,
                              `user` char(11) NOT NULL,
                              `income` float(10,4) NOT NULL,
                              PRIMARY KEY (`id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

                          
                                  DROP TABLE IF EXISTS `'.$first.'_seed_warehouse`;
                                    CREATE TABLE `'.$first.'_seed_warehouse` (
                                     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `user` char(11) NOT NULL,
                                      `seeds` char(255) NOT NULL,
                                      `num` int(10) unsigned NOT NULL,
                                      PRIMARY KEY (`id`)
                                     
                                    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                            
                                   DROP TABLE IF EXISTS `'.$first.'_share_out_bonus`;
                                    CREATE TABLE `'.$first.'_share_out_bonus` (
                                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `user` char(11) NOT NULL,
                                      `money` int(11) NOT NULL,
                                      `time` int(10) unsigned NOT NULL,
                                      PRIMARY KEY (`id`)
                                    
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                            
            ');
            $statistical=M('Statistical');
            $data['name']=$first;
            $statistical->add($data);
        }
        
    }

}

?>