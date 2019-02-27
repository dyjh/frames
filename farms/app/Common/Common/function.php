<?php

function material_handle_list($all_material=array()){
    foreach ($all_material as $key=>$val){
        $material = array();
        $material = unserialize($val['cost']);
        foreach ( $material as $k=>$v){
            $all_material[$key][$v['seed_id']] = $v['seed_value'];
        }
    }
    return $all_material;
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
	
    $str = trim($str);
    $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
    //过滤字符串

    $str = str_replace($html_string, $html_clear, $str);

    //过滤JS

    $str = preg_replace($js_string, $js_clear, $str);

    //过滤ifram

    $str = preg_replace($frame_string, $frame_clear, $str);

    //过滤style

    $str = preg_replace($style_string, $style_clear, $str);


    $keyword = 'select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|and|union|order|or|into|load_file|outfile';
    $arr = explode( '|', $keyword );
    $str = str_ireplace( $arr, '', $str );

    return $str;

}

function material_handle_one($all_material=array()){
    $material = array();
    $material = unserialize($all_material['cost']);
    foreach ( $material as $k=>$v){
        $all_material[$v['seed_id']] = $v['seed_value'];
    }
    return $all_material;
}


function resultOrderBy($arrs, $orderBy){
       $orderArr = array();
       $orderType = array();
       $sortRule = '';
       foreach ($orderBy as $key => $value) {
           $temp = array();
           for ($i = 0; $i < count($arrs); $i++) {
               $temp[] = $arrs[$i][$key];
           }
           $orderArr[] = $temp;
           $orderType[] = $value == 'asc' ? SORT_ASC : SORT_DESC;
       }
       for ($i = 0; $i < count($orderBy); $i++) {
           $sortRule .= '$orderArr[' . $i . '],' . $orderType[$i] . ',';
       }
       //echo 'array_multisort('.$sortRule.'$arrs);';
       eval('array_multisort(' . $sortRule . '$arrs);');
       return $arrs;
  }

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

  function msubstr($str,$start=0,$length,$charset="utf-8",$suffix=true){
	   	if(function_exists("mb_substr")){
	   		if ($suffix && strlen($str)>$length)
	   			return mb_substr($str, $start, $length, $charset)."";
	   		else
	   			return mb_substr($str, $start, $length, $charset);
	   	    }
	   	elseif(function_exists('iconv_substr')){
	   		if ($suffix && strlen($str)>$length)
	   			return iconv_substr($str,$start,$length,$charset)."";
	   		else
	   			return iconv_substr($str,$start,$length,$charset);
	   	    }
	   	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	   	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	   	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	   	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	   	preg_match_all($re[$charset], $str, $match);
	   	$slice = join("",array_slice($match[0], $start, $length));
		if($suffix) return $slice."…";
		return $slice;
   }


   function image_name_icover($name){
	   
	   $array = array(

		  //商店
	      'zhongzi' => '种子',
		  'feiliao' => '肥料',
		  'shuifu' => '水壶',
		  'chucaoji'=> '除草剂',
		  'chuchongji' => '除虫剂',
		  'huangtong' => '黄铜宝箱',
		  'baiying' => '白银宝箱',
		  'huangjin' => '黄金宝箱',
		  'zhuanshi' => '钻石宝箱',
		  'caozaishoufu' => '草灾守护',
		  'chongzaishoufu' => '虫灾守护',
		  'hanzaishoufu' => '旱灾守护',
		  'fengshouzhixin' => '丰收之心',
		  'scarecrow_shop' => '稻草人',
		  //材料
		  'zhuantou' => '砖头',
		  'shuili' => '水泥',
		  'ganjing' => '钢筋',
	       //物品
	      'buy_diamond'=>'宝石',
		  'level_diamond'=>'升级宝石',
		  //果实
		  'tudou' => '土豆',
		  'caomei' => '草莓',
		  'yingtao' => '樱桃',
		  'daomi' => '稻米',
		  'putao' => '葡萄',
		  'fanqie' => '番茄',
		  'boluo' => '菠萝',
		  'yaoqianshu' => '摇钱树',
          'fenhongbao' => '分红宝',
		  //活动
		  'shouji1' => '手机碎片1',
		  'shouji2' => '手机碎片2',
		  'shouji3' => '手机碎片3',
		  'shouji4' => '手机碎片4',
		  'shouji5' => '手机碎片5',
		  'shouji6' => '手机碎片6',
	   );

	   foreach($array as $v=>$key){
		   if($key==$name){
			   return $v;
		   }
	   }
   }
   
   //生成表单令牌
   function token(){
	   $code = '12345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY!@%#&*$*(#(*()$E)_ED)_D)(W*(@W*(*UDSIIFOSDJIOW)(DIj';
	   $code = str_shuffle($code); 
	   $length = rand(1,40);
	   $code = MD5(substr($code,0,$length));
	   return authcode($code);
   }
   
   function authcode($str) {
      $key = "lyogame";
      $str = substr(md5($str), 8, 10);
	  session('token', md5($key.$str));
      return md5($key.$str);
   }
?>
