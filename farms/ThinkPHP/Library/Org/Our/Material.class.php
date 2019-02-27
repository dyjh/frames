<?php
namespace Org\Our;
use Think\Model;
use Org\Our\Record;

/**
 * Class Material  材料类
 * @package Org\Our
 * return @$data['status']
 *               40001  Invalid cost ID     传入的果实ID  无效
 *               40002  Invalid cost value  传入的果实消耗值无效
 *
 * 用户兑换材料
 *               40003  Invalid member ID    兑换时，传入的用户ID 无效
 *               40004  Invalid material ID  兑换时，传入的材料ID 无效
 *               40005  Invalid material num 需要兑换的材料数量不正确
 *               40009  Error change type    兑换材料的方式不合法
 *               40006  member is not exist  请求兑换材料用户不存在
 *               40020  兑换材料的果实不足
 *               40007  seed_warehouse update error   种子仓库减少果实数量失败
 *               40021  金币不足
 *               40010  member coin update error   用户金币数据减少失败
 *               40008  meterial_warehouse update error    材料仓库增加材料数量失败*
 *
 * 后台增加材料
 *               40011  Invalid meterial name   无效的材料名称
 *               40012  meterial name exists    该材料已存在
 *               40013  insert meterial error   新增材料失败
 */

class Material {

    protected $cache = "0";//   缓存时间

    protected $table = "house_material";

    /**
     *   异步修改 兑换材料 所需的成本价格
     * @param  $material_id  材料ID
     * @param  $object       成本价格数组
     * @param  $type         类型
     * @return $data         返回消息
     */
    public function update_ajax($material_id,$object,$type="update"){

        $price = $object['price'];
        unset($object['price']);

        foreach ($object as $key=>$val){

            if(!$key  || !is_numeric($key) ){
                $data['status']  = 40001;
                return $data;
            }

            if( $val ){
                if(! is_numeric($val)){
                    $data['status']  = 40002;
                    $data['content'] = 'Invalid cost value';
                    return $data;
                }
            }else{
                unset($object[$key]);
            }

            $objects[$key]['seed_id']=$key;
            $objects[$key]['seed_value']=$val;
        }

        $updata_arr = array(
            "cost"    => serialize($objects),
            "price"   => $price,
        );

        $res = M($this->table)->where("id={$material_id}")->save($updata_arr);

        if($res == 1){
            if($type=="update"){
                $data['status']  = 0;
                $data['content'] = 'OK';
            }elseif($type=="insert"){
                $data['status']  = -1;
                $data['content'] = 'OK';
            }

            return $data;
        }
    }

    /**
     *   用户 兑换材料
     * @param  $member_id      会员ID
     * @param  $material_id    材料ID
     * @param  $material_num   兑换的材料数量
     * @param  $member_phone   会员电话 前三位
     * @param  $change_type    会员兑换的方式  coin 金币   material 材料  默认为材料
     * @return $data          返回消息
     *
     */
    public function exchange($material_id,$material_num=1,$change_type="material"){

        $table_fix = substr(session('user'),0,3);
		$material_num = (int)$material_num;
			
        if(!is_numeric($material_id) || $material_id < 0  ){
            $data['state'] = 60001;
            $data['content'] = '材料不存在';
            echo json_encode($data);
            exit;
        }

        if(!$material_num || ! is_numeric($material_num) || $material_num < 1){
            $data['state']  = 60002;
            $data['content'] = '数量格式有误';
            echo json_encode($data);
            exit;
        }

        if($change_type!="material" && $change_type!="coin"){
            $data['state']  = 60003;
            $data['content'] = '只能用果实或金币进行兑换';
            echo json_encode($data);
            exit;
        }

        //查询该会员 要兑换的材料所需的成本
        $need_material =  M($this->table)->where("id={$material_id}")->find();

        if($change_type == "material"){
            // 使用成本 就行兑换
            $need_material = material_handle_one($need_material);

            foreach ($need_material as $key=>$val){
                if(!is_numeric($key)){

                }else{
                    // 查询用户仓库中的对应成本是否足够
                    $join = " left JOIN ".$table_fix."_seed_warehouse as seed_ware on  seeds.varieties = seed_ware.seeds and seed_ware.user='".$_SESSION['user']."'";         // 联立查询成本对应名称
                    $field = " seed_ware.num , seeds.varieties ";

                    $member_has_warehouse[$key] = M("seeds")->field($field)->join($join)->where("seeds.id=".$key)->find();

                    if($member_has_warehouse[$key]['num'] < $val*$material_num){
                        $data['state']  = 60004;
                        $data['content'] = $member_has_warehouse[$key]['varieties'];
                        echo json_encode($data);
                        exit;
                    }
                }
            }
            M()->startTrans();
            //   进行兑换
            foreach ($member_has_warehouse as $key=>$val){
                //  用户仓库中 减少对应成本的数量
                $map = array();
                $map['user']   = session('user');
                $map['seeds']  = $val['varieties'];
                $res_seed = M("{$table_fix}_seed_warehouse")->where($map)->setDec("num",(int)$need_material[$key]*$material_num);
                if($res_seed!= "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
                    $data['state']  = 60005;
                    $data['content'] = '兑换失败，果实仓库修改出错';
                    echo json_encode($data);
                    exit;
                }
            }

        }elseif($change_type=="coin"){
            // 使用金币 就行兑换
            $need_coin = $need_material['price']*$material_num;

            if($_POST['coin']<$need_coin){
                $data['state']  = 60006;
                $data['content'] = '金币不足，目前拥有'.$_POST['coin'].'个，需要'.$need_coin.'个';
                echo json_encode($data);
                exit;
            }

            M()->startTrans();
            $map['user']  = session('user');
            // 减少用户金币
            $res_seed = M("{$table_fix}_members")->where($map)->setDec("coin",(int)$need_coin);
            if($res_seed!= "1"){
                //  修改不成功 抛出异常
                M()->rollback();
                $data['state']  = 60007;
                $data['content'] = '兑换失败，金币修改出错';
                echo json_encode($data);
                exit;
            }
        }


        if($res_seed==1){
            // 减少成功后  增加用户对应材料
            $map_prop['id'] = M("{$table_fix}_meterial_warehouse")->where("user='".$_SESSION['user']."' and props={$material_id}")->getField("id");

            $map_prop['user'] = $data_prop['user'] = $_SESSION['user'];
            $map_prop['props'] = $data_prop['props'] = $material_id;
            $data_prop['prop_name'] = $need_material['name'];
            $data_prop['num'] = array("exp","num + " . $material_num );
            if($map_prop['id']){
                $res_prop = M("{$table_fix}_meterial_warehouse")->where($map_prop)->save($data_prop);
            }else{
                $data_prop['num'] = $material_num;
                $res_prop = M("{$table_fix}_meterial_warehouse")->add($data_prop,"",true);
            }
            if(is_numeric($res_prop) && $res_prop>0){
                 $array['user'] = $_SESSION['user'];
                 $array['name'] = $need_material['name'];
                 $array['num'] = $material_num;
                 $array['type'] = 'm';
                 $record = new record();
                 if($record->Record_Conversion($array)){
                     M()->commit();
                     $data['state']  = 60009;
                     $data['content'] = "兑换成功";
                     echo json_encode($data);
                     exit;
                 }else{
                     M()->rollback();
                     $data['state']  = 60010;
                     $data['content'] = '兑换记录修改失败';
                     echo json_encode($data);
                     exit;
                 }
            }else{
                M()->rollback();
                $data['state']  = 60008;
                $data['content'] = '兑换失败，材料增加出错';
                echo json_encode($data);
                exit;
            }
        }
    }

    /**
     *  增加 材料的种类
     *
     */
    public function add_meterial($object){

        if( $object['name']){
            $map['name'] = $object['name'];
            unset($object['name']);
        }else{
            $data['status']  = 40011;
            return $data;
        }
        $all_material = M($this->table)->where($map)->find();
        if($all_material){
            if($all_material['cost'] || $all_material['price']){
                $data['status']  = 40012;
                return $data;
            }else{
                return $this->update_ajax($all_material['id'],$object,"insert");
            }
        }else{
            $insert_id = M($this->table)->add($map);
            if($insert_id > 0 ){
                return $this->update_ajax($insert_id,$object,"insert");
            }else{
                $data['status']  = 40013;
                return $data;
            }
        }
    }
}
