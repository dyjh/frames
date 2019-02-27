<?php
namespace Think;
use Think\Tool;

class Chest{

        public function chest($name,$user,$double){
            $data=M('treasure_chest')->where('name="'.$name.'"')->find();
            $table=new Tool();
            $case='seed_warehouse';
            //$tel=session('user');
            $tel=$user;
            //$user = session('user');
            $case_seed=$table->table($tel,$case);
            $seed=$data['seed'];
            if($double>1){
                $num=($double-1)*$data['seed_num'];
				$data_buy['get_seed']=$seed;
				$data_buy['get_seed_num']=$num;
				$data_buy['time']=1;
				$data_buy['type']='b';
				M('record_treasure')->add($data_buy);
            }else{
                $num=0;
            }
            $data_seed=M($case_seed)->where('user="'.$user.'" AND seed ="'.$seed.'"')->find();
            $num_seed=$data_seed['num'];
            if($num_seed>$num){
                if(M($case_seed)->where('user="'.$user.'" AND seed ="'.$seed.'"')->setDec('num',$num)){
                    $case='treasure_warehouse';
                    //$tel=session('user');
                    $tel=$user;
                    //$user = session('user');
                    $case_t=$table->table($tel,$case);
                    $data_chest=M("$case_t")->where('user="'.$user.'" AND name="'.$name.'"')->find();
                    if($data_chest['num']>0){       //判断是否有宝箱
                        if(M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->setDec('num',1)){   //删除一个宝箱
                            if($data['number']>0){            //判断该宝箱可中奖人数还有多少
                                $max=$data['chance']*100;
                                $k=rand(1,100);
                                if($k<=$max){     //判定中奖
                                    //$num=$num*$data['multiple'];
                                    $num=$data['seed_num']*$double;
                                    $table=new Tool();
                                    $case='seed_warehouse';
                                    $tel=$user;
                                    $case_s=$table->table($tel,$case);
                                    $c_seed=M('Seeds')->count();
                                    /*$data_seed=M('Seeds')->select();
                                    $i=rand(0,$c_seed-1);
                                    $seed=$data_seed[$i]['varieties'];*/
                                    $shop = M('shop');
                                    $shop_message = $shop->field('buy')->where('name="'.$name.'"')->find();
                                    $seed = $shop_message['buy'];
                                    $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$seed.'"')->find();
                                    if(empty($data_seed)){
                                        $data_se['seeds']=$seed;
                                        $data_se['num']=$num;
                                        $data_se['user']=$user;
                                        if(M(''.$case_s.'')->add($data_se)){
                                            $data_record['get_seed']=$seed;
                                            $data_record['get_seed_num']=$num;
                                            $data_record['time']=1;
                                            $data_record['type']='k';
                                            $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$seed.'"')->find();
                                            if(empty($data_re)){
                                                M('record_treasure')->add($data_record);
                                                if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo 0;
                                                }
                                            }else{
                                                M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$num);
                                                if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo -1;
                                                }
                                            }
                                        }else{
                                            echo -2;
                                        }
                                    }else{
                                        if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$seed.'"')->setInc('num',$num)){
                                            $data_record['get_seed']=$seed;
                                            $data_record['get_seed_num']=$num;
                                            $data_record['time']=1;
                                            $data_record['type']='k';
                                            $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$seed.'"')->find();
                                            if(empty($data_re)){
                                                M('record_treasure')->add($data_record);
                                                if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo -3;
                                                }
                                            }else{
                                                M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$num);
                                                if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo -4;
                                                }
                                            }
                                        }else{
                                            echo -5;
                                        }
                                    }
                                }else{
                                    //判定未中奖赠送果实

                                    $table=new Tool();
                                    $case='seed_warehouse';
                                    $tel=$user;
                                    $case_s=$table->table($tel,$case);
                                    $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->find();
                                    if(empty($data_seed)){
                                        $data_se['num']=$data['gift_num'];
                                        $data_se['seeds']=$data['gift'];
                                        $data_se['user']=$user;
                                        if(M(''.$case_s.'')->add($data_se)){
                                            $data_record['get_seed']=$data['gift'];
                                            $data_record['get_seed_num']=$data['gift_num'];
                                            $data_record['time']=1;
                                            $data_record['type']='k';
                                            $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                                            if(empty($data_re)){
                                                if(M('record_treasure')->add($data_record)){
                                                    $data=json_encode($data_re);
                                                    echo $data;
                                                }else{
                                                    echo -7;
                                                }
                                            }else{
                                                if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                                    echo $data_se;
                                                }else{
                                                    echo -8;
                                                }
                                            }
                                        }else{
                                            echo -9;
                                        }
                                    }else{
                                        if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->setInc('num',$data_se['num'])){
                                            $data_record['get_seed']=$data['gift'];
                                            $data_record['get_seed_num']=$data['gift_num'];
                                            $data_record['time']=1;
                                            $data_record['type']='k';
                                            $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                                            if(empty($data_re)){
                                                if(M('record_treasure')->add($data_record)){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo -10;
                                                }
                                            }else{
                                                if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                                    $data=json_encode($data_record);
                                                    echo $data;
                                                }else{
                                                    echo -11;
                                                }
                                            }
                                        }else{
                                            echo -12;
                                        }
                                    }
                                }
                            }else{
                                //当日剩余中奖人数为零
                                $data_se['seeds']=$data['gift'];
                                $data_se['num']=$data['gift_num'];
                                $table=new Tool();
                                $case='seed_warehouse';
                                $tel=$user;
                                $case_s=$table->table($tel,$case);
                                $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->find();
                                //echo 1;die;
                                //print_r($data_seed);
                                if(empty($data_seed)){

                                    $data_se['num']=$data['gift_num'];
                                    $data_se['user']=$user;
                                    if(M(''.$case_s.'')->add($data_se)){
                                        $data_record['get_seed']=$data['gift'];
                                        $data_record['get_seed_num']=$data['gift_num'];
                                        $data_record['time']=1;
                                        $data_record['type']='k';
                                        $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                                        if(empty($data_re)){
                                            if(M('record_treasure')->add($data_record)){
                                                $data=json_encode($data_record);
                                                echo $data;
                                            }else{
                                                echo -14;
                                            }
                                        }else{
                                            if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                                $data=json_encode($data_record);
                                                echo $data;
                                            }else{
                                                echo -15;
                                            }
                                        }
                                    }else{
                                        echo -16;
                                    }
                                }else{



                                    if(M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->setInc('num',$data_se['num'])){
                                        $data_record['get_seed']=$data['gift'];
                                        $data_record['get_seed_num']=$data['gift_num'];
                                        $data_record['time']=1;
                                        $data_record['type']='k';
                                        $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                                        if(empty($data_re)){
                                            if(M('record_treasure')->add($data_record)){
                                                $data=json_encode($data_se);
                                                echo $data;
                                            }else{
                                                echo -17;
                                            }
                                        }else{

                                            if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                                $data=json_encode($data_se);
                                                echo $data;
                                            }else{
                                                echo -18;
                                            }
                                        }
                                    }else{
                                        echo -19;
                                    }
                                }
                            }
                        }else{
                            echo -21;
                        }
                    }elseif($data_chest['num']==0){
                        M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->delete();
                        echo 2;       //删除记录
                    }
                }else{
                    return 12;
                }
            }else{
                return 11;
            }
      }
}
