<?php
function resultOrderBy($arrs, $orderBy){
       $orderArr = array();
       $orderType = array();
       $sortRule = '';
       foreach ($orderBy as $key => $value) {
           $temp = array();
           for ($i = 0; $i < count($arrs); $i++) {
               $temp[] = $arrs[$i][$key];
           }
           $orderArr[] = $temp;
           $orderType[] = $value == 'asc' ? SORT_ASC : SORT_DESC;
       }
       for ($i = 0; $i < count($orderBy); $i++) {
           $sortRule .= '$orderArr[' . $i . '],' . $orderType[$i] . ',';
       }
       //echo 'array_multisort('.$sortRule.'$arrs);';
       eval('array_multisort(' . $sortRule . '$arrs);');
       return $arrs;
  }

   function https_request($url,$data=null){ //定义程序内https传输 get或post
  	   $ch = curl_init();
  	   curl_setopt($ch, CURLOPT_URL, $url);
  	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

  	   if(!empty($data)){
  		   curl_setopt($ch, CURLOPT_POST, 1);
  		   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   }

       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   $output = curl_exec($ch);  
       curl_close($ch);
	   return $output;
   }

   function httprequest($url){ //定义程序内http传输 get
	     $ch = curl_init();
	     curl_setopt($ch, CURLOPT_URL, $url);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	     $output = curl_exec($ch);

	     curl_close($ch);
	     if($output === FALSE){
		   return "CURL Error:".curl_error($ch);
	   }
	   return $output;
   }

   //获取token
   function get_token(){ //获取access_token
      
     $access_token = S('access_token');  
     if($access_token == false){
		 $appid ="wx1a1816fc5499000d";
		 $secret = "41817906c6d65a01f0e8bbe1f20860a0";	 
         $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
         $json = https_request($url);
         $arr = json_decode($json,true);
		 
         //设置缓存时间
         S('access_token',$arr['access_token'],7000);
             $access_token = $arr['access_token'];   
       }     
	   return $access_token;
	}  

   //获取接口ticket
   function get_ticket($access_token){
		$access_token = get_token();
		if($ticket == false){
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
			$json = https_request($url);
	        $arr = json_decode($json, true);
			S('ticket',$arr['ticket'],7000);
			$ticket = $arr['ticket'];
		}
		return $ticket;
	}
	
	//生成随机字符串
	function createNonceStr(){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456";
		$str="";
		for($i=0;$i<16;$i++){
			$str .=substr($chars,mt_rand(0,strlen($chars)-1),1);
		}
		return $str;
	}
	
	//生成签名
	function get_sdk(){
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$argu['appid'] = "wx1a1816fc5499000d";
		$argu['url'] = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$argu['noncestr'] = createNonceStr();
		$argu['timestamp'] = time();
		$argu['access_token'] = get_token();
		$argu['ticket'] = get_ticket($argu['access_token']);
		$str = "jsapi_ticket=".$argu['ticket']."&noncestr=".$argu['noncestr']."&timestamp=".$argu['timestamp']."&url=".$argu['url'];
		$argu['signature'] = sha1($str);
		return $argu;
	}
	
?>