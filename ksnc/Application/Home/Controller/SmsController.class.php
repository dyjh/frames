<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5 0005
 * Time: 13:25
 */

namespace Home\Controller;
use Think\Controller;
use Think\Smscode;

class SmsController extends Controller
{
 //注册账号
 public function sms(){
     //短信验证码
     if(IS_AJAX){
         if ($_POST['Reg']){
             new Smscode($_POST['Reg'],'Reg');//注册验证码
         }elseif ($_POST['Zeg']){
             new Smscode($_POST['Zeg'],'Zeg');//找回密码验证码
         }elseif ($_POST['Xeg']){
             new Smscode($_POST['Xeg'],'Xeg');//修改密码验证码
         }else{
             return false;
         }
      }
 }

}