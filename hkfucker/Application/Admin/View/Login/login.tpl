<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>登陆</title>
    <link rel="stylesheet" href="__CSS__/bootstrap.css" />
    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
    <js file="__JS__/jquery-1.11.2.min.js" />
    <js file="__JS__/md5.min.js" />
    <!-- BOOTSTRAP STYLES-->
    <link rel="stylesheet" href="__CSS__/font-awesome.css" />
    <!-- FONTAWESOME STYLES-->



</head>
<body style="background-color: #E2E2E2;">
    <div class="container">
        <div class="row text-center " style="padding-top:100px;">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <img src="__IMG__/LOGO.png" class=".img-responsive " alt="Responsive image" style="margin-left: 5%; width: 90%; float: left"/>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1"    >
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data" action=""  onsubmit="return cheack_form()">
                        <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                        <hr />
                        <h5>欢迎登陆后台管理系统</h5>
                        <br />
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"  ></i></span>
                            <input type="text" class="form-control" name="username" value="" placeholder="用户名 " />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"  ></i></span>
                            <input type="password" class="form-control" name="password" autocomplete="off" placeholder="密码" />
                        </div>
                        <div class="form-group">

                            <!--<input type="text" class="form-control"  name="verify" style="float: left; width: 50%;" placeholder="验证码">

                            <img src="{:U('Login/Verify')}" id="verify" style="float: right; height: 33.99px;" border="0" />
							<div style="height: 34px; background-color: #00a7d0; cursor: pointer; width: 20%; margin-left: 55%; border-radius: 5px;">
                                <a href="javascript:check()" id="Sms" style="line-height:34px; margin-left: 1%; color: white; padding: 12px 9px; text-decoration: none;">验证码</a>
                            </div>-->
                            <div style="clear: both;"></div>
                        </div>
                        <!--<div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="remember" value="1" checked /> 记住密码
                            </label>
                        </div>-->
                        <button type="submit" class="btn btn-primary" id="btn" >登陆</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<script>
	function showTime() {
        if (s == 0||s<0) {
            clearInterval(IntVal);

            $("#Sms").attr("href","javascript:check()");
            $("#Sms").text("验证码");
        }else{
            $("#Sms").html(s);

            $("#Sms").attr("href", "javascript:void(0)");
        }
        s--;

    }
    function check() {
        var user=$('input[name="username"]').val();
        $.post("{:U('Login/check')}",{user:user},function(msg){
           if(msg==1){
                alert('验证码已发送');
                $('#Sms').text('180');
                s = 180;
                IntVal = window.setInterval("showTime(s)",1000);
            }else if(msg==0){
                alert('验证码发送错误');
            }else if(msg==-1){
                alert(-1);
            }
        });
    }
    function cheack_form(){
        if($('input[name="name"]').val() == ''){
            alert('账号不能为空');
            return false;
        }
        if($('input[name="password"]').val() == ''){
            alert('密码不能为空');
            return false;
        }else{
            var pwd=$('input[name="password"]').val();
            pwd=md5(pwd);
            $('input[name="password"]').val(pwd);
            return true;
        }
        if($('input[name="verify_code"]').val() == ''){
            alert('验证码不能为空');
            return false;
        }
    }

    /*$('#verify').click(function(){
        $('#verify').attr('src',"{:U('Login/Verify')}?math="+Math.random());
    });*/
    $('input[name="verify"]').blur(function(){
        var verify = $('input[name="verify"]').val();
        if($.trim(verify) !=''){
            $.post("{:U('Login/check_Verifys')}",{verify_code:verify},function(msg){
                if(! msg){
                    alert('验证码错误');
                }
            });
        }else{
            alert('请输入验证码');
        }

    });


</script>