<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\PlantRModel;
use Org\Our\Admin;
use Think\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PlantController extends AdminController{

    /**
     *   异步修改用户当前土地的预计收获果实
     * @param  $material_id  配置ID
     * @param  $object       配置信息
     * @return $data         返回消息
     */
    public function update_ajax($material_id,$object,$type="update"){

        $object = I("post.object") ;

        $tel = substr_tel($object['tel']);

        $updata_arr = array(
            "harvest_num"    =>   $object['harvest_num'],

        );
        $res = M($tel."_planting_record")->where('id='.$object['id'])->filter('strip_tags')->save($updata_arr);

        if($res == 1){
            if($type=="update"){
                $data['status']  = 0;
                $data['content'] = 'OK';
            }elseif($type=="insert"){
                $data['status']  = -1;
                $data['content'] = 'OK';
            }

        }elseif($res == 0){
            $data['status']  = 0;
            $data['content'] = 'global_conf not update';
        }else{
//            $this->ajaxReturn(M($this->table)->getLastSql(),'json');
            $data['status']  = 40033;
            $data['content'] = 'update global_conf error';
        }

        $this->ajaxReturn($data,'json');

    }

    /**
     *  用户种植信息 查看
     */
    public function plant_record(){
        //  2017-5-24  模拟传入的是 用户的 ID 及 电话号码前三位。
        //$id=I('get.id','','');
        //$tel=I('get.user','','');

        //$member_id      = $id;

        $member_phone   = substr(I('get.user'),0,3);
        //  查询用户的等级   有多少块地
       // print_r($member_phone);die;
        $id = I('get.id');
        $user_info = M("{$member_phone}_members")->where('id='.$id)->filter('strip_tags')->field("level,user,tel,id")->find();

        // 用户不存在
        if(!$user_info){
            $error = '用户不存在！';
            $this->error($error,"");
        }

        $PlantR = new PlantRModel($member_phone."_planting_record");

        $all_land_info =  $PlantR->one_user_planting_record($user_info);

        $this->assign("all_land_info",$all_land_info);

        $this->assign("user_info",$user_info);

        $this->display();
    }

    public function plant_harvest(){
        //$id=I('get.id','','');
        //$tel=I('get.user','','');
        //print_r($_GET);die;
        //  2017-5-24  模拟传入的是 用户的 ID 及 电话号码前三位。
        //$member_id      = $id;
        $member_phone   = substr(I('get.user'),0,3);
        //  查询用户的等级   有多少块地
        $id = I('get.id');
        $user_info = M("{$member_phone}_members")->where("id=".$id)->filter('strip_tags')->field("level,user,tel,id")->find();

        // 用户不存在
        if(!$user_info){
            $error = '用户不存在！';
            $this->error($error,"");
        }

        $PlantR = new PlantRModel($member_phone."_planting_record");

        $this->assign("seed_list_info",$PlantR->users_planting_harvest("",$user_info));

        $this->assign("user_info",$user_info);

        $this->display();
    }

    public function all_plant_harvest(){
        $all_member_phone = M("statistical")->where('name>0')->filter('strip_tags')->select();

        $stime=microtime(true); #获取程序开始执行的时间

        #你写的php代码

        $all_seed_list_info = array();
        foreach($all_member_phone as $member_phone){
            $PlantR = new PlantRModel($member_phone['name'] . "_planting_record");

            $seed_list_info = $PlantR->users_planting_harvest();

            foreach ($seed_list_info as $key => $val) {
                $all_seed_list_info[$key]['get_harvest_num'] += $val['get_harvest_num'];
                $all_seed_list_info[$key]['get_disasters_value'] += $val['get_disasters_value'];
                $all_seed_list_info[$key]['plan_harvest_num'] += $val['plan_harvest_num'];
                $all_seed_list_info[$key]['plan_disasters_value'] += $val['plan_disasters_value'];
            }
        }



//  print_r($all_member_phone);
        $this->assign("seed_list_info",$all_seed_list_info);
        $etime=microtime(true); #获取程序执行结束的时间
        $total=$etime-$stime;   #计算差值
        //echo "<br />{$total} times";
        $this->display();
    }

}