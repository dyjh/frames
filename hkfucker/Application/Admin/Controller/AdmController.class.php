<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12 0012
 * Time: 16:32
 */

namespace Admin\Controller;
use Think\Controller;

class AdmController extends AdminController
{
    public function admin(){
        define('UC_AUTH_KEY', 'h@x.Mb^50W(TC:g?Xr_>4LjZ6|{k3]z"aE2vi1),'); //加密KEY 自定义
        if(IS_POST){
            //print_r($_POST);
            if (!checkToken($_POST['TOKEN'])) {          //验证令牌
                $this->redirect('Adm/admin');
                return;
            }
            $password=I('post.pwd','','strip_tags');
            //print_r($id);die;
            $pwd=I('post.pwd_old','','strip_tags');
            //print_r(UC_AUTH_KEY);echo'<b/>';
            //print_r(think_ucenter_md5($password, UC_AUTH_KEY));
            $map['username'] = $_SESSION['name'];

            $member=M('ucenter_member');
            $user = $member->where('username=%s',array($map['username']))->find();     
            if(is_array($user) && $user['status']){
                /* 验证用户密码 */
                //print_r($user);die;
                if(think_ucenter_md5($pwd, UC_AUTH_KEY) === $user['password']){
                    $data['password']=think_ucenter_md5($password, UC_AUTH_KEY);
                    $member->password=$data['password'];
                    if($member->where('username=%s',array($map['username']))->save() !==false){
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("修改成功！"); </script>';
                        echo "<script> window.location.href='".U('Adm/admin')."';</script>";
                        exit();
                    }else{
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                        echo '<script> alert("修改失败1！"); </script>';
                        echo "<script> window.location.href='".U('Adm/admin')."';</script>";
                        exit();
                    }
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    echo '<script> alert("修改失败2！"); </script>';
                    echo "<script> window.location.href='".U('Adm/admin')."';</script>";
                    exit();
                }
            } else {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                echo '<script> alert("修改失败3！"); </script>';
                echo "<script> window.location.href='".U('Adm/admin')."';</script>";
                exit();
            }
        }else{
            creatToken();
            $this->display();
        }
    }
}
