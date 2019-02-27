<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17 0017
 * Time: 15:47
 */

namespace Think;
use Think\Model;

class Smscode{

    public function code_case($tel){
        $srand = rand(100000,999999);
        session('Reg',$srand);
        $contents='尊敬的用户：您正在登录，验证码为：'.$srand.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        return $this->Tel_Sms($contents,$tel);
    }
    //手机验证码
    private function Tel_Sms($contents,$tel){
        if($tel){
            //发送短信参数：账号，密码，手机号，短信内容，特服号(可选)，定时时间(可选)
            $res = new sendMessage();
            return $res->sendMessage("scxlsw","012689",$tel,$contents);

        }
    }
}