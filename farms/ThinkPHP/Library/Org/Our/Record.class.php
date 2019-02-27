<?php
namespace Org\Our;
use Think\Model;

class Record{

    //宝石兑换记录
    public function Record_Conversion($data){
        $data['buy_time'] = time();
        $table_fix = substr(session('user'),0,3);
        $record = $table_fix."_record_conversion";
        if(M("$record")->add($data)){
			//$member = $table_fix."_member_record";支出记录
			//$mid = M("$member")->where('user="'.session('user').'"')->find();
			//M("$member")->where('id="'.$mid.'"')->setDec('income',$data['coin']);
             return true;
        }else{
             return false;
        }
    }

    //道具购买记录
    public function Record_Shop($data){

        $data['buy_time'] = time();
        $table_fix = substr(session('user'),0,3);
        $record = $table_fix."_record_shop";
        $res = M("$record")->add($data);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}
