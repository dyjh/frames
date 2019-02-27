<?php
namespace Home\Controller;
use Think\Controller;
use Org\Our\Account;
use Org\Our\Autoadd;
use Org\Our\Matching;
use Org\Our\Pay;
use Org\Our\Deal;
use Think\Verify;
use Think\Model;
use Think\Qrcode;
use Think\Find;
use Home\Model\FruitRecordModel;
class UserController extends HomeController {

    private $limit 		=  10;   //  限制可以查看的数据条数
	
    private $coin_rate  =  100;  //  金币换钻石
		
    protected  $IsOpenTokenVerify = true;
	  
	  
	function reset_user_cash(){

		$all_tables  =  M("statistical")->order("name asc")->select();
		
		$first_table_name = $all_tables[0]['name'];

		/** 金币*/
		$first_coin_table = $first_table_name."_members";
		
		// 使用mysql if  语句	
		$first_coin_field =  $first_table_name. "_members.user,"					
						 .  " (`coin`+`coin_freeze`) as  coin , " . $first_table_name."_users_gold.`buy_and_sell` ," 
						 . " " . $first_table_name."_users_gold.`user_fees` , " . $first_table_name."_members.`num_id` ";		

		$first_coin_join  = " right join ".$first_table_name. "_users_gold on ".$first_table_name. "_members.user = ".$first_table_name. "_users_gold.user ";

		$coin_where = "1=1 and ((" . $first_table_name."_users_gold.user_fees + " . $first_table_name."_users_gold.buy_and_sell) > (" . $first_table_name."_members.`coin`+ " . $first_table_name."_members.`coin_freeze` ) )";
		
		/** 金币*/

		unset($all_tables[0]);					
		
		// 拼接 sql；
		foreach($all_tables as $val){
					
			/** 金币*/
			$coin_field  =   $val['name']. "_members.user,"					
						 . " (`coin`+coin_freeze) as  coin  , " . $val['name']."_users_gold.`buy_and_sell` ," 
						 . " " . $val['name']."_users_gold.`user_fees` , " . $val['name']."_members.`num_id` ";
		
		
			$coin_join  = " left join ".$val['name']. "_users_gold on ".$val['name']. "_members.user = ".$val['name']. "_users_gold.user ";

			$coin_sql = "select " . $coin_field . " from " . $val['name']."_members" . $coin_join ." where ((" . $val['name']."_users_gold.user_fees + " . $val['name']."_users_gold.buy_and_sell) > (" . $val['name']."_members.`coin` + " . $val['name']."_members.`coin_freeze`) )";
			
			$coin_union[] = $coin_sql;	
			/** 金币*/
		}			
		
		$coin_list =  M($first_coin_table)->join($first_coin_join)->union($coin_union,true)->field($first_coin_field)->where($coin_where)->fetchSql(false)->select();
		PRINT_R($coin_list);
		foreach($coin_list as $val){
			
				$sql 			= "";
				$user_fees 		= 0;
				$buy_and_sell 	= 0;
				
				$table_name = substr($val['user'],0,3);		
				$coin_table	= $table_name . "_users_gold";			
	
				if($val['buy_and_sell']  >= $val['coin']  || $val['buy_and_sell'] >= $val['coin'] ){
					$buy_and_sell = $val['coin'];
					$user_fees    = 0 ;
				}else{
					$buy_and_sell = $val['buy_and_sell'];
					$user_fees    = $val['coin'] - $val['buy_and_sell'] ;
				}
				
				
				$sql = "Update $coin_table set num_id='".$val['num_id']."' , `buy_and_sell`=$buy_and_sell , user_fees=$user_fees where user='".$val['user']."'";
				
					$res[] = $sql;
				M()->execute($sql);
				
		}
		if(_USERTEL_ == '18780164595'){
			PRINT_R($res);
		}
	
		
		
	}  
	  	  
	function move_team_other(){
		
		$Alert  = new \Think\Alert();
		
		$move_user  = $_GET['move_user'];//"13262076769";  	//  被移动账户
		$get_user   = $_GET['get_user']; //"18780164595";   //  接受团队用户
		
		
		echo $Alert->replace($get_user,$move_user);
		
	}
	 
	function create_table(){
		
		$all_table = M("statistical")->order("name")->select();
		
		$table_name = "_" . $_GET['table_name'];

		foreach($all_table as $key=>$val){			
			$table_pre = $val['name'];			
				$sql = '
					ALTER TABLE `'.$val['name'].'_users_gold`

MODIFY COLUMN `user_fees`  double(20,5) NOT NULL DEFAULT 0.00000 AFTER `num_id`,
MODIFY COLUMN `buy_and_sell`  double(20,5) UNSIGNED NOT NULL DEFAULT 0.00000 AFTER `user_fees`,
MODIFY COLUMN `user_top_up`  double(20,5) UNSIGNED NOT NULL DEFAULT 0.00000 AFTER `buy_and_sell`,
ADD COLUMN `static_gold`  double(20,5) NOT NULL DEFAULT 0 AFTER `user_top_up`,
ADD COLUMN `dynamic_gold`  double(20,5) NOT NULL DEFAULT 0 AFTER `static_gold`;


					';
			echo $sql;
			echo "\n<br/>";
			// echo M()->execute($sql);
			// echo "\n<br/>";
			// echo M()->getDbError();
			// echo "\n";
		}					
	}

	//  获取用户团队	
	function GetAllTeam($AllMembers,$user_info,$get_arr=array()){

        extract($get_arr);

        $referees = $user_info['referees'];
		
        $TeamList = "";
        // $phoneid = array_search($tel,$AllPhone);
		
        foreach($AllMembers as $key=>$val){
			
            if( $val['user'] == $referees ){
				
                $TeamList = $val['user'] ." " .$TeamList;
                if($val['referees']){
                    $TeamList = $this->GetAllTeam($AllMembers,$val,array("get_parent"=>1,"get_self"=>0)) .  $TeamList;
                }
            }
        }

        return $TeamList;
    }
  
	function update_one_team(){
		
		$one_people 	=   $_GET["man"];
		
		$start		 	=   $_GET["start"];
		
		$end 			=   $_GET["end"];
		
		$AllMembers = $this->get_all_members();
		
		$user_list  =  M(substr($one_people,0,3)."_members")->limit($start,$end)->select();					 
		
	// print_r(M(substr($one_people,0,3)."_members")->getLastSql());
		foreach($user_list as $user_info){
			
			$TeamList = '';
			
			$data['team'] = "";		

			$where['user'] = $user_info['user'];

			if($user_info['referees']){

				$TeamList = $this->GetAllTeam($AllMembers,$user_info,array("get_parent"=>1,"get_son"=>0,"get_self"=>1));

				$data['team'] = $TeamList;

			}
			
			echo $user_info['user'] . "--------" . $TeamList;
			
			echo M(substr($user_info['user'],0,3)."_members")->where($where)->save($data);
			
			echo "<br/>";
			
		}
		
	}

	//  获取所有用户
	function get_all_members(){
		
		$FriendRecom = new \Org\Our\FriendRecom();

        $AllMembersArray = $FriendRecom->GetAllMembersArray();

		return $AllMembersArray;
	}

	//  获取所有用户
	function get_all_members_test(){
		
		$FriendRecom = new \Org\Our\FriendRecom();

        $AllMembersArray = $FriendRecom->test_all();
	
		print_r(($AllMembersArray));

	}
	
	function move_move(){
			$a=$_GET['a'];
			$all_tables  =  M("statistical")->order("name asc")->select();
		
			$first_table = $all_tables[0]['name']."_members";
			
			unset($all_tables[0]);	

			$where       = "referees  = '' ";
			
			// 拼接 sql；
			foreach($all_tables as $val){
				$field   =  $val['name']."_members" .  ".user";
				$table   =  $val['name']."_members";
				
				$sql 		= "select " . $field . " from " . $table ." where  ".$where  ." ";
				
				$union[]       = $sql;			
					
			}	
			
			$list  =  M($first_table)->union($union,true)->field("user")->where($where)->select();
			
			$list  =  array_slice($list , 0 , 100);
			
			foreach($list as $key=>$val){
				$tables   =  substr($val['user'],0,3)."_members";
				$wheres   =  "user = '".$val['user']."'";
				$sql 	  = "update " . $tables ." set referees='{$a}' where  ".$wheres  ." ";
				echo M()->execute($sql);
				echo "\n <br/>";
				
			}

		
	}
	
	///  批量更新团队  传入 表名
    function index_aaa(){
		
        $string = $_GET['string'];

        $arr = explode("_",$string);
        $count = count($arr);
        $a=0;
		// print_r($arr);
		
        for($i=0; $i < $count ;$i++){
           // echo $i;
           // print_r($arr[$i]);
          $q =  $this->update_team($arr[$i]);
          $a+=$q;
        }									
        echo $a;
    }

	///  批量更新团队
    function update_team($table){
        header("charset=utf-8");
        $table .= "_members";
        $FriendRecom = new \Org\Our\FriendRecom();

        $AllMembersArray = $FriendRecom->GetAllMembersArray();

           $All = M($table)->where("user<>''")->field("user,tel,level,team,referees,num_id")->limit()->select();

        $q=0;
        foreach($All as $key=>$val){
			
			
			$TeamList = '';
			$data['team'] = "";		

			$where['user'] = $val['user'];

            if($val['referees']){

                $TeamList = $this->GetAllTeam($AllMembersArray,$val,array("get_parent"=>1,"get_son"=>0,"get_self"=>1));

                $data['team'] = $TeamList;

            }
			
				echo $val['user'] . "--------" . $TeamList;
			
			echo M(substr($val['user'],0,3)."_members")->where($where)->save($data);
			
			echo "<br/>";
			
            $q++;
        }

        return $q;

    }
		
    public function _initialize(){
        //先运行一次父类的构造方法
        //判断是否登录

        $varify_login = array("index","market_detail","pay","entrust","promote","user_info");

        if(in_array(ACTION_NAME,$varify_login) ){
            if(!session('?login') ){
                $this->error('您没有登录，请先登录',U('User/lyologin'),3);
            }
        }

        $this->assign('nav_titels',"用户中心");

    }
		
	function flush_pay(){
		
		$all_list = M("2017-07_rebate_record")->select();
		
		foreach($all_list as $key=>$val){
			
			$order_list[$val['user']]['money']  += $val['money'];
				
		}
		
		print_r($all_list);
		
	}
	  		
	function flush_refress(){
		
		if(! is_mobile($_POST['referees'])){
			$this->error("您的上级用户号码不正确。");
		}
		
		if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify){
			// 令牌验证错误
			$this->redirect("User/user_info");
		}
		
		$save_data['referees'] = $_SESSION['login']['referees'] = $_POST['referees'];
		
		
		
		$res = M(substr($_SESSION['login']['referees'],0,3)."_members")->where("user='"._USERTEL_."'")->save($save_data);
		
		$this->success("更新成功",U('User/user_info'));
	}
	
	function user_info(){	
	
		$prop_warehouse_where['props'] 	= '种子';
		$prop_warehouse_where['user'] 	= _USERTEL_;
					
		$y = date("Y");		
		$m = date("m");		
		$d = date("d");
	
		$s_data=M('Global_conf')->where('cases="start_time"')->find();
		$e_data=M('Global_conf')->where('cases="end_time"')->find();
		$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
		$start_t=$start-3600*24;
		$time=time();
		
		$end = mktime($e_data['value'],0,0,$m,$d,$y);
		$end_t=$end-3600*24;
		$num = substr(_USERTEL_,0,3);
		$user = _USERTEL_;
		$case_seed= ''.$num.'_seed_warehouse';
		$money_user=0;
		$seed_where['varieties']  = array("not in",array("摇钱树","种子","蓝莓","榴莲"));
		$seed_data=M('Seeds')->field('id,open_price,first_price,varieties')->where($seed_where)->select();
		
		$fruit=array();

		foreach($seed_data as $key=>$val){
			$case_m=''.date('Y-m').'_matching';
			$data_today=M(''.$case_m.'')->field('money')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$val['varieties'].'"')->order('time DESC')->select();
			$money=M('Pay_statistical')->where('seed ="'.$val['varieties'].'"')->field('end_money')->order('time DESC')->select();
			if($data_today){
				$today['new']=$data_today[0]['money'];
			}else{
				if($v['open_price']!=0){
					$today['new']=$val['open_price'];
				}else{ 
					if(!$money){
					   $today['new']=$val['first_price'];
					}else{
					   $today['new']=$money[0]['end_money'];
					}
				}
			}
			$seed_num=M($case_seed)->where('user = '.$user.' AND seeds ="'.$val['varieties'].'"')->find();
			$total=$seed_num['num']*$today['new'];
			$money_user+=$total;
			$fruit[$val['id']]['seed']=$val['varieties'];
			$fruit[$val['id']]['now_money']=$today['new'];
			$fruit[$val['id']]['total']=$total;		
		}	
		
		// $prop_warehouse_join  =  " right join shop on shop.name='种子' ";
		
		// $need_field  		  =  "price , " . substr(_USERTEL_,0,3) . "_prop_warehouse.* ";		
		
		$prop_price = M($case_m)->where('seed="种子"')->order('time DESC')->getfield('money');
					
		$prop_info = M(substr(_USERTEL_,0,3) . "_prop_warehouse")->where($prop_warehouse_where)->find();
			
		$prop_info['all_price']   = ( $prop_info['num'] * $prop_price ) ;		// 更改为金币价值
		
		$this->assign('prop_info',$prop_info);
		
		$house_where['level']  = array("elt",$_SESSION['login']['level']);
		$house_data 		   = M('house')->where($house_where)->select();
		$house_data 		   = material_handle_list(($house_data));
		
		foreach($house_data  as $val){
			foreach($val as $key=>$vo){
				if(is_numeric($key)){
					$material_list[$key] += $vo;
				}
			}
		}
		
		$material_data=M('house_material')->select();
		$material_data = material_handle_list(($material_data));
		
		foreach($material_data  as $val){
			foreach($val as $key=>$vo){
				if(is_numeric($key)){
					$cost_list += $vo * $fruit[$key]["now_money"] * $material_list[$vo['id']];
				}
			}
		}
		
		$fruit['land']['seed']   = '土地资产';
		$fruit['land']['total']  = $cost_list;
		$money_user += $cost_list;
		// print_r($material_list);
		
		// **   增加上种子资产
		
		$money_user +=  $prop_info['all_price']  ;
		
		$this->assign('fruit',$fruit);
		$this->assign('money',$money_user);
		
		// 加上土地资产
				
		// $son_list = array();
        // $son_list[0]['title']     = "个人中心";
        // $son_list[0]['i_class']   = "ti-comments";
        // $son_list[0]['url']       = U('User/user_info');
			
		$referees_info = M(substr($_SESSION['login']['referees'],0,3)."_members")->where("user='".$_SESSION['login']['referees']."'")->find();	

		if(( ! $team_list = S(_USERTEL_ . "_team_list") )||   $_GET['reflush']==1 ){
			
			$team_list = array();
		
			$team_info = M(substr(_USERTEL_,0,3)."_members")->where("user='"._USERTEL_."'")->field('team')->find();	
			
			$FriendRecom = new \Org\Our\FriendRecom();
			
			$AllMembersArray = $FriendRecom->GetAllMembersArray();
			
			foreach($AllMembersArray as $key=>$val){
				if( strstr($val['team'],_USERTEL_) && $val ){
					$team_list[] = $val ;				
				}	
			}	
			
			S(_USERTEL_ . "_team_list" , $team_list , 12*3600);
			
		}
		
		$team_list = array_filter($team_list);		
		
		$HighLevel = 1;
		foreach($team_list as $k=>$val){
			
			//  直推用户
			if($val['referees'] ==  _USERTEL_ ){
				
				$Direct_drive_list[] =  $val;
				
			}
			
			if($val['level'] >= 6) $TeamLevelList[$val['level']]++ ;
			// if($val['level'] >= 6) $TeamLevelList[$val['level']]++ ;
	
			
		}	

		krsort($TeamLevelList);
		
		$team_num = count( $team_list );
		
		$this->assign('TeamLevelList',$TeamLevelList);
		// $this->assign('son_nav',$son_list);
		$this->assign('referees_info',$referees_info);
		$this->assign('team_num',$team_num);
		$this->assign('Direct_drive_list',$Direct_drive_list);
		// $this->display();
	}
	
	public function caifu(){
		if(IS_AJAX){
			 $y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");
            //print_r($m);die;
            //$tm =date("d")+1;
            $s_data=M('Global_conf')->where('cases="start_time"')->find();
            $e_data=M('Global_conf')->where('cases="end_time"')->find();
            $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天开盘的时间戳
            $start_t=$start-3600*24;
            $time=time();
            //print_r($start);die;
            $end = mktime($e_data['value'],0,0,$m,$d,$y);
            $end_t=$end-3600*24;
			$num = substr(_USERTEL_,0,3);
			$user = _USERTEL_;
			$case_seed= ''.$user.'_seed_warehouse';
			$money=0;
			$seed_data=M('Seeds')->field('open_price,first_price,varieties')->limit(0,6)->select();
			foreach($seed_data as $key=>$val){
				$case_m=''.date('Y-m').'_matching';
                $data_today=M(''.$case_m.'')->field('money')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$val['varieties'].'"')->order('time DESC')->select();
				$money=M('Pay_statistical')->where('seed ="'.$val['varieties'].'"')->field('end_money')->order('time DESC')->select();
				if($data_today){
					$today['new']=$data_today[0]['money'];
				}else{
					if($v['open_price']!=0){
						$today['new']=$val['open_price'];
					}else{ 
						if(!$money){
						   $today['new']=$val['first_price'];
						}else{
						   $today['new']=$money[0]['end_money'];
						}
					}
				}
				$seed_num=M($case_seed)->where('user = '.$user.' AND seed ="'.$val['varieties'].'"')->find();
				$total=$seed_num['num']*$today['new'];
				$money+=$today;
			}
			echo $money;
		}
	}
 
	public function index(){

	    // $son_list = array();
        // $son_list[0]['title']   = "个人中心";
        // $son_list[0]['i_class']   = "ti-comments";
        // $son_list[0]['url']     = U('User/user_info');
        
	
        //  查询用户的果实数量
        $member_pro = substr(_USERTEL_,0,3);

        $member_where['user'] = _USERTEL_;

        $SeedWareHouse = new FruitRecordModel($member_pro."_seed_warehouse");

        $seed_warehouse = $SeedWareHouse->get_user_seed_list($member_where,1);
		
		$prop_warehouse_where['props'] 	= '种子';
		$prop_warehouse_where['user'] 	= _USERTEL_;	
		
		$prop_info = M(substr(_USERTEL_,0,3) . "_prop_warehouse")->where($prop_warehouse_where)->find();			
		
		$this->assign('prop_info',$prop_info);
		
		// 获取用户果实  及 当前 果实价格		
		
        $this->assign('seed_warehouse',$seed_warehouse);
		
        // $this->assign('son_nav',$son_list);
		//活动期间推荐人数

		$user= _USERTEL_;
		$refree=M("team_relationship")->where('user='.$user.'')->find();
		$num=$refree['activity_info'];
		
		$this->assign('num',$num);

		

		$this->user_info();

        $this->display();

    }
		
	public function promote(){

		$num=substr($_SESSION['login']['user'],0,3);
		$case=''.$num.'_members';
		$data_m=M($case)->where('user='.$_SESSION['login']['user'])->find();
        $url=new Qrcode($data_m['num_id']);
		
        $data=$url->generate();
		
		$name=$data_m['nickname'];
		$str='推荐人：'.$name.'.';
        //echo $data;
		//print_r($data);die;
		$argu = get_sdk();
		$this->assign('argu',$argu);
		$this->assign('str',$str);
        $this->assign('url',$data);
        $this->display();
    }

    public function lyologin(){

        if (IS_POST) {
			$data_g=M('Global_conf')->where('cases="login"')->find();	
			if($data_g['value']==0){
				if($_POST['user']=='18228068397'||$_POST['user']=='18382050570'||$_POST['user']=='15802858094'||$_POST['user']=='18382050570'||$_POST['user']=='18584084806'||$_POST['user']=='18768477519'||$_POST['user']=='18780164595'){
			
				}else{
					// $this->error("系统维护，暂时无法登陆");
				}
			}
            if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify){
                // 令牌验证错误
                $this->redirect("User/lyologin");
            }
			
			if (!checkToken($_POST['TOKEN'])) {
					 $this->redirect('index/index');
					return;
			}

            if($_POST['VCode']){
                $code = new Verify();
                if(!$code->check($_POST['VCode'])){
                    $this->error("验证码错误");
                }
            }else{
                $this->error("请填写验证码");
            }

			if( ! is_numeric($_POST['user']) || !is_mobile($_POST['user']) ){
				 $this->error("用户名输入错误");
			}

            $login = new Account();
            $return_code = $login->User_Freeze($_POST);
			
			if( $_SESSION['prize']  == 1 ){
				$return_code = 2;
				unset($_SESSION['prize']);
			}

            switch($return_code){
                case 1:
                    $this->redirect("User/index");
                    break;
				case 2:
                    $this->redirect("Prize/index");
                    break;
                case -2:
                    $this->error("该账户涉嫌违规，已禁止登陆！");
                    break;
                default:
                    $this->error("账号或密码错误");
                    break;
            }
            die;


        } else {

           if(session('?login')){

                $this->redirect("User/index");
                die;

            }
			
			creatToken();

            $this->display();
        }

    }

    public function lyoregister(){

		 //$this->error("通道维护中！");
		
        if (IS_POST) {
			$data_g=M('Global_conf')->where('cases="regist"')->find();
			if($data_g['value']==0){
				if($_POST['user']=='18228068397'||$_POST['user']=='18382050570'||$_POST['user']=='15802858094'||$_POST['user']=='18382050570'||$_POST['user']=='18584084806'||$_POST['user']=='18768477519'||$_POST['user']=='18780164595'){
			
				}else{
					// $this->error("系统维护，暂时无法注册");
				}
			}
			/*if($_POST['user'] != '18780164595'){
				 $this->error("系统维护中，请稍后",U('User/lyoregister'));
			}*/
		
            if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify){
                // 令牌验证错误
                $this->redirect("User/lyoregister");
            }
			
			if (!checkToken($_POST['TOKEN'])) {
				$this->redirect('index/index');
				return;
			}
			
            if( ! is_numeric($_POST['user']) || !is_mobile($_POST['user']) ){
                $this->error("用户名输入错误");
            }

            if( $_POST['user'].$_POST['code'] != $_SESSION['Reg'] || md5($_POST['user'].$_POST['code']) != $_POST['ReMobileVCodePost'] || md5($_SESSION['Reg']) != $_POST['ReMobileVCodePost'] || !is_numeric($_POST['code']) ){

               $this->error("短信验证码错误，请重试",U('User/lyoregister'));

            }

            if( $_POST['password'] != $_POST['RePassword'] ){
               $this->error("两次密码不一致",U('User/lyoregister'));
            }

            if( ! is_idcard( $_POST['id_card'] ) ){
               $this->error("身份证不正确。",U('User/lyoregister'));
            }
			 $_POST['num_id'] = "";
			if($_POST['num_freeze']!=='' && !empty($_POST['num_freeze']) ){
				$referees=$_POST['num_freeze'];
				$for=M('statistical')->select();
				foreach($for as $k=>$v){
					$case=''.$v['name'].'_members';
					$data=M($case)->where('num_id='.$referees)->find();
					if($data){
						break;
					}
				}
				$_POST['referees']=$data['user'];
			}
	
            $_POST['level'] = 1;
            $Acc = new Account();
            $return_code = $Acc->Ver_Code($_POST);
		
            switch($return_code){
                case 1:
                    $this->success( '注册成功,跳转登录',U('User/lyologin'));
                    break;
                case 0:
                    $this->error("短信验证码错误",U('User/lyoregister'));
                    break;
                default:
                    $this->error("COODE:".$return_code."<BR/>注册失败,请重试。",U('User/lyoregister'));
                    break;
            }
            die;

        } else {
			creatToken();
			
            $user=$_GET['user'];
			/*if($user){
				$for=M('statistical')->select();
				foreach($for as $k=>$v){
					$case=''.$v['name'].'_members';
					$data=M($case)->where('num_id='.$user)->find();
					if($data){
						break;
					}
				}
			}*/
			//print_r($data);
            
			$this->assign('user',$user);
            
			

            $this->display();
        }
    }

    //验证码
    public function verify()
    {
        // 实例化Verify对象
        $verify = new \Think\Verify();
        // 配置验证码参数
        $verify->fontSize 	= 25;     // 验证码字体大小
        $verify->length 	= 4;        // 验证码位数
        $verify->imageH 	= 50;       // 验证码高度
        $verify->useCurve   = false;   // 是否使用混淆曲线
		$Verify->fontttf    = '5.ttf'; 	//验证码的字体
		$Verify->codeSet 	= '0123456789'; 
        $verify->useImgBg   = false;   // 开启验证码背景true
        $verify->useNoise   = false;  // 关闭验证码干扰杂点
        $verify->entry();
    }

    //ajax验证验证码
    public function check_code(){
        if(IS_AJAX){
            $yzm = I('post.yzm','','htmlspecialchars');  //接收数据
            $code = new Verify();
            if($code->check($yzm)){
                echo 1;
            }else{
                echo -1;
            }
        }else{
            echo 0;  //请求错误
        }
    }

    //ajax 验证身份证是否年满18
    public function IDcard(){
        if (IS_AJAX) {
            $card = I('post.IDcard','','addslashes');
            $Ycard = substr($card, 6, 4);
            $Mcard = substr($card, 10, 2);
            $Dcard = substr($card, 12, 2);

            $time = date('Y-m-d',time());

            $Ytime = substr($time, 0, 4);
            $Mtime = substr($time, 5, 2);
            $Dtime = substr($time, 8, 2);

            $cardtime = $Ytime - $Ycard;
			
			$Is_Idcard = is_idcard($card);
			if(!$Is_Idcard){
				echo 0;
				die;
			}

            if($cardtime == 18){
                if($Mcard <= $Mtime){
                    if($Dcard<=$Dtime){
                        if(M('verification')->where('id_card="'.$card.'"')->find()){
                            echo '-2';//身份证已注册过
                            die;
                        }else{
                            echo '1';//身份年满18且并未注册
                            die;
                        }
                    }else{
                        echo '-1';//周日不足
                        die;
                    }
                }else{
                    echo '-1';//月份不足
                    die;
                    die;
                }
            }else if($cardtime < 18){
                echo '-1';//未满18岁
                die;
            }else{
                if(M('verification')->where('id_card="'.$card.'"')->find()){
                    echo '-2';//身份证已注册过
                    die;
                }else{
                    echo '1';//身份年满18且并未注册
                    die;
                }
            }
      
	  }else{
            echo 0;
            die;
        }
    }

    //ajax 验证账号重复
    public function Yuser(){
        if (IS_AJAX) {
            $seuser = $_POST['user'];
            //$sqluser = substr($seuser, 0, 3);
            //$sqlname = '' . $sqluser . '_members';
            $data = M("verification")->where("user='".$seuser."'")->find();
	
            if ($data) {
                echo 1;
            }else{
                echo -1;
            }
        }else{
            echo 0;
        }

    }

    //用户注销
    public function logout()
    {
        // 清楚所有session

        session("login",null);
        $this->success( '正在退出登录...',U('User/lyologin'));
    }

    //找回密码
    public function retrievepassword(){
        if (IS_POST) {

            $post_data = $_POST['post'];

            if ( ! M()->autoCheckToken($post_data) && $this->IsOpenTokenVerify){
                // 令牌验证错误
                // $this->redirect("User/lyologin");

            }

            if( ! is_numeric($post_data['user'])){
                $this->error("用户名输入错误");
            }

            if( $post_data['user'].$post_data['code'] != $_SESSION['Zeg'] || md5($post_data['user'].$post_data['code']) != $post_data['MobileVCodePost'] || md5($_SESSION['Zeg']) != $post_data['MobileVCodePost']  ){

                $this->error("短信验证码错误",U('User/retrievepassword'));

            }

            $Ret = new Account();

            $return_code = $Ret->Zeg_Code($post_data);

            $this->ajaxReturn($return_code);
            die;
        }else{
            $this->display();
        }
    }

    function showPassword(){
        $md5_user  =  session('md5_user');
        if( $md5_user != md5(session('user').$_GET['rand_num']) ){
            session('md5_user',null);
            $this->error("非法操作",U("User/lyologin"));
        }
        $this->display("Password");
    }
    //找回密码->重置密码
    public function password(){
        if (IS_POST){

            $pass = new Account();
            $return_code = $pass->User_pass($_POST);

            if($return_code == 1){
                unset($_POST);
                $this->success("重置成功，返回登录页面",U("User/lyologin"));
            }else{
                $this->error("账号或密码错误");
            }
        }else{
            $this->display();
        }
    }

    //ajax 忘记密码-验证账号
    public function user(){
        if (IS_AJAX) {
            $seuser = I('post.user','','addslashes');
            if( ! is_numeric($_POST['user'])){
               die(-1);
            }
            $seus = $_SESSION['user'];
            if($seuser){
                $sqluser = substr($seuser, 0, 3);
                $sqlname = '' . $sqluser . '_members';
                $data = M($sqlname)->where("user=".$seuser)->find();
                if ($data) {
                    echo 2; //一致
                }else{
                    echo -2; //请求错误
                }
            }else{
                echo 3; //该电话不是当前账户预留电话
            }
        }else{
            echo -4; //没有数据接收
        }
    }

    public function Market_Detail(){

        $seed=I('get.procode','','');

        defined("__SALECHARGE__") ? "" : $this->GetSaleCharge() ;

        //  如果产品没有数据，查询是否有该产品
        // 并获得 用户当前拥有的种子数量
//        if(! $data){
        $join_user_seed_where = "seed_ware.user = '"._USERTEL_."' and seed_ware.seeds = '{$seed}'";
        $join_user_seed = " LEFT JOIN ".substr_tel(_USERTEL_)."_seed_warehouse as seed_ware on ".$join_user_seed_where;
        $field = "seeds.varieties,seed_ware.num as user_has_seed_num";
        $user_has_seed = M("seeds")->where("varieties='{$seed}'")->join($join_user_seed)->field($field)->find();

        if(! $user_has_seed){
            $this->error('要查看的产品好像有些问题。',U('Matching/index'),3);
        }

        $MarketInfo = $this->GetFlushEntrust(1,$seed);

        // 获得 所有种子
        $where['varieties'] = array("not in",array("摇钱树"));

        $AllSeedList = M("seeds")->where($where)->field()->select();

        // 得到 历史交易数据
        $HistorySaleList = $this->find_old($seed,_USERTEL_,$this->limit);

        // 得到 当前委托数据
        $NowEntrustList = $this->find_per($seed,_USERTEL_,$this->limit);

        $today_str = date("Y-m-d");                     //  今日零点的

        $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);

        $this->assign('nav_titels',"交易中心");

        $this->assign("salecharge",__SALECHARGE__);

        $this->assign("OpenCloseTime",array('Open'=>$GetOpenCloseTime['start'],'Close'=>$GetOpenCloseTime['end'] ) );

        $this->assign("AllSeedList",$AllSeedList);

        $this->assign("MarketInfo",$MarketInfo);

        $this->assign("seed",$seed);

        $this->assign("user_has_seed",$user_has_seed);

        $this->assign("HistorySaleList",$HistorySaleList);

        $this->assign("NowEntrustList",$NowEntrustList);

        $this->display();
    }

    public function ajax_k(){
        $seed=I('get.procode','','');

        $data = $this->k($seed);

        echo json_encode($data);
    }

    private function k($seed){

        $today_str = date("Y-m-d");

        //print_r($start);die;
        $end = strtotime($today_str) + 3600 * 24;

        $case_m='pay_statistical';

        $data_today=M($case_m)->where(' time <= " '.$end.'" AND seed="'.$seed.'"')->limit()->order('time asc')->select();

        return $data_today;
    }

    public function ajax_min(){
        $seed = I('post.procode','','');

        $data = $this->market_f($seed);

        echo json_encode($data);
    }

    private function market_f($seed){

        $all_data = $this->GetMarketInfo($seed);

        $all_data['SysDT'] = time();

        return array("data"=>$all_data);

    }

	// 获取某产品的 当日销售量
    private function GetMarketInfo($seed){

        $today_str = date("Y-m-d");                     //  今日零点的

        $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);

        extract($GetOpenCloseTime);

        // 记录表
        $case_m=''.date('Y-m').'_matching';

        /***********************************************************/
//        $aaaa = M($case_m)->order('time desc')->find();
//        $aaaa['time'] += 60 * 5;
//        $aaaa['num']  = rand(500,1000);
//        $aaaa['money']  = rand(10000,11000)/100000;
//        unset($aaaa['id']);
//        M($case_m)->add($aaaa);
        /***********************************************************/

        $ClosingQuotationTime = strtotime($today_str_end) - 3600 * 24; //  设置前一天的收盘时间

        //  获得前一天的收盘价格
        if(! $LastDayProductInfo = S("LastDayProductInfo_$seed")  ){
            $LastDayProductInfo = M("pay_statistical")->where(' seed="'.$seed.'"')->order('time desc')->find();
            if(empty($LastDayProductInfo)){
                $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
            }
            $LastDayProductInfo['end_money'] = $seeds_info['first_price'];
            S("LastDayProductInfo_$seed",$LastDayProductInfo,(strtotime($today_str)-time()));
        }

        //  获取当前 今天的交易数据
        //  规定 每 5 分钟 刷新一次数据

        $interval  = 60 * 5;  //  设置需要间隔的秒数

        //  获取当前 今天的交易数据
        $seed_info = S("SeedSalesInfo_$seed".$today_str);
        $seed_info = $seed_info ? $seed_info : array();
		// $seed_info = array();
        // 得到  当前缓存了多少条数据
        $ListLength = count($seed_info);
		$start_time = strtotime($today_str_begin);

        $NowTime = time() > strtotime($today_str_end) ? strtotime($today_str_end) : time();

        $ShouldHaveLength = floor(($NowTime - $start_time) / $interval);

        $where['seed'] = $seed;

        if($ShouldHaveLength > $ListLength){
            for ($time_arr = $start_time + $ListLength * $interval ; $time_arr <= $NowTime; $time_arr += $interval) {
                $where["time"] = array("BETWEEN",array($time_arr,$time_arr + $interval));
                $list = array();
                $list = M($case_m)->where($where)->field(" * , sum(num) as total_num")->order('time asc')->find();
                $list['time'] = $time_arr;
                $seed_info[] = $list;
            }	
            unset($list);
            S("SeedSalesInfo_$seed".$today_str,$seed_info);
        }

        foreach($seed_info as $key=>$val){
            $TimeShare['Time']       = $val['time'];
            $TimeShare['Price']     = number_format($val['money'],6);
            $TimeShare['Volume']     = (int)$val['total_num'];
            $data['TimeShare'][] = $TimeShare;
        }

        $MarketInfo = $this->GetFlushEntrust(0,$seed);

        $data = array_merge($data,$MarketInfo);

        return $data;

    }

    //手动撤销
    public function RevokeEntrust(){
        if(IS_AJAX){
			 $id           = I('post.entrust_id','','');

            $time = I('post.entrust_time','','');
			
			//$time=I('post.time');
			   $time=date('Y-m',$time);
			   $user=_USERTEL_;
			   $num_u=substr($user,0,3);
			   $case_p=''.$time.'_pay';
			   //$id=I('post.id');
			   $data=M(''.$case_p.'')->where('id='.$id.' AND user ='.$user.' AND queue !=1 AND state<2')->find();
			   $data_p['state']=3;
			   $model=new Model();
			   $model->startTrans();
			   if(M(''.$case_p.'')->where('id='.$id.' AND user ='.$user.' AND queue !=1 AND state<2')->save($data_p)){
				   if($data['type']==1){
					   $money=$data['num']*$data['money'];
					   //$table=new Tool();
					   //$case='members';
					   $tel=$data['user'];
					   $case_m=''.$num.'_members';
					  //print_r($money);die;
					   if(M(''.$case_m.'')->where('user='.$tel)->setInc('coin',$money)){
						   if(M(''.$case_m.'')->where('user='.$tel)->setDec('coin_freeze',$money)){
							   $model->commit();
							   /*$data_c=M(''.$case_m.'')->field('coin')->where('user='.$tel)->find();
								$coin_record['coin']=$data_c['coin'];
								$coin_record['coin_freeze']=0;
								$coin_record['time']=time();
								$coin_record['user']=$tel;
								M('coin_record')->add($coin_record); */
								$_SESSION['login']['coin'] += $money;
							   echo 1;
						   }else{
							   $model->rollback();
							   echo 0;//撤销冻结金额失败
						   }
					   }else{
						   $model->rollback();
						   echo -1;//金额回滚失败
					   }
				   }else{
					   $seed=$data['seed'];
					   $num=$data['num'];
					   $money=$data['money'];
					   //$table=new Tool();
					   //$case='fruit_record';
					   $tel=$data['user'];
					   $case_f=''.$num_u.'_fruit_record';
					   //echo $case_f;echo '<br/>';
					    //echo $tel;echo '<br/>';echo $money;echo '<br/>';echo $num;echo '<br/>';echo $seed;echo '<br/>';
						
					   if(M(''.$case_f.'')->where('user='.$tel.' AND seed ="'.$seed.'" AND money='.$money)->setDec('num',$num)){
						   //echo M(''.$case_f.'')->getLastSql();
						   $data_record=M(''.$case_f.'')->where('user='.$tel.' AND seed ="'.$seed.'" AND money='.$money)->find();
						   if($data_record['num']==0){
							   M(''.$case_f.'')->where('user='.$tel.' AND seed ="'.$seed.'" AND money='.$money)->delete();
						   }
						   //$case='seed_warehouse';
						   $case_s_b=''.$num_u.'_seed_warehouse';
						   $data_seed=M(''.$case_s_b.'')->where('user ='.$tel.' AND seeds ="'.$seed.'"')->find();
						   if(empty($data_seed)){
							   $data_s_b['seeds']=$seed;
							   $data_s_b['user']=$tel;
							   $data_s_b['num']=$num;
							   if(M(''.$case_s_b.'')->add($data_s_b)){
								   $model->commit();
								   echo 1;
							   }else{
								   $model->rollback();
								   echo -2;   //果实回滚失败
							   }
						   }else{
							   if(M(''.$case_s_b.'')->where('user ='.$tel.' AND seeds ="'.$seed.'"')->setInc('num',$num)){
								   $model->commit();
								   echo 1;
							   }else{
								   $model->rollback();
								   echo -3;     //果实回滚失败
							   }
						   }
					   }else{
						   //echo M(''.$case_f.'')->getLastSql();
						   $model->rollback();
						   echo -4;      //冻结记录消除失败
					   }
				   }
			   }else{
				   $model->rollback();
				   echo 8;//撤销订单失败
			   }
			
			
			
			
			
			
			

            
        }
    }

    //AJAX实个人委托
	private function find_per($seed,$user,$limit){
	
        $cases=''.date('Y-m').'_pay';

        $data=M($cases)->where('user="'.$user.'" AND state < 2 AND seed="'.$seed.'"')->limit($limit)->order('time DESC')->select();

		
		if(date('d')=='01'){
			
			$data = $data ? $data : array();
						
			$table =  date('Y-m' ,strtotime("-1 month", time()) );

			$data_last=M($table.'_pay')->where('user="'.$user.'" AND state < 2 AND seed="'.$seed.'"')->limit(0,($limit-count($data)))->order('time DESC')->select();
			
			$data_last = $data_last ? $data_last : array();
			
			$data = array_merge($data , $data_last);
		
		}

        return $data;

    }


    //AJAX实 历史交易
    public function find_old($seed,$user,$length){

        $time=M('matching_statistical')->order('id')->select();
        $k  =   count($time)    -  1;

        $t=0;

        $data=array();

        $data=$this->find_old_d($time,$user,$seed,$k,$data,$length,$t);

        return $data;
    }

    public function find_old_d($time,$user,$seed,$k,$data,$length,$t){
        $cases=''.$time[$k]['name'].'_pay';

        // print_r($k);
        $data_m=M($cases)->where('user="'.$user.'" AND state > 1 AND seed="'.$seed.'"')->order('time DESC')->select();

        $count = count($data_m);

        if($count>=$length){
            for($i=$t;$i<$t+$length;$i++){
                $data[$i]=$data_m[$i];
            }

            return $data;
        }else{
            $length=10-$count;
            for($i=$t;$i<$count;$i++){
                $data[$i]=$data_m[$i];
                $t++;
            }
            if($k==0){
                return $data;
            }else{
                if($length==0){
                    return $data;
                }else{
                    $k--;

                    $data = $this->find_old_d($time,$user,$seed,$k,$data,$length,$t);

                    return $data;
                }
            }
        }
    }

    // 获得当前产品 交易信息
    public function FlushEntrust(){

        $seed = I("post.procode") ;

        $data = $this->GetFlushEntrust(1,$seed);

        $result = array("data"=>$data);

        print_r(json_encode($result));die;
    }

    public function UserEntrustSubmit(){

        if(IS_POST){
			$data_g=M('Global_conf')->where('cases="start_end"')->find();
			//if($data_g['value']==0){
				if($_SESSION['user']=='18228068397'||$_SESSION['user']=='18382050570'||$_SESSION['user']=='15802858094'||$_SESSION['user']=='18584084806'||$_SESSION['user']=='18780164595'){
					
				}else{
					// $back_data['status'] = 1;
					// $back_data['msg'] = "交易关闭";
					// echo json_encode($back_data);
					// exit;
				}
			//}
			$back_data['state'] = - 1;   // 提交成功
			$back_data['content'] = '请在游戏中交易';  
			echo json_encode($back_data);
			die;
            $seed=I('post.seed');
            /**时间**/
            $y = date("Y");
            //获取当天的月份
            $m = date("m");
            //获取当天的号数
            $d = date("d");
            //print_r($m);die;
            $s_data=M('Global_conf')->where('cases="start_time"')->find();
            $e_data=M('Global_conf')->where('cases="end_time"')->find();

            $start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
            $start_t=$start-3600*24;

            $time=time();

            $end = mktime($e_data['value'],0,0,$m,$d,$y);
			$end_end=$end+3600;
            $end_t=$end-3600*24;
			if($time>=$end||$time<=$start){
				$back_data['status'] = -2;
				$back_data['msg'] = '收盘后不能挂单';  
				echo json_encode($back_data);
				exit;
			}
			
            $cases=''.date('Y-m').'_matching';
			$float_conf=M('Global_conf')->where('cases="float"')->find();
			$zhi=$float_conf['value'];
			$money=M('Pay_statistical')->field('end_money,time')->where('seed="'.$seed.'"')->order('time DESC')->select();
			$data_seed_r=M('Seeds')->field('first_price,open_price')->where('varieties="'.$seed.'"')->find();
			if($time<$start||$time>$end){
				  if(!$money){
					  $end_money=$data_seed_r['first_price'];
				  }else{
					  $end_money=$money[0]['end_money'];
				  }
			  }else{
				  //$cases=''.date('Y-m').'_matching';
				  $data_max=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time')->select();
				  if(!$data_max){
					  //$money=M('Pay_statistical')->where('seed="'.$seed.'"')->order('time DESC')->select();
					  if(!$money){
							  //$data_seed_r=M('Seeds')->field('first_price')->where('varieties="'.$seed.'"')->find();
							  $end_money=$data_seed_r['first_price'];
						  }else{
							  $end_money=$money[0]['end_money'];
						  }
				  }else{
					  $end_money=$data_max[0]['money'];
				  }
			  }
			//开盘前委托最高价格
			$max_entrust=$end_money+$end_money*$zhi;
			//最低价
			$min_entrust=$end_money-$end_money*$zhi;
			

			


            //$data_now=M($cases)->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time DESC')->select();

            //$now=$data_now[0]['money'];

            $model=new Model();

            $model->startTrans();

            $data['type']=(int)$_POST['type']*1;
			$sweeping=intval(I('post.sweeping',2,'addslashes'));     //等于1 则为扫荡   否则为正常交易
			
            $data['user'] = _USERTEL_;

            $data['submit_num'] = $data['num'] = $num = $_POST['num']*1;

            $data['seed']=$_POST['seed'];
            //
            $data['money']=$_POST['money']*1;

            $data['time']=time();
//echo 1;die;
			$fruit_name = array('土豆','草莓','樱桃','稻米','葡萄','番茄');
			$num_check=$data['num']/100;
			$data['time']=time();
			$max_entrust=round($max_entrust,5);
			  $min_entrust=round($min_entrust,5);
			if($data['money']>$max_entrust||$data['money']<$min_entrust||/*$sweeping>=2||$sweeping<0||*/$data['num']<=0||!is_int($num_check)||$data['submit_num']<=0||$data['money']<=0||!in_array($seed,$fruit_name)){
				$arr['state'] = -2;
				$arr['content'] = '错误';  
				echo json_encode($arr);
				exit;
			}

            $data_seed_r=M('Seeds')->field('first_price,open_price')->where('varieties="'.$seed.'"')->find();
            if($data['time']>$start&&$data['time']<$end){
				if($sweeping==1){
					$data['trans_type']=2;  //扫荡 2
				}else{
					$matching = date('Y-m').'_matching';
					$matching_message = M($matching);
					$today_matching = $matching_message->where('time>="'.$start.'" and time<="'.$end.'" AND seed="'.$seed.'"')->find();
					if($today_matching){
						$data_now=M(''.$cases.'')->where('time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"')->order('time DESC')->select();
						if(!$data_now){
							if($data_seed_r['open_price']!=0){
								$now=$data_seed_r['open_price'];
							}else{
                                $money=M('Pay_statistical')->field('end_money,time')->where('seed="'.$seed.'"')->order('time DESC')->select();
								if(!$money){
									$now=$data_seed_r['first_price'];
								}else{
									$now=$money[0]['end_money'];
								}
							}
						}else{
							$now=$data_now[0]['money'];
						}

						if($data['money']==$now){
							$data['trans_type']=1;  //委托 0
							
						}else{
							$data['trans_type']=0;  //委托 0
						}
					}else{
						$data_open=M('Seeds')->field('open_price')->where('varieties="'.$seed.'"')->find();
						if($data_open['open_price']==0){
							$data['trans_type']=0;  //委托 0
							
						}else{
							$data['trans_type']=1;  //委托 0
						}
					}
				}
			}else{
				$data['trans_type']=0; 
				//委托 0
			}
			$data['trans_type']=1; 	
			
            $case=''.date('Y-m').'_pay';

            $tel=_USERTEL_;

            if(M($case)->add($data)){
				
                $id=M($case)->GetlastinsID();
                if( $data['type']==1){
                    //买入
                    $case_m = substr_tel($tel)."_members";

                    $data_m['coin_freeze'] = $data['money'] * $data['num'];

                    $user_info = M($case_m)->where('user ='.$tel)->find();

                    if($user_info && $user_info['coin'] >= $data_m['coin_freeze']){

                        $_SESSION['login']['coin'] = $user_info['coin'];

                    }else{

                        $back_data['status'] = - 1;
                        $back_data['msg'] = "金币不足";
                        echo json_encode($back_data);   // 提交成功
                        exit();

                    }
					$coin_record['coin']=$user_info['coin']-$data_m['coin_freeze'];
					$coin_record['coin_freeze']=$data_m['coin_freeze'];
					$coin_record['time']=time();
					$coin_record['user']=$tel;
					if(M('coin_record')->add($coin_record)){
                    //if(M($case_m)->where('user ='.$tel)->save($data_m) !==false){
						if(M(''.$case_m.'')->where('user ='.$tel)->setInc('coin_freeze',$data_m['coin_freeze'])){
							if(M($case_m)->where('user ='.$tel)->setDec('coin',$data_m['coin_freeze'])){
								$_SESSION['login']['coin'] -= $data_m['coin_freeze'];
								$model->commit();
                                $mou=date('Y-m');
                                $path='../log/order/'.$mou.'';
                                if (!file_exists($path)){
                                    mkdir($path,0777,true);
                                }
                                $day=date('Y-m-d');
                                $str='用户;'.$data['user'].';买入果实:'.$data['seed'].'买入数量：'.$data['num'].'买入单价:'.$data['money'].'';
                                file_put_contents('../log/order/'.$mou.'/'.$day.'buyorder.log',$str.PHP_EOL."\n",FILE_APPEND);
								if($data['trans_type']==1||$data['trans_type']==2){
									//print_r($data['trans_type']);die;
									//查询今天是否已经有交易，没有则不能进行实时买入
									//$id= $addid;  //存入的id
									//echo $id;die;
									$type = $data['type']; // 买卖
									$case = date('Y-m').'_pay';
									$buy=M($case)->where('id='.$id)->select();

									$t = ($type==0) ? 1 : 0;

									$k=0;
									//echo 1;die;
	//print_r($buy);die;
									$state=0;
									$matching=new Matching();
									$data=M('Global_conf')->where('cases="poundage"')->find();
									$poundage=$data['value'];
									$number=0;
									$data=$matching->time_buy($number,$poundage,$buy,$k,$t,$state);
									$s=$data['zhi'];
							
									switch($s){
										case 3 :
											
											$back_data['status'] = 1;
											$back_data['msg'] = "匹配成功";
											$back_data['money'] = $_SESSION['login']['coin'];
											break;
										case 4 :
											$back_data['status'] = 1;
											$back_data['msg'] = "挂单排队中";
											$back_data['money'] = $_SESSION['login']['coin'];
											break;
										case 2 :
										$save['trans_type']=0;
										 M($case)->where('id='.$id)->save($save);
											$back_data['status'] = - 1;
											$back_data['msg'] = "不在开盘时间";
											break;
										case 1 :
										
										 $save['trans_type']=0;
										 M($case)->where('id='.$id)->save($save);
										 $back_data['status'] = 1;
										 //$back_data['money'] = $_SESSION['login']['coin'];
										$back_data['msg'] = "已交易".$data['number']."，自动转入委托";
											 
									}

								}else{
									$back_data['status'] =  1;
									$back_data['msg'] = "委托成功";
									$back_data['money'] = $_SESSION['login']['coin'] ;
								}

								echo json_encode($back_data);   // 提交成功
								exit();
							}else{
								$model->rollback();
								$back_data['status'] = - 1;   // 提交成功
								echo json_encode($back_data);
								die;
							}
						}else{
							$model->rollback();
                        $back_data['status'] = - 1;   // 提交成功
                        echo json_encode($back_data);
                        die;
						}
                    }else{
                        $model->rollback();
                        $back_data['status'] = - 1;   // 提交成功
                        echo json_encode($back_data);
                        die;
                    }

                }else{

                    if($_SESSION['login']['level'] < 5)
                    {
                        $back_data['status'] = -1;
                        $back_data['msg'] = "等级不足，无法交易";
                        echo json_encode($back_data);
                        exit();
                    }
                    $data_g=M('Global_conf')->where('cases="poundage"')->find();
					$num_tel=substr($tel,0,3);
                    $case_m=''.$num_tel.'_members';
                    $data_m['coin_freeze']=$data['money']*$data['num']*$data_g['value'];
                    $data_coin=M(''.$case_m.'')->where('user ='.$tel)->find();
                    if($data_m['coin_freeze']>$data_coin['coin']){
                        $model->rollback();
                        $back_data['status'] = -1;
                        $back_data['msg'] = '金币不足（手续费：'.$data_m['coin_freeze'].'）';
                        echo json_encode($back_data);
                        exit();
                    }

                    //echo $data_m['coin_freeze'];die;
                    //$data_m['coin_freeze']=(float)$data_m['coin_freeze'];
                    $coin=M(''.$case_m.'')->where('user ='.$tel)->find();
                    $save['coin_freeze']=$coin['coin_freeze']+$data_m['coin_freeze'];
                    $save['coin']=$coin['coin']-$data_m['coin_freeze'];
					$coin_record['coin']=$save['coin'];
					$coin_record['coin_freeze']=$save['coin_freeze'];
					$coin_record['time']=time();
					$coin_record['user']=$tel;
					M('coin_record')->add($coin_record);   
                    M(''.$case_m.'')->where('user ='.$tel)->save($save);
                    //卖出
                    $table=new \Think\Tool();
                    $case='fruit_record';

                    $case_f = substr_tel($tel)."_fruit_record";

                    $case_s=substr_tel($tel)."_seed_warehouse";

                    $data_num=M($case_s)->where('user ='.$tel.' AND seeds ="'.$data['seed'].'"')->find();
                    //echo $data_num['num'];die;
                    if($data_num['num']<$num){
//                        echo '果实数量不足';
                        $back_data['status'] = -1;
                        $back_data['msg'] = "果实数量不足";
						echo json_encode($back_data);

                        $model->rollback();
                    }else{

                        if(M($case_s)->where('user ='.$tel.' AND seeds ="'.$data['seed'].'"')->setDec('num',$data['num'])){
                            $data_record_s=M($case_f)->where('user="'.$tel.'" AND money='.$data['money'].' AND seed="'.$data['seed'].'"')->find();

                            if(empty($data_record_s)) {
								
                                $data_s['seed'] = $data['seed'];
                                $data_s['num'] = $data['num'];
                                $data_s['time'] = time();
                                $data_s['user'] = _USERTEL_;
                                $data_s['money'] = $data['money'];
                                if (M($case_f)->add($data_s)) {
									//echo M(''.$case_f.'')->GetLastsql();echo 2;die;
                                    $model->commit();
                                    $mou=date('Y-m');
                                    $path='../log/order/'.$mou.'';
                                    if (!file_exists($path)){
                                        mkdir($path,0777,true);
                                    }
                                    $day=date('Y-m-d');
                                    $str='用户;'.$data['user'].';卖出果实:'.$data['seed'].'卖出数量：'.$data['num'].'卖出单价:'.$data['money'].'';
                                    file_put_contents('../log/order/'.$mou.'/'.$day.'sellorder.log',$str.PHP_EOL."\n",FILE_APPEND);
                                    if ($data['trans_type'] == 1||$data['trans_type']==2) {
                                        //echo $num;die;
                                        $type = $data['type']; // 买卖
                                        $case = '' . date('Y-m') . '_pay';
                                        $matching = new Matching();
                                        $sell = M($case)->where('id=' . $id)->select();
                                        if ($type == 0) {
                                            $t = 1;
                                        } else {
                                            $t = 0;
                                        }
										$state=0;
                                        $i = 0;
										$data=M('Global_conf')->where('cases="poundage"')->find();
										$poundage=$data['value'];
										$number=0;
                                        $data = $matching->time_sell($number,$poundage,$sell, $i, $t,$state);
										$s=$data['zhi'];
                                        if ($s == 3) {
                                            $back_data['status'] = 1;
                                            $back_data['msg'] = "匹配成功";
                                            $back_data['num'] = $data_num['num'] - $data['num'];
											//返佣
                                            //匹配成功
                                        } elseif ($s == 2) {
                                            //不在开盘时间
                                            $back_data['status'] = 1;
											$save['trans_type']=0;
											M($case)->where('id='.$id)->save($save);
                                            $back_data['msg'] = "不在开盘时间";
//                                        echo '不在开盘时间';
                                        } elseif ($s == 1) {
											
											 $save['trans_type']=0;
											 M($case)->where('id='.$id)->save($save);
											 $back_data['status'] = 1;
											 if($data['number']==0){
												$back_data['msg'] = "当前无单匹配，自动转入委托";
											}else{
												$back_data['msg'] = "已交易".$data['number']."，自动转入委托";
											}
                                            
										
                                        }elseif ($s == 4) {
											 $back_data['status'] = 1;
                                            $back_data['msg'] = "挂单排队中";
										
                                        }
										echo json_encode($back_data);
										exit();
                                    } else {
//                                    echo '委托成功';
                                        $back_data['status'] = 1;
                                        $back_data['msg'] = "委托成功";
                                        $back_data['num'] = $data_num['num'] - $data['num'];
                                    }

                                    echo json_encode($back_data);
                                    exit();
                                } else {
                                    $model->rollback();
                                    $back_data['status'] = -1;
                                    echo json_encode($back_data);
                                    exit();
                                }
                            }else{
								
                                if(M(''.$case_f.'')->where('user="'.$tel.'" AND money='.$data['money'].' AND seed="'.$data['seed'].'"')->setInc('num',$data['num'])) {
									//echo M(''.$case_f.'')->GetLastsql();echo 1;die;
                                    $model->commit();
                                    $mou=date('Y-m');
                                    $path='../log/order/'.$mou.'';
                                    if (!file_exists($path)){
                                        mkdir($path,0777,true);
                                    }
                                    $day=date('Y-m-d');
                                    $str='用户;'.$data['user'].';卖出果实:'.$data['seed'].'卖出数量：'.$data['num'].'卖出单价:'.$data['money'].'';
                                    file_put_contents('../log/order/'.$mou.'/'.$day.'sellorder.log',$str.PHP_EOL."\n",FILE_APPEND);
                                    if ($data['trans_type'] == 1||$data['trans_type']==2) {
                                        //echo $num;die;
                                        $type = $data['type']; // 买卖
                                        $case = '' . date('Y-m') . '_pay';
                                        $matching = new Matching();
                                        $sell = M($case)->where('id=' . $id)->select();
										$state=0;
                                        if ($type == 0) {
                                            $t = 1;
                                        } else {
                                            $t = 0;
                                        }
                                        $i = 0;
										$data=M('Global_conf')->where('cases="poundage"')->find();
										$poundage=$data['value'];
										$number=0;
                                        $data = $matching->time_sell($number,$poundage,$sell,$i,$t,$state);
										$s=$data['zhi'];
                                        if ($s == 3) {
                                            $back_data['status'] = 1;
                                            $back_data['msg'] = "匹配成功";
                                            $back_data['num'] = $data_num['num'] - $data['num'];
                                            //匹配成功
                                        } elseif ($s == 2) {
                                            //不在开盘时间
											$save['trans_type']=0;
											M($case)->where('id='.$id)->save($save);
                                            $back_data['status'] = 1;
                                            $back_data['msg'] = "不在开盘时间";
											//echo '不在开盘时间';
                                        } elseif ($s == 1) {
											
											 $save['trans_type']=0;
											 M($case)->where('id='.$id)->save($save);
											 $back_data['status'] = 1;
											 if($data['number']==0){
												$back_data['msg'] = "当前无单匹配，自动转入委托";
											}else{
												$back_data['msg'] = "已交易".$data['number']."，自动转入委托";
											}
                                        }
										echo json_encode($back_data);
                                    exit();
                                    } else {
//                                    echo '委托成功';
                                        $back_data['status'] = 1;
                                        $back_data['msg'] = "委托成功";
                                        $back_data['num'] = $data_num['num'] - $data['num'];
                                    }
									echo json_encode($back_data);
                            exit();
                                }else {
                                    $model->rollback();
                                    $back_data['status'] = -1;
                                    echo json_encode($back_data);
                                    exit();
                                }
                            }
                        }else{
                            $model->rollback();
                            $back_data['status'] =  -2;
                            echo json_encode($back_data);
                            exit();
                        }
                    }
                }
            }else{
                $model->rollback();
                $back_data['status'] =  -3;
                echo json_encode($back_data);
                exit();
            }
        }
    }
    //交易记录
    public function pay(){
        //种子
        $mou=M('matching_statistical')->select();
        $m=$mou[0]['name'];
        $m_y=substr($m,0,4);
        $m_m=substr($m,5,2);
        $m_time = mktime(0,0,0,$m_m,01,$m_y);


        $where['varieties']=array('not in',"摇钱树");
        $data_seed=M('seeds')->where($where)->select();
        $this->assign('data_seed',$data_seed);

        $seuser = $_SESSION['login']['user'];
        $user=$seuser;
		$this->assign('user',$user);
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        //$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        $time=time();
        //print_r($start);die;
        //$end = mktime($e_data['value'],0,0,$m,$d,$y);

        //$id=1;


        $thismonth = date('m');
        $thisyear = date('Y');
        if ($thismonth == 1) {
            $lastmonth = 12;
            $lastyear = $thisyear - 1;
        } else {
            $lastmonth = $thismonth - 1;
            $lastyear = $thisyear;
        }
        if ($thismonth == 1) {
            $lastmonth_s = 11;
            $lastyear_s = $thisyear - 1;
        } elseif($thismonth == 2) {
            $lastmonth_s = 12;
            $lastyear_s = $thisyear - 1;
        }else{
            $lastmonth_s = $thismonth - 2;
            $lastyear_s = $thisyear;
        }
        if( $lastmonth_s<10){
            $lastmonth_s='0'.$lastmonth_s.'';
        }
        if( $lastmonth<10){
            $lastmonth='0'.$lastmonth.'';
        }
        $lastEndDay_t=date('Y-m');//本月
        $lastEndDay_f = $lastyear . '-' . $lastmonth ;  //上个月
        $lastEndDay_s = $lastyear_s . '-' . $lastmonth_s ; //前月
        $case_t=''.$lastEndDay_t.'_pay';
        $case_f=''.$lastEndDay_f.'_pay';
        $case_s=''.$lastEndDay_s.'_pay';
        $s_T = mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
        $f_T = mktime(0,0,0,$lastmonth,01,$lastyear);
        if(IS_POST){
            $id=I('post.id','','');
            $this->assign('id',$id);
            $type=I('post.type','','');     //买入1卖出0
            $start_times=I('post.start','','');
            $end_times=I('post.end','','');

            $s_y=substr($start_times,0,4);
            $s_m=substr($start_times,5,2);
            $s_d=substr($start_times,8,2);
            $star = mktime(0,0,0,$s_m,$s_d,$s_y);
            $e_y=substr($end_times,0,4);
            $e_m=substr($end_times,5,2);
			$e_d=substr($end_times,8,2);
            $end = mktime(0,0,0,$e_m,$e_d,$e_y);
            //输出
            if(empty($id)){
                $seed='';
            }else{
                $data_fruit=M('Seeds')->where('id ='.$id)->find();
                $seed=$data_fruit['varieties'];
            }
			
            $one=mktime(0,0,0,date('m'),01,date('y'));
			if($one>$star){
				$start_time=date('Y-m-d');
			}else{
				$start_time=$start_times;
			}
			$end_time=$end_times;
            
			//print_r($start_time);echo "<br/>";
            //print_r($end_time);die;
            $find= new Find();
            $data=$find->total_pay($start_time,$end_time,$seed,$type,$user);
            //print_r($data['sstate_t']);
        }else{
			if(IS_GET){
				$start_time=I('get.start');
				$end_time=I('get.end');
				$start_time=str_replace("_","-",$start_time);
				$end_time=str_replace("_","-",$end_time);
				//print_r($_GET);
				if(empty($start_time)){
					$start_time=date('Y-m-d');
				}
				if(empty($end_time)){
					$end_time=date('Y-m-d');
				}
				$id=I('get.id');
				$this->assign('id',$id);
				$type=I('get.type');     //买入1卖出0
				if(empty($id)){
					$seed='';
				}else{
					$data_fruit=M('Seeds')->where('id ='.$id)->find();
					$seed=$data_fruit['varieties'];
				}
				$c='pay';
				$trans_type = 1;
				$tool= new \Think\Find();
				$data=$tool->total_pay($start_time,$end_time,$seed,$type,$user);
			}
        }
		//print_r($data);die;
		array_multisort(i_array_column($data,'time'),SORT_DESC,$data);
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);

        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o','','int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $off=floor($showPage/2);
        $start_page=$o-$off;
        $end_page=$o+$off;
        //起始页
		//print_r($off);
        if($o-$off <=1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$off >= $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
        $this->assign('start_page',$start_page); //分页
        $this->assign('end_page',$end_page+1); //分页
		//print_r($start_page);
		//print_r($end_page);
        $res =array_slice($data,($o-1)*8,8);
        $this->assign('data',$res);//分页内容
        $this->assign('type',$type);
		$start_time=str_replace("-","_",$start_time);
		$end_time=str_replace("-","_",$end_time);
        $this->assign('start',$start_time);
        $this->assign('end',$end_time);
        $this->assign('id',$id);
        $this->assign('nav_titels',"交易中心");


        $son_list = array();
        $son_list[0]['title']   = "交易中心";
        $son_list[0]['i_class']   = "ti-comments";
        $son_list[0]['url']     = U('User/pay');
        $son_list[1]['title']   = "委托记录";
        $son_list[1]['i_class']   = "ti-direction";
        $son_list[1]['url']     = U('User/entrust');

        $this->assign('son_nav',$son_list);

        $this->display();
    }
    //委托记录
    public function entrust(){
        $mou=M('matching_statistical')->select();
        $m=$mou[0]['name'];
        $m_y=substr($m,0,4);
        $m_m=substr($m,5,2);
        $m_time = mktime(0,0,0,$m_m,01,$m_y);

        $where['varieties']=array('not in',"摇钱树");
        $data_seed=M('seeds')->where($where)->select();
        $this->assign('data_seed',$data_seed);

        $seuser = $_SESSION['login']['user'];
        $user=$seuser;
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        //print_r($m);die;
        //$tm =date("d")+1;
        $s_data=M('Global_conf')->where('cases="start_time"')->find();
        $e_data=M('Global_conf')->where('cases="end_time"')->find();
        //$start= mktime($s_data['value'],0,0,$m,$d,$y);//即是当天零点的时间戳
        //$time=time();
        //print_r($start);die;
        //$end = mktime($e_data['value'],0,0,$m,$d,$y);
        //$id=1;
        $thismonth = date('m');
        $thisyear = date('Y');
        if ($thismonth == 1) {
            $lastmonth = 12;
            $lastyear = $thisyear - 1;
        } else {
            $lastmonth = $thismonth - 1;
            $lastyear = $thisyear;
        }
        if ($thismonth == 1) {
            $lastmonth_s = 11;
            $lastyear_s = $thisyear - 1;
        } elseif($thismonth == 2) {
            $lastmonth_s = 12;
            $lastyear_s = $thisyear - 1;
        }else{
            $lastmonth_s = $thismonth - 2;
            $lastyear_s = $thisyear;
        }
        if( $lastmonth_s<10){
            $lastmonth_s='0'.$lastmonth_s.'';
        }
        if( $lastmonth<10){
            $lastmonth='0'.$lastmonth.'';
        }
        $lastEndDay_t=date('Y-m');//本月
        $lastEndDay_f = $lastyear . '-' . $lastmonth ;  //上个月
        $lastEndDay_s = $lastyear_s . '-' . $lastmonth_s ; //前月
        $case_t=''.$lastEndDay_t.'_pay';
        $case_f=''.$lastEndDay_f.'_pay';
        $case_s=''.$lastEndDay_s.'_pay';
        $s_T = mktime(0,0,0,$lastmonth_s,01,$lastyear_s);
        $f_T = mktime(0,0,0,$lastmonth,01,$lastyear);
        if(IS_POST){
            $id=I('post.id','','');
            $this->assign('id',$id);
            $type=I('post.type','','');     //买入1卖出0
            $start_times=I('post.start','','');
            $end_times=I('post.end','','');

            $s_y=substr($start_times,0,4);
            $s_m=substr($start_times,5,2);
            $s_d=substr($start_times,8,2);
            $star = mktime(0,0,0,$s_m,$s_d,$s_y);
            $e_y=substr($end_times,0,4);
            $e_m=substr($end_times,5,2);
			$e_d=substr($end_times,8,2);
            $end = mktime(0,0,0,$e_m,$e_d,$e_y);
            //输出
            if(empty($id)){
                $seed='';
            }else{
                $data_fruit=M('Seeds')->where('id ='.$id)->find();
                $seed=$data_fruit['varieties'];
            }
            $one=mktime(0,0,0,date('m'),01,date('y'));
			if($one>$star){
				$start_time=date('Y-m-d');
			}else{
				$start_time=$start_times;
			}
			$end_time=$end_times;
            $c='pay';
            $trans_type = 0;
            $tool= new \Think\Find();
            $data=$tool->total($start_time,$end_time,$seed,$type,$user,$c,$trans_type);
            //print_r($data['sstate_t']);
        }else{
			if(IS_GET){
				$start_time=I('get.start');
				$end_time=I('get.end');
				$start_time=str_replace("_","-",$start_time);
				$end_time=str_replace("_","-",$end_time);
				if(empty($start_time)){
					$start_time=date('Y-m-d');
				}
				if(empty($end_time)){
					$end_time=date('Y-m-d');
				}
				$id=I('get.id');
				$this->assign('id',$id);
				$type=I('get.type');     //买入1卖出0
				if(empty($id)){
					$seed='';
				}else{
					$data_fruit=M('Seeds')->where('id ='.$id)->find();
					$seed=$data_fruit['varieties'];
				}
				$c='pay';
				$trans_type = 0;
				$tool= new \Think\Find();
				$data=$tool->total($start_time,$end_time,$seed,$type,$user,$c,$trans_type);
			}
        }
		array_multisort(i_array_column($data,'time'),SORT_DESC,$data);
        if(empty($data)){
            $state=0;
        }else{
            $state=1;
        }
        $this->assign('state',$state);
        $count=count($data);//得到数组元素个数
        $num =8;
        $pages = ceil($count/$num);
//print_r($pages);die;
        //$this->assign('pages',$pages+1); //分页
        if($_GET['o']!==null){
            $o =I('get.o','','int');
        }else{
            $o =1;
        }
        if($o<1){
            $o =1;
        }else if($o > $pages){
            $o = $pages;
        }
        $this->assign('o',$o);
        $showPage = 5;
        $off=floor($showPage/2);
        $start_page=$o-$off;
        $end_page=$o+$off;
        //起始页
        if($o-$off < 1){
            $start_page = 1;
            $end_page = $showPage;
        }
        //结束页
        if($o+$off > $pages){
            $end_page = $pages;
            $start_page = $pages-$showPage+1;
        }
        if($pages < $showPage){
            $start_page = 1;
            $end_page = $pages;
        }
        $this->assign('start_page',$start_page); //分页
        $this->assign('end_page',$end_page+1); //分页

        $res =array_slice($data,($o-1)*8,8);
        $this->assign('data',$res);//分页内容
        $this->assign('type',$type);
		$start_time=str_replace("-","_",$start_time);
		$end_time=str_replace("-","_",$end_time);
        $this->assign('start',$start_time);
        $this->assign('end',$end_time);
        $this->assign('id',$id);

        $this->assign('nav_titels',"委托记录");


        $son_list = array();
        $son_list[0]['title']   = "交易中心";
        $son_list[0]['i_class']   = "ti-comments";
        $son_list[0]['url']     = U('User/pay');
        $son_list[1]['title']   = "委托记录";
        $son_list[1]['i_class']   = "ti-direction";
        $son_list[1]['url']     = U('User/entrust');

        $this->assign('son_nav',$son_list);

        $this->display();
    }

    function login(){
		
        if( ! is_mobile( $_SESSION['user']) ){
            $this->error("请重新登录。",U('User/lyologin'));
        }

        $where['user'] = $_SESSION['user'];
		$mem_fix = substr($_SESSION['user'],0,3);
        $field = "id,user,nickname,headimg,coin,coin_freeze,diamond,login_time,name,id_card,referees,level,num_id";
        $User_info = M($mem_fix."_members")->field($field)->where("user='".$_SESSION['user']."'")->find();
		
        // $key = md5($User_info['user'].$User_info['id_card']);
		$login_time['login_time'] = time();
        if($User_info){
            $_SESSION['login'] = $User_info;
			 M($mem_fix."_members")->where($where)->save($login_time);
			 
			if($_GET['is_pay'] == 1){
				$this->redirect("Pay/pay_test");
				
			}
			
			if($_GET['content'] == 1){
				$this->redirect("content/index");
			}
			
            $this->redirect("index");   
        }
		
        $this->redirect("lyologin");
    }

		// 获取新的提现订单
	function get_new_cash(){
		
		$stime=microtime(true); 
			
		$all_tables  =  M("statistical")->order("name asc")->select();
		
		$first_table = $all_tables[0]['name']."_order";
		
		unset($all_tables[0]);
			
		$where  = "  `pay_cash`='1' and `state`='0'  ";
		
		// 拼接 sql；
		foreach($all_tables as $val){
			
			$field   =  $val['name']."_order.*  ";
			$table   =  $val['name']."_order ";
			
			$sql = "select " . $field . " from " . $table ." where  ".$where . " ";
			
			$union[] = $sql;
			
		}	
		
		$all_list =  M($first_table)->union($union,true)->field('*')->where($where)->select();
		
		array_multisort(i_array_column($all_list,'add_time'),SORT_DESC,$all_list);
		
		// 获得分页
		
		$page = $_GET['page'] > 1 ?  $_GET['page'] : 1;
		
		$now_list = page_array(10,$page,$all_list);
			
		echo "\n <br/>";
		// echo M()->getLastSql();
		// echo "\n <br/>";
		// echo count($all_sql);
		$etime=microtime(true); 
		print_r($now_list);
		// echo count($list);
		echo "\n <br/>";
		echo $stime;
		echo "\n <br/>";
		echo $etime;
		echo "\n <br/>";
		echo $etime - $stime;
	}	
}