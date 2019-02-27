<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
header("content-type:text/html;charset=utf8");
class CommonController extends Controller{

    function __construct(){
        //SESSION是否开启
        if(!isset($_SESSION['user'])){
            //echo '跳转到首页';
            header("Location: http://lyogame.cn/farms/Index"); 
        }
    }
	
	/*public function aaa(){
		 $statistical = M('statistical');
         $table_fix = $statistical->field('name')->select();
		 for($i=0;$i<count($table_fix);$i++){
			  $table = $table_fix[$i]['name'].'_members';
			  $res = M("$table")->select();
			  for($j=0;$j<count($res);$j++){
				  $arr['content_num'] = 4;
				  $arr['user'] = $res[$i]['user'];
				  M('users_behavior')->add($arr);
			  }
			   
			  
		 } 
	}*/
}
?>
