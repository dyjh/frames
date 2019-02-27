<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 18:38
 */


namespace Org\Our;

class Alert
{

    public function alter($tel,$pho){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $where['team'] = array("LIKE", "%".$pho."%");
            $sql = $members->field('user')->where($where)->select();
            $arr[$i] = $sql;
        }
        $list = array();
        $temp = 0;
        for($i=0;$i<count($arr);$i++){
            for($j=0;$j<count($arr[$i]);$j++) {
                $list[$temp] = $arr[$i][$j];
                $temp++;
            }
        }
        //return count($list);
        $success = 0;
        $error = 0;
        for($j=0;$j<count($list);$j++){
            $pr = substr($list[$j]['user'],0,3);
            $member = M(''.$pr.'_members');
            $sql = $member->field('team')->where('user='.$list[$j]['user'].'')->select();
            $cond['team'] =str_replace($tel,$pho,$sql[0]['team']); //$tel." ".$sql[0]['team'];
            if($member->where('user='.$list[$j]['user'].'')->save($cond)){
                $success+=1;
            }else{
                $error+=1;
            }
        }
        return '成功'.$success.'条！失败'.$error.'条！';
    }
	
	
	
	
	
	/**
     * @add_user
     * add_user  批量添加用户进team_team_relationship表里
     **/
    public function add_user(){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        $num_sql = count($dsql);
        //获取手机号字段
        $arr = array();
        for($i=0;$i<$num_sql;$i++){
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$dsql[$i]['name'].'_members');
            $sql = $members->field('user,level,referees,team')->select();
            $arr[$i] = $sql;          
        }
        //return $arr;
        $list = array();
        $temp = 0;
        $num_arr = count($arr);
        for($i=0;$i<$num_arr;$i++){
            for($j=0;$j<count($arr[$i]);$j++) {
                $list[$temp] = $arr[$i][$j];
                $temp++;
            }
        }
        //return $list;
        $num_list = count($list);
        $success = 0;
        $error = 0;
        for($j=0;$j<$num_list;$j++){
            //return $list[$j]['user'];
            $team_relationship = M('team_relationship');
            $cond['user'] = $list[$j]['user'];
            $cond['level'] = $list[$j]['level'];
            $cond['referees'] = $list[$j]['referees'];
            $cond['team'] = $list[$j]['team'];
            if($team_relationship->add($cond)!==false){
                $success+=1;
            }else{
                $error+=1;
            }
        }
        return $success."/".$error;
    }
	
	
	
	
	
	
	
	
	
	
	
	

}