<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 11:33
 */

namespace Think;
use Think\Tool;
use Think\Model;
class Package
{
    public function get($user){
        $model=new Model();
        $model->startTrans();
        $table=new Tool();
        $case='prop_warehouse';
        $tel=$user;
        $case_p=$table->table($tel,$case);
        $data_0=M('Package')->where('type=0')->select();
        foreach ($data_0 as $k=>$v){
            $data_prop=M(''.$case_p.'')->where('props="'.$v['name'].'" AND user='.$user)->find();
            if(empty($data_prop)){
                $data['props']=$v['name'];
                $data['num']=$v['num'];
                $data_b=M('butler_service')->where('type="'.$v['name'].'"')->find();
                $data['user']=$user;
                if(empty($data_b)){
                    $data['prop_id']=0;
                }else{
                    $data['prop_id']=$data_b['b_id'];
                }
                if(M(''.$case_p.'')->add($data)){

                }else{
                    return 0;
                }
            }else{
                if(M(''.$case_p.'')->where('props="'.$v['name'].'" AND user='.$user)->setInc('num',$v['num'])){

                }else{
                    return -1;
                }
            }
        }
        $data_1=M('Package')->where('type=1')->find();
        $table=new Tool();
        $case='members';
        $tel=$user;
        $case_m=$table->table($tel,$case);
        if(M(''.$case_m.'')->where('user='.$user)->setInc('diamond',$data_1['num'])){
            $data_m['gift_state']=1;
            if(M(''.$case_m.'')->where('user='.$user)->save($data_m) !==false){
                return 1;
            }else{
                return -2;
            }
        }
    }
}