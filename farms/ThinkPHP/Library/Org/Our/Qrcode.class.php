<?php
namespace Org\Our;
use Think\Autoadd;
use Think\Tool;

class Qrcode{

			 private $user;
       private $url = 'htttp://www.baidu.com';

			 function __construct($user){
					 $this->user = $user;
           $this->generate($this->user);
			 }

       //组装url
			 function generate($user){
            $this->url = $this->url."?user=".$user;
						$this->generateQRfromGoogle($this->url);
			 }

			 function generateQRfromGoogle($url){
					echo '<img src="http://pan.baidu.com/share/qrcode?w=200&h=200&url='.$url.'"/>';
			 }
}







?>
