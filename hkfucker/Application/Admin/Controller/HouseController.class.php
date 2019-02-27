<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
use Org\Our\House;
class HouseController extends Controller {
    public function index(){
        //  查询所有房屋等级
        $all_house = M("house")->field("id,name,cost,level")->order("level asc ")->select();

        //查询所有材料
        $all_seed = M("house_material")->select();

        foreach ($all_house as $key=>$val){
            $house = array();
            $house = unserialize($val['cost']);
            foreach ( $house as $k=>$v){
                $all_house[$key][$v['seed_id']] = $v['seed_value'];
            }
        }

        $this->assign("all_seed",$all_seed);
        $this->assign("all_material",$all_house);
        $this->display();
    }

    public function update_ajax($material_id,$object){

        $level = $object['level'];
        unset($object['level']);
        $name = $object['name'];
        unset($object['name']);

        foreach ($object as $key=>$val){
            // 取消 验证 $key  是否为数字型

//            if( !$val || ! is_numeric($val) ){
//                $data['status']  = 50002;
//                $data['content'] = 'Invalid cost value';
//                Log::record('50002 ，Invalid cost value','WARN',TRUE);
//                $this->ajaxReturn($data,'json');
//                die;
//            }
            if($val){
                $objects[$key]['seed_id']=$key;
                $objects[$key]['seed_value']=(int)$val;
            }

        }

        //  判断  房屋等级 或 名称是否重复
        $map['name'] = $name;
        $map['level'] = $level;
        $map['_logic'] = 'or';
        $where['_complex'] = $map;
        $where['id'] = array("NEQ",$material_id);

        $is_error = M("house")->where($where)->fetchSql(false)->find();

        if($is_error){
            $data['status']  = 50001;
            $data['content'] = 'house (name or level ) exist ';
//            qhp_log_save('50001 ，house (name or level ) exist','WARN',"House",TRUE);
            $this->ajaxReturn($data,'json');
            die;
        }

        $updata_arr['cost'] = serialize($objects);
        if($level){
            $updata_arr['level'] = $level;
        }
        if($name){
            $updata_arr['name'] = $name;
        }

        $res = M("house")->where("id={$material_id}")->save($updata_arr);

        if($res == 1){
            $data['status']  = 0;
            $data['content'] = 'OK';
//            qhp_log_save('0 ，OK ','success',"House",TRUE);
            $this->ajaxReturn($data,'json');
            die;
        }
    }

    public function add_house($material_id,$object){

        if( $object['name']){
            $map['name'] = $object['name'];
            unset($object['name']);
        }else{
            $data['status']  = 50010;
            $data['content'] = 'Invalid house name';
            $this->ajaxReturn($data,'json');
            die;
        }

        if( $object['level'] && is_numeric($object['level'])){
            $map['level'] = $object['level'];
            unset($object['level']);
        }else{
            $data['status']  = 50011;
            $data['content'] = 'Invalid house level';
            $this->ajaxReturn($data,'json');
            die;
        }

        $all_house = M("house")->where($map)->find();
        

        if($all_house){
            if($all_house['cost'] || $all_house['price']){
                $data['status']  = 50009;
                $data['content'] = 'meterial name exists';
                $this->ajaxReturn($data,'json');
                die;
            }else{
                $this->update_ajax($all_house['id'],$object);
            }
        }else{
            $insert_id = M("house")->add($map);
            if($insert_id > 0 ){
                $this->update_ajax($insert_id,$object);
            }
        }

    }

    /**
     *   用户 升级房屋
     * @param  $member_id      会员ID
     * @param  $member_phone  会员电话 前三位
     * @return $data          返回消息
     *   支持 外部函数调用 或请求URL  优先URL
     *
     *    测试时  设置默认值
     *    后期 应删除默认值
     *     TODO:所有的返回代码使用的是error  或success 函数 返回。有跳转的动作。
     */
    public function exchange($member_id='1',$member_phone="136"){

        $member_id    = I("param.member_id")     ?  I("param.member_id")    :  $member_id;
        $member_phone = I("param.member_phone") ?  I("param.member_phone") :  $member_phone;

        if(!$member_id || ! is_numeric($member_id)){
//            qhp_log_save('50003 \n Invalid member ID','WARN',"House",TRUE);
            $this->error("50003 \n Invalid member ID");
            die;
        }


        //  查询 会员是否存在
        //   可注释
        $member_info = M("{$member_phone}_members")->where("id={$member_id}")->fetchSql(false)->field("diamond,level")->find();
        if(! $member_info){
//            qhp_log_save('40006 ，member is not exis','WARN',"House",TRUE);
            $this->error("40006 \n member is not exist");
            die;
        }

        //  查询该会员 要兑换的材料所需的成本
         $need_material =  M("house")->where("level=".($member_info['level']+1))->find();

         $need_material = material_handle_one($need_material);

        // 检查用户的材料是否足够
            foreach ($need_material as $key=>$val){
                if(! is_numeric($key) && $key != "price"){
                    unset($need_material[$key]);
                }else{
                    if( is_numeric($key) ){
                        // 查询用户仓库中的对应成本是否足够
                        $join = " left JOIN ".$member_phone."_prop_warehouse as prop_ware on  house_material.id = prop_ware.props and prop_ware.user={$member_id} ";         // 联立查询成本对应名称
                        $field = " prop_ware.num , house_material.name ";
                        $member_has_warehouse[$key] = M("house_material")->field($field)->join($join)->where(" house_material.id=".$key)->fetchSql(false)->find();
                        if( $member_has_warehouse[$key]['num'] < $val){
                            $this->error('您的材料'.$member_has_warehouse[$key]['name']."不足，当前拥有".(int)$member_has_warehouse[$key]['num']."个，需要".$val."个。");
                            die;
                        }
                    }elseif($key == "price"){
                        if( $member_info['diamond'] < $val){
                            $this->error("您的钻石不足，当前拥有".$member_info['diamond']."个，需要".$val."个。");
                            die;
                        }
                    }
                }
            }

            M()->startTrans();
            //   进行兑换
        foreach ($need_material as $key=>$val){
            //  用户仓库中 减少对应成本的数量
            if( is_numeric($key) ){
                $map = array();
                $map['user']   = $member_id;
                $map['props']  = $key;
                $res_seed = M("{$member_phone}_prop_warehouse")->where($map)->fetchSql(false)->setDec("num",(int)$val);

                if($res_seed != "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
//                    qhp_log_save('40007 ，prop_warehouse update error','WARN',"House",TRUE);
                    $this->error("40007 \n prop_warehouse update error");
                    die;
                }
            }elseif($key == "price"){
                // 修改用户的宝石
                $map = array();
                $map['id']          = $member_id;
                $res_seed = M("{$member_phone}_members")->where($map)->fetchSql(false)->setDec("diamond",(int)$val);
                if($res_seed != "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
//                    qhp_log_save('40009 ，members diamond update error','WARN',"House",TRUE);
                    $this->error("40009 \n members diamond update error");
                    die;
                }
            }
        }

        if($res_seed == 1){
            // 减少成功后  升级用户房屋等级
            $member_map = array();
            $member_map['id']          = $member_id;
            $res_prop = M("{$member_phone}_members")->where($map)->fetchSql(false)->setInc("level",1);

            if(is_numeric($res_prop) && $res_prop > 0){
                M()->commit();
                echo "OK";
//                qhp_log_save("0 ，members' house level  has upgraded to :".($member_info['level']+1),'INFO',"House",TRUE);
                die;
            }else{
                M()->rollback();
//                qhp_log_save('40008 \n members house level update error','WARN',"House",TRUE);
                $this->error("40008 \n members house level update error");
                die;
            }
        }

        $this->display();
    }

}