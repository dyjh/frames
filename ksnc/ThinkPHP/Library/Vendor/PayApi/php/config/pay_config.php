<?php
//=======================卡类支付和网银支付公用配置==================
//银泰商户ID
$shunfoo_merchant_id		= '1982';

//银泰通信密钥
$shunfoo_merchant_key		= '41efc25404f842fe8d7e9114c93a22c4';	//hc6NOTDETVQe9Lgr


//==========================卡类支付配置=============================
//支付的区域 0代表全国通用
$shunfoo_restrict			= '0';


//接收银泰下行数据的地址, 该地址必须是可以再互联网上访问的网址
$shunfoo_callback_url		= "http://www.initepay.com/callback/pay_card_callback.php";
$shunfoo_callback_url_muti  = "http://www.initepay.com/callback/pay_card_callback_muti.php";

//======================网银支付配置=================================
//接收银泰网银支付接口的地址
$shunfoo_bank_callback_url	= "http://www.initepay.com/callback/pay_bank_callback.php";


//网银支付跳转回的页面地址
$shunfoo_bank_hrefbackurl	= 'http://127.0.0.1/ksnc/index.php/Home/Pay/pay_back';


?>