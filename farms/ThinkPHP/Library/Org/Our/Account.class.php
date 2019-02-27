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

	//用户是否冻结状态
    public function User_Freeze($data){
        $res = M('user_freeze')->where('user='.$data['user'])->field('state')->find();
        if ($res['state']==1){
            $this->Login($data);
        }else{
            echo '-2';//当前用户被冻结
        }
    }

    //用户登陆
   private function Login($data){
       /*if(getenv('HTTP_CLIENT_IP')){
           $client_ip = getenv('HTTP_CLIENT_IP');
       } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
           $client_ip = getenv('HTTP_X_FORWARDED_FOR');
       } elseif(getenv('REMOTE_ADDR')) {
           $client_ip = getenv('REMOTE_ADDR');
       } else {
           $client_ip = $_SERVER['REMOTE_ADDR'];
       }*/
       $sqluser = substr($data['user'], 0, 3);
        $sqlname = ''.$sqluser.'_members';
        $data['password'] = md5($data['password']);
        if($date = M($sqlname)->where($data)->find()) {
            // 存储session
            foreach ($date as $v=>$key){
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

            //$save['mac'] = $client_ip;
            $save['login_time'] = time();
            $res = M($sqlname)->where('id='.$login_session['id'])->save($save);   // 更新登录时间
            if($res){
                //session('mac',$client_ip);
                session('login',$login_session);
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
      
		if($data['code'] == $_SESSION['Reg']){
            session('Reg',null);
            $this->Merge($data);
        }else{
            echo 0;
        }
    }

    private function Merge($data){

        foreach($data as $v=>$key){
            if($v == 'code'){
                continue;
            }else if ($v == 'passworld'){
                $User_Reg_Message[$v] = md5($key);
            }else{
                $User_Reg_Message[$v] = $key;
            }
        }
        $User_Reg_Message['regis_time'] = time();
        $this->User_Add($User_Reg_Message);

    }

    private function User_Add($data){
        $Auto = new Autoadd();
        $Auto->Autoadd($data['user']);

        $sqluser = substr($data['user'],0,3);
        $sqlname = ''.$sqluser.'_members';

        M($sqlname)->add($data);
		$data['regis_time'] = time();
        $veri = M('verification')->add($data);
        if ($veri){
			$member = $sqluser."_member_record";
			M("$member")->add($data);
            echo 1; //注册成功
        }else{
            echo 0; //注册失败
        }
    }

    //用户找回密码
    public function Zeg_Code($data){
        if($data['code'] == $_SESSION['Zeg']){
            session('Zeg',null);
            session('user',$data['user']);
            echo 1;//手机短信验证成功
        }else{
            echo 0;//手机短信验证失败
        }
    }

    public function User_Pass($data){
        $Auto = new Autoadd();
        $Auto->Autoadd($_SESSION['user']);
        $sqluser = substr($_SESSION['user'],0,3);
        $sqlname = ''.$sqluser.'_members';
        $save['passworld'] = md5($data['passworld']);
        if(M($sqlname)->where("user=".$_SESSION['user'])->save($save)){
            session(null);
            echo 1;//重置密码成功，跳转登陆
        }else{
            echo 0;//重置失败
        }
    }

    //用户修改密码
    public function Xeg_Pass($data){
        if($data['code'] == $_SESSION['Xeg']){
            session('Xeg',null);
            $sqluser = substr($data['user'],0,3);
            $sqlname = ''.$sqluser.'_members';
            $save['passworld'] = md5($data['passworld']);
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
