<?php
namespace Org\Our;
use Think\Model;
use Org\Our\SendMessage;

class Smscode{

    private $tel;
    private $key;

    function __construct($tel,$key){
        $this->tel = $tel;
        $this->key = $key;
        $this->code_case($this->tel,$this->key);
    }


    private function code_case($tel,$key){
        $code = rand(100000,999999);
        if($key == 'Reg'){
            session('Reg',$code);
            $contents='尊敬的用户：您正在注册通行证，验证码为：'.$code.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }elseif ($key == 'Xeg'){
            session('Xeg',$code);
            $contents='尊敬的用户：您正在修改通行证密码，验证码为：'.$code.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }elseif ($key == 'Zeg'){
            session('Zeg',$code);
            $contents='尊敬的用户：您正在找回通行证密码，验证码为：'.$code.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }
        $this->Tel_Sms($contents,$tel);
    }

    //手机验证码
    private function Tel_Sms($contents,$tel){
        if($tel){
            //发送短信参数：账号，密码，手机号，短信内容，特服号(可选)，定时时间(可选)
            $Send = new SendMessage();
            $Send->sendMessage($tel,$contents);
        }
    }
}
