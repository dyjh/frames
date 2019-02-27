﻿<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title>顺付-收银台</title>
    <link id="linkWebCss" href="App_Themes/Cashier/Web.min.css" rel="stylesheet" />
<link id="linkWeixinCss" href="App_Themes/Cashier/Weixin.css" rel="stylesheet" />
    <link href="App_Themes/Css/css.css" rel="stylesheet" type="text/css" />
<link id="linkPaymentDialogCss" href="App_Themes/Cashier/PaymentDialog.css" rel="stylesheet" />
<script type="text/javascript" src="App_Themes/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="App_Themes/js/zzsc.js"></script>
    <meta name="keywords" content="" />
</head>
<body>
   <form id="payfrom" action="PayInterface.aspx" method="post" target="_blank">
         <input type="hidden" name="bankCardType" value="00"/>
        <input type="hidden" name="totalAmount" value="1.00"/>
  <div id="divTitle" class="Header">
            <div class="Wrap1000">
                <div class="Logo">
                    <span></span>| &nbsp;收银台
                </div>
            </div>
        </div>
        <div id="divLine" style="border-bottom: 3px solid #A2AABB; margin-top: 10px;">
        </div>
<div class="w1280">
	<div class="pay">
    	<div class="title"><span>支付号：顺付 </span><span>商户：测试</span><span>商品名称：充值体验</span><span>金额：<em>1.00</em>元</span></div>
        <div class="bank">
            <div class="demo1">
            <ul class="tab_menu">
              <li class="current">网银</li>
              <li>支付宝</li>
              <li>财付通</li>
              <li>微信</li>
              <li>点卡</li>
            </ul>
            <div class="tab_box">
              <div class="">
              	<div class="zhifu">
                <label>
                  <input type="radio" name="bankCode" id="b_abc" value="964" checked="checked"/><img src="App_Themes/images/bank/ABC.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_boc" value="963" /><img src="App_Themes/images/bank/BOC.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_ccb" value="965" /><img src="App_Themes/images/bank/CCB.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_icbc" value="967" /><img src="App_Themes/images/bank/ICBC.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_cmb" value="970" /><img src="App_Themes/images/bank/cmb.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_psbc" value="971" /><img src="App_Themes/images/bank/psbc.png" width="144" height="44" />
                </label>
				<label>
                  <input type="radio" name="bankCode" id="b_bcom" value="981" /><img src="App_Themes/images/bank/bcom.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_spdb" value="977" /><img src="App_Themes/images/bank/spdb.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_cib" value="972" /><img src="App_Themes/images/bank/cib.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_citic" value="962" /><img src="App_Themes/images/bank/ECITIC.png" width="144" height="44" />       </label>
				  <label>
                  <input type="radio" name="bankCode" id="b_gdb" value="985" /><img src="App_Themes/images/bank/gdb.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_pab" value="978" /><img src="App_Themes/images/bank/pab.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_shb" value="975" /><img src="App_Themes/images/bank/shb.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_ceb" value="986" /><img src="App_Themes/images/bank/cebb.png" width="144" height="44" />       </label>
				  <label>
                  <input type="radio" name="bankCode" id="b_cmbc" value="980" /><img src="App_Themes/images/bank/cmbc.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_hxb" value="982" /><img src="App_Themes/images/bank/hxb.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_bob" value="989" /><img src="App_Themes/images/bank/bob.png" width="144" height="44" />                </label>
                <label>
                  <input type="radio" name="bankCode" id="b_nbcb" value="998" /><img src="App_Themes/images/bank/nbb.png" width="144" height="44" />       </label>
				  <label>
                  <input type="radio" name="bankCode" id="b_hzb" value="983" /><img src="App_Themes/images/bank/hzb.png" width="144" height="44" />                </label>

                </div>
                
                <div class="clear"></div>
                <div class="qita">
                </div>
                <div class="btn"><a href="javascript:sub()">下一步</a></div>
                
              </div>
              <div class="hide">
                
                <div class="zhifu">
                <label>
                  <input type="radio" name="bankCode" id="b_zfb" value="992" /><img src="App_Themes/images/zfb.png" />
                </label>
                </div>
                <div class="btn"><a href="javascript:sub()">下一步</a></div>
                
              </div>
              <div class="hide">
                
                <div class="zhifu">
                <label>
                  <input type="radio" name="bankCode" id="b_cft" value="993" /><img src="App_Themes/images/bank/tenpay.png" />
                </label>
                </div>
                <div class="btn"><a href="javascript:sub()">下一步</a></div>
                
              </div>
               <div class="hide">
                
                <div class="zhifu">
                <label>
                  <input type="radio" name="bankCode" id="b_weixin" value="1004" /><img src="App_Themes/images/bank/wxpay.jpg" />
                </label>
                </div>
                <div class="btn"><a href="javascript:sub()">下一步</a></div>
                
              </div>
               <div class="hide">
                
                <div class="zhifu">
                      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" 

style="border-spacing: 0;">
                     <tr>
                        <td>
                            充值金额(元)
                        </td>
                        <td>
                            &nbsp;&nbsp;<input size="50" type="text" name="facevalue" 

id="facevalue" value="100" />&nbsp;<span
                                style="color: #FF0000; font-weight: 100;">*</span>
                        </td>
                    </tr>
                       <tr id="trcardNum">
                        <td>
                            卡号
                        </td>
                        <td>
                            &nbsp;&nbsp;<input size="50" type="text" name="cardNo" id="cardNo" 

value="" />&nbsp;<span
                                style="color: #FF0000; font-weight: 100;">*</span>
                        </td>
                    </tr>
                    <tr id="trcardPwd" >
                        <td>
                            卡密
                        </td>
                        <td>
                            &nbsp;&nbsp;<input size="50" type="text" name="cardPwd" id="cardPwd" 

value="" />&nbsp;<span
                                style="color: #FF0000; font-weight: 100;">*</span>
                        </td>
                    </tr>
                          </table>
              <div class="zhifu">
                <label>
                                                <input type="radio" name="Channel" id="Channel1" value="1">
                                                <img src="App_Themes/images/dianka/QBCZK.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio2" value="2">
                                                <img src="App_Themes/images/dianka/SDYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio3" value="3">
                                                <img src="App_Themes/images/dianka/JWYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio4" value="5">
                                                <img src="App_Themes/images/dianka/WMYKT.png" />
                                            </label>
                                     
                                            <label>
                                                <input type="radio" name="Channel" id="Radio5" value="6">
                                                <img src="App_Themes/images/dianka/SHYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio6" value="7">
                                                <img src="App_Themes/images/dianka/ZTYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio8" value="8">
                                                <img src="App_Themes/images/dianka/JYYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio9" value="9">
                                                <img src="App_Themes/images/dianka/WYYKT.png" />
                                            </label>
                                     
                                       
                                            <label>
                                                <input type="radio" name="Channel" id="Radio10" value="12">
                                                <img src="App_Themes/images/dianka/DXGK.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio11" value="13">
                                                <img src="App_Themes/images/dianka/YDSZX.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio12" value="14">
                                                <img src="App_Themes/images/dianka/LTYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio16" value="21">
                                                <img src="App_Themes/images/dianka/TXYKT.png" />
                                          
                                        </label>
                                        <div style="margin-bottom: 20px;">
                                            <label>
                                                <input type="radio" name="Channel" id="Radio17" value="22">
                                                <img src="App_Themes/images/dianka/THYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio18" value="23">
                                                <img src="App_Themes/images/dianka/ZYYKT.png" />
                                            </label>
                                            <label>
                                                <input type="radio" name="Channel" id="Radio20" value="28">
                                                <img src="App_Themes/images/dianka/SFYKT.png" />
                                            </label>
                                        </div>

                <div class="clear"></div>
                <div class="qita">
                </div>
                <div class="btn"><a href="javascript:sub()">下一步</a></div>
                
              </div>
              </div>
            </div>
        
        </div>
        
        </div>
    </div>
</div>
</form>
<script src="App_Themes/js/jquery.tabs.js"></script>

<script>
    $(function () {
        //$('.demo1').Tabs({
        //	event:'click',
        //	switchBtn : true
        //});
        function tabcallback() {
            var index = $(".current").index();
            if (index == 0) {
                $("#b_abc").attr("checked", true);
            }
            else if (index == 1) {
                $("#b_zfb").attr("checked", true);
            }
            else if (index == 2) {
                $("#b_cft").attr("checked", true);
            }
            else if (index == 3) {
                $("#b_weixin").attr("checked", true);
            }

            if (index == 4) {
                $("#bankCardType").val("01");
                $("#Channel1").attr("checked", true);
            } else {
                $("#bankCardType").val("00");
            }
        }

        $('.demo1').Tabs({
            event: 'click',
            callback: tabcallback
        });


        $(".tab_menu > li").onclick(function (e) {
            // var index = $.inArray(this, tabs);
            alert("ok");
        });
    });

    function sub() {
        var index = $(".current").index();
        if (index == 4) {//
            var cardno = $("#cardNo").val();
            if (!cardno) {
                alert("请输入卡号");
                return;
            }
            var cardPwd = $("#cardPwd").val();
            if (!cardPwd) {
                alert("请输入卡密");
                return;
            }
            var facevalue = $("#facevalue").val();
            if (!facevalue) {
                alert("请输入面值");
                return;
            }
        }
        $("#payfrom").submit();
    }
</script>

    </form>
     <div id="divFooter" class="Footer" style="margin-top: 100px;">
            <div class="ClearFloat">
            </div>
            <div class="Footer_part2">
                <div style="border: 1px solid #CFCFCF;"></div>
                
<div class="footerD">

</div>

           </div>
        </div>
</body>
</html>