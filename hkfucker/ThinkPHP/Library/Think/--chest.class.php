<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/24
 * Time: 17:28
 */

namespace Think;


class chest
{
    public function chest($user,/*$seed,$num,*/$name){
        $data=M('treasure_chest')->where('name="'.$name.'"')->find();
        $table=new Tool();
        $case='treasure_warehouse';
        $tel=$user;
        $case_t=$table->table($tel,$case);
        $data_chest=M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->find();
        if($data_chest['num']>0){       //判断是否有宝箱
            if(M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->setDec('num',1)){   //删除一个宝箱
                if($data['number']>0){            //判断该宝箱可中奖人数还有多少
                    $max=$data['chance']*100;
                    $k=rand(1,100);
                    if($k<=$max){     //判定中奖
                        //$num=$num*$data['multiple'];
                        $num=$data['seed_num']*$data['multiple'];
                        $table=new Tool();
                        $case='seed_warehouse';
                        $tel=$user;
                        $case_s=$table->table($tel,$case);
                        $c_seed=M('Seeds')->count();
                        $data_seed=M('Seeds')->select();
                        $i=rand(0,$c_seed-1);
                        $seed=$data_seed[$i]['varieties'];
                        $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$seed.'"')->find();
                        if(empty($data_seed)){
                            $data_se['seeds']=$seed;
                            $data_se['num']=$num;
                            $data['user']=$user;
                            if(M(''.$case_s.'')->add($data_se)){
                                $data_record['get_seed']=$seed;
                                $data_record['get_seed_num']=$num;
                                $data_record['time']=1;
                                $data_record['type']='k';
                                $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$seed.'"')->find();
                                if(empty($data_re)){
                                    M('record_treasure')->add($data_record);
                                    if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                        return $data_record;
                                    }else{
                                        return 0;
                                    }
                                }else{
                                    M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$num);
                                    if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                        return $data_record;
                                    }else{
                                        return -1;
                                    }
                                }
                            }else{
                                return -2;
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
                                        return $data_record;
                                    }else{
                                        return -3;
                                    }
                                }else{
                                    M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$num);
                                    if(M('treasure_chest')->where('name="'.$name.'"')->setDec('number',1)){
                                        return $data_record;
                                    }else{
                                        return -4;
                                    }
                                }
                            }else{
                                return -5;
                            }
                        }
                    }else{
                        //判定未中奖赠送果实
                        $data_se['seeds']=$data['gift'];
                        $data_se['num']=$data['gift_num'];
                        $table=new Tool();
                        $case='seed_warehouse';
                        $tel=$user;
                        $case_s=$table->table($tel,$case);
                        $data_seed=M(''.$case_s.'')->where('user="'.$user.'" AND seeds = "'.$data_se['seeds'].'"')->find();
                        if(empty($data_seed)){
                            $data_se['num']=$data['gift_num'];
                            $data['user']=$user;
                            if(M(''.$case_s.'')->add($data_se)){
                                $data_record['get_seed']=$data['gift'];
                                $data_record['get_seed_num']=$data['gift_num'];
                                $data_record['time']=1;
                                $data_record['type']='k';
                                $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                                if(empty($data_re)){
                                    if(M('record_treasure')->add($data_record)){
                                        return $data_se;
                                    }else{
                                        return -7;
                                    }
                                }else{
                                    if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                        return $data_se;
                                    }else{
                                        return -8;
                                    }
                                }
                            }else{
                                return -9;
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
                                        return $data_se;
                                    }else{
                                        return -10;
                                    }
                                }else{
                                    if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                        return $data_se;
                                    }else{
                                        return -11;
                                    }
                                }
                            }else{
                                return -12;
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
                    if(empty($data_seed)){
                        $data_se['num']=$data['gift_num'];
                        $data['user']=$user;
                        if(M(''.$case_s.'')->add($data_se)){
                            $data_record['get_seed']=$data['gift'];
                            $data_record['get_seed_num']=$data['gift_num'];
                            $data_record['time']=1;
                            $data_record['type']='k';
                            $data_re=M('record_treasure')->where('time =1 AND type = "'.$data_record['type'].'" AND get_seed="'.$data_record['get_seed'].'"')->find();
                            if(empty($data_re)){
                                if(M('record_treasure')->add($data_record)){
                                    return $data_record;
                                }else{
                                    return -14;
                                }
                            }else{
                                if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                    return $data_record;
                                }else{
                                    return -15;
                                }
                            }
                        }else{
                            return -16;
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
                                    return $data_se;
                                }else{
                                    return -17;
                                }
                            }else{
                                if(M('record_treasure')->where('id="'.$data_re['id'].'"')->setInc('get_seed_num',$data['gift_num'])){
                                    return $data_se;
                                }else{
                                    return -18;
                                }
                            }
                        }else{
                            return -19;
                        }
                    }
                }
              }else{
                return -21;
            }
        }elseif($data_chest['num']==0){
            M($case_t)->where('user="'.$user.'" AND name="'.$name.'"')->delete();
            return 2;       //删除记录
        }
    }
//每天24点调用
    public function reset(){
        $data=M('treasure_chest')->select();
        foreach ($data as $k=>$v){
            $data['number']=$v['number_max'];
            if(M('treasure_chest')->where('id='.$v['id'])->save($data)){

            }else{

            }
        }
        if(M('record_treasure')->where('time=0')->delete()){
            $data_re=M('record_treasure')->where('time=1')->select();
            foreach ($data_re as $k=>$v){
                $data_n['time']=0;
                if(M('treasure_chest')->where('id='.$v['id'])->save($data_n)){

                }else{

                }
            }
        }else{

        }
    }
}