<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4 0004
 * Time: 17:55
 */


/**
 *   处理兑换材料所属的成本价格
 *   所有兑换材料
 */
function material_handle_list($all_material=array()){
    foreach ($all_material as $key=>$val){
        $material = array();
        $material = unserialize($val['cost']);
        unset($all_material[$key]['cost']);
        foreach ( $material as $k=>$v){
            $all_material[$key][$v['seed_id']] = $v['seed_value'];
        }
    }
    return $all_material;
}

/**
 *   处理兑换材料所属的成本价格
 *   单个兑换材料
 */
function material_handle_one($all_material=array()){
    $material = array();
    $material = unserialize($all_material['cost']);
    foreach ( $material as $k=>$v){
        $all_material[$v['seed_id']] = $v['seed_value'];
    }
    return $all_material;
}

function qhp_log_save($message,$level,$file_name,$record=false){

    $Log = new \Think\Log();// 实例化日志类
    $Log->record($message,$level,$record);
    $Log->save('File', C('LOG_PATH').date('y_m_d').'/'.$file_name.'.log');
}

function substr_tel($str){
    return substr($str,0,3);
}

/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage($count, $pagesize) {
    $p = new Think\Page($count, $pagesize);
    $p->setConfig('header', '<br/><br/><li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

function _safe($str){

    $html_string = array("&amp;", "&nbsp;", "'", '"', "<", ">", "\t", "\r");

    $html_clear = array("&", " ", "&#39;", "&quot;", "&lt;", "&gt;", "&nbsp; &nbsp; ", "");

    $js_string = array("/<script(.*)<\/script>/isU");

    $js_clear = array("");



    $frame_string = array("/<frame(.*)>/isU", "/<\/fram(.*)>/isU", "/<iframe(.*)>/isU", "/<\/ifram(.*)>/isU",);

    $frame_clear = array("", "", "", "");



    $style_string = array("/<style(.*)<\/style>/isU", "/<link(.*)>/isU", "/<\/link>/isU");

    $style_clear = array("", "", "");

    $str =  htmlentities(trim($str),ENT_QUOTES,'UTF-8');

    //过滤字符串

    $str = str_replace($html_string, $html_clear, $str);

    //过滤JS

    $str = preg_replace($js_string, $js_clear, $str);

    //过滤ifram

    $str = preg_replace($frame_string, $frame_clear, $str);

    //过滤style

    $str = preg_replace($style_string, $style_clear, $str);

    return $str;

}

function New_create($data)
{
    $arr = array();
    foreach($data as $k=>$v)
    {
        if($v){
            switch ($v){
                case is_array($v):
                    $arr[$k] = New_create($v);
                    break;
                default:
                    $arr[$k] = addslashes(trim(_safe($v)));
                    break;
            }
        }else{
            $arr[$k] = addslashes(trim(_safe($v)));
        }
    }
    return $arr;
}

function lib_replace_end_tag($str){
    if (empty($str)) return false;
    $str = htmlspecialchars($str);
    $str = strip_tags($str);
    $str = str_replace( '/', "", $str);
    $str = str_replace( '/', "", $str);
    $str = str_replace( '%', "", $str);
    $str = str_replace( '_', "", $str);
    $str = str_replace("\\", "", $str);
    $str = str_replace(">", "", $str);
    $str = str_replace("<", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str = str_replace("select","select",$str);
    $str = str_replace("join","join",$str);
    $str = str_replace("union","union",$str);
    $str = str_replace("where","where",$str);
    $str = str_replace("insert","insert",$str);
    $str = str_replace("delete","delete",$str);
    $str = str_replace("update","update",$str);
    $str = str_replace("like","like",$str);
    $str = str_replace("drop","drop",$str);
    $str = str_replace("create","create",$str);
    $str = str_replace("modify","modify",$str);
    $str = str_replace("rename","rename",$str);
    $str = str_replace("alter","alter",$str);
    $str = str_replace("cas","cast",$str);
    $str = str_replace("&","",$str);
    $str = str_replace(">",">",$str);
    $str = str_replace("<","<",$str);
    $str = str_replace(" ",chr(32),$str);
    $str = str_replace(" ",chr(9),$str);
    $str = str_replace("&",chr(34),$str);
    $str = str_replace("'",chr(39),$str);
    $str = str_replace("<br />",chr(13),$str);
    $str = str_replace("''","'",$str);
    $str = str_replace("css","'",$str);
    $str = str_replace("CSS","'",$str);
    return $str;
}

/********************php验证身份证号码是否正确函数*********************/
function is_idcard( $id )
{
    $id = strtoupper($id);
    $regx = "/(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if(!preg_match($regx, $id))
    {
        return FALSE;
    }
    //检查18位

    $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
    @preg_match($regx, $id, $arr_split);
    $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
    if(!strtotime($dtm_birth)) //检查生日日期是否正确
    {
        return FALSE;
    }
    else
    {
        //检验18位身份证的校验码是否正确。
        //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
        $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $sign = 0;
        for ( $i = 0; $i < 17; $i++ )
        {
            $b = (int) $id{$i};
            $w = $arr_int[$i];
            $sign += $b * $w;
        }
        $n = $sign % 11;
        $val_num = $arr_ch[$n];
        if ($val_num != substr($id,17, 1))
        {
            return FALSE;
        } //phpfensi.com
        else
        {
            return TRUE;
        }
    }


}

function is_mobile($str){
    if (strlen ( $str ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $str )) {
        return false;
    } else {
        return true;
    }
}

//创建TOKEN
function creatToken() {
    $code = chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE)) . chr(mt_rand(0xB0, 0xF7)) . chr(mt_rand(0xA1, 0xFE));
    session('TOKEN', authcode($code));
}

//判断TOKEN
function checkToken($token) {
    if ($token == session('TOKEN')) {
        session('TOKEN', NULL);
        return TRUE;
    } else {
        return FALSE;
    }
}

/* 加密TOKEN */
function authcode($str) {
    $key = "ANDIAMON";
    $str = substr(md5($str), 8, 10);
    return md5($key . $str);
}


/**
 *  银行卡号判断银行
 */
function bankInfo($card,$bankList)
{
    $card_8 = substr($card, 0, 8);
    if (isset($bankList[$card_8])) {
        return;
    }
    $card_6 = substr($card, 0, 6);
    if (isset($bankList[$card_6])) {
        return;
    }
    $card_5 = substr($card, 0, 5);
    if (isset($bankList[$card_5])) {
        return;
    }
    $card_4 = substr($card, 0, 4);
    if (isset($bankList[$card_4])) {
        return;
    }
    return true;
}

//  判断手机访问
function is_mobile_request()  
{  
 $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
 $mobile_browser = '0';  
 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
  $mobile_browser++;  
 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
  $mobile_browser++;  
 if(isset($_SERVER['HTTP_PROFILE']))  
  $mobile_browser++;  
 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
 $mobile_agents = array(  
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
    'wapr','webc','winw','winw','xda','xda-'
    );  
 if(in_array($mobile_ua, $mobile_agents))  {$mobile_browser++;  }
 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  {$mobile_browser++;  }
 // Pre-final check to reset everything if the user is on Windows  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  {$mobile_browser=0;  }
 // But WP7 is also Windows, with a slightly different characteristic  
 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  {$mobile_browser++;  }
 if($mobile_browser>0)   { return true;  }
 else{ return false; }
}


//  数组排序
  function i_array_column($input, $columnKey, $indexKey=null){
 	   	if(!function_exists('array_column')){
   	   		$columnKeyIsNumber = (is_numeric($columnKey))?true:false;
   	   		$indexKeyIsNull = (is_null($indexKey))?true :false;
   	   		$indexKeyIsNumber = (is_numeric($indexKey))?true:false;
   	   		$result = array();
   	   		foreach((array)$input as $key=>$row){
     	   			if($columnKeyIsNumber){
     	   				$tmp= array_slice($row, $columnKey, 1);
     	   				$tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
     	   			}else{
     	   				$tmp= isset($row[$columnKey])?$row[$columnKey]:null;
     	   			}
 	   			if(!$indexKeyIsNull){
   	   				if($indexKeyIsNumber){
   	   					$key = array_slice($row, $indexKey, 1);
   	   					$key = (is_array($key) && !empty($key))?current($key):null;
   	   					$key = is_null($key)?0:$key;
   	   				}else{
   	   					$key = isset($row[$indexKey])?$row[$indexKey]:0;
   	   				}
 	   			}
 	   			$result[$key] = $tmp;
 	   		}
 	   		return $result;
 	   	}else{
 	   		return array_column($input, $columnKey, $indexKey);
 	   	}
  }

  
  /**   
 * 数组分页函数  核心函数  array_slice   
 * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中   
 * $count   每页多少条数据   
 * $page   当前第几页   
 * $array   查询出来的所有数组   
 * order 0 - 不变     1- 反序   
 */       
      
function page_array($count,$page,$array,$order){      
    // global $countpage; #定全局变量      
	
    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面 
	
       $start=($page-1)*$count; #计算每次分页的开始位置      
	   
    if($order==1){      
	
      $array=array_reverse($array);    
	  
    }         
	
    $totals=count($array);  
	
    $countpage=ceil($totals/$count); #计算总页面数      
	
    $pagedata=array();      
	
    $pagedata=array_slice($array,$start,$count);      
	
    return $pagedata;  #返回查询数据      
	
}      























