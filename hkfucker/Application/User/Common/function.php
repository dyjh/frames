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






