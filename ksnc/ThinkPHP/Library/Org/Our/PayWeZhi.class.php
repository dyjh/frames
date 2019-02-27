<?php   
/*
 *	  2017年7月27日11:26:38	
 *    凯撒农场 对接 第三方支付接口支付接口
 *    author  QHP 
 */


namespace Org\Our;
use Think\Model;

//充值  返佣
class PayWeZhi
{
	
	public  $key   			= "Azv6E0bzVsBFPbbexQdmXVFyI5mFtWQN"  ;        				  // 密钥
	
	public  $merid 			= "20170727003";											  //商户id
	
	private $PcOrderUrl		= 'http://jh.yizhibank.com/api/createPcOrder';   		  	  //商户PC或者微信公众号发起订单接口 
	
	private $notifyUrl		= 'http://lyogame.cn/ksnc/pay-pay_back';			   		  //商户的通知地址  
	
	private $noncestr 		= "ksnczhifupay";
	
	
	//发起订单
    function createOrder()
    {        
        $key 				= $this->key;
        $data['merid'] 		= $this->merid;
        $data['merchantOutOrderNo'] = time().rand(100000,999999);
        $data['notifyUrl']  = 'http://jhpay.yizhibank.com/api/callback';
        $data['noncestr']   = '12345678910';
        $data['orderMoney'] = 1.00;
        $data['orderTime']  = date('YmdHis');
        $signstr 			= 'merchantOutOrderNo='.$data['merchantOutOrderNo'].'&merid='.$data['merid'].'&noncestr='.$data['noncestr'].'&notifyUrl='.$data['notifyUrl'].'&orderMoney='.$data['orderMoney'].'&orderTime='.$data['orderTime'];
        $signstr		   .= '&key='.$key;
        $data['sign']       = md5($signstr);
        $url = 'http://jhpay.yizhibank.com/api/createOrder';
        $str =  "<form id='pay_form' method='POST' action=".$url.">";

        foreach($data as $key=>$value){
          $str.="<input type='hidden' id='".$key."' name='".$key."' value='".$value."' />";
        }
        $str.="</form><script>function submit() {
            document.getElementById('pay_form').submit();
          }window.onload = submit;</script>";
        echo $str;
    }
   

   //发起pc订单
    function createPcOrder($merchantOutOrderNo,$orderMoney)
    {        
        $key 							= $this->key;
        $data['merid'] 					= $this->merid;
        $data['merchantOutOrderNo'] 	= $merchantOutOrderNo;
        $data['notifyUrl'] 				= $this->notifyUrl;
        $data['noncestr']				= $this->noncestr;
        $data['orderMoney'] 			= $orderMoney;
        $data['orderTime'] 				= date('YmdHis');
        $signstr 						= 'merchantOutOrderNo='.$data['merchantOutOrderNo'].
										  '&merid='            .$data['merid'].
										  '&noncestr='		   .$data['noncestr'].
										  '&notifyUrl='		   .$data['notifyUrl'].
										  '&orderMoney='	   .$data['orderMoney'].
										  '&orderTime='		   .$data['orderTime'];    
        $data['sign'] 					= md5(($signstr . "&key=".$key));	
		$signstr					   .= '&sign='			   .$data['sign'] 	;		
        $url 							= $this->PcOrderUrl;
		$url  						   .= "?".$signstr;
		
		// return $url;
        $ch = curl_init($url);
		
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $temp = json_decode(curl_exec($ch));        
        curl_close($ch);	
		// return $temp;
        $imgurl = $temp->url;
        // $imgurl = $temp->url;
		if(_USERTEL_ == '18780164595' || _USERTEL_ == '18382050570'){
			// print_r($temp);die;
		}
		return $imgurl;
		
        // $str = '<img src="http://qr.liantu.com/api.php?text='.$imgurl.'"/>';
        // return "http://qr.liantu.com/api.php?text=".$imgurl."";
    }

    //接收通知
    function callback()
    {
        if(IS_POST){
            $content = $_POST;
            $key = '';
            $signstr = 'merchantOutOrderNo='.$content['merchantOutOrderNo'].'&merid='.$content['merid'].'&msg='.$content['msg'].'&noncestr='.$content['noncestr'].'&orderNo='.$content['orderNo'].'&payResult='.$content['payResult'];
            $signstr.= '&key='.$key;
            $sign = md5($signstr);
            if($sign==$content['sign']){
                echo 'SUCCESS';
            }
        }
    }
    //查询订单

    function queryOrder($merchantOutOrderNo)
    {
         $key 							= $this->key;
        $data['merid'] 					= $this->merid;
        $data['merchantOutOrderNo'] 	= $merchantOutOrderNo;
        $data['noncestr']				= $this->noncestr;
        $signstr = 'merchantOutOrderNo='.$data['merchantOutOrderNo'].'&merid='.$data['merid'].'&noncestr='.$data['noncestr'];
        $signstr.= '&key='.$key;
        $data['sign'] = md5($signstr);

        $url 							= 'http://jh.yizhibank.com/api/queryOrder';
		$url  						   .= "?".$signstr;

        $ch = curl_init();//打开
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
  
    // createOrder();


}

 

