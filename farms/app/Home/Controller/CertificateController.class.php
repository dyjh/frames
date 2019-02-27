<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class CertificateController extends Controller{

      //首页
      public function index(){
		  
		  $type = $_GET['Certificate_type'];
          if(is_numeric($type) && $type>0 && $type<7){
			  $this->assign('type',$type);
          	  $this->display();
          }else{
          	  echo '请求错误';
          }
          
      }
}
?>
