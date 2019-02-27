<?php

namespace Org\Our;
use Think\Autoadd;
use Think\Model;
//用户操作类
class Admin{
    public function Set_Level($data){
        $date['user']=array("LIKE",'%'.$data['start_user'].'%');
        $sta = M('statistical')->field('name')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_members';
                $sqllist = M($sqlname)->order('level DESC')->where($date)->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }

    public function Set_Prop($data){
        $date['user']=array("LIKE",'%'.$data['start_user'].'%');
        $sta = M('statistical')->field('name')->select();
        $List = array();
        foreach ($sta as $key=>$val){
            foreach ($val as $k=>$v){
                $sqlname = ''.$v.'_prop_warehouse';
                $sqllist = M($sqlname)->where($date)->select();
                foreach ($sqllist as $a=>$b){
                    $List[] = $b;
                }
            }
        }
        return $List;
    }

    public function Set_Prop_Edit($data){
        $sqluser = substr($data['user'], 0, 3);
        $sqlname = ''.$sqluser.'_prop_warehouse';
        $sqllist = M($sqlname)->where($data)->select();
        return $sqllist;
    }

    public function Page($data){
        
    }
}
