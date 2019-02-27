<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Smscode;
use Org\Our\Account;
use Org\Our\Autoadd;
use Think\Verify;
header("Content-type:text/html;charset=utf8");

class AccountsController extends Controller{

      //注册
      public function Register(){

          if(IS_AJAX){
			   
              $Post = explode('&',$_POST['formdata']);
			  
              $arr = array();
			  
			  $post_count = count($Post);
			  
              for($i=0;$i<$post_count;$i++){
                    				  
				   $list = explode('=',$Post[$i]);		   
				   //验证数据
				   switch($list[0]){
					   //帐号
					   case 'user':
							$data_g=M('Global_conf')->where('cases="regist"')->find();	
								if($data_g['value']==0){
									if($user_login['user']=='18228068397'||$user_login['user']=='18382050570'||$user_login['user']=='15802858094'||$user_login['user']=='18382050570'||$user_login['user']=='18584084806'||$user_login['user']=='18768477519'||$user_login['user']=='18780164595'){
								
									}else{
										 $data['state'] = 90001;
										   $data['content'] = '系统维护，暂时无法注册';
										   echo json_encode($data);
										   exit;
									}
								}
					         if(is_numeric($list[1]) && strlen($list[1])==11){
								  $user_data[$list[0]] = $list[1];
								  continue;
							 }else{
								  $data['state'] = 90001;
								  $data['content'] = '帐号格式错误';
								  echo json_encode($data);
								  exit;
							 }
					   break;
					   //姓名
					   case 'name':						         		   
					   
							if(preg_match("/^[\x{4e00}-\x{9fa5}]{2,10}$/u",urldecode($list[1]))){
								  $user_data[$list[0]] = urldecode($list[1]);
						          continue;
							 }else{
								  $data['state'] = 90001;
								  $data['content'] = '姓名只能为中文';
								  echo json_encode($data);
								  exit;
							 }
					   break;
					   //身份证
					   case 'id_card':
					   
							 $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X|x)$)/";
							 if(preg_match($regx,$list[1]) && strlen($list[1])==18){								  
								  $id_card_message = M('verification')->where(array('id_card'=>$list[1]))->find();
								  if($id_card_message){
										$data['state'] = 90001;
										$data['content'] = '该身份证号已注册';
										echo json_encode($data);
										exit;
									}else{
										$user_data[$list[0]] = $list[1];
										continue;
									}
							 }else{
								  $data['state'] = 90001;
								  $data['content'] = '身份证格式错误';
								  echo json_encode($data);
								  exit;
							 }
					   break;
					   //密码
					   case 'password':
					         $user_data[$list[0]] = MD5(MD5($list[1]));		    
                       break;
                       //推荐人
                       case 'referees':	
					   
                             if(empty($list[1])){
								 $user_data[$list[0]] = $list[1];
								 continue;
							 }else{
								 if(is_numeric($list[1]) && strlen($list[1])==11){
									 $user_data[$list[0]] = $list[1];
								     continue;
								 }else{
									 $data['state'] = 90001;
									 $data['content'] = '推荐人输入错误';
									 echo json_encode($data);
									 exit;
								 }
							 }
                       break;
                       //图形验证码
					   case 'ver_code';
					   
					          if(is_numeric($list[1]) && strlen($list[1])==4){
								  $yzm = new Verify();
								  $bol = $yzm->check($list[1]);
								  if(!$bol){
									 $data['state'] = 90001;
									 $data['content'] = '图片验证码错误';
									 echo json_encode($data);
									 exit;
								  }  
							  }else{
								  $data['state'] = 90001;
								  $data['content'] = '图片验证码错误';
								  echo json_encode($data);
								  exit; 
							  }
					    break;
						//手机验证码
						case 'sms_code':
						     if(is_numeric($list[1]) && strlen($list[1])==6 && $list[1]==session('Reg')){
								  continue;								  			  
							 }else{
								  $data['state'] = 90001;
								  $data['content'] = '手机验证码错误';
								  echo json_encode($data);
								  exit;  
							 }
						break;	
						//token验证
                        case 'token':					
                              if($list[1]!==session('token')){
								  $data['state'] = 90001;
								  $data['content'] = 'token错误';
								  echo json_encode($data);
								  exit;  
							  }
                        break;						
				   }			   
              }

			  if($user_data['referees']==$user_data['user']){
				   $data['state'] = 90001;
				   $data['content'] = '推荐人不能填写本人';
				   echo json_encode($data);
				   exit;  
			  }
			 
              //查找团队
              if(!empty($user_data['referees'])){
				  
                   $referees_fix = substr($user_data['referees'],0,3);
                   $referees_table = $referees_fix.'_members';
                   $referees_members = M("$referees_table");
				   $team = $referees_members->where("user='%s'",$user_data['referees'])->find();

				   if($team){
					    if(!empty($team['team'])){
                           $user_data['team'] = $team['team'].' '.$user_data['referees'];
					    }else{
						   $user_data['team'] = $user_data['referees'];
					    }
				   }else{
					   $data['state'] = 90001;
					   $data['content'] = '推荐人不存在';
					   echo json_encode($data);
					   exit;  
				   }
              }
			  
              $user_data['level'] = 1;
              $user_data['tel'] = $user_data['user'];
              $table_fix = substr($user_data['user'],0,3);
              $statistical = M('statistical');
              $table_name = $statistical->where(array('name'=>$table_fix))->select();
			  
			  //获取相关表
			  $user_table = $table_fix.'_members';
			  $user_record = $table_fix.'_member_record';
			  			  
               //如果表存在
              if($table_name){
                    
					$res = M("$user_table")->where("user='%s'",$user_data['user'])->find();
                    if($res){
                        session('Reg',null);
                        $data['state'] = 90001;
                        $data['content'] = '该手机号已经注册';
                        echo json_encode($data);
                        exit;
                    }else{
						
                         if(M("$user_table")->add($user_data)){//注册信息
							  M('team_relationship')->add($user_data);
							  $user_data['regis_time'] = time();
                              M('user_freeze')->add($user_data);	
							  M('verification')->add($user_data);	  
							  M("$user_record")->add($user_data);
							  M('total_station')->where('id=1')->setInc('member_num',1);
                              session('Reg',null);
							   //记录用户cookies，方便下次加载
					          cookie('login_user',$user_data['user'],time()+3600*24*30*6);
                              $data['state'] = 90002;
                              $data['content'] = '注册成功';
                              echo json_encode($data);
                              exit;
                          }else{
                              //session('Reg',null);
                              $data['state'] = 90001;
                              $data['content'] = '注册失败';
                              echo json_encode($data);
                              exit;
                          }
                    }
              }else{
                   //如果表不存在，建表
                    $new_table = new Autoadd;
                    $new_table->Autoadd($user_data['user']);
		
                    if(M("$user_table")->add($user_data)){
						  M('team_relationship')->add($user_data);
						  $user_data['regis_time'] = time();
						  M('verification')->add($user_data);
						  M("$user_record")->add($user_data);
						  M("user_freeze")->add($user_data);
						  M('total_station')->where('id=1')->setInc('member_num',1);
                          session('Reg',null);
						  //记录用户cookies，方便下次加载
					      cookie('login_user',$user_data['user'],time()+3600*24*30*6);
                          $data['state'] = 90002;
                          $data['content'] = '注册成功';
                          echo json_encode($data);
                          exit;
                    }else{
                          //session('Reg',null);
                          $data['state'] = 90001;
                          $data['content'] = '注册失败';
                          echo json_encode($data);
                          exit;
                    }
              }
          }else{
              session('Reg',null);
              $data['state'] = 90001;
              $data['content'] = '请求错误';
              echo json_encode($data);
              exit;
          } 
      }

      //注册验证码
      public function code(){
		 if(IS_AJAX && is_numeric(I('post.tel')) && strlen(I('post.tel'))==11 && I('post.token')==session('token')){
			 //引用验证码类
             new Smscode($_POST['tel'],'Reg');  
		 }
      }

      //发送找回密码验证码
      public function find_code(){
		  
		  if(IS_AJAX && is_numeric(I('post.tel')) && strlen(I('post.tel'))==11 && I('post.token')==session('token')){
			  
			  $table_fix = substr($_POST['tel'],0,3);
			  $table = $table_fix.'_members';
			  $res = M("$table")->where(array('user'=>$_POST['tel']))->select();
			  
			  if(!$res){
				  $data['state'] = 80001;
				  $data['content'] = "用户不存在";
				  echo json_encode($data);
				  exit;
			  }else{
				 //开启session
				 session('find_user',$_POST['tel']);
				 //引用验证码类
				 new Smscode($_POST['tel'],'Zeg');
				 $data['state'] = 80002;
				 echo json_encode($data);
				 exit;
			  }		  
		  }else{
			  $data['state'] = 80001;
			  $data['content'] = "请求错误";
		      echo json_encode($data);
			  exit;
		  }
      }

      //验证找回密码步骤
      public function find_password(){

			if(IS_AJAX){
				
				$Post = explode('&',$_POST['formdata']);
				$arr = array();
				for($i=0;$i<count($Post);$i++){
					 $list = explode('=',$Post[$i]);
					 $arr[$list[0]] = $list[1];
				}

				if($arr['find_code']!=session('Zeg')){
					$data['state'] = 80003;
					$data['content'] = '验证码错误';
					echo json_encode($data);
					exit;
				}
		
				if($arr['tel']!=session('find_user')){
					$data['state'] = 80001;
					$data['content'] = '用户名不对应';
					echo json_encode($data);
					exit;
				}
				
				if($arr['token']!==session('token')){
					$data['state'] = 80001;
					$data['content'] = '请求错误';
					echo json_encode($data);
					exit;
				}
				
				session('Zeg',null);
				session('zh',$arr['tel']);
				$data['state'] = 80004;
				$data['content'] = '验证成功';
				echo json_encode($data);
				exit;

			}else{
				$data['state'] = 80001;
				$data['content'] = '请求错误';
				echo json_encode($data);
				exit;
			}
       }

      //重置密码
      public function reset_password(){

          if(IS_AJAX){

              $Post = explode('&',$_POST['formdata']);
			  			  		  
			  $arr = array();
			  for($i=0;$i<count($Post);$i++){  
			  
				   $list = explode('=',$Post[$i]);

				   switch($list[0]){
						// 重置密码时，传输的为 密文，因此 后端仅加密一次
					   case 'password':
						  $arr[$list[0]] = (MD5($list[1]));
					   break;
					   case 'token':
						  if($list[1]!==session('token')){
							  $data['state'] = 80005;
							  $data['content'] = '请求错误';
							  echo json_encode($data);
							  exit;
						  }
					   break;	  
				   }   
			  }

              $table_fix = substr(session('zh'),0,3);
              $user_table = $table_fix.'_members';

              $list = M("$user_table")->where(array('user'=>session('zh')))->save($arr);
              if($list){
                  session('zh',null);
                  $data['state'] = 80005;
                  $data['content'] = '重置成功';
                  echo json_encode($data);
                  exit;
              }else{
                  $data['state'] = 80005;
                  $data['content'] = '重置失败';
                  echo json_encode($data);
                  exit;
              }
          }else{
              $data['state'] = 80005;
              $data['content'] = '请求错误';
              echo json_encode($data);
              exit;
          }
    }
	  
}
?>
