<?php error_reporting(E_ERROR);?>
<style>*{font-size:12px; }h1{font-size:24px;}</style>
<form action="card_callback_test.php" method="post">
<h1>银泰卡类支付下行测试</h1>
下行地址：<input name="url" type="text" value="<?php echo(isset($_POST['url']) ? $_POST['url'] : "http://localhost/shunfoo/callback/pay_card_callback.php");?>" size="80"/>
<p><strong>请求协议参数：</strong></p>
<table border="1" cellspacing="0" cellpadding="0" width="552">
  <tr>
    <td width="144" valign="top">      字段名 </td>
    <td width="408" valign="top"><p>说明</p></td>
  </tr>
  <tr>
    <td width="144" valign="top"><p>orderid</p></td>
    <td width="408" valign="top"><p><input type="text" name="orderid" value="<?php echo($_POST['orderid']);?>"/>上行过程中传入的orderid</p></td>
  </tr>
  <tr>
    <td width="144" valign="top"><p>opstate</p></td>
    <td width="408" valign="top"><p><input type="text" name="opstate"  value="<?php echo($_POST['opstate']);?>"/><br>操作结果状态。<br />
      0 卡被成功使用<br />
      -1 卡号密码错误<br />
      -2 卡实际面值和提交时面值不符，卡内实际面值未使用。卡实际面值由ovalue表示<br />
      -3 卡实际面值和提交时面值不符，卡内实际面值已被使用。卡实际面值由ovalue表示<br />
      -4 卡已经使用（卡在提交到银泰之前已经被使用） <br />
      -5 失败(网络原因、具体原因不明确等)</p></td>
  </tr>
  <tr>
    <td width="144" valign="top"><p>ovalue</p></td>
    <td width="408" valign="top"><p><input type="text" name="ovalue"  value="<?php echo($_POST['ovalue']);?>"/>
      <br>opstate=-2或者-3时表示的值，单位元(注：现只提供正确的骏网卡实际面值，其他卡值为0或者无效。为了精确性，该值可能带有4位小数)</p></td>
  </tr>
  <tr>
    <td valign="top">shunfooorderid</td>
    <td valign="top"><input type="text" name="shunfooorderid"  value="<?php echo($_POST['shunfooorderid']);?>"/>
      <br />
      此次卡消耗过程中银泰系统的订单Id。为保持和以前版本兼容，该值不加入返回结果签名验证。</td>
  </tr>
  <tr>
    <td valign="top">shunfootime</td>
    <td valign="top"><input type="text" name="shunfootime"  value="<?php echo($_POST['shunfootime']);?>"/>
      <br />
      此次卡消耗过程中银泰系统的订单结束时间。格式为<br />
        年/月/日 时：分：秒，如2010/04/05 21:50:58。<br />
      为保持和以前版本兼容，该值不加入返回结果签名验证。</td>
  </tr>
  <tr>
    <td width="144" valign="top"><p>&nbsp;</p></td>
    <td width="408" valign="top"><p>
      <label>
      <input type="submit" name="Submit" value="模拟下行">
      </label>
    </p></td>
  </tr>
</table>
</form>

<p><strong>返回协议参数：</strong></p>
<table border="1" cellspacing="0" cellpadding="0" width="552">
  <tr>
    <td width="144" valign="top"><p>返回名称</p></td>
    <td width="408" valign="top"><p>说明</p></td>
  </tr>
  <tr>
    <td width="144" valign="top"><p>opstate</p></td>
    <td width="408" valign="top"><p>操作结果状态。<br>
      0 成功<br>
      -1 请求参数无效<br>
      -2 签名错误</p></td>
  </tr>
</table>

==============================返回结果===============================
<br />
<?php
require_once("../config/pay_config.php");
$callback_url	= $_POST['url'];

/*
$orderid
上行过程中传入的商户orderid
*/
$orderid		= $_POST['orderid'];

/*
$opstate
操作结果状态。
0 卡被成功使用
-1 卡号密码错误
-2 卡实际面值和提交时面值不符，卡内实际面值未使用。卡实际面值由ovalue表示
-3 卡实际面值和提交时面值不符，卡内实际面值已被使用。卡实际面值由ovalue表示
-4 卡已经使用（卡在提交到亿卡联盟之前已经被使用）
-5 失败(网络原因、具体原因不明确等)

*/
$opstate		= $_POST['opstate'];

/*
opstate=-2或者-3时表示的值，单位元(注：现只提供正确的骏网卡实际面值，其他卡值为0或者无效。为了精确性，该值可能带有4位小数)
*/
$ovalue			= $_POST['ovalue'];

/*
此次卡消耗过程中亿卡系统的订单Id
*/
$shunfooorderid		= $_POST['shunfooorderid'];

/*
此次卡消耗过程中亿卡系统的订单结束时间
*/
$shunfootime		= $_POST['shunfootime'];


$sign_text  = "orderid=" . $orderid . "&opstate=" . $opstate . "&ovalue=" . $ovalue;
$sign_md5 	= md5($sign_text .$shunfoo_merchant_key);


$url = 	$callback_url . '?' . $sign_text . "&sign=" .$sign_md5;
$url .= "&shunfooorderid=$shunfooorderid&shunfootime=$shunfootime";


echo("请求地址： ". $url);


$result=file_get_contents($url);
echo("<br />请求结果： ". $result);


?>