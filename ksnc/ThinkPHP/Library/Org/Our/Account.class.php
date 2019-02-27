<?php

/*返回码*/
/*-2 封号状态*/
/*1 登陆成功*/
/*-1 请求错误*/
/*0 帐号密码有误*/

namespace Org\Our;
use Think\Model;
//用户操作类
class Account{

    protected $CodeAboutPass = "#LyoGame#";

    //用户是否冻结状态
    public function User_Freeze($data){

        $where['user'] = $data['user'];
        $res = M('user_freeze')->where($where)->field('state')->find();

        if ($res['state']==0){
            $back_code = $this->Login($data);
            return $back_code;
        }else{
            return '-2';//当前用户被冻结
        }
    }

    //用户登陆
    private function Login($data){
        $sqluser = substr($data['user'], 0, 3);

        $sqlname = ''.$sqluser.'_members';

        $where['user'] = $data['user'];

        $user_info = M($sqlname)->where($where)->find();

        if( md5($data['password']) == $user_info['password']) {
            // 存储session
            foreach ($user_info as $v=>$key){
                if($v == 'tel'){
                    continue;
                }else if($v == 'password'){
                    continue;
                }else if($v == 'team'){
                    continue;
                }else if($v == 'disasters_num'){
                    continue;
                }else{
                    $login_session[$v] = $key;
                }
            }

            if(getenv('HTTP_CLIENT_IP')){
                $client_ip = getenv('HTTP_CLIENT_IP');
            } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
                $client_ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif(getenv('REMOTE_ADDR')) {
                $client_ip = getenv('REMOTE_ADDR');
            } else {
                $client_ip = $_SERVER['REMOTE_ADDR'];
            }

            $save['login_time'] =     time();
            $save['mac']         =     $ip     =     $client_ip;
			
            $res = M($sqlname)->where('id='.$login_session['id'])->save($save);   // 更新登录时间
			
            if($res){
				//echo 1;die;
                session('mac',$ip);
                session('login',$login_session);
                $num=substr($user_info['user'],0,3);

                //用户密钥
                $str    =   '';
                $str   .=  $user_info['user'].$user_info['id_card'];
                session('token',md5($str));
                return 1;//登陆成功
            }else{
                return -1;//请求错误
            }
        } else {
            return 0;//账号或密码错误
        }

    }

    //用户注册
    public function Ver_Code($data){
		
		
        if( $data['user'].$data['code'] == $_SESSION['Reg']){
            $Auto = new Autoadd();
            $Auto->Autoadd($data['user']);
            session('Reg',null);
            return $this->Merge($data);
        }else{
            return 0;
        }

    }

    private function Merge($data){

        foreach($data as $v=>$key){
            if($v == 'code'){
                continue;
            }else if ($v == 'password'){
                $User_Reg_Message[$v] = md5($key);
            }else{
                $User_Reg_Message[$v] = $key;
            }
        }

        $User_Reg_Message['real_name_state'] = 1;//实名状态

        $User_Reg_Message['regis_time'] = time();//注册时间

        return $this->User_Add($User_Reg_Message);

    }

    private function User_Add($data){

        $sqluser = substr($data['user'],0,3);

        $sqlname = $sqluser.'_members';


        if( $data['referees'] != '' && $data['referees'] != $data['user']  ){

            $refereesuser = substr($data['referees'],0,3);

            $refereesname = ''.$refereesuser.'_members';

            $referees_members = M($refereesname);

            if($data['referees'] && !is_numeric($data['referees'])){
                return -2;   //  用户推荐用户填写不正确
            }

            $map = 'user="'.$data['referees'].'"';

            $team = $referees_members->field('team')->where($map)->find();

            if($team && $team['team'] !== ""){
                $data['team'] = $team['team']." ".$data['referees'];
            }else{
                $data['team'] = $data['referees'];
            }
			
        }
		
        if(getenv('HTTP_CLIENT_IP')){
            $client_ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
            $client_ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')) {
            $client_ip = getenv('REMOTE_ADDR');
        } else {
            $client_ip = $_SERVER['REMOTE_ADDR'];
        }

        $save['login_time'] =     time();
        $data['mac']         =     $ip     =     $client_ip;
        $data['tel']         =  $data['user']         ;

        M()->startTrans();

        // echo
	    $res[] = M($sqlname)->add($data);
	    // echo "--0\n";
	    // echo    
	    $res[] = M('verification')->add($data);
		// echo "--1\n";

		
		$team_relationship['user']  	= $data['user'];
		$team_relationship['referees']  = $data['referees'];
		$team_relationship['team']  	= $data['team'];
		$res[] = M('team_relationship')->add($team_relationship);//用户团队大表
		
		$user_freeze['user']	= $data['user'];
		$user_freeze['state']	= 0;
		$user_freeze['freez_time']	= 0;
		$user_freeze['sell']	= 0;
		
		// echo    
		$res[] = M('user_freeze')->add($user_freeze);
		// echo "--2\n";
		// echo   
		$res[] = M($sqluser.'_member_record')->add($data);
		// echo "--3\n";
		// echo
		$res[] = M('total_station')->where('id=1')->setInc('member_num');
		// echo "--4\n";
        $res = array_filter($res);

        if (count($res) == 6){
            M()->commit();
            return 1; //注册成功
        }else{
            M()->rollback();
            return -1; //注册失败
        }
    }

    //用户找回密码
    public function Zeg_Code($data){

        if($data['user'].$data['code'] == $_SESSION['Zeg']){
//            session('Zeg',null);
            session('user',$data['user']);

            $rand_num = rand(100,999);
            session('md5_user',md5($data['user'].$rand_num));
            $result_code['status'] = 1;
            $result_code['rand_num']    = $rand_num;
            return $result_code;//手机短信验证成功
        }else{
            return  $result_code['status'] = 0;//手机短信验证失败
        }
    }

    public function User_Pass($data){

        $sqluser = substr($_SESSION['user'],0,3);
        $sqlname = ''.$sqluser.'_members';

        $save['password'] = md5($data['password']);

        $result = M($sqlname)->where("user='".$_SESSION['user']."'")->save($save);

        if($result !== false){
            session(null);
            return 1;//重置密码成功，跳转登陆
        }else{
            return false;//重置失败
        }
    }

    //用户修改密码
    public function Xeg_Pass($data){
        if($data['code'] == $_SESSION['Xeg']){
            session('Xeg',null);
            $sqluser = substr($data['user'],0,3);
            $sqlname = ''.$sqluser.'_members';
            $save['password'] = md5($data['password']);
            if (M($sqlname)->where("user=".$data['user'])->save($save)){
                echo 1;//修改密码成功
            }else {
                echo -1;//请求错误
            }
        }else{
            echo 0;//手机短信验证失败
        }
    }

}
