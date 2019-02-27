<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Smscode;
use Org\Our\Account;
use Org\Our\Qrcode;
use Think\Verify;

class LoginController extends Controller{

      //首页
      public function index(){
		  
          if(cookie('con')!=="" && is_numeric(cookie('con'))){
              $cook_user = cookie('con');
              $this->assign('cook_user',$cook_user);
          }

          if(cookie('pass')!==""){
              $cook_pass = cookie('pass');
              $this->assign('cook_pass',$cook_pass);
          }
		  
		  $number = array();
		  $number['first_number'] = rand(1,100);
		  $number['secord_number'] = rand(1,100);
		  session('login_number',$number['first_number']+$number['secord_number']);  
		  $confuse_number = array(rand(1,100),rand(1,100),$number['first_number']+$number['secord_number']);
		  shuffle($confuse_number);
		  
		  //生成token
		  $code = '12345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY!@%#&*$*(#(*()$E)_ED)_D)(W*(@W*(*UDSIIFOSDJIOW)(DIj';
	      $code = str_shuffle($code); 
	      $length = rand(1,40);
	      $code = MD5(substr($code,0,$length));
		  $key = "lyogame";
          $str = substr(md5($code),8,10);
	      session('token', md5($key.$str));
		  	  
          $this->assign('number',$number);
		  $this->assign('confuse_number',$confuse_number);
		  $this->assign('token',md5($key.$str));
          $this->display();
      }

	  //验证码
      public function yzm() {
           $code = new Verify();
           $code->fontSize = '30px';
           $code->length = 4;
           $code->codeSet = '0123456789';
           $code->fontttf = '4.ttf';
           $code->useNoise = false;
           $code->useCurve = false;
           //$code->imageH = '39px';
           $code->bg = array (170,101,47);
           $code->entry();
      }

	  //进入游戏
      public function enter_game(){
		  
           if(IS_AJAX){
			   	
			   //分割
			   $Post = explode('&',$_POST['formdata']);

               $arr = array();
               for($i=0;$i<count($Post);$i++){
				   
                    $list = explode('=',$Post[$i]);
							
					switch($list[0]){
						
						 case 'user':
						 if(is_numeric($list[1]) && strlen($list[1])==11){
							 $user_login[$list[0]] = $list[1];
						 }
						 break;
						 case 'password':  
			                   $cook_pass = $list[1];							 
					     break;
                         case 'token':
						     if($list[1]!==session('token')){
								  $data['state'] = 80006;
								  $data['content'] = '无操作时间过长，请重新进入登录界面';
								  echo json_encode($data);
								  exit; 
							 }
                         break;						 
					 }
               }
			   $data_g=M('Global_conf')->where('cases="login"')->find();	
				if($data_g['value']==0){
					if($user_login['user']=='18228068397'||$user_login['user']=='18382077208'||$user_login['user']=='15802858094'||$user_login['user']=='18382050570'||$user_login['user']=='18584084806'||$user_login['user']=='18768477519'||$user_login['user']=='18780164595'){
				
					}else{
						 $data['state'] = 80007;
						   $data['content'] = '系统维护，暂时无法登陆';
						   echo json_encode($data);
						   exit;
					}
				}
			   //查看是否封号
			   $feeeze = M("user_freeze")->field('state')->where(array('user'=>$user_login['user']))->find();
			   if($feeeze['state']==1){
				   $data['state'] = 80007;
                   $data['content'] = '帐号禁止登录';
                   echo json_encode($data);
                   exit;
			   }
			  
			   $table_fix = substr($user_login['user'],0,3);
               $table = $table_fix.'_members';
			  
               $members = M("$table");
               $res = $members->where($user_login)->select();
			   		
			   if(md5($res[0]['password']) == $cook_pass && $res){
				     
                    if($_POST['record_com']==1){
						//开启帐号cookies
						if(cookie('con')){
							//有则清除
							cookie('con',null);
							//重新生成
							cookie('con',$user_login['user'],time()+3600*24*30*6);
						}else{
							//直接生成
							cookie('con',$user_login['user'],time()+3600*24*30*6);
						}        
                    }else{
						if(cookie('con')){
							cookie('con',null);
						}
					}
   
					//新添加
					if($_POST['record_pass']==1){
						//查看是否有cookies
						if(cookie('pass')){
							//有则清除
							cookie('pass',null);
							//重新生成
							cookie('pass',$cook_pass,time()+3600*24*30*6);
						}else{
							//直接生成
							cookie('pass',$cook_pass,time()+3600*24*30*6);
						}                   
                    }else{
						if(cookie('pass')){
							cookie('pass',null);
						}
					}
				
					//如果登录密码与cookie不一致，说明改过密码
					if(md5($user_login['password'])!==cookie('pass')){
						//查看是否有cookies
						if(cookie('pass')){
							//有则清除
							cookie('pass',null);
							//重新生成
							cookie('pass',$cook_pass,time()+3600*24*30*6);
						}     
					}
					
					//记录cookies，方便下次加载
					//cookie('login_user',$user_login['user'],time()+3600*24*30*6);
					
					//获取IP 
				    if(getenv('HTTP_CLIENT_IP')){
						$client_ip = getenv('HTTP_CLIENT_IP');
					} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
						$client_ip = getenv('HTTP_X_FORWARDED_FOR');
					} elseif(getenv('REMOTE_ADDR')) {
						$client_ip = getenv('REMOTE_ADDR');
					} else {
						$client_ip = $_SERVER['REMOTE_ADDR'];
					}

					$ip = $client_ip;
				    			
					$arr['mac'] = $ip;
					$arr['login_time'] = time();
					$members->where($user_login)->save($arr);  		
                    session('user',$user_login['user']);
					
                    $data['state'] = 80009;
                    $data['content'] = '登录成功';
                    echo json_encode($data);
                    exit;
               }else{
                   $data['state'] = 80007;
                   $data['content'] = '帐号或密码有误';
                   echo json_encode($data);
                   exit;
               }

           }else{
               $data['state'] = 80006;
               $data['content'] = '请求错误';
               echo json_encode($data);
               exit;
           }
      }

      //宝箱提示
      public function treasure_show(){

              if(S('treasure_num')!==false){
                   if($_SESSION['treasure_num']<S('treasure_num')){
                       $array = array();
                       $treasure_message = S('treasure_message');
                       $num = S('treasure_num')-$_SESSION['treasure_num'];
                       if($num>=10){
                            $add = $_SESSION['treasure_num']+10;
                            for($i=$_SESSION['treasure_num'];$i<=$add;$i++){
                                 if(!empty($treasure_message[$i])){
                                     Array_push($array,$treasure_message[$i]);
                                 }else{
                                    continue;
                                 }
                            }
                            session('treasure_num',$add);
                       }else{

                           $add = S('treasure_num');
                           for($i=$add;$i>=$_SESSION['treasure_num'];$i--){
                                if(!empty($treasure_message[$i])){
                                    Array_push($array,$treasure_message[$i]);
                                }else{
                                    continue;
                                }
                           }
                           session('treasure_num',$add);
                       }
                      echo json_encode($array);

                   }else{
                      echo '';
                   }
              }else{
                   echo '';
              }
      }

      //刷新种植状态
      public function planting_state(){

           $user = $_SESSION['user'];
           $table_fix = substr($user,0,3);
           $table = $table_fix.'_planting_record';
		   $res = M("$table")->field('harvest_time,number,seed_state,seed_type,seed_img_name,disasters_state')->where('user="'.$user.'" and harvest_state=0 or user="'.$user.'" and disasters_state!=0 and harvest_state=0')->select();

		   //去除种子状态为0
		   $seed_data = array();
		   for($i=0;$i<count($res);$i++){
			   if($res[$i]['seed_state']==0){
				   $seed_data[$i] = $res[$i];
				   unset($seed_data[$i]['seed_img_name']);
				   unset($seed_data[$i]['seed_type']);
			   }else{
				   $seed_data[$i] = $res[$i];
			   }
		   }
		     
           if($seed_data){
               echo json_encode($seed_data);
           }else{
               echo '';
           }
		   
     }
	 
	 //刷新异地登录
	 public function prevent_login(){
			
			if($_SESSION['mac']==S($_SESSION['user'].'ip')){
				 echo 0;
			}else{
				 echo 1;
			}	
	 }
}
?>
