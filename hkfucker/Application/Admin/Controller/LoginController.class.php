<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
use Think\Smscode;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class LoginController extends \Think\Controller {

    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
	 public function check(){
		 
        if(IS_AJAX){
            $user=_safe(I('post.user'));

            if( preg_match("/^1[34578]\d{9}$/", $user) ){

               // print_r($user);
                $Smscode =   new Smscode();
				//echo 1;die;
                $check    =    $Smscode->code_case($user);

                if($check){
                    echo $check;
                }else{
                    echo-1;
                }
            }
        }
    }
    public function login( $password = null, $verify = null){
        if(IS_POST){
            //print_r($_POST);
            $username=_safe(I('post.username'));
            $password=I('post.password','','strip_tags');
            //$verify=I('post.verify');
			$verify=intval(I('post.verify','','addslashes'));
            //print_r(M("verification")->find());DIE;
            //print_r($username);die;
            if (!checkToken($_POST['TOKEN'])) {
                $this->redirect('Login/login');
                return;
            }
            /* 检测验证码 TODO: */
            /*if(!check_verify($verify)){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('验证码错误，请重新登陆');</script>";
                echo "<script> window.location.href='".U('Login/login')."';</script>";
                exit();
            }*/
		
			/*if($verify!==$_SESSION['Reg']){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo "<script> alert('验证码错误，请重新登陆');</script>";
                echo "<script> window.location.href='".U('Login/login')."';</script>";
                exit();
            }*/
			
			

            /* 调用UC登录接口登录 */
            $User = new UserApi;
            $uid = $User->login($username, $password);

            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                $uid=intval(addslashes($uid));
                if($Member->login($uid)){ //登录用户
//                    setcookie('name',$username);
//                    setcookie('pwd',$password);
                    session('name',$username);
                    //TODO:跳转到登录前页面
                     echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
						echo '<script> alert("登录成功！"); </script>';
						echo "<script> window.location.href='".U('Index/index')."';</script>";
						exit();
                } else {
                    $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo "<script> alert('用户名或密码错误，请重新登陆');</script>";
                        echo "<script> window.location.href='".U('Login/login')."';</script>";
                        exit();
                    case -2:echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo "<script> alert('用户名或密码错误，请重新登陆');</script>";
                        echo "<script> window.location.href='".U('Login/login')."';</script>";
                        exit();
                    default: echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo "<script> window.location.href='".U('Login/login')."';</script>";
                        exit(); // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config	=	S('DB_CONFIG_DATA');
                if(!$config){
                    $config	=	D('Config')->lists();
                    S('DB_CONFIG_DATA',$config);
                }
                C($config); //添加配置
                creatToken();
                $this->display("login");
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            session('[destroy]');
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            echo "<script> window.location.href='".U('Login/login')."';</script>";
            exit();
        } else {
            $this->redirect('login');
        }
    }

    /*public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }
*/
    public function check_Verifys($verify_code){
		if($verify_code==$_SESSION['Reg']){
            echo 111;
        }
       // echo check_verifys($verify_code);
    }



}
