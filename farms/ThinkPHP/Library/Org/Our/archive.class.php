<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/6
 * Time: 10:54
 */

namespace Org\Our;
use Think\Model;

class archive
{


    /**
     *判断是否为电话号码
     **/
    function is_mobile($str){
        if (strlen ( $str ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $str )) {
            return false;
        } else {
            return true;
        }
    }
    /**
     *找到该用户所属的团队(找直接推荐人)
     **/
    public function play($tel,$arr=array()){

        $po = substr($tel,0,3);
        $membe = M(''.$po.'_members');
        $ksql = $membe->field('referees')->where('user='.$tel.'')->find();

        if($ksql['referees']!=='' && $this->is_mobile($ksql['referees'])){
            //return $ksql;
            $arr[] = $ksql['referees'];
            return $this->play($ksql['referees'],$arr);
        }

        return $arr;

    }

    /**
     *找到该用户所属的团队(找team)
     **/
    public function team($tel,$arr=array()){

        $po = substr($tel,0,3);
        $membe = M(''.$po.'_members');
        $ksql = $membe->field('team')->where('user='.$tel.'')->find();
        $list = array_reverse(array_unique(explode(' ',$ksql['team'])));
        //return $list;
        $i = 0;
        foreach ($list as $key=>$item){
            if($list[$key]!='' && $this->is_mobile($list[$key])){
                $arr[$i]  = $list[$key];
                $i++;
            }

        }
        return $arr;

    }
    /**
     *存入team_record记录
     **/
    public function store($tel,$type,$num){

        $arr = $this->team($tel);
        //return $arr;
        $success = 0;
        $error = 0;


        $arr_count = count($arr);

        for($i=0;$i<$arr_count;$i++){

            $pr = substr($arr[$i],0,3);
            $team_record = M(''.$pr.'_team_record');
            $team_record->startTrans();
            $cond['user'] = $arr[$i];
            $cond['fromuser'] = $tel;
            $cond['pay_money'] = $num;
            $cond['type'] = $type;
            $cond['pay_time'] = time();
            if($team_record->add($cond)!==false){
                $team_record->commit();
                $success +=1;
            }else{
                $team_record->rollback();
                $error +=1;
            }
        }
        return $success.'/'.$error;
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
}