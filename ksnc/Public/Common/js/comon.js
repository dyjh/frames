/**
 * Created by QHP on 2017/6/2.
 */

function CheckPhoneNum(_id){

    var Phone = $('#'+_id).val();

    if($.trim(Phone) == ''){
        $("span[data-valmsg-for="+_id+"]").html("请填写正确的手机号码");
        $('#yuser').val(0);
        $('#'+_id).focus();
        return false;
    }

    if ( !isPhoneNum( Phone ) ) {
        $("span[data-valmsg-for="+_id+"]").html("请填写正确的电话号码");
        $('#yuser').val(0);
        $('#'+_id).focus();
        return false;
    }else if(isPhoneNum(Phone)){
        $("span[data-valmsg-for="+_id+"]").html(" ");
    }

    // if($('#yuser').val() != -1){
        $.post(_url+"Yuser",{user:Phone},function(msg){
            if(msg == 0){
                alert('请求错误');
            }else if(msg == -1){
                $('#yuser').val(-1);
            }else if(msg == 1){
                $('#yuser').val(1);
                $('#'+_id).focus();
                $("span[data-valmsg-for="+_id+"]").html("该手机号码已经被注册");
                return false;
            }
        });
    // }

}

function CheckIDCardNum(_id){

    var IDCardNum = $('#'+_id).val();

    if ( !isCardID( IDCardNum ) ) {
        $("span[data-valmsg-for="+_id+"]").html("请填写正确的身份证号码");
        $('#IDCard').val(0);
        $('#'+_id).focus();
        return false;
    }else if(isPhoneNum(IDCardNum)){
        $("span[data-valmsg-for="+_id+"]").html(" ");
    }

    if($.trim(IDCardNum) == ''){
        $('#IDCard').val(0);
        $("span[data-valmsg-for="+_id+"]").html("请填写正确的身份证号码");
        $('#'+_id).focus();
        return false;
    }else if($('#IDCard').val() != 1){
        $.post(_url+"IDcard",{IDcard:IDCardNum},function(msg){
            if(msg == 0){
                $("span[data-valmsg-for="+_id+"]").html("请填写正确的身份证号码");
                $('#'+_id).focus();
                return false;
            }else if(msg == -1){
                $('#IDCard').val(-1);
                $("span[data-valmsg-for="+_id+"]").html("未满18岁，不能给予注册");
                $('#'+_id).focus();
                return false;
            }else if(msg == -2){
                $('#IDCard').val(-2);
                $("span[data-valmsg-for="+_id+"]").html("该身份证已经注册，请重新输入");
                $('#'+_id).focus();
                return false;
            }else if(msg == 1){
                $("span[data-valmsg-for="+_id+"]").html(" ");
                $('#IDCard').val(1);
            }
        });
    }
}

function GetSMS() {

    if($('#yuser').val() == -1){
        var Sms = $('#Sms').html();

        if(Sms != "验证码"){
            return false;
        }

        var Phone = $('#Phone').val();
        var code = $('#VCode').val();

        if (checkMobile(Phone)) {
            if($.trim(code) == ''){
                $("span[data-valmsg-for=VCode]").html("图形验证码错误");
                $('#VCode').focus();
                return false;
            }else{
                $("span[data-valmsg-for=VCode]").html(" ");
                $.post(_url+"check_code",{yzm:code},function(msg){
                    if(msg == 0){
                        $("span[data-valmsg-for=MobileVCode]").html("请求失败，请稍后重试");
                        $('#VCode').focus();
                        return false;
                    }else if(msg == -1){
                        $("span[data-valmsg-for=VCode]").html("图形验证码错误");
                        $('#VCode').focus();
                        return false;
                    }else if(msg == 1){
                        $('#yzm').val(1);
                        $("span[data-valmsg-for=VCode]").html(" ");
                        $.post(_module + "Sms"+URL_PATHINFO_DEPR+"sms",{Reg:Phone},function(msg){
                            if (msg.status == 1) {
                                $('#Sms').html('180');
                                s = 180;
                                IntVal = window.setInterval("showTime(s)",1000);
                                $('#MobileVCodePost').val(msg.code);
                            }else{
                                $("span[data-valmsg-for=MobileVCode]").html("短信验证码错误");
                                $('#MobileVCode').focus();
                                return false;
                            }
                        },"json");
                    }
                });
            }
        }
        else {
            $("span[data-valmsg-for=Phone]").html("请输入正确的手机号码");
            $('#Phone').focus();
            return false;
        }
    }else{
        $("span[data-valmsg-for=Phone]").html("该手机号码已经被注册");
        $('#Phone').focus();
        return false;
    }
}

function checkMobile(str) {
    var re = /^1\d{10}$/
    if (re.test(str)) {
        return true;
    } else {
        return false;
    }
}

function showTime() {
    if (s == 0||s<0) {
        clearInterval(IntVal);
        $("#Sms").removeClass("progress");
        $("#Sms").removeAttr("disabled");
        $("#Sms").html("验证码");
    }else{
        $("#Sms").html(s);
        $("#Sms").addClass("progress");
        $("#Sms").attr("disabled", "disabled");
    }
    s--;

}

function GetSMSFindpass() {
    var Phone = $('#Phone').val();
    var code = $('#VCode').val();
    if (checkMobile(Phone)) {
        if($.trim(code) == ''){
            alert('验证码错误');
        }else{
            $.post(_url+"check_code",{yzm:code},function(msg){
                if(msg == 0){
                    alert('请求错误');
                }else if(msg == -1){
                    alert('验证码错误');
                }else if(msg == 1){
                    $('#yzm').val(1);
                    $.post(_url+"user",{user:Phone},function(msg){
                        if(msg == -4){
                            alert('请求错误');
                        }else if(msg == 3){
                            alert('手机号码输入有误，请重新输入');
                        }else if(msg == -2){
                            alert('当前手机号码尚未注册，请注册');
                        }else if(msg == 2){
                            $.post(_module + "Sms"+URL_PATHINFO_DEPR+"sms",{Zeg:Phone},function(data){
                            if (data.status == 1) {
									$('#Sms').html('180');
									s = 180;
									IntVal = window.setInterval("showTime(s)",1000);
									$('#MobileVCodePost').val(data.code); 
								}else{
									$("span[data-valmsg-for=MobileVCode]").html("短信验证码错误");
									$('#MobileVCode').focus();
									return false;
								}
							},"json");
                        }
                    });
                }
            });


        }
    }
    else {
        alert('请输入正确的手机号码');
    }
}

function getPhotoSize(obj) {
    photoExt = obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();//获得文件后缀名
    if (photoExt != '.jpg' && photoExt != '.png') {
        alert("请上传后缀名为jpg或png的照片!");
        return false;
    }
    var fileSize = 0;
    var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
    if (isIE && !obj.files) {
        var filePath = obj.value;
        var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
        var file = fileSystem.GetFile(filePath);
        fileSize = file.Size;
    } else {
        fileSize = obj.files[0].size;
    }
    fileSize = Math.round(fileSize / 1024 * 100) / 100; //单位为KB
    if (fileSize >= 200) {
        alert("照片最大尺寸为200KB，请重新上传!");
        return false;
    }
    var lock = true;
    $('#form').submit(function () {
        if (lock) {
            var jqUpload = $(this);
            $('#form').ajaxSubmit({
                type: 'post',
                url: 'index.php/User/UploadMemberImg',
                dataType: 'text',
                beforeSend: function () {
                    $("#face").addClass("spinning");
                },
                complete: function () {
                    lock = false;
                    $("#face").removeClass("spinning");
                },
                success: function (json) {
                    $("#face").removeClass("spinning");
                    json = eval('(' + json + ')');
                    if (json.success) {
                        $("#face").attr("src", "http://file.taojingy.com//" + json.image + "?" + Math.random());
                        $("#Image").remove();
                        $(".cov").append("<input type=\"file\" id=\"Image\" name=\"Image\" title=\"请自行准备宽高1:1的图片,系统会自动切割图片.\"  onchange=\"getPhotoSize(this)\"/>");
                    }
                    else {
                        alert(json.message);
                    }
                },
                error: function (msg) {
                    $("#face").removeClass("spinning");
                    alert("文件上传失败");
                }
            });
        }
        return false;
    });
    $("#form").submit();
}

function ShowTable(elem) {
    if (elem == 't1') {
        $("#t1").removeAttr("style");
        $("#t2").attr("style", "display:none;");
    } else {
        GetProfitAndLoss();
        $("#t2").removeAttr("style");
        $("#t1").attr("style", "display:none;");
    }
}

function GetProfitAndLoss_____() {

    if ($("#td2").attr("hasdata") == "false") {
        $.ajax({
            type: "post",
            url: "/User/GetProfitAndLoss",
            datatype: "text",
            data: {},
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (result) {

                var json = eval('(' + result + ')');

                if (json.success) {

                    var items = json.data;
                    var html = "";
                    var sum = 0;
                    for (i = 0; i < items.length; i++) {
                        html = html + "<tr>";
                        html = html + "<td><a href=\"/Market/Detail/" + items[i]['Code'] + "\"><img src=http://file.taojingy.com//Content/images/" + items[i]['Code'] + ".png width=\"30\" /></a></td>";
                        html = html + "<td><a href=\"/Market/Detail/" + items[i]['Code'] + "\">" + items[i]["name"] + "</a></td>";
                        html = html + "<td>" + items[i]["b"].toFixed(4) + "</td>"; //总购买
                        html = html + "<td>" + items[i]["h"].toFixed(4) + "</td>"; //持有价值
                        html = html + "<td>" + items[i]["s"].toFixed(4) + "</td>"; //总出售
                        if (parseFloat(items[i]["Column1"]) > 0) {
                            html = html + "<td class=\"color-1\">" + items[i]["Column1"].toFixed(4) + "</td>";   //浮动盈亏
                        } else if (parseFloat(items[i]["Column1"]) < 0) {
                            html = html + "<td class=\"color-4\">" + items[i]["Column1"].toFixed(4) + "</td>";   //浮动盈亏
                        } else {
                            html = html + "<td>" + items[i]["Column1"].toFixed(4) + "</td>";   //浮动盈亏
                        }

                        html = html + "</tr>";
                        sum = sum + parseFloat(items[i]["Column1"]);
                    }

                    if (sum > 0) {
                        html = html + "<tr><td></td><td></td><td></td><td></td><td style=\"text-align:right;\">共计：</td><td class=\"color-1\"><b>" + sum.toFixed(4) + "</b></td></tr>";
                    }
                    else if (sum < 0) {
                        html = html + "<tr><td></td><td></td><td></td><td></td><td style=\"text-align:right;\">共计：</td><td class=\"color-4\"><b>" + sum.toFixed(4) + "</b></td></tr>";
                    }
                    else {
                        html = html + "<tr><td></td><td></td><td></td><td></td><td style=\"text-align:right;\">共计：</td><td><b>" + sum.toFixed(4) + "</b></td></tr>";
                    }

                    //
                    $("#td2").html(html);
                    $("#td2").attr("hasdata", "true");
                } else {
                    $("#td2").html("<td class=\"algin\" colspan=\"6\">查询数据失败</td>");
                }
            },
            error: function () {
                $("#td2").html("<td class=\"algin\" colspan=\"6\">查询数据失败</td>");
            }
        });
    }
}

$(function () {
    Hello();
    if ("" != "") {
        alert("");
    }
});

function Hello() {
    var now = new Date();
    var hr = now.getHours();
    if (hr == 0)
    { $("#Hello").html("Hello，晚安！明天早些起床哦！") }
    if (hr == 1)
    { $("#Hello").html("Hello，午夜1点多了，还在干什么啊？") }
    if (hr == 2)
    { $("#Hello").html("Hello，是不是该睡觉了？") }
    if (hr == 3)
    { $("#Hello").html("Hello，还不睡！小心变熊猫！") }
    if (hr == 4)
    { $("#Hello").html("Hello，您是一夜未睡还是刚起床呀？") }
    if (hr == 5)
    { $("#Hello").html("Hello，您这么早起床有事吗？") }
    if (hr == 6)
    { $("#Hello").html("Hello，您已经准备要刷牙洗脸了？") }
    if (hr == 7)
    { $("#Hello").html("Hello，您准备要去上班或上课了吗？") }
    if (hr == 8)
    { $("#Hello").html("Hello，早餐吃了沒呀?已经开始上班了吗？") }
    if (hr == 9)
    { $("#Hello").html("Hello，来杯咖啡，好好上班。") }
    if (hr == 10)
    { $("#Hello").html("Hello，现在是上午10点了！困吗？") }
    if (hr == 11)
    { $("#Hello").html("Hello，再忍耐一下，就快午休了！") }
    if (hr == 12)
    { $("#Hello").html("Hello，吃饭去吧！") }
    if (hr == 13)
    { $("#Hello").html("Hello，想不想要睡个午觉呀？") }
    if (hr == 14)
    { $("#Hello").html("Hello，不要边打瞌睡边流口水哦！") }
    if (hr == 15)
    { $("#Hello").html("Hello，来杯热茶，让精神抖擞起来？") }
    if (hr == 16)
    { $("#Hello").html("Hello，再忍一下...就快下班了！") }
    if (hr == 17)
    { $("#Hello").html("Hello，准备好下班了吗？") }
    if (hr == 18)
    { $("#Hello").html("Hello，终于下班了！") }
    if (hr == 19)
    { $("#Hello").html("Hello，吃饭了吗?不要虐待自己喔！") }
    if (hr == 20)
    { $("#Hello").html("Hello，晚安!看完新闻，看看书吧？") }
    if (hr == 21)
    { $("#Hello").html("Hello，来杯热茶，让精神抖擞起来！") }
    if (hr == 22)
    { $("#Hello").html("Hello，来杯热茶，让精神抖擞起来！") }
    if (hr == 23)
    { $("#Hello").html("Hello，哇!又是一天过去了，准备上床睡觉了！") }
}

function location_href(_url,_procode){
    location.href = _module + "/" + _url + URL_PATHINFO_DEPR + "procode" + URL_PATHINFO_DEPR + _procode;
}

function MovingAverage(data, i, n) {
    if (i >= n - 1) {
        var sum = 0;

        for (j = 0; j <= n - 1; j++) {
            //sum = sum + parseFloat(data[i - j].ClosingPrice);       //  此处应取 数据逇收盘价格
            sum = sum + parseFloat(data[i - j].min_money);
        }

        return sum / n;
    }
    else {
        return null;
    }
}

function ConfirmRevokeEntrust(entrust_id,entrust_time){
    if(confirm('是否撤销当前委托')){
        $.post(
            _url+"RevokeEntrust",
            {entrust_id : entrust_id,entrust_time : entrust_time},
            function(data){
                if(data==1){
                    $(".revoke_"+entrust_id).remove();
                    $(".states_"+entrust_id).html("用户撤销");
                }else if(data == -1){
                    //alert("");
                }else if(data == -2){
                    //alert("");
                }else {
                    //alert("");
                }
              }
        )
    }
}

function LinkTo() {
    if (banners.length > 0) {
        for (i = 0; i < banners.length; i++) {
            if ($(".backstretch img").attr("src") == banners[i]["img"] && banners[i]["url"] != "") {
                window.location.href = banners[i]["url"];
            }
        }
    }
}

/**
 *     支付 表单验证
 * @constructor
 */
function PaySubmit(){
	// $("input[type='submit']").attr('disabled','disabled');
	// alert(111);
	// return false;
    if( ! $("#ChannelId").val()){
        $(".field-validation-valid").html($("#ChannelId").attr("data-val-required"));
        return false;
    }

    $AmountNum = $("#amount").val();

    if( isNaN($AmountNum)){
        $(".field-validation-valid").html($("#amount").attr("data-val-number"));
		$("#amount").focus();
        return false;
    }else{
        if (  $AmountNum < $("#amount").attr("data-val-range-min") * 1 || $AmountNum > $("#amount").attr("data-val-range-max") * 1   ){
            $(".field-validation-valid").html($("#amount").attr("data-val-range"));
			$("#amount").focus();
            return false;
        }
        if($AmountNum % 10 == 0){
            $(".field-validation-valid").html($("#amount").attr("data-val-range"));
			$("#amount").focus();
            return false;
        }
    }

	$("input[type='submit']").attr('disabled','disabled');
	
}

function CashSubmit(){
	
	if(!confirm("请仔细核对您的银行卡信息与支付宝信息。\n玩家账户信息不正确导致不到账，由玩家自己负责，且手续费不会退还\n回收处理时间：工作日（9:00 - 12:00 , 14:00 - 18:00），节假日（18:00）。")){
		return false;
	}
	
}

function BankSubmit(){

    if( ! $("#BankNameText").html()){
        $(".field-validation-valid").html($("#BankName").attr("data-val-required"));
        return false;
    }
	
	if( ! $("#bank_name_branch").val()){
        $(".field-validation-valid").html($("#bank_name_branch").attr("data-val-required"));
        return false;
    }

    $AmountNum = $("#CardNo").val();

    if( isNaN($AmountNum)){
        $(".field-validation-valid").html($("#CardNo").attr("data-val-number"));
        return false;
    }else{
        if (  $AmountNum.length < $("#CardNo").attr("data-val-range-min") || $AmountNum.length > $("#CardNo").attr("data-val-range-max")  ){
            $(".field-validation-valid").html($("#CardNo").attr("data-val-required"));
            return false;
        }
    }

	$("input[type='submit']").attr('disabled','disabled');
	
}
//$(window).scroll(function () {
//    "use strict";
//    var scroll = $(window).scrollTop();
//    if (scroll > 60) {
//        $(".header").addClass("sticky");
//
//    } else {
//        $(".header").removeClass("sticky");
//    }
//});

function tutorial_show(_id){
									
	$('.tutorial').hide();
	
	$('#'+_id).show();
	
}




