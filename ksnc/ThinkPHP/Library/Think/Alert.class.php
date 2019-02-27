<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/24
 * Time: 18:38
 */

namespace Think;
class Alert
{

    public function alter($tel){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        $po = substr($tel,0,3);
        $membe = M(''.$po.'_members');
        $tjr = $membe->field('team')->where('user='.$tel.'')->select();
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            $members = M(''.$rsql[0]['name'].'_members');
            $where['referees'] = $tel;
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
        //return $list;
        $success = 0;
        $error = 0;
        for($j=0;$j<count($list);$j++){
            $pr = substr($list[$j]['user'],0,3);
            $member = M(''.$pr.'_members');
            $sql = $member->field('team')->where('user='.$list[$j]['user'].'')->select();
            $cond['team'] = $tjr[0]['team']." ".$tel;
            if($member->where('user='.$list[$j]['user'].'')->save($cond)){
                $success+=1;
            }else{
                $error+=1;
            }
        }
        return $success."/".$error;

    }


    public function substitutions(){

        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        //获取手机号字段
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $sql = $members->field('user')->select();
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

        //return $list[0]['user'];
        $success = 0;
        $error = 0;
        for($j=0;$j<count($list);$j++){
            $pdd = new Alert();
            $wpd = $pdd->alter($list[$j]['user']);
            //return $wpd;
            if($wpd){
                $success+=1;
            }else{
                $error+=1;
            }
            
           
        }
        return '成功'.$success.'条！失败'.$error.'条！';

    }


    /**
     *清除用户team里是自己的电话号码
     **/
    public function tion(){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        //获取手机号字段
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $sql = $members->field('user')->select();
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
        
        //return $list;

        $success = 0;
        $error = 0;
        for($j=0;$j<count($list);$j++){
            $pr = substr($list[$j]['user'],0,3);
            $member = M(''.$pr.'_members');
            $cong['user'] = $list[$j]['user'];
            $cong['team'] = array("LIKE", "%".$list[$j]['user']."%");
            $ksql = $member->field('team')->where($cong)->select();

            if($ksql){
                $ass['team'] = str_replace($list[$j]['user'],'',$ksql[0]['team']);
                if($member->where('user='.$list[$j]['user'].'')->save($ass)){
                    $success+=1;
                }else{
                    $error+=1;
                }

            }

        }
        return $success."/".$error;

    }

    function is_mobile($str){
        if (strlen ( $str ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $str )) {
            return false;
        } else {
            return true;
        }
    }

    /**
     *找到该用户所属的团队
     **/
    public function play($tel,$arr=array()){
        $po = substr($tel,0,3);
        $membe = M(''.$po.'_members');
        $ksql = $membe->field('referees')->where('user='.$tel.'')->find();

       
        if($ksql['referees']!=='' && $this->is_mobile($ksql['referees']) && $ksql['referees']!==$arr){
            //return $ksql;
            $arr[] = $ksql['referees'];
            return $this->play($ksql['referees'],$arr);

        }
        return array_reverse($arr);
    }
    /**
     *存入team记录
     **/
    public function store($tel,$type,$num){
        $arr = $this->play($tel);
        //return $arr;
        $success = 0;
        $error = 0; 
        for($i=0;$i<count($arr);$i++){
            $pr = substr($arr[$i],0,3);
            $team_record = M(''.$pr.'_team_record');
            $cond['user'] = '18768477519';
            $cond['fromuser'] = $arr[$i];
            $cond['pay_money'] = $num;
            $cond['type'] = $type;
            $cond['pay_time'] = time();
            if($team_record->data($cond)->add()!==false){
                $success +=1;
            }else{
                $error +=1;
            }
        }
        return $success.'/'.$error;
    }


    public function cont(){
       $statistical=M('Statistical');
       $dsql = $statistical->field('id,name')->select();
       //获取手机号字段
       $arr = array();
       for($i=0;$i<count($dsql);$i++){
           $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
           //return $rsql;
           //父级直接推荐了多少人
           //var_dump($rsql[0]['name']);die;
           $members = M(''.$rsql[0]['name'].'_members');
           $sql = $members->field('user')->select();
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


       $success = 0;
       $error = 0;
       
       for($j=0;$j<count($list);$j++){
           $des = $this->play($list[$j]['user']);
           $conm['team'] = $des;
           $pp = substr($list[$j]['user'],0,3);
           $membe = M(''.$pp.'_members');
           //$dsql = $membe->where('user='.$list[$j]['user'].'')->save();
           if($membe->where('user='.$list[$j]['user'].'')->save()!==false){
               $success+=1;
           }else{
               $error+=1;
           }

       }
       return $success."/".$error;

   }

    /**
     *把佣金存入到users_gold里
     **/
    public function poss(){
        $record = M('2017-07_rebate_record');
        $dsql = $record->select();
        $or = array();
        foreach ($dsql as $key=>$item) {
            $or[$item['user']]['money']  += $item['money'];
        }
        //return $or;
        $success = 0;
        $error = 0;
        foreach ($or as $key=>$v){
            //return $v['money'];
            //$caca = $record->where('user='.$key.'')->find();
            $po = substr($key,0,3);
            $gold = M(''.$po.'_users_gold');
            $fofo = $gold->where('user='.$key.'')->find();
            //return $fofo;
            if($fofo!==null){
                //$cond['user'] = $dsql[$i]['user'];
                $cond['user_fees'] = $fofo['money']+$v['money'];
                //return $cond['user_fees'];
                if($gold->where('user='.$key.'')->save($cond)!==false){
                    $success+=1;
                }else{
                    $error+=1;
                }
            }else{
                $cond['user'] = $key;
                $cond['user_fees'] = $v['money'];
                //return $cond['user_fees'];
                if($gold->data($cond)->add()!==false){
                    $success+=1;
                }else{
                    $error+=1;
                }
            }
        }
        //return $or;
        return $success."/".$error;
    }

	    /**
     *讲$pho的团队移到$Tel下去
     **/
    public function replace_qhp($tel,$pho){
        $statistical=M('Statistical');
		
        $dsql = $statistical->field('id,name')->order('name asc')->select();

		$temp =  array();
        for($i=0;$i<count($dsql);$i++){
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M($dsql[$i]['name'].'_members');

            $where['team'] = array("LIKE", "%".$pho."%");
            $list = $members->field('user , team')->where($where)->select();
			
			$list = $list ? $list : array();
            $temp =  array_merge($temp , $list);
        }
		
		// 获取移动团队的 team 字段
		$move_user_team  = M(substr($pho,0,3).'_members')->where('user="'.$pho.'"')->getfield("team");
		// $move_user_teams = $move_user_team['team'];
		
		print_r($move_user_team);
		die;
        //return count($list);
        $success = 0;
        $error = 0;
        $sz = array();
        $az = array();
        $lass = $this->play($tel);
        $bass = $this->play($pho);
        for($j=0;$j<count($list);$j++){
            $pr = substr($list[$j]['user'],0,3);
            $member = M(''.$pr.'_members');
            $pass = $this->play($list[$j]['user']);
            //$cond['team'] =$pass.' '.$tel.' '.$lass; //$tel." ".$sql[0]['team'];
            $cond['team'] =str_replace($bass,' '.$tel.$lass,$pass);
            if($member->where('user='.$list[$j]['user'].'')->save($cond)){
                $success+=1;
                $az[$j] = $list[$j]['user'];
            }else{
                $error+=1;
                $sz[$j] = $list[$j]['user'];
            }
        }
        //return $lass;
        $vs = substr($pho,0,3);
        $ban['referees'] = $tel;
        $ban['team'] = $tel.' '.$lass;
        if(M(''.$vs.'_members')->where('user='.$pho.'')->save($ban)!==false){
            return "success:".$success."  \n<br/> error:".$error."\n<br/>";
            //return $sz;
        }

    }

	
    /**
     *讲$pho的团队移到$Tel下去
     **/
    public function replace($tel,$pho){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        $arr = array();
		  
		$user_referees['referees'] =  $tel;
		
        // M(substr($pho,0,3).'_members')->where('user='.$pho.'')->save($user_referees);
		
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
        //return $list;
        $success = 0;
        $error = 0;
        $sz = array();
        $az = array();
        $lass = implode(" ",$this->play($tel));
        $bass = implode(" ",$this->play($pho));
        //return $bass;
		
		
        for($j=0;$j<count($list);$j++){
            $pr = substr($list[$j]['user'],0,3);
            $member = M(''.$pr.'_members');
			
			// $list[$j]['user']  = rsort($list[$j]['user']);
			
			// print_r($this->play($list[$j]['user']));
			
			// die;
			
            $pass = implode(" ",  $this->play($list[$j]['user']) );
            //$cond['team'] =$pass.' '.$tel.' '.$lass; //$tel." ".$sql[0]['team'];
            if($bass!=''){
                $cond['team'] =str_replace($bass, $lass .' '.$tel , $pass);
            }else{
                $cond['team'] =' '.$tel.' '.$lass.' '.$pass;
            }
            if($member->where('user='.$list[$j]['user'].'')->save($cond) !== false){
                $success+=1;
                $az[$j] = $list[$j]['user'];
            }else{
                $error+=1;
                $sz[$j] = $list[$j]['user'];
            }
        }
        //return $lass;
        $vs = substr($pho,0,3);
        $ban['referees'] = $tel;
        $ban['team'] = $tel.' '.$lass;
        if(M(''.$vs.'_members')->where('user='.$pho.'')->save($ban)!==false){
            return "success:".$success."  \n<br/> error:".$error."\n<br/>";
            //return $sz;
        }

    }


    /**
     *
     * 替换team里编号为$num_id
     * **/
    public function clear($num_id){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        //获取手机号字段
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $conm['team'] = array("LIKE", "%".$num_id."%");
            $sql = $members->field('user,team')->where($conm)->select();
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

        //return $list;
        $success = 0;
        $error = 0;

        for($j=0;$j<count($list);$j++){
            $des = $this->num($num_id);
            //$conm['team'] = $des;
            $pp = substr($list[$j]['user'],0,3);
            $membe = M(''.$pp.'_members');
            $cong['team'] = str_replace($num_id,$des,$list[$j]['team']);
            if($membe->where('user='.$list[$j]['user'].'')->save($cong)!==false){
                $success+=1;
            }else{
                $error+=1;
            }
        }
        return $success."/".$error;
    }


    /**
     *
     * 找编号对应的电话号码
     *
     * **/
    public function num($num_id){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        //获取手机号字段
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $conm['num_id'] = $num_id;
            $sql = $members->field('user')->where($conm)->find();
            if($sql!==null){
                $arr = $sql['user'];
            }

        }
        return $arr;
    }
    
    /**
     * 替换referees里编号为号码
     * **/
    public function away($num_id){
        $statistical=M('Statistical');
        $dsql = $statistical->field('id,name')->select();
        //获取手机号字段
        $arr = array();
        for($i=0;$i<count($dsql);$i++){
            $rsql = $statistical->field('name')->where('id='.$dsql[$i]['id'].'')->select();
            //return $rsql;
            //父级直接推荐了多少人
            //var_dump($rsql[0]['name']);die;
            $members = M(''.$rsql[0]['name'].'_members');
            $conm['referees'] = $num_id;
            $sql = $members->field('user')->where($conm)->select();
            if($sql!==null){
                $arr[$i] = $sql;
            }

        }
        $list = array();
        $temp = 0;
        for($i=0;$i<count($arr);$i++){
            for($j=0;$j<count($arr[$i]);$j++) {
                $list[$temp] = $arr[$i][$j];
                $temp++;
            }
        }
        //return $list[];
        $success = 0;
        $error = 0;

        for($j=0;$j<count($list);$j++){
            $des = $this->num($num_id);
            //$conm['team'] = $des;

            $pp = substr($list[$j]['user'],0,3);
            $membe = M(''.$pp.'_members');
            $cong['referees'] = $des;
            //return $cong['referees'];
            if($membe->where('user='.$list[$j]['user'].'')->save($cong)!==false){
                $success+=1;
            }else{
                $error+=1;
            }
        }
        return $success."/".$error;
    }

    /**
     *查询团队下面的充值或兑换记录
     **/
    public function pay_record($fromuser,$type,$time){
        $pr = substr($fromuser,0,3);
        $team_record = M(''.$pr.'_team_record');

        $cond['fromuser'] = $fromuser;
        if($type){
            $cond['type'] = $type;
        }
        if($time){
            $cond['pay_time'] = array(array('gt',strtotime($time[0])),array('lt',strtotime($time[1])));
        }
        //return $cond;
        $content = $team_record
                ->field(true)
                ->where($cond)
                ->select();
        return $content;
        $or = array();
        foreach ($content as $key=>$item) {
            $or[$item['user']]['money']  += $item['money'];
        }
        return $or;
    }

    public function exchange_record($user,$type,$time){
        
    }

    public function storage($tel,$arr=array()){
        $po = substr($tel,0,3);
        $membe = M(''.$po.'_members');
        $ksql = $membe->field('referees')->where('user='.$tel.'')->find();
        //return $ksql;

        if($ksql['referees']!=='' && $this->is_mobile($ksql['referees'])){
            $arr[] = $ksql['referees'];
            return $this->play($ksql['referees'],$arr);

        }
        $list = '';
        for($i=0;$i<count($arr);$i++){
            $list .= ' '.$arr[$i];
        }
        return $list;
        //return $arr;
    }


    public function pdo(){
        $pdo = new \PDO("mysql:host=localhost;dbname=test","root","root");
        $sql="select * from Statistical";
        $prepare=$pdo->prepare($sql);
        $prepare->execute();//添加条件数据
        $table = $prepare->fetchAll();
        //var_dump($prepare);
        return $table;
    }



}