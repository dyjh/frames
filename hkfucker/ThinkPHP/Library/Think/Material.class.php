<?php
namespace Think;
use Think\Model;

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
        $name = $object['name'];
        unset($object['name']);
        $object = array_filter($object);
        foreach ($object as $key=>$val){

            if(!$key  || !is_numeric($key) ){
                $data['status']  = 40001;
                $data['content'] = 'Invalid cost ID';
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
            "name"   => $name,
        );
// var_dump(serialize($objects));die;
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
     * @param  $member_phone  会员电话 前三位
     * @param  $change_type   会员兑换的方式  coin 金币   material 材料  默认为材料
     * @return $data          返回消息
     *   支持 外部函数调用 或请求URL  优先URL
     *
     *    测试时  设置默认值
     *    后期 应删除默认值
     *     TODO:所有的返回代码使用的是error  或success 函数 返回。有跳转的动作。
     */
    public function exchange($member_id,$material_id,$member_phone,$material_num,$change_type){

        $member_id    = I("param.member_id")     ?  I("param.member_id")    :  $member_id;
        $material_id  = I("param.material_id")  ?   I("param.material_id") :  $material_id;
        $member_phone = I("param.member_phone") ?  I("param.member_phone") :  $member_phone;
        $material_num = I("param.material_num") ?  I("param.material_num") :  $material_num;
        $change_type  = I("param.change_type")  ?  I("param.change_type")  :  $change_type;

        if(!$member_id || ! is_numeric($member_id)){
            $data['status']  = 40003;
            $data['content'] = 'Invalid member ID ';
            return $data;

        }
        if(!$material_id || ! is_numeric($material_id)){
            $data['status']  = 40004;
            $data['content'] = 'Invalid material ID ';
            return $data;
        }
        if(!$material_num || ! is_numeric($material_num)){
            $data['status']  = 40005;
            $data['content'] = 'Invalid material num ';
            return $data;
        }
        if($change_type != "material" && $change_type != "coin"){
            $data['status']  = 40009;
            $data['content'] = 'Error change type ';
            return $data;
        }

        //  查询 会员是否存在
        //   可注释
        $member_coin = M("{$member_phone}_members")->where("id={$member_id}")->field("coin")->find();
        if(! $member_coin){
            $data['status']  = 40006;
            $data['content'] = 'member is not exist ';
            return $data;
        }
        //  查询 该会员 要兑换的材料所需的成本
        $need_material =  M($this->table)->where("id={$material_id}")->find();


        if($change_type == "material"){
            // 使用成本 就行兑换

            $need_material = material_handle_one($need_material);

            foreach ($need_material as $key=>$val){
                if(! is_numeric($key)){
                    unset($need_material[$key]);
                }else{
                    // 查询用户仓库中的对应成本是否足够
                    $join = " left JOIN ".$member_phone."_seed_warehouse as seed_ware on  seeds.id = seed_ware.seeds and seed_ware.user={$member_id} ";         // 联立查询成本对应名称
                    $field = " seed_ware.num , seeds.varieties ";
                    $member_has_warehouse[$key] = M("seeds")->field($field)->join($join)->where(" seeds.id=".$key)->fetchSql(false)->find();
                    if( $member_has_warehouse[$key]['num'] < $val*$material_num){
                        $data['status']  = 40020;
                        $data['content'] = '您的成本'.$member_has_warehouse[$key]['varieties']."不足，当前拥有".(int)$member_has_warehouse[$key]['num']."个，需要".$val*$material_num."个。";
                        return $data;
                    }
                }
            }
            M()->startTrans();
            //   进行兑换
            foreach ($member_has_warehouse as $key=>$val){
                //  用户仓库中 减少对应成本的数量
                $map = array();
                $map['user']   = $member_id;
                $map['seeds']  = $key;
                $res_seed = M("{$member_phone}_seed_warehouse")->where($map)->fetchSql(false)->setDec("num",(int)$need_material[$key]*$material_num);
                if($res_seed != "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
                    $data['status']  = 40007;
                    $data['content'] = 'seed_warehouse update error';
                    return $data;
                }
            }
        }elseif($change_type == "coin"){
            // 使用金币 就行兑换
            $need_coin = $need_material['price'] * $material_num;

            if( $member_coin['coin'] < $need_coin){
                $data['status']  = 40021;
                $data['content'] = "您的金币不足，当前拥有".$member_coin['coin']."个，需要".$need_coin."个。";
                return $data;
            }
            M()->startTrans();

            $map['id']   = $member_id;
            // 减少用户金币
            $res_seed = M("{$member_phone}_members")->where($map)->fetchSql(false)->setDec("coin",(int)$need_coin);
            if($res_seed != "1"){
                //  修改不成功 抛出异常
                M()->rollback();
                $data['status']  = 40010;
                $data['content'] = 'member coin update error';
                return $data;
            }
        }

        if($res_seed == 1){
            // 减少成功后  增加用户对应材料
            $map_prop['id']    = M("{$member_phone}_prop_warehouse")->where("user={$member_id} and props={$material_id}")->getField("id");
            $map_prop['user']    = $data_prop['user']   = $member_id;
            $map_prop['props']   = $data_prop['props']   = $material_id;
            $data_prop['num'] = array("exp","num + " . $material_num );

            if($map_prop['id'] ){
                $res_prop = M("{$member_phone}_prop_warehouse")->fetchSql(false)->where($map_prop)->save($data_prop);
            }else{
                $res_prop = M("{$member_phone}_prop_warehouse")->fetchSql()->add($data_prop,"",true);
            }
            if(is_numeric($res_prop) && $res_prop > 0){
                M()->commit();
                $data['status']  = 0;
                $data['content'] = "member table {$member_phone} id {$member_id} has changed material:{$material_id}";
                return $data;
            }else{
                M()->rollback();
                $data['status']  = 40008;
                $data['content'] = "prop_warehouse update error";
                return $data;
            }
        }
    }

    /**
     *  增加升级房屋所需的材料
     *
     */
    public function add_meterial($object){

        if( $object['name']){
            $map['name'] = $object['name'];
            // unset($object['name']);
        }else{
            $data['status']  = 40010;
            $data['content'] = 'Invalid meterial name';
            return $data;
        }
        $all_material = M($this->table)->where($map)->find();
        if($all_material){
            if($all_material['cost'] || $all_material['price']){
                $data['status']  = 40009;
                $data['content'] = 'meterial name exists';
                return $data;
            }else{
                return $this->update_ajax($all_material['id'],$object,"insert");
            }
        }else{
            $insert_id = M($this->table)->add($map);
            if($insert_id > 0 ){
                return $this->update_ajax($insert_id,$object,"insert");
            }else{
                $data['status']  = 40011;
                $data['content'] = 'insert meterial error';
                return $data;
            }
        }
    }
}