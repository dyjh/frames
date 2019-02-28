<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Our\Tool;
use Org\Our\Disasters;
class IndexController extends Controller{
		
     
	 public function index(){
		 
		   /*if(!empty($_GET['key']) && !empty($_GET['mac']) && !empty($_GET['user'])){
			
				 $key=I('get.key','','');  //密钥
				 $mac=I('get.mac','','');  //ip
				 $user_zhi=I('get.user','',''); //用户
				 //用户表
				 $num=substr($user_zhi,0,3);
				 $case_user=''.$num.'_members';
				 $data=M($case_user)->field('id_card')->where('user='.$user_zhi.'')->find();
				 //用户密钥
				 $card=$data['id_card'];
				 $str='';
				 $str.=$user_zhi;
				 $str.=$card;
				 $token=md5($str);

				//判断密钥
				if($token==$key){
					//清除之前的session
					session('mac',MD5($mac));
					session('user',$user_zhi);
					$user = session('user');
					 //消除缓存，有则清除
					S(session('user').'ip',null);
				    //开启IP缓存
					S(session('user').'ip',MD5($mac));
				}else{
					echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
      	     	    echo '<script> alert("用户名有误！"),history.back(); </script>';
      	     	    exit();
				}
		    }else{

                
		    }*/
			
		   /*if(session('user')==15802858094 || session('user')==18228068397|| session('user')==18584084806 || session('user')==18382050570 || session('user')==18780164595 || session('user')==18382077208){
			    
		   }else{
			   echo '系统正在维护';;
			   exit; 
		   }*/	
			
		   if(session('user')==''){
                $this->redirect('Login/index');
			    exit;
           }
		   
		   
		   //获取IP 
		   if(getenv('HTTP_CLIENT_IP')){
				$client_ip = getenv('HTTP_CLIENT_IP');
		   }elseif(getenv('HTTP_X_FORWARDED_FOR')) {
				$client_ip = getenv('HTTP_X_FORWARDED_FOR');
		   }elseif(getenv('REMOTE_ADDR')) {
				$client_ip = getenv('REMOTE_ADDR');
		   }else {
				$client_ip = $_SERVER['REMOTE_ADDR'];
		   }
			
		   $mac = $client_ip;
		   //消除缓存，有则清除
		   S(session('user').'ip',null);
		    //开启IP缓存
		   S(session('user').'ip',MD5($mac));
		   //开启IP SESSION	
		   session('mac',MD5($mac));
		   
		   $user = session('user');
			   
           //获取当前中奖信息
           if(S('treasure_num')==false){
                $treasure_num = 0;
           }else{
                $treasure_num = S('treasure_num');
           }
           session('treasure_num',$treasure_num);
		   		  
           $Tool = New Tool;
           $user_members = $Tool->table($user,'members');
           $user_plantione = $Tool->table($user,'planting_record');
           $members = M("$user_members");
           $user_message = $members->field('coin,diamond,level,headimg,sign_state,gift_state,id_card,nickname,num_id,identity')->where(array('user'=>$user))->select();
		   
           $plantione = M("$user_plantione");
           $array = $plantione->where('user="'.$user.'" and harvest_state=0')->select();
		   		   
		    //查看公告信息
	
		    $notice = M('notice')->order('id desc')->where('type=1')->select();
		    $notice_count = count($notice);
			   
		    $users_behavior = M('users_behavior')->field('content_num')->where(array('user'=>$_SESSION['user']))->find();
			  
			$no_read = array();	
            $tmep = 0;			  
		    if($users_behavior){				  
				 if($notice_count-$users_behavior['content_num']>0){
					  for($i=0;$i<$notice_count-$users_behavior['content_num'];$i++){
					      $no_read[$tmep] = $notice[$i];
					      $tmep++;
				      }
					  $no_read_title = 1;
				  }else{
					   $no_read_title = 0;
				  }
		     }else{
				  $nocite_message['user'] = session('user');
				  $nocite_message['content_num'] = $notice_count; 
				  M('users_behavior')->add($nocite_message);  
				  $no_read_title = 0;
			 }	  	   
		   

		   //生成用户密钥
		   $str = session('user').$user_message[0]['id_card'];
		   $key = md5($str);
		   //消除登陆验证
		   session('login_number',null);

           //土地总数
           $land_sum = 12;
           //可用土地数(即级别)
           $land_available = $user_message[0]['level'];
		   
           //将土地编号做为索引处理，并装进数组
           $planting_num = array();
           for($i=0;$i<count($array);$i++){
               $planting_num[$array[$i]['number']] = $array[$i];
           }
           //将可用土地数(即级别)进行土地编号索引循环，判断每一块土地的种植状态，得到种植数据，并装进数组
           $planting_state = array();	
		   
           for($i=1;$i<$land_available+1;$i++){
               if($planting_num[$i]==""){
                   $planting_state[$i] = "";
               }else{
                   $planting_state[$i] = $planting_num[$i];
               }
           }
		   
		   //活动公告
		  /*$arc_str = '';
		  $act_data = M('activities_winning')->select();
		  for($i=0;$i<count($act_data);$i++){
			   $arc_str .= '<p><img src="/farms/Public/Home/images/index/laba.png">'.$act_data[$i]['nickname'].'获得'.$act_data[$i]['prize'].'一部</p>';
		  }*/
		     
		  //稻草人
		  $service_table = substr(session('user'),0,3).'_managed_to_record';
		  $scarecrow = M("$service_table")->where('service_type=5 and state=0 and user="'.$_SESSION['user'].'" and end_time>='.time())->find();  
		  if($scarecrow){
			  $this->assign('scarecrow',$scarecrow);
		  }
		  
		  //$this->assign('arc_str',$arc_str);
		  $this->assign('no_read_title',$no_read_title);
		  $this->assign('no_read',$no_read);
		  $this->assign('key',$key);
		  $this->assign('token',session('token'));
          $this->assign('user_message',$user_message);
          $this->assign('land_sum',$land_sum);
          $this->assign('land_available',$land_available);
          $this->assign('planting_state',$planting_state);
		  $this->assign('next_level',$land_available+1);
          $this->display();
      }


	  
}
?>
