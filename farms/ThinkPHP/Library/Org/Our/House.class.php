<?php
namespace Org\Our;
use Think\Model;
/**
 * Class House
 * @package Org\Our
 * return @$data['status']
 *               50003  无效的用户ID
 *               50006  该用户不存在
 *               50021  用户钻石不足
 *               50007  用户材料仓库 修改失败
 *               50009  用户钻石数量 修改失败
 *               50008  用户房屋等级  level修改失败
 */

class House {

    protected $material_id = ""; //房屋ＩＤ
	
    protected $level_max   = 7;  //当前限制的房屋等级

    protected $object = array(); //房屋对应名称，等级，升级所需材料

    /**  修改各级房屋所需的材料数量
     * @param $material_id      房屋ID
     * @param $object           包括  房屋等级 和 房屋名称 和其他各材料ID及所需数量
     * @return $data   status  状态   content 描述
     */
    function update_ajax($material_id,$object){

        $level = $object['level'];  unset($object['level']);

        $name = $object['name'];    unset($object['name']);

        foreach ($object as $key=>$val){
            // 取消 验证 $key  是否为数字型
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
            $data['status']  = 50002;
            $data['content'] = 'house (name or level ) exist ';
            return $data;
        }

        $updata_arr['cost'] = serialize($objects);
        if($level){
            $updata_arr['level'] = $level;
        }
        if($name){
            $updata_arr['name'] = $name;
        }

        $res = M("house")->where("id={$material_id}")->fetchSql(false)->save($updata_arr);

        if($res == 1){
            $data['status']  = -1;
            $data['content'] = 'OK';
            return $data;
        }
    }

    /**
     * 增加房屋等级
     * @param $material_id
     * @param $object
     *         name  ：房屋名称
     *         level ： 增加的房屋 对应的等级
     * @return $data   status  状态   content 描述
     */
    function add_house($object){

        if( $object['name']){
            $map['name'] = $object['name'];
            unset($object['name']);
        }else{
            $data['status']  = 50010;
            $data['content'] = 'Invalid house name';
            return $data;
        }

        if( $object['level'] && is_numeric($object['level'])){
            $map['level'] = $object['level'];
            unset($object['level']);
        }else{
            $data['status']  = 50011;
            $data['content'] = 'Invalid house level';
            return $data;
        }

        $all_house = M("house")->where($map)->find();


        if($all_house){
            if($all_house['cost'] || $all_house['price']){
                $data['status']  = 50002;
                $data['content'] = 'meterial name exists';
                return $data;
            }else{
                return  $this->update_ajax($all_house['id'],$object);
            }
        }else{
            $insert_id = M("house")->add($map);
            if($insert_id > 0 ){
                return $this->update_ajax($insert_id,$object);
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
    function exchange($user,$number){		

        $table_fix = substr($user,0,3);
        $user_message = M($table_fix."_members")->where("user='".$user."'")->field("level,diamond,nickname")->find();

		 if(intval($number) > $this->level_max){
             $data['state'] = 50001;
             $data['content'] = ($this->level_max + 1). '级暂未开放';
             echo json_encode($data);
             exit;
        }
		
        if(intval($number)-1 !== intval($user_message['level'])){
             $data['state'] = 50001;
             $data['content'] = '升级等级出错！';
             echo json_encode($data);
             exit;
        }

        //  查询该会员 要兑换的材料所需的成本
        $need_material =  M("house")->where("level=".($user_message['level']+1))->find();
        $need_material = $this->material_handle_one($need_material);

        // 检查用户的材料是否足够
        foreach ($need_material as $key=>$val){
            if(! is_numeric($key) && $key != "price"){
                unset($need_material[$key]);
            }else{
                if( is_numeric($key) ){
                    // 查询用户仓库中的对应成本是否足够

                    /** new 单独查询 仅有所需材料的数量 */
                    $where_warehouse['user'] = $user;
                                // 用户ID
                    $where_warehouse['props'] = $key;                   // 材料ID
                    $field = "num,prop_name as name";
                    $member_has_warehouse[$key] = M($table_fix."_meterial_warehouse")->where($where_warehouse)->field($field)->find();

                    if($member_has_warehouse[$key]['num']<$val){
                        $data['state'] = 50002;
                        $arr['other']['name'] = $member_has_warehouse[$key]['name'];
                        $arr['other']['num']  = $member_has_warehouse[$key]['num'];
                        $arr['other']['need'] = $val;
                        if(!$arr['other']['name']){
                              $house_material = M("house_material")->where(array('id'=>$key))->find();
                              $arr['other']['name'] = $house_material['name'];
                              unset($house_material);
                         }

                        $data['content'] = $arr["other"]['name']."不足";
                        unset($arr['other']);
                        echo json_encode($data);
                        exit;
                    }
                }else if($key == "price"){
                    if( $user_message['diamond'] < $val){
                        $data['state']  = 50003;
                        $data['content'] = '钻石不足';
                        echo json_encode($data);
                        exit;
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
                $map['user']   = $user;
                $map['props']  = $key;
                $res_seed = M($table_fix."_meterial_warehouse")->where($map)->setDec("num",(int)$val);

                if($res_seed != "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
                    $data['state']  = 50004;
                    $data['content'] = '升级失败，材料修改失败！';
                    echo json_encode($data);
                    exit;
                }
            }elseif($key == "price"){
                // 修改用户的宝石
                $map = array();
                $map['user'] = $user;
                $res_seed = M($table_fix."_members")->where($map)->setDec("diamond",(int)$val);
                if($res_seed != "1"){
                    //  修改不成功 抛出异常
                    M()->rollback();
                    $data['state']  = 50005;
                    $data['content'] = '升级失败，宝石修改失败！';
                    echo json_encode($data);
                    exit;
                }
            }
        }

        if($res_seed == 1){
            // 减少成功后  升级用户房屋等级
            $member_map = array();
            $member_map['user'] = $user;

            //归零回本状态
            $back_state = M($table_fix."_members")->field('cost_state')->where($map)->find();

            if($back_state['cost_state']==0){
                $back_state_res = true;
            }else{
                $cost['cost_state'] = 0;
                $back_state_res = M($table_fix."_members")->where($map)->save($cost);
            }

            //归零回本资金
            $back_money = M($table_fix."_members_record")->field('income')->where($map)->find();

            if($back_money['income']==0 || $back_money==false){
                 $back_money_res = true;
            }else{
                 $income['income'] = 0;
                 $back_money_res = M($table_fix."_members_record")->where($map)->save($income);
            }

            //查看有无限制记录，有则删除
            $back_user = M('backmoney_user')->where($map)->find();
            if($back_user){
                 $back_allow = M('backmoney_user')->where(array('id'=>$back_user['id']))->delete();
            }else{
                 $back_allow = true;
            }

            $res_prop = M($table_fix."_members")->where($map)->setInc("level",1);
			
			// 修改团队表
            $team_relationship = M("team_relationship")->where("user='". $map['user'] ."'")->setInc("level",1);
			
			
            if(is_numeric($res_prop) && $res_prop>0 && $back_state_res && $back_money_res && $back_allow && $team_relationship){
                   M()->commit();
				   
				   //定义一个数组
				   $array = array();
				   //查看是否有缓存
				   $treasure_message = S('treasure_message');
				   $treasure_num = S('treasure_num');
				   
                   $level['user'] = $user_message['nickname'];
				   $level['next_level'] = $number;

				   //设置过期时间
				   $time=mktime(0,0,0,date('m'),date('d'),date('y'))+24*3600-time();
				   //如果不存在缓存
				   if($treasure_num==false){
					   Array_push($array,$level);  //将新添加数据加入空数组
					   S('treasure_message',$array,$time);  //新数组开启缓存
					   S('treasure_num',1,$time);  //计数从1开始
				   }else{
					   //如果存在缓存
					   Array_push($treasure_message,$level); //将新添加数据加入已有的缓存数组
					   $treasure_num = S('treasure_num')+1;   //计数加1
					   S('treasure_message',null);  //删除以前的缓存
					   S('treasure_num',null);    //删除以前的计数
					   S('treasure_message',$treasure_message,$time); //重新生成缓存
					   S('treasure_num',$treasure_num,$time);  //重新生成计数
				  }

                  $data['state']  = 50007;
                  $data['content'] = '恭喜你，成功升到'.$number.'级';
  				  $data['diamond_number'] = $need_material['price'];
                  $data['next_house'] = $number;
                  echo json_encode($data);
                  exit;
            }else{
                M()->rollback();
                $data['state']  = 50006;
                $data['content'] = '升级失败，级别修改失败！';
                echo json_encode($data);
                exit;
            }
        }
    }

    function material_handle_one($all_material=array()){
        $material = array();
        $material = unserialize($all_material['cost']);
        foreach ( $material as $k=>$v){
            $all_material[$v['seed_id']] = $v['seed_value'];
        }
        return $all_material;
    }
}
