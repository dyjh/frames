<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserTableInfoModel;
use Org\Our\Pay;

class PrizeController extends HomeController {

    public  $PayApiPath   = 'ThinkPHP/Library/Vendor/PayApi';

    private $PayApiIsOpen = true;                      //  是否开始第三方支付接口

    private $PayPrefix    = "kszypay";                 //  充值订单前缀

    private $PayList      = array('800','1500');                   //  提现订单订单前缀
	
    private $UrlHost      = "lyogame.cn";              //  充值回调域名
	
	private $REQUEST_SCHEME = "http";		

    protected  $IsOpenTokenVerify = true;			   //是否开启令牌验证

	public function __construct(){

        parent::__construct();
      	
	    // $this->show('程序小哥哥正在熬夜！' ,  'utf-8');
		
		$this->error('礼包升级中！',U('User/index'),3);
	    die;
		if(!session('?login') ){
			$_SESSION['prize']  = 1;
			$this->error('您没有登录，请先登录',U('User/lyologin'),3);
		}
        
        $this->assign('PayApiPath',$this->PayApiPath);
        $this->assign('PayApiIsOpen',$this->PayApiIsOpen);

    }

	
	// 充值页面 
    public function index(){

		// $this->error("系统维护中。请稍候");
        require_once($this->PayApiPath.'/php/shunfoo/ClassShunfoo.php');

        $PayApi = new \shunfoo();

        //  网银支付列表。
        $this->assign('shunfoo_banktype',$shunfoo_banktype);

        $this->assign('shunfoo_banktype_support',$shunfoo_banktype_now_support);

		$Usertel = _USERTEL_;
        // 获取用户 充值记录
        $table_prefix = substr($Usertel,0,3);
				
		$join = " left join " . $table_prefix . "_order as _order on _order.user='".$Usertel."' and _order.is_gift=1 and _order.state<2 ";		
		
		$field = $table_prefix."_members.user, _order.id , _order.state   ";

		$member_is_exist = M($table_prefix."_members")->field($field)->join($join,'left')->where($table_prefix."_members.user='".$Usertel."'")->find();
		
		if( $member_is_exist['id'] ){
			$order_where['id']  = $member_is_exist['id'];
			$order_info = M($table_prefix."_order")->where($order_where)->find();
					
			switch($member_is_exist['state']){	
				case 0 : 					
					if( $order_info['add_time'] < ( time() - 3600 * 2) ){
						$save_data['state'] = 2;
						M($table_prefix."_order")->where($order_where)->save($save_data);
					}else{
						$this->do_pay_request($order_info);						
					}	
					break;
				case 1 :
					$this->error("您已充值过大礼包，请勿重复充值",U('Prize/show_success', 'order_id='.$order_info['id']) );
					break;
				case 2 :
					break;	
				default :
					$this->error("您已充值过大礼包，请勿重复充值",U('Prize/show_success', 'order_id='.$order_info['id']) );				
			}			
		}		
				
		/***
		 增加微信判断
		*/
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			$this->assign('Is_MicroMessenger',true);
		}	

		// 判断手机访问
		if( is_mobile_request() ){
			$this->assign('Is_Wap_App',true);
		}
		
		$this->assign('PayApiIsOpen',$this->PayApiIsOpen);
			
        $this->assign('nav_titels',"礼包充值");

        $this->assign('All_Entrust',$UserEntrustList['All_Entrust']);

        $this->display();

    }

	// 执行添加充值操作
    /**
     *  增加订单
     */
    public function pay_go(){

		// $Usertel = $_POST['account'];
		$Usertel = _USERTEL_;
		
		//  判断用户使用 领取过充值礼包
		$table_prefix = substr($Usertel,0,3);
				
		$join = " left join " . $table_prefix . "_order as _order on _order.user='".$Usertel."' and _order.is_gift=1 and _order.state<2  ";		
		
		$field = $table_prefix."_members.user, _order.id , _order.state   ";

		$member_is_exist = M($table_prefix."_members")->field($field)->join($join,'left')->where($table_prefix."_members.user='".$Usertel."'")->find();

		if(!$member_is_exist['user']){
			$this->error("用户不存在，请确认后输入");
		}
		
		if( $member_is_exist['id'] ){
			$order_where['id']  = $member_is_exist['id'];
			$order_info = M($table_prefix."_order")->where($order_where)->find();
					
			switch($member_is_exist['state']){	
				case 0 : 
					if( $order_info['add_time'] < ( time() - 3600 * 2) ){
						$save_data['state'] = 2;
						M($table_prefix."_order")->where($order_where)->save($save_data);
					}else{
						$this->do_pay_request($order_info);						
					}		
					
					break;
				case 1 :
					$this->error("您已充值过大礼包，请勿重复充值",U('Prize/show_success', 'order_id='.$order_info['id']) );
					// $this->redirect("Prize/show_success" , array('order_id' => $order_info['id']) );
					break;
				case 2 :
					break;	
				default :
					$this->error("该用户已进行过礼包充值，请勿重复充值!");				
			}			
		}		
		
		if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify  ){
            // 令牌验证错误
            $this->redirect("Prize/index");

        }		
		
		
        if( ! in_array( $_POST['amount'] ,$this->PayList  ) ){

            $this->error("礼包充值种类为800元与1500元");

        }

        if(! $_POST['bankType'] || !is_numeric( $_POST['bankType'] ) ){

            $this->error("请选择支付方式");

        }					

        //  创建订单
        if($_POST['account'] || !is_numeric( $_POST['bankType'] )){		
			
			switch( $_POST['bankType'] ){
				case 2001:
					unset( $_SESSION['account']['order_id']);
					$this->success("提交成功，收到汇款后，30分钟内为您完成充值",U('Prize/index'));					
					break;
				case 992:
					$Pay_name 			= "支付宝";
					$Pay_name_img 		= "ZhiPayBack";				
					break;
				case 9999: 
					if($$USERTEL != '18780164595'){											
						// $this->error("扫码支付维护升级中，请使用支付宝支付");
					}
					$Pay_name 			= "扫码";
					$Pay_name_img 		= "ScanPayBack";
										
					break;
				case 1004:		
					if($$USERTEL != '18780164595'){	
						// $this->error("微信支付维护升级中，请使用支付宝支付");	
					}			
					$Pay_name 			= "微信";
					$Pay_name_img   	= "WeChetPay";						
					break;				
				default :	
					$this->error("支付方式不正确");					
					break;
			}		
				
			unset($order_info);
				
			$time = time();
		
			$order_info['user']         =   $Usertel;
			$pre_order_num 		     	= 	$this->PayPrefix . $table_prefix . substr($Usertel,-4,-1) . $time;

			/*
							  ,;,,;
							 ,;;'(    马
				   __      ,;;' ' \   ┇
				/'  '\'~~'~' \ /'\.)  到 
			 ,;(      )    /  |.      ┇
			,;' \    /-.,,(   ) \     成
				 ) /       ) / )|     ┇ 
				 ||        ||  \)     功
				 (_\       (_\
			
			   增加最后一位随机码验证
			*/
			$new_api_order_num			=   $table_prefix . substr($Usertel,-4,-1) . $time;
			$last_order_num  			= 	$this->getlastnume($new_api_order_num) ;
			$order_info['order_num']    =   $order_id =			$pre_order_num . $last_order_num;
			
			$order_info['money']        =   $_POST['amount'];
			$order_info['pay_bank']     =   $_POST['bankType'];
			$order_info['pay_cash']     =   1;
			$order_info['is_gift']      =   1;
			
			// if(_USERTEL_ == '18780164595'|| _USERTEL_ == '18382050570' || _USERTEL_ == '13668288296'){
				// $order_info['money']        =   1;
			// }
			
			
			$order_info['add_time']     =   $time;			
			
			$res = M($table_prefix."_order")->add($order_info);
			
			$order_info['id']    =   $res;

			$_SESSION['account']['order_id'] = $res;

			$this->do_pay_request($order_info);
        
        }

    }
	
	// 充值返回处理
	/***
	 *   接受支付回传参数	 							
	 */
    public function pay_back(){
	
		$merchantOutOrderNo  =  $_POST['merchantOutOrderNo'];
		$where['order_num']  =  $this->PayPrefix . $merchantOutOrderNo;
		
		// 查询订单
		$order_info   =  M(substr($merchantOutOrderNo,0,3)."_order")->where($where)->find();
		
		$this->mdzz_ordertate($order_info,true);
		
    }

			
	// 支付成功页面
	function show_success(){
							
		$table_prefix			   =  substr(_USERTEL_,0,3);
		$order_where['user'] 	   =  _USERTEL_;
		$order_where['id'] 		   =  $_GET['order_id'];
		
		$order_info = M($table_prefix."_order")->where($order_where)->find();

		if($order_info['state'] == 1 ){
			
			$content  = $this->package_content($order_info['pay_money']);
			
		//  获取 给用户增加的材料数量
			$material_where['level']  =  array("elt",$content['material_level']);
			$house_list = M("house")->where($material_where)->field("cost")->select();
			
			foreach($house_list as $val){
				$material = unserialize($val['cost']); unset($material['price']);
				foreach($material as $v){
					$material_list[$v['seed_id']]['number'] += $v['seed_value'];
				}	
			}
			//  获取材料名称
			foreach($material as $key=>$val){
				$join  = " left join ".$table_prefix. "_meterial_warehouse as mw on mw.props='".$key."' and  mw.user='".$order_where['user']."'";
				$field = "house_material.* , mw.id as mw_id ,mw.num";
				$info = M("house_material")->where("house_material.id=$key")->join($join)->field($field)->find();			
				$material_list[$key]['name']  = $info['name'];
				$material_list[$key]['id']    = $info['mw_id'];
				$material_list[$key]['num']   = (int)$info['num'];					
			}	
			
			$this->assign("order_info",$order_info);
			
			$this->assign("material_list",$material_list);
			
			$this->assign('nav_titels',"充值成功");
			
			$this->display("success");
			
		}else{
			
			$this->error("您无权限查看此单");
			
		}		
		
	}
	
	// 	获取礼包  内容
	private function package_content($order_pay_money){
		
		switch ($order_pay_money){					
			case $this->PayList[0] : 
				$content['member_diamond']= 8000;
				$content['member_coin']	  = 1;
				$content['member_seed']   = 5000;
				$content['material_level']= 6;
				
				//2017年10月18日10:15:56
				//增加作物定向
				$content["orientation"][0]['user'] 	    =  _USERTEL_;
				$content["orientation"][0]['seed'] 	    =  "葡萄";
				$content["orientation"][0]['imm_num']   =  1;
				$content["orientation"][0]['num'] 	    =  1;
				$content["orientation"][0]['start_time']=  time();
				$content["orientation"][0]['end_time'] 	=  $content["orientation"][0]['start_time'] + (32 * 24 * 60 * 60);    //  种植期限 32天
				$content["orientation"][0]['count'] 	=  0;
				$content["orientation"][0]['type'] 	    =  1;
				
				//增加 守护
			
				$content["managed_to_record"][0]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][0]['service_type'] 	=  "1";
				$content["managed_to_record"][0]['end_time']   		=  (32 * 24 * 60 * 60);     //  期限 32天
				$content["managed_to_record"][0]['state'] 	    	=  0;
				
				$content["managed_to_record"][1]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][1]['service_type'] 	=  "2";
				$content["managed_to_record"][1]['end_time']   		=  (32 * 24 * 60 * 60);     //  期限 32天
				$content["managed_to_record"][1]['state'] 	    	=  0;
				
				$content["managed_to_record"][2]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][2]['service_type'] 	=  "3";
				$content["managed_to_record"][2]['end_time']   		=  (32 * 24 * 60 * 60);     //  期限 32天
				$content["managed_to_record"][2]['state'] 	    	=  0;
				
				$content["managed_to_record"][3]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][3]['service_type'] 	=  "4";
				$content["managed_to_record"][3]['end_time']   		=  (32 * 24 * 60 * 60);     //  期限 32天
				$content["managed_to_record"][3]['state'] 	    	=  0;
								
				break;
			case $this->PayList[1] : 
				$content['member_diamond']= 15000;
				$content['member_coin']	  = 1;
				$content['member_seed']   = 9000;
				$content['material_level']= 6;
				
				//2017年10月18日10:15:56
				//增加作物定向
				$content["orientation"][0]['user'] 	    =  _USERTEL_;
				$content["orientation"][0]['seed'] 	    =  "葡萄";
				$content["orientation"][0]['imm_num']   =  2;
				$content["orientation"][0]['num'] 	    =  2;
				$content["orientation"][0]['start_time']=  time();
				$content["orientation"][0]['end_time'] 	=  $content["orientation"][0]['start_time'] + (33 * 24 * 60 * 60);  //  种植期限 33天
				$content["orientation"][0]['count'] 	=  0;
				$content["orientation"][0]['type'] 	    =  1;
				
				$content["managed_to_record"][0]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][0]['service_type'] 	=  "1";
				$content["managed_to_record"][0]['end_time']   		=  (33 * 24 * 60 * 60);      //  期限 33天
				$content["managed_to_record"][0]['state'] 	    	=  0;
				
				$content["managed_to_record"][1]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][1]['service_type'] 	=  "2";
				$content["managed_to_record"][1]['end_time']   		=  (33 * 24 * 60 * 60);      //  期限 33天
				$content["managed_to_record"][1]['state'] 	    	=  0;
				
				$content["managed_to_record"][2]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][2]['service_type'] 	=  "3";
				$content["managed_to_record"][2]['end_time']   		=  (33 * 24 * 60 * 60);      //  期限 33天
				$content["managed_to_record"][2]['state'] 	    	=  0;
				
				$content["managed_to_record"][3]['user'] 	    	=  _USERTEL_;
				$content["managed_to_record"][3]['service_type'] 	=  "4";
				$content["managed_to_record"][3]['end_time']   		=  (33 * 24 * 60 * 60);      //  期限 33天
				$content["managed_to_record"][3]['state'] 	    	=  0;
				
				break;
			default :					
				$this->error("该单无法获取礼包");
				break;
		}
		
		return $content;		
		
	}
		// 获取礼包
	function get_packpage(){
							
		$table_prefix			   =  substr(_USERTEL_,0,3);
		$order_where['user'] 	   =  _USERTEL_;
		$order_where['id'] 		   =  $_GET['order_id'];
		
		$order_info = M($table_prefix."_order")->where($order_where)->find();		
		
		$content  = $this->package_content($order_info['pay_money']);

	//  获取 给用户增加的材料数量
		$material_where['level']  =  array("elt",$content['material_level']);
		$house_list = M("house")->where($material_where)->field("cost")->select();
		
		foreach($house_list as $val){
			$material = unserialize($val['cost']); unset($material['price']);
			foreach($material as $v){
				$material_list[$v['seed_id']]['number'] += $v['seed_value'];
			}	
		}
		//  获取材料名称
		foreach($material as $key=>$val){
			$join  = " left join ".$table_prefix. "_meterial_warehouse as mw on mw.props='".$key."' and  mw.user='".$order_where['user']."'";
			$field = "house_material.* , mw.id as mw_id ,mw.num";
			$info = M("house_material")->where("house_material.id=$key")->join($join)->field($field)->find();			
			$material_list[$key]['name']  = $info['name'];
			$material_list[$key]['id']    = $info['mw_id'];
			$material_list[$key]['num']   = (int)$info['num'];					
		}	

		if($order_info['is_gift'] == 1 && $order_info['get_gift'] == 0 ){			
			//  开始为用户增加  道具 金币				
				M()->startTrans();				
				$members_where['user'] 			=    $order_info['user'];
				//  给用户增加钻石  及 金币
					$members_save_data['diamond']  = array("exp","diamond+".$content['member_diamond']);
					$members_save_data['coin']	   = array("exp","coin+".$content['member_coin']);
					$res_members   = M($table_prefix."_members")->where($members_where)->save($members_save_data);
				//  给用户增加种子
					$prop_where			  = $members_where;
					$prop_where['props']  = "种子";
					$prop_data = M($table_prefix."_prop_warehouse")->where($prop_where)->find();					
					$prop_data['user']	 = $order_info['user'];
					$prop_data['props']  = "种子";
					$prop_data['prop_id']= 6;
					$prop_data['num']	+= $content['member_seed'];					
					$res_prop      = M($table_prefix."_prop_warehouse")->where($members_where)->add($prop_data,"",true);
				//  给用户增加材料
					foreach($material_list as $key=>$val){	
						$material_data['user']	 	= $order_info['user'];
						$material_data['prop_name'] = $val['name'];
						$material_data['props'] 	= $key;
						$material_data['num']	    = $val['num'] + $val['number'];					
						$material_data['id']	    = $val['id'];					
						$res_material[]  = M($table_prefix."_meterial_warehouse")->where($members_where)->add($material_data,"",true);
					}
				//  给用户增加佣金
					$res_commission = $this->deal_Commission( $order_info['user'],( $content['member_diamond'] / 100) );
				//2017年10月18日10:23:00	
				// 	增加作物定向
					foreach($content["orientation"] as $key=>$val){												
						$res_orientation[]  = M("seed_orientation")->add($val);
						// echo M("seed_orientation")->getLastSql();die;
					}
				//  增加 守护	
					foreach($content["managed_to_record"] as $key=>$val){	
						// 查询该用户该守护是否还有
						$where 					= array();
						$where['user']  		= $val['user'];
						$where['service_type']  = $val['service_type'];
						
						$managed_to_record = M($table_prefix."_managed_to_record")->where($where)->find();
						
						$val['id']        = $managed_to_record 						? $managed_to_record['id'] 		   : '';
						$val['end_time'] += $managed_to_record['end_time'] > time() ? $managed_to_record['end_time']   : time();						
						
						$res_m_t_record[]  = M($table_prefix."_managed_to_record")->add($val,"",true);
						
					}
				
				
				//  修改订单 礼包获取状态
					$res_order   = M($table_prefix."_order")->where("id=".$order_info['id'])->setField('get_gift',1);
					
					if( $res_members && $res_prop && count($res_material) == count($material_list) && count($res_orientation) == count($content["orientation"]) && count($res_m_t_record) == count($content["managed_to_record"]) && $res_commission == 'success' && $res_order ){
						
						M()->commit();
						
						$this->assign("material_list",$material_list);
						
						$order_info = M($table_prefix."_order")->where($order_where)->find();
						
						$this->assign("order_info",$order_info);
						
						$this->display("success");
						
					}else{
						
						M()->rollback();

						$this->error("暂无法获取礼包，请稍后重试",U('Prize/show_success', 'order_id='.$order_info['id']) );
						
						die;
						
					}
														
		}else{
			$this->assign("material_list",$material_list);
			
			$this->assign("order_info",$order_info);
						
			$this->display("success");
			
		}		
		
	}
	
	protected function deal_Commission($user,$sum){
	
		$Pay=new Pay();		
			
		return $Pay->recharge($user,$sum);
		
	}

	// 发起支付请求
	private function do_pay_request($order_info){
		
		$Usertel = $order_info['user'];
		
		if($order_info['state'] == 2){
			
			unset($_SESSION['account']['order_id']);
			$this->error("该订单已失效，请重新提交");
			
		}
		
		$res = $order_info['id'];
		
		// 2017-07-27  新接口  订单号必须为全数字
		$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
		
		if($res){								
				
				switch( $order_info['pay_bank'] ){
					case 2001:
						unset( $_SESSION['account']['order_id']);

						$this->success("提交成功，收到汇款后，30分钟内为您完成充值",U('Prize/index'));
						
						break;
					case 992:
										
						$Pay_name 			= "支付宝";
						$Pay_name_img 		= "ZhiPayBack";
										
						//  配备 同步及异步 链接地址
						$notify_url  = $this->REQUEST_SCHEME  . "://" . $this->UrlHost . U('Pay/alipaypoststate');			// 异步

						$return_url  = $this->REQUEST_SCHEME  . "://" . $this->UrlHost . U('Pay/alipaygetstate');			// 同步
					
						require_once("ThinkPHP/Library/Vendor/AliPayApi".'/wappay/pay.php');
						
						$this->assign('Pay_img',$Pay_img);
						
						$this->assign('Order_id',$res);

						$this->assign('Pay_name',$Pay_name);
						
						$this->assign('Pay_name_img',$Pay_name_img);
						
						$this->display("pay_go");										
								
					case 9999:
					
						$Pay_name 			= "扫码";
						$Pay_name_img 		= "ScanPayBack";
						
						$today = date('Y-m-d');
												
						$Pay_img =  "Application/Runtime/ScanCode/".$today."/".$Usertel."_". $order_info['id'] . ".png" ;	
							
						if ( ! file_exists( $Pay_img ) ){ 
							//  2017-07-27
							$PayWeZhi = new \Org\Our\PayWeZhi();
																			
							// $order_info['money'] = 1;  //测试
							
							$Pay_url = $PayWeZhi->createPcOrder( $new_api_order_num , $order_info['money'] );	

							$log_dir = dirname($Pay_img);
							if (!is_dir($log_dir)) {
								mkdir($log_dir, 0755, true);
							}							
							
							Vendor('phpqrcode.phpqrcode');
							
							$errorCorrectionLevel =intval(3) ;//容错级别 
							
							$matrixPointSize = intval(4);//生成图片大小 
							//生成二维码图片 

							$object = new \QRcode();
							 
							 //  如果，没有文件夹 则创建																				
							
							$object->png($Pay_url ,  $Pay_img, $errorCorrectionLevel, $matrixPointSize, 2); 
													
						}
						
						$this->assign('Pay_img' ,  $Pay_img );						
						
						$this->assign('Order_id',$res);						

						$this->assign('Pay_name',$Pay_name);
						
						$this->assign('Pay_name_img',$Pay_name_img);
						
						$this->display("pay_go");										
											
						exit;	
												
					case 1004:				
											
						$Pay_name 			= "微信";
						$Pay_name_img   	= "WeChetPay";
						
						$PayWeZhi = new \Org\Our\PayWeZhi();
						
						$Pay_url = $PayWeZhi->createPcOrder( $new_api_order_num , $order_info['money'] );	
						
						$today = date('Y-m-d');
						
						$_SESSION['wechat']['lastorderid'] 	 =	 $order_info['id'];
						
						$Pay_img =  "Application/Runtime/ScanCode/".$today."/".$Usertel."_". $order_info['id'] . ".png" ;
												
						if ( ! file_exists( $Pay_img ) ){ 
							//  2017-07-27																																
							Vendor('phpqrcode.phpqrcode');
							
							$errorCorrectionLevel =intval(3) ;//容错级别 
							
							$matrixPointSize = intval(4);//生成图片大小 
							//生成二维码图片 

							$object = new \QRcode();
							 
							 //  如果，没有文件夹 则创建
							
							$log_dir = dirname($Pay_img);
							if (!is_dir($log_dir)) {
								mkdir($log_dir, 0755, true);
							}							
							
							$object->png($Pay_url , $Pay_img , $errorCorrectionLevel, $matrixPointSize, 2); 
													
						}
						
						$this->assign('Pay_img' ,  $Pay_img );						
						
						$this->assign('Pay_url',$Pay_url);
						
						$this->assign('Pay_name_img',$Pay_name_img);					
						header("location:".$Pay_url);
						die;
						
					default :	
										
						$this->error("支付方式不正确");
						
						break;
				}				

		}else{

			$this->error("订单提交失败，请稍后重试");

		}		
	}
	
	// 生成订单最后一位数字
	private function getlastnume($str){
		
		$arr_ch    = array('1', '0', '9', '8', '7', '6', '5', '4', '3', '2');
		
		$arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		
		$arr_count = strlen($str);		
		
		$sign = 0;
		for ( $i = 0; $i < $arr_count; $i++ )
        {
			
            $b = (int) $str{$i};
            $w = $arr_int[$i];
            $sign += $b * $w;
        }
		
		$n = $sign % 10;
        $val_num = $arr_ch[$n];
				
		return $val_num;
		
	}
			
}