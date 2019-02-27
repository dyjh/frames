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

    private $tel;
    private $key;
    function __construct($tel,$key)
    {
        $this->tel = $tel;
        $this->key = $key;
        $this->code_case($this->tel,$this->key);
    }

    private function code_case($tel,$key){
        $srand = rand(100000,999999);
        if($key == 'Reg'){
            session('Reg',$tel.$srand);
            $contents='尊敬的用户：您正在注册通行证，验证码为：'.$srand.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }elseif ($key == 'Xeg'){
            session('Xeg',$srand);
            $contents='尊敬的用户：您正在修改通行证密码，验证码为：'.$srand.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }elseif ($key == 'Zeg'){
            session('Zeg',$tel.$srand);
            $contents='尊敬的用户：您正在找回通行证密码，验证码为：'.$srand.'，如非本人操作，请勿转告他人。【凯撒庄园】';
        }
        $this->Tel_Sms($contents,$tel,$srand);
    }
    //手机验证码
    private function Tel_Sms($contents,$tel,$srand){
        if($tel){
            //发送短信参数：账号，密码，手机号，短信内容，特服号(可选)，定时时间(可选)
             $res = new sendMessage();
             $result = $res->sendMessage("scxlsw","012689",$tel,$contents);
             // $result = 1; 
            $back_data =array();
            if($result == 1){
                $back_data['status']     = 1;
                $back_data['code']       = md5($tel.$srand);
               // $back_data['AAAA']       = $srand;
            }else{
                $back_data['status']     = -1;
                $back_data['randcode']  = $result;
            }

            echo json_encode($back_data);
        }
    }
}