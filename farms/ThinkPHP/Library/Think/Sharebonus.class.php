<?php
namespace Think;
use Think\Autoadd;
use Think\Tool;

class Sharebonus{

			 private $user;

			 function __construct($user){
					 $this->user = $user;
           $this->Select_Level($this->user);
			 }
}

?>
