<?php
namespace Home\Controller;
use Think\Controller;

class HomeController extends Controller {

    public function __construct(){
		
        parent::__construct();

        if(!empty($_POST)){
            $_POST = New_create($_POST);
        }
        if(!empty($_GET)){
            $_GET = New_create($_GET);
        }
		
		//$this->error("系统维护中，请稍后！");

        //判断是否登录
        if(session('?login')){

            defined("_USERTEL_") ? "" : define("_USERTEL_",$_SESSION['login']['user']) ;
            $this->assign('user_info',$_SESSION['login']);

        }
    }

    protected function GetOpenCloseTime($today_str){
        // 获取系统的开收盘时间
        if( S("SALE_START_TIME") && S("SALE_END_TIME") && 1<>1 ){
			
			$start =  S("SALE_START_TIME");

            $end   =  S("SALE_END_TIME");
			
			$today_str_begin = ($today_str ." " .  $today_str_begin . ":00:00");
			
			$today_str_end   = ($today_str ." " .  $today_str_end   . ":00:00");
			
        }else{
			
            $Global_conf =  M('Global_conf')->where('cases="start_time" or cases="end_time"')->select();

            foreach($Global_conf as $val){
                if($val['cases'] == 'start_time') { $today_str_begin = $today_str ." " .$val['value'] . ":00:00"; S("SALE_START_TIME",$val['value'],24*3600); $start =$val['value'];}
                if($val['cases'] == 'end_time'  ) { $today_str_end   = $today_str ." " .$val['value'] . ":00:00"; S("SALE_END_TIME",$val['value'],24*3600);	 $end =$val['value'];}			
			}
			
        }

        return array("start"=>$start,"end"=>$end,"today_str_begin"=>$today_str_begin,"today_str_end"=>$today_str_end);

    }

    protected function GetSaleCharge(){
        // 获取

        $Global_conf =  M('Global_conf')->where('cases="poundage"')->find();

        define("__SALECHARGE__",$Global_conf['value']);

    }

    /**
     * 获取交易信息
     * @param int $isget_BUY_AND_SALE  是否获取   最新的交易信息
     * @param string $seed  种子名称
     * @return array  返回数组
     */
    public function GetFlushEntrust($isget_BUY_AND_SALE = 1,$seed=''){

        $today_str = date("Y-m-d");                     //  今日零点的

        $GetOpenCloseTime = $this->GetOpenCloseTime($today_str);

        extract($GetOpenCloseTime);

        // 记录表
        $case_m=''.date('Y-m').'_matching';

        $ClosingQuotationTime = strtotime($today_str_end) - 3600 * 24; //  设置前一天的收盘时间

        //  获得前一天的收盘价格

        if(! $LastDayProductInfo = S("LastDayProductInfo_$seed") ){
            $LastDayProductInfo = M("pay_statistical")->where(' seed="'.$seed.'"')->order('time desc')->find();
            if( empty($LastDayProductInfo)){
                $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
                $LastDayProductInfo['end_money'] = $seeds_info['first_price'];
            }
            S("LastDayProductInfo_$seed",$LastDayProductInfo,(strtotime($today_str)-time()));
        }

		
		
		/***
		    2017-07-23 
		    修改交易信息的查询功能。
			每5分钟计算一次，
		*/
		// $interval = 60 * 5;
		
		// $info_count = floor(($end - $start) / ($interval));     //  今日交易信息总条数
		
		// $seed_info = S("SeedSalesInfo_$seed");
		
		// $seed_info = array();
		
		$now_info_count  =  count($seed_info);
		
		// $need_info_count =  floor((time() - $start) / ($interval));
		
		// for($i = $now_info_count ; $i < $need_info_count ; $i++ ){
			// $where = "" ; 
			// $where =  'time >= "'.($start+($i*$interval)).'"  AND time <= " '.($start+($i*$interval)+$interval).'" AND seed="'.$seed.'"';
			// $info = M($case_m)->where($where)->order('time desc')->field(" * , sum(num) as total_num")->find();
			// echo M($case_m)->getLastSql();echo "\n";
			// $seed_info[] = $info;
		// }
		// die;
		// print_r($seed_info);die;
		
		// S("SeedSalesInfo_$seed",$seed_info,array('type'=>'file','expire'=>($end - $start)));
		$start = strtotime($today_str_begin);
		$end = strtotime($today_str_end);
		
		
		$where_seed_info = 'time >= "'.$start.'"  AND time <= " '.$end.'" AND seed="'.$seed.'"';
		
        if(! $seed_info = S("SeedSalesInfo_$seed") ){
			$field = " *  ";
            $seed_info = M($case_m)->where($where_seed_info)->order('time asc')->select();
            S("SeedSalesInfo_$seed",$seed_info,array('type'=>'file','expire'=>300));
        }
		
		$TodayProductSum = M($case_m)->where($where_seed_info)->field(" max(money) as highestprice, min(money) as lowestprice ,  sum(num) as total_num")->find();

        //  取得 最新的交易数据
        $NowProductInfo = $seed_info[ count($seed_info)-1];
// print_r($seed_info);die;
        if(!$NowProductInfo ){

            $NowProductInfo = $this->GetNowProductInfo($start,$end,$seed);

        }

        // 取得今天第一笔交易 数据
        $FirstProductSaleInfo = $seed_info[0] ? $seed_info[0] : array();

        $data  = array();

        $data['MarketInfo']['ProductName'] = $seed;
        // 计算 当前产品的  涨幅额度
        $data['MarketInfo']['Increase']           =  ($NowProductInfo['money'] - ( $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $LastDayProductInfo['end_money'])) ;
        $data['MarketInfo']['IncreaseRate']       =  number_format($data['MarketInfo']['Increase'] / ( $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $LastDayProductInfo['end_money']) , 4 ) ;
        //  计算产品的 涨跌停价格
        $data['MarketInfo']['OpenPrice']          =  $FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['open_price']  ;
        $data['MarketInfo']['LimitUp']            =  round( (($FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['money']) * 1.1 ) ,5 );
        $data['MarketInfo']['LimitDown']          =  round( (($FirstProductSaleInfo['money'] ? $FirstProductSaleInfo['money'] : $NowProductInfo['money']) * 0.9 ) ,5);
        $data['MarketInfo']['Price']              =  $NowProductInfo['money']  ;
        $data['MarketInfo']['ProductId']          =  null;
        $data['MarketInfo']['ProductImage']       =  null;
        $data['MarketInfo']['ProductCode']        =  null;
        $data['MarketInfo']['HighestPrice']       =  round($TodayProductSum['highestprice'],5) ;
        $data['MarketInfo']['LowestPrice']        =  round($TodayProductSum['lowestprice'],5) ;
        $data['MarketInfo']['Volume']             =  $TodayProductSum['total_num'];
  
// print_r($data);die;
        //  获取 产品正在进行的买入卖出信息
        if($isget_BUY_AND_SALE){

            $buy_list  = $this->find_buy(5,$seed);

            foreach ($buy_list as $key=>$val){
                if($val['money']){
                    $List['Price']  = $val['money'];
                    $List['Number'] = $val['num'];

                    $data['BuyList'][] = $List;
                }

            }

            $sale_list = $this->find_sell(5,$seed);

            foreach ($sale_list as $key=>$val){
                if($val['money']){
                    $List['Price']  = $val['money'];
                    $List['Number'] = $val['num'];
                    $data['SaleList'][] = $List;
                }

            }
        }

        return $data;
    }

    /**
     * 获取产品 最新的交易信息
     * @param $start  开盘时间
     * @param $end    收盘时间
     * @param $seed   种子名称
     * @return array   返回数组
     */
    private function GetNowProductInfo($start,$end,$seed){

	
	
	
	
	/****
				TODO:
				20717-07-19
			 更改为吧不需要查询上一天收盘价格作为现价
			 若新一天不存在交易，以数据库中设置的的 open_price 作为 开盘价 及 现价
	*/
	
	  
        // $time = 60 * 60 * 24;

        // if($NowProductInfo = S($seed."NowProductInfo") ){
            // return $NowProductInfo;
        // }

        // $start_str = date("H",$start);
        // $end_str   = date("H",$end);

        // $all_match_sta =  M("matching_statistical")->select();

        // foreach ( $all_match_sta as $val){

            // $case_m = $val['name'].'_matching';

      //    // FROM_UNIXTIME(add_time,"%X年%m月")

            // $NowProductInfo = M($case_m)->where(' FROM_UNIXTIME(time,"%H") >= "'.$start_str.'"  AND  FROM_UNIXTIME(time,"%H") <= "'.$end_str.'" AND seed="'.$seed.'"')->order('time desc')->find();

            // if($NowProductInfo){
                // break;
            // }

        // }

        // if(empty($NowProductInfo)){
            $seeds_info = M("seeds")->where(' varieties="'.$seed.'"')->find();
            $NowProductInfo['money'] 		= $seeds_info['open_price']>0 ? $seeds_info['open_price'] : $seeds_info['first_price'];
			$NowProductInfo['open_price']   = $seeds_info['open_price'];
        // }

        S($seed."NowProductInfo",$NowProductInfo,$time);

        return $NowProductInfo;

    }

    /**
     * 获取买如的交易信息
     * @param $limit  限制条数
     * @param $seed   种子名称
     * @return array  返回数组
     */
    private function find_buy($limit,$seed){

        //接收种子类别

        $case_p=''.date('Y-m').'_pay';
        $data_p_b=M(''.$case_p.'')->order('money asc')->where('type= 1 AND state<2 AND seed="'.$seed.'"')->select();
        $count_b=M(''.$case_p.'')->order('money asc')->where('type= 1 AND state<2 AND seed="'.$seed.'"')->count();
        $data_b=array();
        $k=0;
        for($i=0;$i<$count_b;$i++){
            if($i==0){
                $data_b[$k]['money']=$data_p_b[$i]['money'];
                $data_b[$k]['num']=$data_p_b[$i]['num'];
                $k++;
            }else{
                if($data_p_b[$i]['money']==$data_b[$k-1]['money']){
                    $data_b[$k-1]['num']+=$data_p_b[$i]['num'];
                }else{
                    $data_b[$k]['money']=$data_p_b[$i]['money'];
                    $data_b[$k]['num']=$data_p_b[$i]['num'];
                    $k++;
                }
            }
        }
        return $data_b;

    }

    //AJAX实实时卖出数据
    private function find_sell($limit,$seed){

        $case_p=''.date('Y-m').'_pay';

        $data_p_s=M(''.$case_p.'')->order('money asc')->where('type= 0  AND state<2 AND seed="'.$seed.'"')->select();
        $count_s=M(''.$case_p.'')->order('money asc')->where('type= 0  AND state<2 AND seed="'.$seed.'"')->count();
        
        $data_s=array();
        $f=0;
        for($i=0;$i<$count_s;$i++){
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

        return $data_s;
    }

}