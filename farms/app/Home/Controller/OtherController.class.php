<?php
namespace Home\Controller;
use Think\Model;

class OtherController{

 public function xxx(){
		  $seed='稻米';
           $case_p=''.date('Y-m').'_pay';
		   $str = "";
		   //卖方数据
           $data_p_s=M(''.$case_p.'')->order('money asc')->where('type= 0 AND state<2 AND seed="'.$seed.'"')->select();
           $count_s=M(''.$case_p.'')->order('money asc')->where('type= 0 AND state<2 AND seed="'.$seed.'"')->count();
		   print_r($data_p_s);die;
           $data_s=array();
           $f=0;
           for($i=0;$i<$count_s;$i++){
			   if($f==5){
				   break;
			   }
               if($i==0){
                   $data_s[$f]['money']=$data_p_s[$i]['money'];
                   $data_s[$f]['num']=$data_p_s[$i]['num'];
                   $f++;
               }else{
                   if($data_p_s[$i]['money']==$data_s[$f-1]['money']){
                       $data_s[$f-1]['num']+=$data_p_s[$i]['num'];
                   }else{
                       $data_s[$f]['money']=$data_p_s[$i]['money'];
                       $data_s[$f]['num']=$data_p_s[$i]['num'];
                       $f++;
                   }
               }
           }
		   print_r($data_s);die;
	  }
	public function chaxun(){
		$case='2017-08_pay';
		$data=M($case)->field('user')->where('time > 1503414000 AND time <1503453600')->order('user')->select();
		$count=count($data);
		//print_r($count);
		$data_s=array();
	   $f=0;
	   for($i=0;$i<$count;$i++){
		   if($i==0){
			   $data_s[$f]['user']=$data[$i]['user'];
			   $f++;
		   }else{
			   if($data[$i]['user']!=$data_s[$f-1]['user']){
				   $data_s[$f]['user']=$data[$i]['user'];
				   $f++;
			   }
		   }
	   }
	   $k=0;
	   $data_pay=array();
	   for($i=0;$i<$f;$i++){
		   $buy=M($case)->field('user')->where('time > 1503414000 AND time <1503453600 AND tyoe =1 AND user='.$data_s[$i]['user'])->select();
		   $sell=M($case)->field('user')->where('time > 1503414000 AND time <1503453600 AND tyoe =0 AND user='.$data_s[$i]['user'])->select();
		   if(!empty($buy)&&!empty($sell)){
			   $data_pay[$k]['user']=$data_s[$i]['user'];
			   $k++;
		   }
	   }
	   print_r($data_pay);
	}
      //临时发放道具
	  // 2017年8月27日11:27:30 QHP
	  public function mdzz(){
		  
		$string    = $_GET['string'];	
		$get_table = $_GET['table'] ? $_GET['table'] : "prop_warehouse";
		$num 	   = $_GET['num']   ? $_GET['num']   : 200;

        $arr = str_replace("_",",",$string);
		$all_tables_where  = $arr ? 'name in ('.$arr.')' : "";
		  
		$all_tables  =  M("statistical")->where($all_tables_where)->order("name asc")->select();
		
		$first_table = $all_tables[0]['name']."_members";	

		foreach($all_tables as $val){
			
			$tables   =  substr($val['name'],0,3)."_" . $get_table;
			$wheres   =  "props = '种子' and  prop_id=6";
			
			$sql 	  = "update " . $tables ." set num=num+'{$num}' where  ".$wheres  ." ";
			echo M()->execute($sql);
			echo "\n <br/>";	
			
		}	
				
		
		$all_list =  M($first_table)->union($union,true)->where($first_where)->select();
		
	  }
	  
	  public function sss(){
		  $user = M('statistical')->field('name')->select();
		  for($i=0;$i<count($user);$i++){
             /* echo 'CREATE TABLE `'.$user[$i]['name'].'_maintain_record`(
					`id`  int(11) NOT NULL AUTO_INCREMENT ,
					`user`  char(20) NOT NULL ,
					`maintain_time`  int(11) NOT NULL ,
					`due_time`  int(11) NOT NULL ,
					`cost_coin`  int(11) NOT NULL ,
					`cost_fruit`  char(100) NOT NULL ,
					`name`  char(10) NOT NULL ,
					PRIMARY KEY (`id`)
			 )ENGINE=InnoDB DEFAULT CHARSET=utf8;';
			  echo '<br/>';	  */
		  }
	  }
	  
	  public function cnm(){
	      $var=M('verification')->field('user')->select();
          //$var=M('user_freeze')->field('user')->select();
	      $p=0;
	      foreach ($var as $k=>$v){
	          $num=substr($v['user'],0,3);
	          $case=''.$num.'_members';
	          $member=M($case)->where('user ='.$v['user'])->find();
	          if(empty($member)){
                  M('verification')->where('user ='.$v['user'])->delete();
                  echo M('verification')->getLastSql();echo '<br/>';
	              $p++;
              }
          }
          echo $p;
    }

      public function demo(){
        $str='';
        $data=M('statistical')->select();
        foreach($data as $k=>$v){
            $str.='ALTER TABLE `'.$v['name'].'_users_gold`
			MODIFY COLUMN `num_id`  int(11) UNSIGNED NOT NULL AFTER `user`,
			MODIFY COLUMN `user_coin`  double(20,5) UNSIGNED NOT NULL AFTER `num_id`,
			MODIFY COLUMN `user_fees`  double(20,5) NOT NULL AFTER `user_coin`,
			MODIFY COLUMN `buy_and_sell`  double(20,5) UNSIGNED NOT NULL AFTER `user_fees`,
			MODIFY COLUMN `user_top_up`  double(20,5) UNSIGNED NOT NULL AFTER `buy_and_sell`;
			';
        }
        //print_r($data);
        echo $str;
    }

		// 查询用户施肥状态
	  function 	get_fertilization(){
		for($i=1;$i<13;$i++){
			
			 // S(I('get.user')."1"."_fertilization");
			echo $i;
		   print_r( S(I('get.user').$i."_fertilization")  );
		   
			
		}
			
	  }
	  
	  public function miss(){
		  $data=M('statistical')->select();
		  /*$start = 1503898200;
          $end = 1504071000;
		  for($i=0;$i<count($data);$i++){
			  $table = $data[$i]['name'].'_record_shop';
              $res = M("$table")->field('user')->where('buy_time>='.$start.' and buy_time<='.$end.' and name="肥料"')->select();
              for($j=0;$j<count($res);$j++){
				   echo $res[$j]['user'].'<br/>';
			  }			  
		  }*/
		  
		  /*for($i=0;$i<count($data);$i++){
			    $user = M('statistical')->field('name')->select();
				for($i=0;$i<count($user);$i++){
				$tabel = $user[$i]['name'].'_activity_warehouse';
				//echo 'alter table '.$tabel.' add auto int(11) NULL;';
				echo 'truncate table '.$tabel.';';
				echo '<br/>';
             }  
		  }*/
		  
		  
		  $num = 0;
		  $user = '';
		  $diamond = '';
		  for($i=0;$i<count($data);$i++){
			   $table = $data[$i]['name'].'_members';
			   $res = M("$table")->field('user,diamond')->select();
			   for($j=0;$j<count($res);$j++){
				   if($res[$j]['diamond']%10!==0){
					    $num++;
					    //echo $res[$j]['user'].' '.$res[$j]['diamond'].'<br/>';
						$user.= $res[$j]['user'].'<br/>';
						$diamond.= $res[$j]['diamond'].'<br/>';
				   }
			   }
		  }
		  echo date('Y-m-d',time()).' 共计:'.$num.'<br/>';
		  echo $user;
		  echo $diamond;
	  }
	  
	  
	 public function test(){	
		$res = file_put_contents('./Log/test.log',date('Y-m-d H:i:s',time()).PHP_EOL."\n",FILE_APPEND);
		echo $res;
	}
	
	
	public function boxs(){
		
		$h = 0;
		$t = 0;
		$b = 0;
		$z = 0;
		
        $data=M('statistical')->select();
		for($i=0;$i<count($data);$i++){
			 $table = $data[$i]['name'].'_treasure_warehouse';
			 $res = M("$table")->where('num>0')->select();
			 if($res){
				  for($j=0;$j<count($res);$j++){
					  
					   echo $res[$j]['user'].$res[$j]['name'].$res[$j]['num']."<br/>";
					  
					   if($res[$j]['name']=="黄金宝箱"){
						   $h+= $res[$j]['num'];
					   }else if($res[$j]['name']=="黄铜宝箱"){
						   $t+= $res[$j]['num'];
					   }else if($res[$j]['name']=="白银宝箱"){
						   $b+= $res[$j]['num'];
					   }else if($res[$j]['name']=="钻石宝箱"){
						   $z+= $res[$j]['num'];
					   }
				  }
			 } 
		} 
		
		echo '黄金'.$h.'个，黄铜'.$t.'个，白银'.$b.'个，钻石'.$z.'个';
	}
	
    public function shopex(){
		
		$num = 0;
		
		$data=M('statistical')->select();
		for($i=0;$i<count($data);$i++){
			$table = $data[$i]['name'].'_record_shop';
			$count = M("$table")->where('type="h"')->count();
			if($count){
				//echo $data[$i]['name'].'<br/>';
				$num+=$count;
			}
			
		}
		
		echo $num;
	}
	
}
?>
