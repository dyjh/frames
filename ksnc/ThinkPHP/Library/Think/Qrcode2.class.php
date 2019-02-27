<?php
namespace Think;
use Think\Autoadd;


class Qrcode{

	   private $user;
       private $url = 'http://lyogame.cn/ksnc/index.php/Home/User/register.html';

	   function __construct($user){
		   $this->user = $user;
	   }

       //组装url
	   function generate(){
		   //print_r($this->user);die;
           $this->url = $this->url."?user=".$this->user ;

           $qrcode['img'] = '<img width="100%" id="tupian" src="http://pan.baidu.com/share/qrcode?w=200&h=200&url='.$this->url.'"/>';
			$qrcode['url'] =$this->url;
           return $qrcode;

           return $this->generateQRfromGoogle($this->url);
	   }

	   //生成二维码
	   function generateQRfromGoogle($url){
		   $qrcode = '<img	width="600" src="http://pan.baidu.com/share/qrcode?w=200&h=200&url='.$url.'"/>';
		   return $qrcode;
	   }
}

?>
