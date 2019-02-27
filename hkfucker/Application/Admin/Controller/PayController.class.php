<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/5 0005
 * Time: 16:46
 */

namespace Admin\Controller;
use Org\Our\Admin;
use Think\Model;

class PayController extends AdminController{

    public function index(){
				
		$where  = "  `pay_cash`='1'  ";
		       
        if(isset($_GET['state'])){			
			switch ($_GET['state']){				
				case "1,9":
					$state   = " and state in ( ". $_GET['state'] .") ";
					$where  .= $state;
					break;
				case "2":
					$state   = " and state=2";
					$where  .= $state;
					break;
				case "all":
					// $date['state']   = "";
					unset($state);
					break;
				default:
					$state   = " and state=0";
					$where  .= $state; 
					break;
			}						

		}else{
			
			$where  .= " and state=0";
			
		}
		$num =8;			
			
		$all_tables  =  M("statistical")->order("name asc")->select();
		
		$first_table = $all_tables[0]['name']."_order";		

		$first_field = $all_tables[0]['name']."_order.* , ".$all_tables[0]['name']."_members.name  ";
		
		$first_where = $where;
		
		$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$all_tables[0]['name']. "_order.user ";

		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
            $user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
			$first_where	.=  sprintf($user_where,$first_table) ;
        }

		unset($all_tables[0]);					
		
		// 拼接 sql；
		foreach($all_tables as $val){
			
			$field   =  $val['name']."_order.* , ".$val['name']."_members.name  ";
			// $field   =  $val['name']."_order.* ";
			$table   =  $val['name']."_order";
			$join    =  " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$val['name']. "_order.user ";
			
			$sql = "select " . $field . " from " . $table . $join ." where  ".$where . sprintf($user_where,$table) ." ";
			
			$union[] = $sql;	
			
		}	
				
		$all_list =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->select();
			
		array_multisort(i_array_column($all_list,'add_time'),SORT_DESC,$all_list);
		
		// 获得分页						
		$page = intval(I("get.p",1,'addslashes'));	
		
		$result = page_array($num,$page,$all_list);	
	
		$data   =  $result['array']; 		
	
        $this->assign('start_user',$_GET['start_user']);
		
        $this->assign('state',$_GET['state']);
		
        require_once("ThinkPHP/Common/init.php");

        $this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support); //分页

        $this->assign('user_info',$data);
		
        $this->assign('result',$result);
		
        $this->assign('start',$result['start']);
		
        $this->assign('now_oage',$result['now_oage']);
		
        $this->assign('end',$result['end']+1);
		
        $this->display();
    }
	
	public function hundred(){
				   
	$where = "(user_fees+buy_and_sell) > 100";
	
	$num =8;			
		
	$all_tables  =  M("statistical")->order("name asc")->select();
	
	$first_table_name = $all_tables[0]['name'];
	
	$first_table = $all_tables[0]['name']."_users_gold";		

	$first_field = $first_table.".* , ".$all_tables[0]['name']."_members.name  , ".$all_tables[0]['name']."_members.coin  , ".$all_tables[0]['name']."_members.coin_freeze  ";
	
	$first_where = $where;
	
	$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$first_table. ".user ";

	if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
		$user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
		$first_where	.=  sprintf($user_where,$first_table) ;
	}

	unset($all_tables[0]);					
	
	// 拼接 sql；
	foreach($all_tables as $val){
		
		$table   =  $val['name']."_users_gold";
			
		$field   =  $table.".* , ".$val['name']."_members.name  , ".$val['name']."_members.coin, ".$val['name']."_members.coin_freeze  ";
	
		$join    =  " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$table. ".user ";
		
		$sql 	 = "select " . $field . " from " . $table . $join ." where  ".$where . sprintf($user_where,$table) ." ";
		
		$union[] = $sql;	
		
		/** 金币*/
		$coin_field  =   $val['name']. "_members.user,"					
					 . " (`coin`+coin_freeze) as  coin  , " . $val['name']."_users_gold.`buy_and_sell` ," 
					 . " " . $val['name']."_users_gold.`user_fees` , " . $val['name']."_members.`num_id` ";
	
	
		$coin_join  = " right join ".$val['name']. "_users_gold on ".$val['name']. "_members.user = ".$val['name']. "_users_gold.user ";

		$coin_sql = "select " . $coin_field . " from " . $val['name']."_members" . $coin_join ." where ((" . $val['name']."_users_gold.user_fees + " . $val['name']."_users_gold.buy_and_sell) > (" . $val['name']."_members.`coin` + " . $val['name']."_members.`coin_freeze`) )";
		
		$coin_union[] = $coin_sql;	
		// $coin_union = $coin_sql;	
		/** 金币*/
		
	}	
			
	$all_list =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->fetchSql(false)->select();
		
		
		/** 金币*/
		$first_coin_table = $first_table_name."_members";
		
		// 使用mysql if  语句	
		$first_coin_field =  $first_table_name. "_members.user,"					
						 .  " (`coin`+`coin_freeze`) as  coin , " . $first_table_name."_users_gold.`buy_and_sell` ," 
						 . " " . $first_table_name."_users_gold.`user_fees` , " . $first_table_name."_members.`num_id` ";		

		$first_coin_join  = " right join ".$first_table_name. "_users_gold on ".$first_table_name. "_members.user = ".$first_table_name. "_users_gold.user ";

		$coin_where = "1=1 and ((" . $first_table_name."_users_gold.user_fees + " . $first_table_name."_users_gold.buy_and_sell) > (" . $first_table_name."_members.`coin`+ " . $first_table_name."_members.`coin_freeze` ) )";
		
		// $coin_list =  M($first_coin_table)->join($first_coin_join)->union($coin_union,true)->field($first_coin_field)->where($coin_where)->fetchSql(false)->select();
			// print_r($coin_list);die;
		// echo $coin_list;die;
		// foreach($coin_list as $val){
			
				// $sql 			= "";
				// $user_fees 		= 0;
				// $buy_and_sell 	= 0;
				
				// $table_name = substr($val['user'],0,3);		
				// $coin_table	= $table_name . "_users_gold";			
	
				// if($val['buy_and_sell']  >= $val['coin']  || $val['buy_and_sell'] >= $val['coin'] ){
					// $buy_and_sell = $val['coin'];
					// $user_fees    = 0 ;
				// }else{
					// $buy_and_sell = $val['buy_and_sell'];
					// $user_fees    = $val['coin'] - $val['buy_and_sell'] ;
				// }
				
				
				// $sql = "Update $coin_table set num_id='".$val['num_id']."' , `buy_and_sell`=$buy_and_sell , user_fees=$user_fees where user='".$val['user']."'";
				
					// $res[] = $sql;
				// M()->execute($sql);
				
		// }

		
	array_multisort(i_array_column($all_list,'add_time'),SORT_DESC,$all_list);
	
	// 获得分页						
	$page = intval(I("get.p",1,'addslashes'));	
	
	$result = page_array($num,$page,$all_list);	

	$data   =  $result['array']; 		
// print_r($data);
	$this->assign('start_user',$_GET['start_user']);
	
	$this->assign('state',$_GET['state']);
	
	require_once("ThinkPHP/Common/init.php");

	$this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support); //分页

	$this->assign('user_info',$data);
	
	$this->assign('result',$result);
	
	$this->assign('start',$result['start']);
	
	$this->assign('now_oage',$result['now_oage']);
	
	$this->assign('end',$result['end']+1);
	
	$this->display();
}

	public function cash(){
				
		$where  = "  `pay_cash`='2'  ";		

        if(isset($_GET['state'])){			
			switch ($_GET['state']){				
				case "1,9":
					$state   = " and state in ( ". $_GET['state'] .") ";
					$where  .= $state;
					break;
				case "2":
					$state   = " and state=2";
					$where  .= $state;
					break;
				case "all":
					// $date['state']   = "";
					unset($state);
					break;
				default:
					$state   = " and state=0";
					$where  .= $state; 
					break;
			}						

		}else{
			
			$where  .= " and state=0";
			
		}
		
		$start_time    =  empty($_GET['start_time'])  ? strtotime( date("Y-m") )  : strtotime($_GET['start_time']);
		$end_time  	   =  empty($_GET['end_time'])    ? strtotime(date("Y-m") ." , +1 month")  : strtotime($_GET['end_time']);
		
		$_GET['start_time'] =  date("Y-m-d",$start_time);
		$_GET['end_time']   =  date("Y-m-d",$end_time);
		
		$where    .= " and `add_time` between {$start_time} and {$end_time}" ;	
		
		$num =10;			
					
		$all_tables  =  M("statistical")->order("name asc")->select();
		
		$first_table = $all_tables[0]['name']."_order";
		
		$first_field = $all_tables[0]['name']."_order.* , ".$all_tables[0]['name']."_members.name  ";
		
		/** 金币*/
		$first_coin_table = $all_tables[0]['name']."_members";
		
		// 使用mysql if  语句	
		$first_coin_field =  $all_tables[0]['name']. "_members.user,"
						 . " SUM( if( ((" . $all_tables[0]['name']."_users_gold.user_fees + " . $all_tables[0]['name']."_users_gold.buy_and_sell) > " . $all_tables[0]['name']."_members.`coin`) , " . $all_tables[0]['name']."_members.`coin` , (" . $all_tables[0]['name']."_users_gold.user_fees + " . $all_tables[0]['name']."_users_gold.buy_and_sell) ) ) as sum_money , "
						 . " SUM(" . $all_tables[0]['name']."_members.`coin` + " . $all_tables[0]['name']."_members.`coin_freeze` ) as sum_coin";
		
		$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$all_tables[0]['name']. "_order.user ";
		
		$first_coin_join  = " right join ".$all_tables[0]['name']. "_users_gold on ".$all_tables[0]['name']. "_members.user = ".$all_tables[0]['name']. "_users_gold.user ";

		$coin_where = "1=1";
		/** 金币*/
		$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$all_tables[0]['name']. "_order.user ";
		
		$first_where = $where;
		
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
            $user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
			$first_where	.=  sprintf($user_where,$first_table) ;
        }
		
		if(isset($_GET['pay_bank_name']) && !empty($_GET['pay_bank_name']) ){
            $first_where  .= " and {$first_table}.pay_bank like '%" . addslashes($_GET['pay_bank_name']) . "%'";//
        }

		unset($all_tables[0]);					
		
		// 拼接 sql；
		foreach($all_tables as $val){
			
			$field   =  $val['name']."_order.* , ".$val['name']."_members.`name`  ";
			
			$table   =  $val['name']."_order";
			$join    =  " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$val['name']. "_order.user ";
			
			$sql = "select " . $field . " from " . $table . $join ." where  ".$where  . sprintf($user_where,$table) . sprintf($pay_bank_where,$table) ." ";
			if(isset($_GET['pay_bank_name']) && !empty($_GET['pay_bank_name']) ){
				$sql  .= " and {$table}.pay_bank like '%" . addslashes($_GET['pay_bank_name']) . "%'";//
			}

			$union[] = $sql;			
			/** 金币*/
			$coin_field  =    $val['name']. "_members.user,"
							. " SUM( if( ((" . $val['name']."_users_gold.user_fees + " . $val['name']."_users_gold.buy_and_sell) > " . $val['name']."_members.`coin`) , " . $val['name']."_members.`coin` , (" . $val['name']."_users_gold.user_fees + " . $val['name']."_users_gold.buy_and_sell) ) ) as sum_money , "
							. " SUM(" . $val['name']."_members.`coin` + " . $val['name']."_members.`coin_freeze` ) as sum_coin";
		
		
			$coin_join  = " left join ".$val['name']. "_users_gold on ".$val['name']. "_members.user = ".$val['name']. "_users_gold.user ";

			$coin_sql = "select " . $coin_field . " from " . $val['name']."_members" . $coin_join ." ";
			
			$coin_union[] = $coin_sql;	
			/** 金币*/
		}	
		
		
		$coin_list =  M($first_coin_table)->join($first_coin_join)->union($coin_union,true)->field($first_coin_field)->where($coin_where)->select();
		
		$coin['can_cash']  = 0;
		$coin['user_coin'] = 0;
		foreach($coin_list as $val){			
			$coin['can_cash'] += $val['sum_money'];
			$coin['user_coin'] += $val['sum_coin'];	
		}
		
		$this->assign('coin',$coin);
		
		$all_list =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->select();
		
		array_multisort(i_array_column($all_list,'add_time'),SORT_DESC,$all_list);
		
		// 获得分页						
		$page = intval(I("get.p",1,'addslashes'));	
		
		$result = page_array($num,$page,$all_list);

		// print_r(M($first_coin_table)->getLastSql());
		// print_r($coin);
		
		$data   =  $result['array']; 		
		
		foreach($data as $key=>$val){
			
			if($val['money']<=499){
				$data[$key]['hook'] = ($data[$key]['money']-($data[$key]['money']*0.029));
			}else if($val['money']<=999 && $val['money']>=500){
				$data[$key]['hook'] = ($data[$key]['money']-($data[$key]['money']*0.025));
			}else{
				$data[$key]['hook'] = ($data[$key]['money']-($data[$key]['money']*0.02));
			}
	
		}
		
		
		$legal_parameter = array("start_user","state","start_time","end_time");
		foreach($_GET as $key=>$val){
            if(in_array($key,$legal_parameter) && !empty($val)){
                $get_url[$key] .= $key."=".$val;
            }
        }

        $get_url_str .= implode("&",$get_url);
	// print_r($get_url)	;
		$this->assign('get_url_str'	,$get_url_str);
		
		$this->assign('start_time'	,$start_time);
		
		$this->assign('end_time'	,$end_time);
		
        $this->assign('start_user',$_GET['start_user']);
		
        $this->assign('pay_bank',$_GET['pay_bank_name']);
		
        $this->assign('state',$_GET['state']);

        $this->assign('user_info',$data);
		
        $this->assign('result',$result);
		
        $this->assign('start',$result['start']);
		
        $this->assign('now_oage',$result['now_oage']);
		
        $this->assign('end',$result['end']+1);
		
        $this->display();
    }

    public function cash_old(){
        $Admin = new Admin();
      
	    $where['pay_cash']  = 2;
		
		switch($_GET){
			case "start_user":
				$where['start_user'] = $start_user = $_GET['start_user'];
				continue;
			case "state":
				$where['state'] = $state = $_GET['state'];
				continue;
			default:
				break;
		}

        $data = $Admin->Set_Order($where); 
	

        $this->assign('start_user',$start_user);
		
        $this->assign('state',$state);
		
        $count=count($data);//得到数组元素个数
		
        $num =8;
		
        $pages = ceil($count/$num);
        $p = intval(I("get.p",0,'addslashes'));
        if($p!==null){
            $p =$p;
        }else{
            $p =1;
        }
        if($p<1){
            $p =1;
        }else if($p > $pages){
            $p = $pages;
        }
        $showPage = 5;
        $off=floor($showPage/2);

        $start=$p-$off;
        $end=$p+$off;

        //起始页
        if($p-$off < 1){
            $start = 1;
            $end = $showPage;
        }
        //结束页
        if($p+$off > $pages){
            $end = $pages;
            $start = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start = 1;
            $end = $pages;
        }

        require_once("ThinkPHP/Common/init.php");

        $this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support); //分页
        $this->assign('start',$start); //分页
        $this->assign('end',$end+1); //分页
        $this->assign('p',$p);
        $res =array_slice($data,($p-1)*8,8);
        if(empty($res)){
            $state=0;
            $state_p=0;
        }else{
            $state=1;
            $state_p=1;
        }
        creatToken();
		$this->assign('cash_notice',true);
        $this->assign('state',$state);
        $this->assign('state_p',$state_p);
        $this->assign('user_info',$res);
        $this->display();
    }

    public function edit(){
        if (IS_POST){
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Pay/edit');
                return;
            }
            $sqluser = substr($_POST['user'], 0, 3);
            $sqlname = ''.$sqluser.'_members';
            $sqlorder = ''.$sqluser.'_order';
            $Iosf = M($sqlname);
            $Iosf->startTrans();
            if($Iosf->where('user="'.$_POST['user'].'"')->setInc('coin',$_POST['coin'])){
                $arr['state'] = 9 ;
                if (M($sqlorder)->where('id="'.$_POST['id'].'"')->save($arr)){
                    $Iosf->commit();
                    $this->redirect("Pay/index");
                }else{
                    $Iosf->rollback();
                    $this->redirect("Pay/edit");
                }
            }else{
                $Iosf->rollback();
                $this->redirect("Pay/edit");
            }
        }else{
            $user =I('get.user');
            if(preg_match("/^1[34578]\d{9}$/", $user)){
                creatToken();
                $id = intval(I("get.i",0,'addslashes'));
                if($id==0){
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("参数错误！"); </script>';
                    echo "<script> window.location.href='".U('Pay/index')."';</script>";
                    exit();
                }else{
                    $sqluser = substr($user, 0, 3);
                    $sqlname = ''.$sqluser.'_members';
                    $sqlorder = ''.$sqluser.'_order';
                    $sqllist = M($sqlname)->where('user='.$user)->find();
                    $orderlist = M($sqlorder)->where("user='%s' AND id=%d",array($user,$id))->find();
                    $this->assign('order',$orderlist);
                    $this->assign('data',$sqllist);
                    $this->display('');
                }
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("参数错误！"); </script>';
                echo "<script> window.location.href='".U('Pay/index')."';</script>";
                exit();
            }
        }
    }

    function dousercash(){
        $OrderUser  = I("post.OrderUser");
        if(preg_match("/^1[34578]\d{9}$/", $OrderUser)) {
            $OrderID    = I("post.OrderId");
            $OrderID=_safe($OrderID);
            $order_where['user'] = $OrderUser;

            $order_where['id']   = $OrderID;

            $table_prefix = substr($OrderUser,0,3);

            M()->startTrans();

			$order_info = M($table_prefix."_order")->where($order_where)->find();
			
            if($_POST['OrderType'] == 1){               
                if(!$order_info){
                    $err_msg     = "没有该订单。。";
                    $data['status'] = -1;
                    $data['msg']    = $err_msg;
                    echo  json_encode($data);
                    exit;
                }
				$res_member = 1;
				$res_gold   = 1;

                $save_data['pay_time'] = time();
                $save_data['state']    =  1;

                $success_msg = "完成该单确认。";
                $err_msg     = "该单确认未完成，请稍后重试。";
                $button      = "<span>订单已完成</span>";
            }else{
                $save_data['pay_time'] = time();
                $save_data['state']    =  2;
                $success_msg = "完成改单取消。";
                $err_msg     = "该单取消未完成，请稍后重试。";
                $button      = "<span>订单已取消</span>";
							

				if($order_info['money']<=499){
					$order_info['hook'] = ($order_info['money']-($order_info['money']*0.029));
				}else if($order_info['money']<=999 && $order_info['money']>=500){
					$order_info['hook'] = ($order_info['money']-($order_info['money']*0.025));
				}else{
					$order_info['hook'] = ($order_info['money']-($order_info['money']*0.02));
				}
							
				$res_member = M($table_prefix."_members")->where("user='".$order_info['user']."'")->setInc("coin",$order_info['hook']);
		
				/***
				 *  增加修改 用户金币表中 佣金金币 或 果实买卖
				 */ 		 
				
				$res_gold   = M($table_prefix."_users_gold")->where("user='".$order_info['user']."'")->setInc("user_fees",$order_info['hook']);		
						
            }

            $res_order  = M($table_prefix."_order")->where($order_where)->save($save_data);		
		
            if($res_order & $res_gold & $res_member){
                M()->commit();
                $data['status'] = 1;
                $data['msg']    = $success_msg;
                $data['button']= $button;
            }else{
                M()->rollback();
                $data['status'] = -1;
                $data['msg']    = $err_msg;
            }
        }else{
            $data['status'] = -1;
            $data['msg']    ='参数错误';
        }
        echo  json_encode($data);
    }

	// 获取新的提现订单
	function get_new_cash(){
		// echo 1;
		$stime=microtime(true); 
		$cash_where['pay_cash']	  = 	2;			
		$cash_where['state']	  = 	0;
		// $cash_where['']	  = 	"0";
			
		$all_tables  =  M("statistical")->order("name asc")->select();

		$field = "";
		$table = "";
		
		$first_table = $all_tables[0]['name']."_order";
		
		unset($all_tables[0]);
		
		$where  = " and `pay_cash`='2' and `state`='0'  ";
		// echo 2;
		// 拼接 sql；
		foreach($all_tables as $val){
			
			
			
			// $field   =  $val['name']."_order.*  ";
			// $table   =  $val['name']."_order ";
			// $where  = " and `pay_cash`='2'  ";
			
			// $sql = "select " . $field . " from " . $table ." where 1=1 ".$where . "";
			
			$total		 = M($val['name']."_order")->where($cash_where)->find();			// 获取总额
			// ECHO M($val['name']."_order")->getLastSql();
			// echo ";\n<br/>";
			//die;
			if($total){	echo 1;die; }
					
			// $union[] = $sql;
			
		}	
		
		// $all_sql =  M($first_table)->union($union,true)->field('*')->where("`pay_cash`='2' and `state`='0'")->buildSql();
		
		// $all_sql = substr($all_sql , 1 , -1);
		
					// ->table($first_table)
					  // ->union($union)
	  
		// $table = substr( $table , 0 , -1);
		// $field = substr( $field , 0 , -1);
		// $where = substr( $where , 0 , -1);
		
		// 组装sql
		// $sql = "select " . $field . " from " . $table ." where 1 ".$where;//. " where pay_cash=1 and state=0";
		
		
		// $list = M()->query($all_sql);
		
		// echo $sql;
		// echo "\n <br/>";
		// echo M()->getLastSql();
		// echo "\n <br/>";
		// echo $table;
		// $etime=microtime(true); 
		// print_r($list);
		// echo count($list);
		// echo "\n <br/>";
		// echo $stime;
		// echo "\n <br/>";
		// echo $etime;
		// echo "\n <br/>";
		// echo $etime - $stime;
	}
		
	function pay_all(){
		
		require_once("ThinkPHP/Common/init.php");
		
		if(!empty($_GET['start_user'])){
			$pay_where[substr($_GET['start_user'],0,3).'_order.user']	  = 	addslashes($_GET['start_user']);	
			$where_statistical['name'] = substr($_GET['start_user'],0,3);
		}
				
		$all_tables  =  M("statistical")->order("name asc")->where($where_statistical)->select();
				
		/**
		 *   来一些花草
						   _(_)_                        wWWWw
			   @@@@       (_)@(_)  vVVVv    _     @@@@  (___)
			  @@()@@ wWWWw  (_)\   (___)  _(_)_  @@()@@   Y
			   @@@@  (___)     `|/   Y   (_)@(_)  @@@@   \|/
				/      Y       \|   \|/   /(_)    \|      |/
			 \ |     \ |/       | /\ | / \|/       |/    \|
			   |///  \\|/// \\\\|//\\|///\|///  \\\|//  \\|//
			^^^^^^ 百 ^^^^^^^^^^^^^ 草 ^^^^^^^^^^^^^^^ 集 ^^^^^
			
		 */
		 		 
		$start_time  =  empty($_GET['start_time'])  ? strtotime(date("Y-m-d"))  : strtotime($_GET['start_time']);
		$end_time  	 =  empty($_GET['end_time'])    ? $start_time + (3600 * 24) : strtotime($_GET['end_time']);
				
		$pay_where['pay_cash']	  = 	1;			
		$pay_where['state']	  	  = 	1;
		$pay_where['pay_time']    =  array("between",array($start_time,$end_time));	
				
		$Ali_pay_where 			  = $pay_where;
		$Three_Pay_where 		  = $pay_where;
		$Ali_pay_where['pay_bank']= 992;
		
		$Three_Pay_where['pay_bank']= array("in","9999,1004");
		
		$field  	 = "sum(pay_money) as money,count(id) as count ";
		
		$cash_field  = "sum(money) as money,count(id) as count ";
		
		$cash_where  			  =  $pay_where;
		$cash_where['pay_cash']	  =  2;	
		// $cash_where['add_time']	  =  $pay_where['pay_time'];			
		
		$all_list 	 = array();
		$all__money  = 0;
		$all__number = 0;
		
		$pay_time = $cash_where;
		unset($pay_time['add_time'] , $pay_time['pay_time']	);
		$pay_time_data['pay_time']	  =  array("exp","add_time");
		
		$gift_where 			= $pay_where;
		$gift_where['is_gift']  = 1;
		$gift_total = array();
		// print_r($gift_where);
		foreach($all_tables as $val){
			
			$list 		 = array();
			
			$total		 = M($val['name']."_order")->field($field)->where($pay_where)->find();			// 获取充值总额
			
			$gift_join  = " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$val['name']. "_order.user ";
					
			$gift_total_list	 = M($val['name']."_order")->field($val['name']."_order.*,".$val['name']."_members.name")->join($gift_join)->where($gift_where)->select(["index"=>"money",'index_array'=>true]);			// 获取充值总额
			// echo M($val['name']."_order")->getLastSql();die;
			$cash_total	 = M($val['name']."_order")->field($cash_field)->where($cash_where)->find();	// 获取提现总额
			
			$gift_total = array_merge_recursive($gift_total,$gift_total_list);
			// print_r($gift_total);
			// 将已完成的提现单pay_time 加上
			// $sql =  M($val['name']."_order")->where($pay_time)->fetchsql(true)->save($pay_time_data);	// 获取提现总额
		
			// $sql .= " and pay_time is null";
			// M($val['name']."_order")->execute($sql);
		
			// echo ";\n<br/>";
			
			$total_wechat= M($val['name']."_order")->field($field)->where($Three_Pay_where)->find();	// 微信  、 扫码
			
			$total_ali	 = M($val['name']."_order")->field($field)->where($Ali_pay_where)->find();		// 支付宝
			
			$list		 = $list ? $list : array();
			
			$all_reult['all_money']  	 += $total['money'];
						
			$all_reult['all_cash_money'] += $cash_total['money'];
			
			$all_reult['all_number'] 	 += $total['count'];
			
			$all_reult['all_cash_number']+= $cash_total['count'];
			
			$all_reult['wechat_money']   += $total_wechat['money'];
			
			$all_reult['wechat_number']  += $total_wechat['count'];
			
			$all_reult['ali_money']  	 += $total_ali['money'];
			
			$all_reult['ali_number'] 	 += $total_ali['count'];
							
		}			
		// print_r($gift_total);
		$all_reult['800']  	   	  = $gift_total['800.00000'];
		$all_reult['800_number']  = count($gift_total['800.00000']);
		$all_reult['800_money']   = $all_reult['800_number'] * 800 ;
		
		$all_reult['1500'] 	      = $gift_total['1500.00000'];
		$all_reult['1500_number'] = count($gift_total['1500.00000']);
		$all_reult['1500_money']  = $all_reult['1500_number'] * 1500 ;
				
		// print_r($gift_total['5700.00000']);
		$this->assign('start_user'	,$_GET['start_user']);
		$this->assign('all_reult'	,$all_reult);
		$this->assign('start_time'	,$start_time);
		$this->assign('end_time'	,$end_time);

		$this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support);

        $this->display();

	}
		
	function record_download(){
		
		require_once("ThinkPHP/Common/init.php");
		
		$all_tables  =  M("statistical")->order("name asc")->select();
	
		/**
		 *   来一些花草
						   _(_)_                        wWWWw
			   @@@@       (_)@(_)  vVVVv    _     @@@@  (___)
			  @@()@@ wWWWw  (_)\   (___)  _(_)_  @@()@@   Y
			   @@@@  (___)     `|/   Y   (_)@(_)  @@@@   \|/
				/      Y       \|   \|/   /(_)    \|      |/
			 \ |     \ |/       | /\ | / \|/       |/    \|
			   |///  \\|/// \\\\|//\\|///\|///  \\\|//  \\|//
			^^^^^^ 百 ^^^^^^^^^^^^^ 草 ^^^^^^^^^^^^^^^ 集 ^^^^^
			
		 */
		 		 
		$start_time    =  empty($_GET['start_time'])  ? strtotime(date("Y-m-d"))  : strtotime($_GET['start_time']);
		$end_time  	   =  empty($_GET['end_time'])    ? $start_time + (3600 * 24) : strtotime($_GET['end_time']);
		$pay_cash  	   =  empty($_GET['pay_cash'])    ? 1 : $_GET['pay_cash'];
		$pay_state 	   =  !isset($_GET['pay_state'])  ? 1 : $_GET['pay_state'];
		$pay_cash_lang = $pay_cash==1 ? "充值" 		 :  "提现";
		$phpexcel_name = $pay_cash==1 ? "export_pay" :  "export_cash";
		$pay_where     = "  `pay_cash`='{$pay_cash}'  ";
		
		$pay_where    .= " and state = " . $pay_state;
		$pay_cash_lang =  ( $pay_state==2 )    ?   "未完成" . $pay_cash_lang :  "已完成" . $pay_cash_lang ; ;
		$pay_where    .=  $pay_cash==1 ?  " and `pay_time` between {$start_time} and {$end_time}" : " and `add_time` between {$start_time} and {$end_time}" ;	
			// echo $_GET['pay_state'];die;
		
		$Ali_pay_where 			  = $pay_where;
		$Three_Pay_where 		  = $pay_where;
		
		if($pay_cash == 1){
			$Ali_pay_where    .=  " and pay_bank=992";		
			$Three_Pay_where  .=  " and pay_bank in (9999,1004)";						
		}else{
			$Ali_pay_where    .=  " and pay_bank like '%支付宝%'";		
			$Three_Pay_where  .=  " and pay_bank like '%银行%'";	
			// 增加 支付宝单独提现导出
			if($phpexcel_name == 'export_cash'  && $_GET['export_type'] == 2){
				$phpexcel_name = 'export_ali_cash';
			}
		}	
		
		if($_GET['record_export'] == 'record_export'){
									
			$all_tables  =  M("statistical")->order("name asc")->select();
		
			$first_table = $all_tables[0]['name']."_order";
			
			$first_field = $all_tables[0]['name']."_order.* , ".$all_tables[0]['name']."_members.name  ";
			// $first_field = "*";
			
			$first_join  = " right join ".$all_tables[0]['name']. "_members on ".$all_tables[0]['name']. "_members.user = ".$all_tables[0]['name']. "_order.user ";
			
			$first_where = $pay_where;
			
			if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
				$user_where  = " and %s.user = '" . addslashes($_GET['start_user']) . "'";
				$first_where	.=  sprintf($user_where,$first_table) ;
			}

			unset($all_tables[0]);					
			
			// 拼接 sql；
			foreach($all_tables as $val){
				
				$field   =  $val['name']."_order.* , ".$val['name']."_members.`name`  ";
				
				$table   =  $val['name']."_order";
				$join    =  " right join ".$val['name']. "_members on ".$val['name']. "_members.user = ".$val['name']. "_order.user ";
				
				$sql 		= "select " . $field . " from " . $table . $join ." where  ".$pay_where . sprintf($user_where,$table) ." ";
				$ali_sql 	= "select " . $field . " from " . $table . $join ." where  ".$Ali_pay_where . sprintf($user_where,$table) ." ";
				$three_sql  = "select " . $field . " from " . $table . $join ." where  ".$Three_Pay_where . sprintf($user_where,$table) ." ";
				
				$union[]       = $sql;			
				$ali_union[]   = $ali_sql;			
				$three_union[] = $three_sql;			
			}	
			
			switch ($_GET['export_type']){
				case 1:
					$export_type  =  "全部";
					$export_list  =  M($first_table)->join($first_join)->union($union,true)->field($first_field)->where($first_where)->select();
					break;
				case 2:	
					$export_type  =  "支付宝";
					$export_list  =  M($first_table)->join($first_join)->union($ali_union,true)->field($first_field)->where($Ali_pay_where)->select();
					break;
				case 3:	
					$export_type  =  "银行";
					$export_list  =  M($first_table)->join($first_join)->union($three_union,true)->field($first_field)->where($Three_Pay_where)->select();
					break;
			}
			
			// print_r(M($first_table)->getLastSql());die;
			$excel_titel  =  date("y-m-d",$start_time) . "到" . date("y-m-d",$end_time) . $export_type . $pay_cash_lang . "表";
			$this->record_export($excel_titel,$phpexcel_name,$export_list);
			
		}else{
				
			$field  = "count(id) as count ";		
			
			foreach($all_tables as $val){
				
				$list 		 = array();
				
				$total		 = M($val['name']."_order")->field($field)->where($pay_where)->find();			// 获取总额
				
				$total_wechat= M($val['name']."_order")->field($field)->where($Three_Pay_where)->find();			// 微信  、 扫码
				
				$total_ali	 = M($val['name']."_order")->field($field)->where($Ali_pay_where)->find();			// 支付宝
		
				$list		 = $list ? $list : array();						
			
				// $all_reult['all_money']  	+= $total['money'];
				
				$all_reult['all_number'] 	+= $total['count'];
				
				// $all_reult['wechat_money']  += $total_wechat['money'];
				
				$all_reult['wechat_number'] += $total_wechat['count'];
				
				// $all_reult['ali_money']  	+= $total_ali['money'];
				
				$all_reult['ali_number'] 	+= $total_ali['count'];
								
			}	
// echo $pay_where;
// echo "\n";
// echo $Three_Pay_where;
// echo "\n";
// echo $Ali_pay_where;
			if($_GET['is_ajax']  == 1){			
				echo json_encode($all_reult);
				die;
			}
			
		}

		$this->assign('all_reult'	,$all_reult);
		$this->assign('start_time'	,$start_time);
		$this->assign('end_time'	,$end_time);

		$this->assign('shunfoo_banktype_now_support',$shunfoo_banktype_now_support);

        $this->display();

	}
		
	private function record_export($excel_titel,$phpexcel_name,$export_list){

		require  "./ThinkPHP/Library/Vendor/PhpExcel/Download/".$phpexcel_name.".php" ;
	
	}
	
	// 批量确认
	function batch_order(){
		
		$batch_id = $_POST['OrderId'];
		
		$result['success'] = 0;
		$result['error']   = 0;
		
		foreach($batch_id as $val){
			$id_user = array();
			$id_user = explode("_",$val);
			
			$table_prefix       = intval(substr($id_user[1],0,3));
			
			$order_where['id']  	= intval($id_user[0]);
			$save_data['state']		= 1;
			$save_data['pay_time']  = time();
			$do_batch_sql = M($table_prefix."_order")->where($order_where)->save($save_data);	
			
			
			
			($do_batch_sql > 0) ? $result['success']++ : $result['error']++;
			
		}
		
		
		echo json_encode($result);
		die;
		
	}

	function groupuser(){
	
		//查询 team_relationship  team 字段大于5的用户
		
		if(!($User_Array_String = S("User_Array_String")) ){			
			
			$team_List   = M("team_relationship")->where("LENGTH(team) > 46")->select();
			
			$User_Array    = Array();
			
			foreach($team_List as $val){
				
				$User_Array[]  =  $val['user'];
				$User_Array = array_unique(array_filter(array_merge($User_Array,explode(" ",$val['team']))));

			}

			$User_Array_String = implode("," , $User_Array);
		
			S("User_Array_String" ,$User_Array_String  , 3600 * 3) ;
		
		}
		
		//  获取用户
		$where_team['user'] = array("not in",$User_Array_String);
		
		if(isset($_GET['start_user']) && !empty($_GET['start_user']) ){
           //  获取用户
			$where_team['user'] = $_GET['start_user'];
        }
				
		$page = intval(I("get.p",1,'int'));
		$num  = 100000;
		
		$Team_List_User = M("team_relationship")->field("*")->where($where_team)->order('user asc')->limit(($page-1)*$num,$page*$num)->select();
				
		$field_pay  = "sum(money) as pay_money ";
		$field_cash = "sum(money) as cash_money ";
		$balance    = 0;
		// 获取订单
		foreach($Team_List_User as $key=>$val){
			
			$pre_fix  = substr($val['user'],0,3);
			$Team_List_User[$key]['name']  = M($pre_fix."_members")->where('user = "'.$val['user'].'" ')->getfield("name");
			$Team_List_User[$key]['pay_money']  = M($pre_fix."_order")->where('user = "'.$val['user'].'" and pay_cash=1 and state=1')->getfield($field_pay);
			$Team_List_User[$key]['cash_money'] = M($pre_fix."_order")->where('user = "'.$val['user'].'" and pay_cash=2 and state=1')->getfield($field_pay);
		
			if( $Team_List_User[$key]['pay_money'] >= $Team_List_User[$key]['cash_money'])  unset($Team_List_User[$key]);
			else  $Team_List_User[$key]['balance'] =  $Team_List_User[$key]['cash_money'] - $Team_List_User[$key]['pay_money']; $balance += $Team_List_User[$key]['balance'];
		}
		

		// var_dump(($Team_List_User));
		
		$this->assign('Team_List_User',$Team_List_User);
		
		$this->assign('balance',$balance);
		
		$this->assign('count',count($Team_List_User));
		
        $this->display();
		
		die;
	}
	
	
	function no_referees(){
		
		//查询 team_relationship  team 字段大于5的用户
		if(!($User_Referees_String = S("User_Referees_String")) ){			
			
			$team_List   = M("team_relationship")->where(" referees is not null and referees<>'' ")->field("referees")->select();
			
			$User_Array    = Array();
			
			foreach($team_List as $val){
				
				$User_Array[]  =  $val['referees'];
				
			}

			$User_Referees_String = implode("," , $User_Array);
		
			S("User_Referees_String" ,$User_Referees_String  , 3600 * 3) ;
		
		}
		
		$page = intval(I("get.p",1,'int'));
		$num  = 10;
		
		$where['user']     = array("not in" , $User_Referees_String );
		$where['referees'] = array("exp","=''");
		
		//->limit(($page-1)*$num,$page*$num)
		$Team_List_User = M("team_relationship")->where($where)->order("level desc")->select();			
					
		$field_pay  = "sum(money) as pay_money ";
		$field_cash = "sum(money) as cash_money ";
		$balance    = 0;
		// 获取订单
		foreach($Team_List_User as $key=>$val){
			
			$pre_fix  = substr($val['user'],0,3);
		
			$Team_List_User[$key]['name']       = M($pre_fix."_members")->where('user = "'.$val['user'].'" ')->getfield("name");
			$Team_List_User[$key]['pay_money']  = M($pre_fix."_order")->where('user = "'.$val['user'].'" and pay_cash=1 and state=1')->getfield($field_pay);
			$Team_List_User[$key]['cash_money'] = M($pre_fix."_order")->where('user = "'.$val['user'].'" and pay_cash=2 and state=1')->getfield($field_pay);
		
			if( $Team_List_User[$key]['pay_money'] >= $Team_List_User[$key]['cash_money'])  unset($Team_List_User[$key]);
			else  $Team_List_User[$key]['balance'] =  $Team_List_User[$key]['cash_money'] - $Team_List_User[$key]['pay_money']; $balance += $Team_List_User[$key]['balance'];
		
		}
		

		$this->assign('Team_List_User',$Team_List_User);
		
		$this->assign('balance',$balance);
		
		$this->assign('count',count($Team_List_User));
		
        $this->display();
		
		
	}
	
}














// that  is  all

//  BY  QHP   2017年8月21日16:19:27
