<?php
namespace Think;
use Think\Model;
class Disasters{
    public function disaster_new(){
        $new=M('disaster_time')->where('type=0')->find();
        //echo 1;die;

        if(empty($new)){
        }else{
            $data_plant=array();
            $f=0;
            $statistical=M('Statistical');
            $data_s=$statistical->select();
            foreach ($data_s as $k=> $v){
                $case=''.$v['name'].'_members';
                $member=M($case);
                $cases='disasters_old';
                $data_old=M('global_conf')->where('cases ="'.$cases.'"')->find();
                //$count =$member->field('user')->where('disasters_num < '.$data_old['value'].' AND cost_state=0')->count();
                $data_me=$member->field('user')->where('disasters_num < '.$data_old['value'].' AND cost_state=0')->select();
                foreach ($data_me as $key=>$val){
                    $num=substr($val['user'],0,3);
                    $case_plant=''.$num.'_planting_record';
                    $planting=M(''.$case_plant.'')->where('disasters_state=0 AND harvest_state=0 AND seed_state < 3 AND user ='.$val['user'] )->select();
                    $count=M(''.$case_plant.'')->where('disasters_state=0 AND harvest_state=0 AND seed_state < 3 AND user ='.$val['user'])->count();
                    for($i=0;$i<$count;$i++){
                        $data_plant[$f]=$planting[$i];
                        $f++;
                    }
                }
            }

            $zhi=$new['type'];
            $i=1;
            $k=$new['k'];
            //print_r($k);die;
            $res=$this->Disasters($k,$i,$zhi,$data_plant);
            if($res==1){
                M('disaster_time')->where('type=0')->setDec('times',1);
                $data_new=M('disaster_time')->where('type=0')->find();
                if($data_new['times']==0){
                    M('disaster_time')->where('type=0')->delete();
                }
            }
        }
    }
    public function disaster_old(){
        $new=M('disaster_time')->where('type=1')->find();
        if(empty($new)){
        }else{
            $data_plant=array();
            $f=0;
            $statistical=M('Statistical');
            $data_s=$statistical->select();
            foreach ($data_s as $k=> $v){
                $case=''.$v['name'].'_members';
                $member=M($case);
                $cases='disasters_old';
                $data_old=M('global_conf')->where('cases ="'.$cases.'"')->find();
                //$count =$member->field('user')->where('disasters_num < '.$data_old['value'].' AND cost_state=0')->count();
                $data_me=$member->field('user')->where('disasters_num < '.$data_old['value'].' AND cost_state=1')->select();
                foreach ($data_me as $key=>$val){
                    $num=substr($val['user'],0,3);
                    $case_plant=''.$num.'_planting_record';
                    $planting=M(''.$case_plant.'')->where('disasters_state=0 AND seed_state < 3 AND user ='.$val['user'])->select();
                    $count=M(''.$case_plant.'')->where('disasters_state=0 AND seed_state < 3 AND user ='.$val['user'])->count();
                    for($i=0;$i<$count;$i++){
                        $data_plant[$f]=$planting[$i];
                        $f++;
                    }
                }
            }
            //print_r($data);die;
            $zhi=$new['type'];
            $i=1;
            $k=$new['k'];
            //print_r($k);die;
            $res=$this->Disasters($k,$i,$zhi,$data_plant);
            if($res==1){
                M('disaster_time')->where('type=0')->setDec('times',1);
                $data_new=M('disaster_time')->where('type=0')->find();
                if($data_new['times']==0){
                    M('disaster_time')->where('type=0')->delete();
                }
            }
        }
    }
    public function Disasters($k,$i,$zhi,$data_plant){
        //print_r($data_plant);
        //print_r($i);echo '<br/>';
        if($k<$i){
            echo 'you die';
            return 1;
        }else{
            $count=count($data_plant);
            $number=mt_rand(0,$count-1);
            if($zhi==0){
                $s=mt_rand(0,1);
            }else{
                $s=1;
            }
            if($s==0){
                //echo 1;echo '<br/><br/>';
                unset($data_plant[$number]);
                //重置索引，重新进入循环
                $data_plant = array_values($data_plant);
                $i++;
                return $this->Disasters($k,$i,$zhi,$data_plant);

            }else{

                $user=$data_plant[$number]['user'];
                $num=substr($user,0,3);
                $case=''.$num.'_members';
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
                    $data_seed=M(''.$case_seed.'')->field('user,seeds')->where('user ='.$user)->order('num DESC')->select();
                    $seed=$data_seed[0]['seeds'];
                    if($seed==$data_plant[$number]['seed_type']){
                        $disaster_state=1;
                    }else{
                        $disaster_state=mt_rand(0,2);
                    }
                    if($disaster_state==1){
                        //echo 4;echo '<br/><br/>';
                        $yan=M(''.$case_plant.'')->where('id ='.$data_plant[$number]['id'])->find();
                        if($yan['harvest_state']==0){
                            $data_n['disasters_state']=$dis_rand['d_id'];
                            $data_n['disasters_time']=time();
                            $data=M('Global_conf')->where('cases="disasters_number"')->find();
                            //print_r($plant['id']);die;
                            //
                            M(''.$case_plant.'')->where('id ='.$data_plant[$number]['id'])->setInc('disasters_value',$data['value']);
                            M(''.$case_plant.'')->where('id ='.$data_plant[$number]['id'])->save($data_n);
                            M(''.$case.'')->where('user ='.$user)->setInc('disasters_num',1);
                            //删除数组元素
                            unset($data_plant[$number]);
                            //重置索引，重新进入循环
                            $data_plant = array_values($data_plant);
                            $i++;
                            return $this->Disasters($k,$i,$zhi,$data_plant);
                        }else{
                            //删除数组元素
                            unset($data_plant[$number]);
                            //重置索引，重新进入循环
                            $data_plant = array_values($data_plant);
                            $i++;
                            return $this->Disasters($k,$i,$zhi,$data_plant);
                        }
                    }else{
                        //echo 5;echo '<br/><br/>';
                        //删除数组元素
                        unset($data_plant[$number]);
                        //重置索引，重新进入循环
                        $data_plant = array_values($data_plant);
                        $i++;
                        return $this->Disasters($k,$i,$zhi,$data_plant);
                    }
                }else{
                    //echo 6;echo '<br/><br/>';
                    return $this->Disasters($k,$i,$zhi,$data_plant);
                }
            }
        }
    }

}
?>