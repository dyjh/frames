<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 15:44
 */

namespace Think;


class sendMessage
{
   public function sendMessage($username,$password,$phones,$contents,$scode='',$setTime='')
    {
        $srv_ip = 'cf.82009668.com';//你的目标服务地址或频道.
        $srv_port = 888;
        $url = '/sdk/Service.asmx/sendMessage'; //接收你post的URL具体地址
        $fp = '';
        $resp_str = '';
        $errno = 0;
        $errstr = '';
        $timeout = 10;
        $post_str = "username=".$username."&pwd=".$password."&phones=".$phones."&contents=".$contents."&scode=".$scode."&setTime=".$setTime;
        $err='';
        if ($srv_ip == '' || $url == '')
        {
            $err='ip or dest url empty</br>';
        }
        $fp = fsockopen($srv_ip,$srv_port,$errno,$errstr,$timeout);
        if (!$fp){$err.='fp fail</br>';  }
        $content_length = strlen($post_str);
        $post_header = "POST $url HTTP/1.1\r\n";
        $post_header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $post_header .= "User-Agent: MSIE\r\n";
        $post_header .= "Host: ".$srv_ip."\r\n";
        $post_header .= "Content-Length: ".$content_length."\r\n";
        $post_header .= "Connection: close\r\n\r\n";
        $post_header .= $post_str."\r\n\r\n";
        fwrite($fp,$post_header);
        $inheader = 1;
        while(!feof($fp)){
            $line=fgets($fp,512);
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
                $resp_str .= $line;//返回值放入$resp_str
            }
        }
        $bodytag = trim($resp_str);
        fclose($fp);
        $dom = new \DOMDocument('1.0');
        $dom ->loadXML($bodytag);
        $xml = simplexml_import_dom($dom);
        $res= $xml;
        unset ($resp_str);
        if($res=='1'){
            return 1;
        }else{
            return $res;
        }
    }

}