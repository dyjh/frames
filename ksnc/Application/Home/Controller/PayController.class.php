<?php
namespace Home\Controller;
use Think\Controller;

use Home\Model\UserTableInfoModel;

class PayController extends HomeController {

    public  $PayApiPath   = 'ThinkPHP/Library/Vendor/PayApi';

    private $PayApiIsOpen = true;                      //  是否开始第三方支付接口

    private $PayPrefix    = "kszypay";                 //  充值订单前缀

    private $CashPrefix   = "kszycash";                //  提现订单订单前缀
	
    private $UrlHost      = "lyogame.cn";              //  充值回调域名
	
    private $CashStart    = "9";             		   //  提现开始时间
	
    private $CashEnd      = "18";		               //  提现结束时间
	
	private $REQUEST_SCHEME = "http";				

	private $cash_set_ali_money   = 2499 ; 			   //  支付宝提现上限金额  超过 转换为银行接收

    protected  $IsOpenTokenVerify = true;
	
    public function __construct(){

        parent::__construct();
      	
		$varify_login = array("pay_test","pay_go","usercash","dobindbank","bindbank","cancel_cash","pay_again","cancel_pay","security","ordertate","bank_remit","paygotogame","payapitest","show_success");

        if(in_array(ACTION_NAME,$varify_login) ){
            if(!session('?login') ){
                $this->error('您没有登录，请先登录',U('User/lyologin'),3);
            }
        }
		
        $this->assign('PayApiPath',$this->PayApiPath);
        $this->assign('PayApiIsOpen',$this->PayApiIsOpen);

    }

	// 充值页面 
    public function pay_test(){

		// $this->error("系统维护中。请稍候");
        require_once($this->PayApiPath.'/php/shunfoo/ClassShunfoo.php');

        $PayApi = new \shunfoo();

        //  网银支付列表。
        $this->assign('shunfoo_banktype',$shunfoo_banktype);

        $this->assign('shunfoo_banktype_support',$shunfoo_banktype_now_support);

        // 获取用户 充值记录
        $member_pro = substr(_USERTEL_,0,3);

        $member_where['user'] = _USERTEL_;

        $UserTable = new UserTableInfoModel($member_pro."_order");

        $UserEntrustList = $UserTable->GetUserEntrust();
				
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
		
        $this->assign('nav_titels',"账户充值");

        $this->assign('All_Entrust',$UserEntrustList['All_Entrust']);

        $this->display();

    }

	// 执行添加充值操作
    /**
     *  增加订单
     */
    public function pay_go(){

        if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify  ){
            // 令牌验证错误
            $this->redirect("Pay/pay_test");

        }

        if( $_POST['amount'] < 11 ||  $_POST['amount'] > 2000 || $_POST['amount'] % 10 == 0 || ! is_numeric($_POST['amount']) ){

            $this->error("充值金额最低充值11金币，且单笔低于2000金币。");

        }

        if(! $_POST['bankType'] || !is_numeric( $_POST['bankType'] ) ){

            $this->error("请选择支付方式");

        }

        if(! is_numeric($_POST['account'])){

            $this->error("请直接输入充值用户手机号码");

        }						

        //  创建订单
        if($_POST['account'] || !is_numeric( $_POST['bankType'] )){

            $table_prefix = substr(_USERTEL_,0,3);

            $member_is_exist = M($table_prefix."_members")->where("user='"._USERTEL_."'")->find();

            if(! $member_is_exist){
                $this->error("用户不存在，请确认后输入");
            }			
			
			switch( $_POST['bankType'] ){
				case 2001:
					unset( $_SESSION['account']['order_id']);
					$this->success("提交成功，收到汇款后，30分钟内为您完成充值",U('Pay/pay_test'));					
					break;
				case 992:
					$Pay_name 			= "支付宝";
					$Pay_name_img 		= "ZhiPayBack";	
					if(_USERTEL_ != '18780164595'){											
						$this->error("暂不开放充值！");
					}					
					break;
				case 9999: 
					if(_USERTEL_ != '18780164595'){											
						// $this->error("扫码支付维护升级中，请使用支付宝支付");
						$this->error("暂不开放充值！");
					}
					$Pay_name 			= "扫码";
					$Pay_name_img 		= "ScanPayBack";
										
					break;
				case 1004:		
					if(_USERTEL_ != '18780164595'){	
						// $this->error("微信支付维护升级中，请使用支付宝支付");	
						$this->error("暂不开放充值！");
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
		
			$order_info['user']         =   _USERTEL_;
			$pre_order_num 		     	= 	$this->PayPrefix . $table_prefix . substr(_USERTEL_,-4,-1) . $time;
			
			//  2017-08-01
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
			$new_api_order_num			=   $table_prefix . substr(_USERTEL_,-4,-1) . $time;
			$last_order_num  			= 	$this->getlastnume($new_api_order_num) ;
			$order_info['order_num']    =   $order_id =			$pre_order_num . $last_order_num;
			
			$order_info['money']        =   $_POST['amount'];
			$order_info['pay_bank']     =   $_POST['bankType'];
			$order_info['pay_cash']     =   1;
			
			$order_info['add_time']     =   $time;
			
			if(_USERTEL_ == '18780164595'|| _USERTEL_ == '18382050570'){
				// $order_info['money']        =   0.01;
			}
			
			$res = M($table_prefix."_order")->add($order_info);
			
			$order_info['id']    =   $res;

			$_SESSION['account']['order_id'] = $res;
			
			$user_pay_log  =  " 充值金额 ： " . $order_info['money'] . "\n";
			$user_pay_log .=  " 充值方式 ： " . $Pay_name . "\n";
			$user_pay_log .=  " 订单编号 ： " . $order_info['order_num'] . "\n";
			
			// 保存日志
			$Log_Save = new \Think\Log();
			$order_info_log = $Log_Save->record($user_pay_log,"INFO");
			$Log_Save->save("file","Application/Runtime/PayLog/".date("Y-m-d")."/"._USERTEL_.".txt");						
			$this->do_pay_request($order_info);

            $this->error("请直接输入充值用户手机号码");

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

	// 提现页面
    function usercash(){
		
		// 2017年10月11日17:40:33  
		// qhp 
		//
		// $UserController = new UserController;
		// $UserController->reset_user_cash();

        $this->assign('nav_titels',"金币回收");
        // 判断用户是否绑定过 银行账号
        $member_pro = substr(_USERTEL_,0,3);

        $UserTable = new UserTableInfoModel($member_pro."_members");

        $Userorder = new UserTableInfoModel($member_pro."_order");

        $BankInfoIsExists = $UserTable->IsBindBankInfo(_USERTEL_);
		
		// 判断用户是否绑定过 银行账号和银行支行
		$user_infomation = M($member_pro . "_infomation")->field('user,ali_num,bank_subbranch')->where("user='"._USERTEL_."'")->find();

        if(! $BankInfoIsExists['bank_name'] || ! $BankInfoIsExists['bank_num'] || !$user_infomation['bank_subbranch'] || !$user_infomation['ali_num'] ){           
            $this->error("请填写银行账户及开户支行网点及支付宝账户",U("Pay/bindbank","update=1"));			
        }

        $UserUsercash = $Userorder->IsBindUsercashInfo(_USERTEL_);

        $UserList = M($member_pro."_members")->where('user="'._USERTEL_.'"')->find();

		/**
		 *   增加用户金币表
		 *   用户提现仅支持果实买卖 及 佣金中的金币
		 *   2017-7-26 
		 */
		
		$UserGoldInfo = M($member_pro."_users_gold")->where('user="'._USERTEL_.'"')->find();
		
		$UserCanCash = $UserGoldInfo['user_fees'] + $UserGoldInfo['buy_and_sell'];
		
		$UserCanCash = $UserList['coin'] < $UserCanCash ? $UserList['coin'] : $UserCanCash ;
		
		$filed = "user";
		
		$res = M("user_freeze")->field($filed)->where("user='".$_SESSION['login']['user']."'")->find();
		
		if( $res['is_cash'] == 1 ){

            $UserCanCash = 0;

        }
		
        $_SESSION['login']['pay_password'] = $UserList['pay_password'];

        $this->assign('UserUsercash', $UserUsercash);
		
        $this->assign('UserCanCash', $UserCanCash);

        $this->assign('BankInfoIsExists', $BankInfoIsExists['All_Entrust']);

        $this->assign('UserList', $UserList);

        $this->display();

    }

	// 支付宝账户提现页面
    function alicash(){
		
        $this->assign('nav_titels',"金币回收");
        // 判断用户是否绑定过 银行账号
        $member_pro = substr(_USERTEL_,0,3);
		
		$Userorder = new UserTableInfoModel($member_pro."_order");
		
		$UserTable = new UserTableInfoModel($member_pro."_members");

        $BankInfoIsExists = $UserTable->IsBindBankInfo(_USERTEL_);
	
		$user_infomation = M($member_pro . "_infomation")->field('user,ali_num,bank_subbranch')->where("user='"._USERTEL_."'")->find();

        if(! $BankInfoIsExists['bank_name'] || ! $BankInfoIsExists['bank_num'] || !$user_infomation['bank_subbranch'] || ! $user_infomation['ali_num']  ){
            $this->error("请填写银行账户及开户支行网点及支付宝账户",U("Pay/bindbank","update=1"));	
        }

        $UserUsercash = $Userorder->IsBindUsercashInfo(_USERTEL_);

        $UserList = M($member_pro."_members")->where('user="'._USERTEL_.'"')->find();

		/**
		 *   增加用户金币表
		 *   用户提现仅支持果实买卖 及 佣金中的金币
		 *   2017-7-26 
		 */
		
		$UserGoldInfo = M($member_pro."_users_gold")->where('user="'._USERTEL_.'"')->find();
		
		$UserCanCash = $UserGoldInfo['user_fees'] + $UserGoldInfo['buy_and_sell'];
		
		$UserCanCash = $UserList['coin'] < $UserCanCash ? $UserList['coin'] : $UserCanCash ;
		
		$filed = "user";
		
		$res = M("user_freeze")->field($filed)->where("user='".$_SESSION['login']['user']."'")->find();
		
		if( $res['is_cash'] == 1 ){

            $UserCanCash = 0;

        }
		
        $_SESSION['login']['pay_password'] = $UserList['pay_password'];

        $this->assign('UserUsercash', $UserUsercash);
		
        $this->assign('UserCanCash', $UserCanCash);

        $this->assign('user_infomation', $user_infomation);

        $this->assign('UserList', $UserList);

        $this->display();

    }
	
	// 绑定银行账户页面
    function bindbank(){

        $this->assign('nav_titels',"账户绑定");

        require_once($this->PayApiPath.'/php/shunfoo/init.php');

        //  网银支付列表。
        $this->assign('shunfoo_banktype',$shunfoo_banktype);

        $member_pro = substr(_USERTEL_,0,3);

        $UserTable = new UserTableInfoModel($member_pro."_members");

        $BankInfoIsExists = $UserTable->IsBindBankInfo(_USERTEL_);

        if( $BankInfoIsExists['bank_name'] && $BankInfoIsExists['bank_num'] ) {

            $this->assign('can_update_bind', 1);
            $this->assign('have_cash_num', 1);

        }
		
		if($_GET['update'] || !$BankInfoIsExists['bank_name'] || !$BankInfoIsExists['bank_num'] ) {

            $this->assign('can_update_bind', 1);
            $this->assign('have_cash_num', 0);

        }
		
		/**
		 *	获取支付宝及 银行支行	
		 */
		 
		$user_infomation = M($member_pro . "_infomation")->field('user,ali_num,bank_subbranch')->where("user='"._USERTEL_."'")->find();		

        $this->assign('BankInfoIsExists', $BankInfoIsExists);
		
        $this->assign('user_infomation', $user_infomation);

        $this->display();

    }

	// 绑定银行卡操作
    function dobindbank(){

        if ( ! M()->autoCheckToken($_POST) ){
            // 令牌验证错误
           $this->redirect("Pay/bindbank");
        }
		
        //判断 银行卡号是否合法
        $IsBankCard = bankInfo($_POST['bank_num']);

        if(! $IsBankCard){
            $this->error("银行卡号错误");
        }

        if(! $_POST['bank_name'] ){
            $this->error("所属银行错误");
        }
		
		if(! $_POST['bank_name_branch'] ){
            $this->error("填写银行支行");
        }

        $member_pro = substr(_USERTEL_,0,3);

        $UserTable = new UserTableInfoModel($member_pro."_members");

        $result = $UserTable->BindBankInfo(_USERTEL_);
		
		/**
		 *	 增加 添加用户银行支行的配置      
		 *	          用户支付宝配置
		 */

		$info_data['bank_subbranch']   =  $_POST['bank_name_branch'];		//  银行支行
		$info_data['ali_num']   	   =  $_POST['AliPayNum'];				//  支付宝账户
		$info_data['user']   	 	   =  _USERTEL_;				//  支付宝账户
		
		M($member_pro . "_infomation")->add($info_data,'',true);
		
        if($result['state'] >= 0){

            $this->success("提交成功",U("Pay/bindbank"));

            // $this->display("bindbank");

        }else{

            $this->error("未知错误，请稍后重试");

        }
    }

	// 申请提现
    function dousercash(){

        if(! $_SESSION['login']['user'] ){

            $this->error("请先登录",U("User/login"));

        }
			
		if( date("H") < $this->CashStart ||  date("H") >= $this->CashEnd ){

            $this->error("请在提现处理时间内提交");

        }				

        if ( ! M()->autoCheckToken($_POST) && $this->IsOpenTokenVerify ||  md5($_SESSION['login']['pay_password']) != $_POST['Pwd']){
            // 令牌验证错误
            $this->redirect("Pay/usercash");
        }
				
        if(! $_POST['Amount'] || $_POST['Amount'] < 100 || $_POST['Amount'] > 20000 || !is_numeric( $_POST['Amount'] ) ||  $_POST['Amount'] % 100 != 0){

            $this->error("回收金币须为100的倍数");

        }

        $table_prefix = substr(_USERTEL_,0,3);

        $member_info  = M($table_prefix."_members")->where("user='"._USERTEL_."'")->field("coin,bank_name,bank_num,name")->find();		
		
		/***
		 *  增加判断  用户提现金额 为  佣金中的金币 及 果实买卖
		 */ 
		$UserGoldInfo = M($table_prefix."_users_gold")->where('user="'._USERTEL_.'"')->find();
		
		$UserCanCash  = $UserGoldInfo['user_fees'] + $UserGoldInfo['buy_and_sell'];

		$UserCanCash = $member_info['coin'] < $UserCanCash ? $member_info['coin'] : $UserCanCash ;
		
		$filed = "user";
		
		$res = M("user_freeze")->field($filed)->where("is_cash=1")->select();
		foreach($res as $val){
			$CanNotCashUserList[] =  $val['user'];
		}
		if(in_array( $_SESSION['login']['user'] , $CanNotCashUserList )){

            $UserCanCash = 0;

        }
		
        if($_POST['Amount'] > $member_info['coin'] || $_POST['Amount'] > $UserCanCash){

            $this->error("金币不足");

        }
		
		//  如果 金额超过 设定价格  自动转换为 银行充值
		$default_cash_way           =  ($_POST['Amount']  >  $this->cash_set_ali_money)  ? 0 : 1 ;   //  设定默认的提现方式
		
		$_POST['IsAliCash'] 		=  isset($_POST['IsAliCash']) ?  ($_POST['IsAliCash'])  :  $default_cash_way;		// 用户和如果选择了银行卡提现设置为银行卡提现
		
		/*
		 *   无FUCK说
		 *   2017年8月29日12:08:07
		 *   叮
		 */
		// $_POST['IsAliCash'] = $default_cash_way;
		/**
		 *	获取用户阿里 银行支行
		 */
		
		$user_infomation = M($table_prefix . "_infomation")->field('user,ali_num,bank_subbranch')->where("user='"._USERTEL_."'")->find();
		
        $order_info['user']         =    _USERTEL_;
        $order_info['order_num']    =    $order_id = $this->CashPrefix . substr(_USERTEL_,-4,-1) . date("ymdHis");
        $order_info['money']        =    $_POST['Amount'];
        $order_info['pay_bank']     =    ($_POST['IsAliCash']==1) ? (" 支付宝账户 ------ " . $user_infomation['ali_num']) : ($member_info['bank_name']." - " . $user_infomation['bank_subbranch'] . "-" .$member_info['name'].' - '.$member_info['bank_num']);
        $order_info['pay_cash']     =    2;
        $order_info['add_time']     =    time();

        M()->startTrans();

        $res_order  = M($table_prefix."_order")->add($order_info);
        $res_member = M($table_prefix."_members")->where("user='"._USERTEL_."'")->setDec("coin",$_POST['Amount']);
		/***
		 *  增加修改 用户金币表中 佣金金币 或 果实买卖
		 */ 
		$gold_info['user_fees']    = ($_POST['Amount'] < $UserGoldInfo['user_fees']) ? ($UserGoldInfo['user_fees']  - $_POST['Amount'] ) : "0" ;    
		$gold_info['buy_and_sell'] = ($_POST['Amount'] < $UserGoldInfo['user_fees']) ?  $UserGoldInfo['buy_and_sell'] : ($UserGoldInfo['buy_and_sell'] + $UserGoldInfo['user_fees'] - $_POST['Amount'] );    
		
		$res_gold   = M($table_prefix."_users_gold")->where("user='"._USERTEL_."'")->save($gold_info); 			
		
        if($res_member && $res_order && $res_gold){
            M()->commit();
            $this->success("提交成功，请等待审核!", U('Pay/usercash'));
        }else{
            M()->rollback();
            $this->error("未知错误,请重试");
        }
    }
	
	// 取消提现
	function cancel_cash(){
		
		if(! $_SESSION['login']['user'] ){

            $this->error("请先登录",U("User/login"));

        }
		
		$table_prefix = substr(_USERTEL_,0,3);
		
		$where['state']     = 0;
		$where['pay_cash']  = 2;
		$where['id']		= $_POST['OrderId'];
		$where['user']      = _USERTEL_;
				
		$order_info = M($table_prefix."_order")->where($where)->find();		

		if( !$order_info ){
			
			$data['error']   = 1;
			$data['message'] = "没有该订单";
			echo json_encode($data);
			die;
			
		}
		
		M()->startTrans();
		
		$order_save_data['state'] = 2;
		
		$res_order  = M($table_prefix."_order")->where($where)->save($order_save_data);
		
        $res_member = M($table_prefix."_members")->where("user='"._USERTEL_."'")->setInc("coin",$order_info['money']);
		
		/***
		 *  增加修改 用户金币表中 佣金金币 或 果实买卖
		 */ 		 
		
		$res_gold   = M($table_prefix."_users_gold")->where("user='"._USERTEL_."'")->setInc("user_fees",$order_info['money']);		
		
        if($res_member && $res_order && $res_gold){
            M()->commit();
			echo 1;
        }else{
            M()->rollback();
			echo 0;
        }
		
	}

	// 重新发起支付
	function pay_again(){		
		
		$table_prefix = substr(_USERTEL_,0,3);
		
		// $where['state']     = 0;
		$where['pay_cash']  = 1;
		$where['id']		= $_GET['OrderId'];
		$where['user']      = _USERTEL_;
				
		$order_info = M($table_prefix."_order")->where($where)->find();		

		$_SESSION['account']['order_id'] = $order_info['id'];
		$this->do_pay_request($order_info);				
		if( $order_info['state'] == 0 && $order_info){
			
			$this->do_pay_request($order_info);					
			
		}elseif( $order_info['state'] == 1 ){
			
			$this->redirect('Pay/show_success', array('order_id' => $order_info['id'])) ;
			
			$this->do_pay_request($order_info);

		}else{
			
			$this->error('该订单已失效...' ,U('Pay/pay_test') );
			
		}
		
	}
	
	// 取消充值
	function cancel_pay(){
				
		$table_prefix = substr(_USERTEL_,0,3);
		
		$where['state']     = 0;
		$where['pay_cash']  = 1;
		$where['id']		= $_POST['OrderId'];
		$where['user']      = _USERTEL_;
				
		$order_info = M($table_prefix."_order")->where($where)->find();		

		if( !$order_info ){
			
			$data['error']   = 1;
			$data['message'] = "没有该订单";
			echo json_encode($data);
			die;
			
		}
		
		M()->startTrans();
		
		$order_save_data['state'] = 2;
		
		$res_order  = M($table_prefix."_order")->where($where)->save($order_save_data);
		
        if( $res_order ){
            M()->commit();
			echo 1;
        }else{
            M()->rollback();
			echo 0;
        }
		
	}

	// 设置支付密码
    function security(){
        if(IS_POST){
			
            $arre['pay_password'] = md5(I('post.Pwd'));
			
            $member_pro = substr(_USERTEL_,0,3);
			
            $UserTable = new UserTableInfoModel($member_pro."_members");
			
            if($UserTable->where('user="'._USERTEL_.'"')->save($arre)){
                $this->redirect("Pay/security");
            }else{
                $this->redirect("Pay/security");
            }

        }else{
            $member_pro = substr(_USERTEL_,0,3);

            $UserTable = new UserTableInfoModel($member_pro."_members");

            $UserList = $UserTable->where('user="'._USERTEL_.'"')->find();

            if($_GET['update']=='true'){
                $this->assign('update', $_GET['update']);
            }

            if(! $UserList ){
                $this->assign('update', $_GET['update']);
            }

            $this->assign('UserList', $UserList);

            $this->display();
        }
    }

	// 银行转账页面
    function bank_remit(){

        require_once($this->PayApiPath.'/php/shunfoo/init.php');

        $this->assign('shunfoo_banktype_support',$shunfoo_banktype_now_support);

        $member_pro = substr(_USERTEL_,0,3);

        $UserTable_members = new UserTableInfoModel($member_pro."_members");

        $BankInfoIsExists = $UserTable_members->IsBindBankInfo(_USERTEL_);

        $UserTable_order = new UserTableInfoModel($member_pro."_order");
		
		$member_where['user'] = _USERTEL_;

        $UserEntrustList = $UserTable_order->GetUserEntrust();

        $this->assign('nav_titels',"账户充值");

        $this->assign('All_Entrust',$UserEntrustList['All_Entrust']);

        $this->assign('BankInfoIsExists', $BankInfoIsExists);

        $this->display();

    }

	// 官网进入游戏
    function paygotogame(){
       
	   if(! $_SESSION['login']) {
            $this->error("请先登录");
        }

        $USERIP     = $_SERVER["REMOTE_ADDR"];
        $USERIDCARD = $_SESSION['login']['id_card'];
        $USER       = $_SESSION['login']['user'];

        $game_url = __ROOT__."/../farms/Index/index";
		
		$_SESSION['user'] = $USER;

       // $game_url .= "?" ."user=" . $USER . "&mac=". md5($USERIP) . "&key=". md5( $USER.$USERIDCARD );

        header("location:".$game_url);

    }

	// 游戏进入金币充值
    function payapitest(){

        $USER       = $_GET['user'];
        $USER_USERCARDID       = $_GET['key'];
        $prefix = substr($USER,0,3);
		
        $field = "id,user,nickname,headimg,coin,coin_freeze,diamond,login_time,name,id_card,referees,level";
        $user_info = M($prefix."_members")->field($field)->where("user='".$USER."'")->find();

        if($USER_USERCARDID === md5($user_info['user'].$user_info['id_card'])){
            $_SESSION['login'] = $user_info;
             $this->redirect("Pay/pay_test");
        }else{
            unset($_SESSION['login']);
            $this->redirect("User/lyologin");
        }


        $USERIP     = $_SERVER["REMOTE_ADDR"];
        $USERIDCARD = $_SESSION['login']['id_card'];
    }
	
	// 接口获取订单状态
	function ordertate(){
		
		$table_prefix			   =  substr(_USERTEL_,0,3);
		$order_where['user'] 	   =  _USERTEL_;
		$order_where['id'] 		   =  $_POST['order_id'];
		$order_where['pay_cash']   =  1;
		// $order_where['order_num']  =  $_POST['order_id'];

		$order_info = M($table_prefix."_order")->where($order_where)->find();

		if( !$order_info ){				
				$data['error']   = 1;
				$data['message'] = "没有该订单";
				echo json_encode($data);
				die;				
		}
		
		if($order_info['state']==1){
			$data['success']  =  1;
			$data['url']      = U("Pay/show_success/?order_id=".$order_where['id']);
			
			if( $order_info['is_gift']  == 1 ){ 
				$data['url']      = U("Prize/show_success/?order_id=".$order_where['id']);
			}
			     
			echo json_encode($data);
			die;
		}elseif($order_info['state']==0){			
						
			// 通过接口获取订单状态		
			if($order_info['pay_bank'] == 9999 || $order_info['pay_bank'] == 1004){
				
				// 2017-07-27  新接口  订单号必须为全数字
				$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
			
				
				$PayWeZhi = new \Org\Our\PayWeZhi();
				
				$PayOrderState = $PayWeZhi->queryOrder( $new_api_order_num  );
											
				// 如果支付成功
				// 进行字段验证
				$PayOrderState = (array)json_decode($PayOrderState);
				
				$merchantOutOrderNo  =  $PayOrderState['merchantOutOrderNo'];				// 提交的订单号
				$merid  			 =  $PayOrderState['merid'];							// 商户ID
				$orderMoney  		 =  $PayOrderState['orderMoney'];						// 返回的金额
				$orderNo  			 =  $PayOrderState['orderNo'];							// 订单平台编号
				
			}
		
	
			if($PayOrderState['payResult'] == 1){
											
				$verify_sign   = $merchantOutOrderNo . $merid . $orderMoney;
				
				//  设置千分符不以逗号；

				/**
				  坑爹啊。。。。。。。。。。。。。。。。。。				  				 				  
							 ,%%%%%%%%,
						   ,%%/\%%%%/\%%
						  ,%%%\c "" J/%%%
				 %.       %%%%/ o  o \%%%
				 `%%.     %%%%    _  |%%%
				  `%%     `%%%%(__Y__)%%'
				  //       ;%%%%`\-/%%%'
				 ((       /  `%%%%%%%'
				  \\    .'          |
				   \\  /       \  | |
					\\/         ) | |
					 \         /_ | |__
					 (___________)))))))
										
				**/
				$verify_ksnc   = $new_api_order_num  . $PayWeZhi->merid  . number_format($order_info['money'],2,".","");					
			
				if(md5($verify_ksnc) === md5($verify_sign)){
								
					$save_order['state']  			=   1;
					$save_order['back_order_num']  	=   $orderNo;
					$save_order['pay_money']  		=   $order_info['money'];
					
					if( $order_info['is_gift'] == 1 ){ $order_info['money'] = 0; }
					
					$save_order['pay_time'] 		=   strtotime($PayOrderState['payTime']);	
					
					M()->startTrans();					
					$members_where['user'] 			=   $order_where['user']     = $order_info['user'];
					$order_where['order_num']  		=   $this->PayPrefix . $merchantOutOrderNo;
					
					$res_order 	   = M($table_prefix."_order")->where($order_where)->save($save_order);												// 修改订单表状态
					$res_members   = M($table_prefix."_members")->where($members_where)->setInc('coin',$order_info['money']);						// 修改用户金币数
					$res_total     = M("total_station")->where("1=1")->setInc("top_num",1);
					$member_record = M($table_prefix."_member_record")->where($members_where)->setInc("top_money",$order_info['money']);
					if( $order_info['is_gift'] == 1 ){ 
						$res_members 	= true; 
						$member_record  = true; 
					}	
					if($res_order && $res_members && $res_total && $member_record){
						M()->commit();
						$_SESSION['account']['order_id'] = '';
						$user_pay_log  =  " 充值金额 ："  . $save_order['money'] 	 . "\n";
						$user_pay_log .=  " 订单编号 ："  . $order_info['order_num'] . "\n";
						$user_pay_log .=  " 支付时间 ："  . $save_order['pay_time']  . "\n";
						$user_pay_log .=  " 平台订单号 ：". $orderNo  			     . "\n";
						
						// 保存日志
						$Log_Save = new \Think\Log();
						$order_info_log = $Log_Save->record($user_pay_log,"INFO");
						$Log_Save->save("file","Application/Runtime/PayLog/" . date("Y-m-d") . "/" . _USERTEL_.".txt");
						
						/**
							增加为团队添加资金功能
						*/
						/*********************************/
						$team_list =  M($table_prefix."_members")->where($members_where)->field('team')->find();
						$this->addteammoney($team_list['team'],$members_where['user'],$orderMoney);							
						/*********************************/
						
						$data['success']  =  1;
						$data['url']      = U("Pay/show_success/?order_id=".$order_where['id']);
						
						if( $order_info['is_gift']  == 1 ){ 
							$data['url']      = U("Prize/show_success/?order_id=".$order_where['id']);
						}
						
						echo json_encode($data);
						die;
					}
					else{
						M()->rollback();
						$user_pay_log  =  " 修改订单状态 ："  . $res_order 	 	. "\n";
						$user_pay_log .=  " 修改用户金币 ："  . $res_members 	. "\n";
						$user_pay_log .=  " 修改全站统计 ："  . $res_total 		. "\n";
						$user_pay_log .=  " 修改用户记录 ："  . $member_record  . "\n";
						echo $user_pay_log;
					}				
												
				}
				
			}
		}	
		
	}		
	
	// 微信 扫码时  先请求接口该单 是否已经进行支付
	private function mdzz_ordertate($order_info,$is_post = false){
		
		$table_prefix			   =  substr($order_info['user'],0,3);

		if( !$order_info ){				
			return false;				
		}
		
		if($order_info['state']==1){
			
			if( $is_post ){				
				echo "success";
				die;				
			}
			
			if( $order_info['is_gift']  == 1 ){ 
				$this->redirect('Prize/show_success', array('order_id' => $order_info['id']));
			}
							
			$this->redirect('Pay/show_success', array('order_id' => $order_info['id']));
		}elseif($order_info['state']==0){			
						
			// 通过接口获取订单状态		
			if($order_info['pay_bank'] == 9999 || $order_info['pay_bank'] == 1004){
				
				// 2017-07-27  新接口  订单号必须为全数字
				$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
							
				$PayWeZhi = new \Org\Our\PayWeZhi();
				
				$PayOrderState = $PayWeZhi->queryOrder( $new_api_order_num  );
											
				// 如果支付成功
				// 进行字段验证
				$PayOrderState = (array)json_decode($PayOrderState);
				
				$merchantOutOrderNo  =  $PayOrderState['merchantOutOrderNo'];				// 提交的订单号
				$merid  			 =  $PayOrderState['merid'];							// 商户ID
				$orderMoney  		 =  $PayOrderState['orderMoney'];						// 返回的金额
				$orderNo  			 =  $PayOrderState['orderNo'];							// 订单平台编号
			
				if($PayOrderState['payResult'] == 1){
											
					$verify_sign   = $merchantOutOrderNo . $merid . $orderMoney;
					
					//  设置千分符不以逗号；

					/**
					  坑爹啊。。。。。。。。。。。。。。。。。。				  				 				  
								 ,%%%%%%%%,
							   ,%%/\%%%%/\%%
							  ,%%%\c "" J/%%%
					 %.       %%%%/ o  o \%%%
					 `%%.     %%%%    _  |%%%
					  `%%     `%%%%(__Y__)%%'
					  //       ;%%%%`\-/%%%'
					 ((       /  `%%%%%%%'
					  \\    .'          |
					   \\  /       \  | |
						\\/         ) | |
						 \         /_ | |__
						 (___________)))))))
											
					**/
					$verify_ksnc   = $new_api_order_num  . $PayWeZhi->merid  . number_format($order_info['money'],2,".","");					
				
					if(md5($verify_ksnc) === md5($verify_sign)){		
						$save_order['state']  			=   1;
						$save_order['back_order_num']  	=   $orderNo;
						$save_order['pay_money']  		=   $order_info['money'];
						
						if( $order_info['is_gift'] == 1 ){ $order_info['money'] = 0; }
						
						$save_order['pay_time'] 		=   strtotime($PayOrderState['payTime']);	
						
						M()->startTrans();					
						$members_where['user'] 			=   $order_where['user']     = $order_info['user'];
						$order_where['order_num']  		=   $this->PayPrefix . $merchantOutOrderNo;
						
						$res_order 	   = M($table_prefix."_order")->where($order_where)->save($save_order);												// 修改订单表状态
						$res_members   = M($table_prefix."_members")->where($members_where)->setInc('coin',$order_info['money']);						// 修改用户金币数
						$res_total     = M("total_station")->where("1=1")->setInc("top_num",1);
						$member_record = M($table_prefix."_member_record")->where($members_where)->setInc("top_money",$order_info['money']);
										
						if( $order_info['is_gift'] == 1 ){ 
							$res_members 	= true; 
							$member_record  = true; 
						}	
						
						if($res_order && $res_members && $res_total && $member_record){
							M()->commit();
						
							$_SESSION['account']['order_id'] = '';
							$user_pay_log  =  " 充值金额 ："  . $save_order['money'] 	 . "\n";
							$user_pay_log .=  " 订单编号 ："  . $order_info['order_num'] . "\n";
							$user_pay_log .=  " 支付时间 ："  . $save_order['pay_time']  . "\n";
							$user_pay_log .=  " 平台订单号 ：". $orderNo  			     . "\n";
							
							// 保存日志
							$Log_Save = new \Think\Log();
							$order_info_log = $Log_Save->record($user_pay_log,"INFO");
							$Log_Save->save("file","Application/Runtime/PayLog/" . date("Y-m-d") . "/" . $order_info['user'].".txt");
							
							/*
							 *	增加为团队添加资金功能
							 */
							/*********************************/
							$team_list =  M($table_prefix."_members")->where($members_where)->field('team')->find();
							$this->addteammoney($team_list['team'],$members_where['user'],$orderMoney);							
							/*********************************/
							if( $is_post ){				
								echo "success";
								die;				
							}
							
							if( $order_info['is_gift']  == 1 ){ 
								$this->redirect('Prize/show_success', array('order_id' => $order_info['id']));
							}
						
							$this->redirect('Pay/show_success', array('order_id' => $order_info['id']));
						}
						else{
							M()->rollback();
							$user_pay_log  =  " 修改订单状态 ："  . $res_order 	 	. "\n";
							$user_pay_log .=  " 修改用户金币 ："  . $res_members 	. "\n";
							$user_pay_log .=  " 修改全站统计 ："  . $res_total 		. "\n";
							$user_pay_log .=  " 修改用户记录 ："  . $member_record  . "\n";
							
							if( $is_post ){				
								echo "error";
								die;				
							}	
							echo $user_pay_log;							
						}				
													
					}
					
				}
			
			}

		}	
		
	}		
	
	private function doalipay_deal($order_info){
		
		$table_prefix			   =  substr($order_info['user'],0,3);
		
		// $order_info['money'] = 0.01;  //测试	
			if($order_info['user'] == '18780164595'){
				// $order_info['money']        =   0.01;
			}

			// 通过接口获取订单状态		
			if($order_info['pay_bank'] == 992){
				
				// 2017-07-27  新接口  订单号必须为全数字
				$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
																		
				$out_trade_no 		   = $new_api_order_num;
				
				// $trade_no 	  		   = $_GET['trade_no'];
									
				require_once("ThinkPHP/Library/Vendor/AliPayApi" . '/wappay/query.php');
						
				// 如果支付成功
				// 进行字段验证
				$PayOrderState = (array)($result);
					
				$merchantOutOrderNo    =  $PayOrderState['out_trade_no'];						// 提交的订单号
				$orderMoney  		   =  $PayOrderState['total_amount'];						// 返回的金额
				$orderNo  			   =  $PayOrderState['trade_no'];							// 订单平台编号
										
			}
			
	
			if($PayOrderState['trade_status'] == "TRADE_SUCCESS"){
															
				$verify_sign   = $merchantOutOrderNo .  number_format($orderMoney,2);
				$verify_ksnc   = $new_api_order_num  .  number_format($order_info['money'],2);		

				if(md5($verify_ksnc) === md5($verify_sign)){
								
					$save_order['state']  			=   1;
					$save_order['back_order_num']  	=   $orderNo;
					$save_order['pay_money']  		=   $orderMoney;
					
					if( $order_info['is_gift'] == 1 ){ $orderMoney = 0; }
					
					$save_order['pay_time'] 		=   strtotime($PayOrderState['send_pay_date']);	
					
					M()->startTrans();					
					$members_where['user'] 			=   $order_where['user']     = $order_info['user'];
					$order_where['order_num']  		=   $this->PayPrefix . $merchantOutOrderNo;
					
					$res_order 	   = M($table_prefix."_order")->where($order_where)->save($save_order);												// 修改订单表状态
					$res_members   = M($table_prefix."_members")->where($members_where)->setInc('coin',$orderMoney);						// 修改用户金币数
					$res_total     = M("total_station")->where("1=1")->setInc("top_num",1);
					$member_record = M($table_prefix."_member_record")->where($members_where)->setInc("top_money",$orderMoney);
					
					if( $order_info['is_gift'] == 1 ){ 
						$res_members 	= true; 
						$member_record  = true; 
					}	
						
					if($res_order && $res_members && $res_total && $member_record){
						M()->commit();						
							$_SESSION['account']['order_id'] = '';
							$user_pay_log  =  " 充值金额 ："  . $save_order['pay_money'] . "\n";
							$user_pay_log .=  " 订单编号 ："  . $order_info['order_num'] . "\n";
							$user_pay_log .=  " 支付时间 ："  . $save_order['pay_time']  . "\n";
							$user_pay_log .=  " 平台订单号 ：". $orderNo  			     . "\n";
							
							// 保存日志
							$Log_Save = new \Think\Log();
							$order_info_log = $Log_Save->record($user_pay_log,"INFO");
							$Log_Save->save("file","Application/Runtime/PayLog/".date("Y-m-d")."/".$order_info['user'].".txt");
							
							// 2017-08-01
							/*
								增加为团队添加资金功能
							*/
							/*********************************/
							$team_list =  M($table_prefix."_members")->where($members_where)->field('team')->find();
							$this->addteammoney($team_list['team'],$members_where['user'],$orderMoney);
							
							/*********************************/
							
							return $data;
					}else{
						M()->rollback();
						$user_pay_log  =  " 修改订单状态 ："  . $res_order 	 	. "\n";
						$user_pay_log .=  " 修改用户金币 ："  . $res_members 	. "\n";
						$user_pay_log .=  " 修改全站统计 ："  . $res_total 		. "\n";
						$user_pay_log .=  " 修改用户记录 ："  . $member_record  . "\n";
						
						echo $user_pay_log;
					}																
				}				
			}
		
		
	}
	
	// 支付宝获取订单状态
	function alipaygetstate(){
		
		$table_prefix			   =  substr($_GET['out_trade_no'],0,3);
		// $order_where['user'] 	   =  _USERTEL_;
		$where['pay_cash']  	   =  1;
		// $order_where['state'] 	   =  0;
		$order_where['order_num']  =  $this->PayPrefix . $_GET['out_trade_no'];			// 平台订单号
		
		$order_info = M($table_prefix."_order")->where($order_where)->find();
		
		// print_r($order_info);die;
				
		if( !$order_info ){
				
			$this->error("订单已失效",U("Pay/pay_test"));
		}
		
		if($order_info['state']==1){
			
			if( $order_info['is_gift']  == 1 ){ 
				$this->redirect('Prize/show_success', array('order_id' => $order_info['id'])) ;
			}

			$this->redirect('Pay/show_success', array('order_id' => $order_info['id'])) ;
			
		}elseif($order_info['state']==0){			
						
			$data = $this->doalipay_deal($order_info);
		
			if($data['success'] == 1){
				$this->redirect('Pay/show_success', array('order_id' => $order_info['id']));
			}

			$this->error("支付失败",U("Pay/pay_test"));
		}
	
		if($data['success'] != 1){
	
			$this->error("支付失败",U("Pay/pay_test"));
			
		}
	}
	
	// 支付宝获取订单状态
	function alipaypoststate(){
		
		$table_prefix			   =  substr($_POST['out_trade_no'],0,3);
		
		$where['pay_cash']  	   =  1;

		$order_where['order_num']  =  $this->PayPrefix . $_POST['out_trade_no'];			// 平台订单号
		
		$order_info 			   =  M($table_prefix."_order")->where($order_where)->find();
		
		$str  = "";
		
		foreach($_POST as $key=>$val){
			
			$str  .= $key . "-----"  . $val . "\n";
			
		}		
		
		if($order_info['state']==0){	

			$this->doalipay_deal($order_info);
		
		}
	
	}
			
	// 支付成功页面
	function show_success(){
			
		if(_USERTEL_ != '13823143881' ){
			// $this->error("充值维护中，请稍后");
		}
						
						
		$table_prefix			   =  substr(_USERTEL_,0,3);
		$order_where['user'] 	   =  _USERTEL_;
		$order_where['id'] 		   =  $_GET['order_id'];
		// $order_where['order_num']  =  $_POST['order_id'];
		
		$order_info = M($table_prefix."_order")->field('state,money,add_time')->where($order_where)->find();

		if($order_info['state']){
			$this->assign("order_info",$order_info);
			$this->assign('nav_titels',"充值成功");
			$this->display("success");
		}else{
			$this->error("您无权限查看此单");
		}		
		die;
	}

	// 发起支付请求
	private function do_pay_request($order_info){
		
		if($order_info['state'] == 2){
			
			unset($_SESSION['account']['order_id']);
			$this->error("该订单已失效，请重新提交");
			
		}
		// print_r($order_info);die;
		
		$res = $order_info['id'];
		
		// 2017-07-27  新接口  订单号必须为全数字
		$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
		
		if($res){								
				
				switch( $order_info['pay_bank'] ){
					case 2001:
						unset( $_SESSION['account']['order_id']);

						$this->success("提交成功，收到汇款后，30分钟内为您完成充值",U('Pay/pay_test'));
						
						break;
					case 992:
										
						$Pay_name 			= "支付宝";
						$Pay_name_img 		= "ZhiPayBack";
					
						// $order_info['money'] = 0.01;  //测试
						if(_USERTEL_ == '18780164595' || _USERTEL_ == '18382050570'){
							// $order_info['money']        =   0.01;
							
						}
						//  配备 同步及异步 链接地址
						$notify_url  = $this->REQUEST_SCHEME  . "://" . $this->UrlHost . U('Pay/alipaypoststate');			// 异步

						$return_url  = $this->REQUEST_SCHEME  . "://" . $this->UrlHost . U('Pay/alipaygetstate');			// 同步
						// print_r($_SERVER);
						// echo 	$notify_url;die;
						require_once("ThinkPHP/Library/Vendor/AliPayApi".'/wappay/pay.php');
						
						$this->assign('Pay_img',$Pay_img);
						
						$this->assign('Order_id',$res);

						$this->assign('Pay_name',$Pay_name);
						
						$this->assign('Pay_name_img',$Pay_name_img);
						
						$this->display("pay_go");										
											
						// exit;
					case 9999:
					
						$Pay_name 			= "扫码";
						$Pay_name_img 		= "ScanPayBack";
						
						$today = date('Y-m-d');
												
						$Pay_img =  "Application/Runtime/ScanCode/".$today."/"._USERTEL_."_". $order_info['id'] . ".png" ;	

						if(_USERTEL_ == '18780164595' || _USERTEL_ == '18382050570'){
							// $PayWeZhi = new \Org\Our\PayWeZhi();
																			
							// $order_info['money'] = 1;  //测试
							
							// $Pay_url = $PayWeZhi->createPcOrder( $new_api_order_num , $order_info['money'] );	
							
							// print_r($Pay_url);die;
							
						}
						
						// 
						$this->mdzz_ordertate($order_info);
					
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
																			
						// $order_info['money'] = 1;  //测试
						
						$Pay_url = $PayWeZhi->createPcOrder( $new_api_order_num , $order_info['money'] );	
						
						$today = date('Y-m-d');
						
						$_SESSION['wechat']['lastorderid'] 	 =	 $order_info['id'];
						
						$Pay_img =  "Application/Runtime/ScanCode/".$today."/"._USERTEL_."_". $order_info['id'] . ".png" ;
						
						$this->mdzz_ordertate($order_info);						
						
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
						if(_USERTEL_ == '18780164595' || _USERTEL_ == '18382050570'){
							// echo 11;
							// ECHO $Pay_url;DIE;
						}						
						header("location:".$Pay_url);
						die;
						$this->assign('pay_bank',1004);
												
						$this->assign('Order_id',$res);
						
						$this->assign('Pay_name',$Pay_name);
						
						$this->display("pay_go");										
											
						exit;
						
					default :	
					
						// require_once($this->PayApiPath.'/php/pay_go.php');
						
						$this->error("支付方式不正确");
						
						break;
				}				

		}else{

			$this->error("订单提交失败，请稍后重试");

		}		
	}
	
	public function wechatpayback(){
		
		$table_prefix = substr(_USERTEL_,0,3);
		// $_SESSION['wechat']['lastorderid'] = 16;
		// $where['state']     = 0;
		$where['pay_cash']  = 1;
		$where['id']		= $_SESSION['wechat']['lastorderid'];
		$where['user']      = _USERTEL_;
		
		// unset($_SESSION['wechat']['lastorderid']);
				
		$order_info = M($table_prefix."_order")->where($where)->find();		

		if( ! $order_info ){
			
			// $data['error']   = 1;
			// $data['message'] = "没有该订单";
			$this->redirect( 'Pay/pay_test' );
			
		}
		//18382077208
	
		$today				= date("Y-m-d",$order_info['add_time']);
		
		$res 				= $order_info['id'];
		$Pay_name 			= "微信";
		$Pay_name_img   	= "WeChetPay";
		$Pay_img 			=  "Application/Runtime/ScanCode/".$today."/"._USERTEL_."_". $order_info['id'] . ".png" ;
		
		
		$this->assign('Pay_img' ,  $Pay_img );												
		
		$this->assign('Pay_name_img',$Pay_name_img);							
								
		$this->assign('Order_id',$res);
		
		$this->assign('Pay_name',$Pay_name);
		
		$this->display("pay_go");
		
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
			
	// 增加团队的team资金
	private function addteammoney($team_list , $fromuser , $money){
		
		$team_arr    =  explode(" ",$team_list);
		
		$time = time();
		foreach($team_arr as $val){
			
			$add_arr 				= array();
			$add_arr['user'] 		= $val;
			$add_arr['fromuser'] 	= $fromuser;
			$add_arr['pay_money'] 	= $money;
			$add_arr['pay_time'] 	= $time;
			M(substr($val,0,3)."_team_record")->add($add_arr);
		}
		
		
	}		
	
	function qhp_get_order_state(){
		
		$table_prefix			   =  substr($_GET['user'],0,3);
		$order_where['user'] 	   =  $_GET['user'];
		$order_where['order_num'] 		   =  $_GET['order_id'];
		$order_where['pay_cash']   =  1;		
		
		$order_info = M($table_prefix."_order")->where($order_where)->find();

		if( !$order_info ){				
				$data['error']   = 1;
				$data['message'] = "没有该订单";
				echo json_encode($data);
				die;				
		}
		
			
						
		// 通过接口获取订单状态		
		if($order_info['pay_bank'] == 9999 || $order_info['pay_bank'] == 1004){
		
			// 2017-07-27  新接口  订单号必须为全数字
			$new_api_order_num 			=  str_replace($this->PayPrefix,"",$order_info['order_num']);
		
			
			$PayWeZhi = new \Org\Our\PayWeZhi();
			
			$PayOrderState = $PayWeZhi->queryOrder( $new_api_order_num  );
			
			 echo $PayOrderState;die;
										
			// 如果支付成功
			// 进行字段验证
			$PayOrderState = (array)json_decode($PayOrderState);
			
			$merchantOutOrderNo  =  $PayOrderState['merchantOutOrderNo'];				// 提交的订单号
			$merid  			 =  $PayOrderState['merid'];							// 商户ID
			$orderMoney  		 =  $PayOrderState['orderMoney'];						// 返回的金额
			$orderNo  			 =  $PayOrderState['orderNo'];							// 订单平台编号
				
		}
		
		$verify_sign   = $merchantOutOrderNo . $merid . $orderMoney;

		$verify_ksnc   = $new_api_order_num  . $PayWeZhi->merid  . number_format($order_info['money'],2,".","");	
					
	}
	
	function weixin_test(){
		
		// require_once($this->PayApiPath.'/php/shunfoo/ClassShunfoo.php');
		
	}
	
}