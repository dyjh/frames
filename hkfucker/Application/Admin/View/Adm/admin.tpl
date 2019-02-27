<extend name="Public:base" />
<block name="content">
    <js file="__JS__/md5.min.js" />
    <div class="page-header">
        <h1>密码修改</h1>
    </div>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
        <div style=" margin: 0 auto;">
            <form method="post" enctype="multipart/form-data" action="" onsubmit="return cheack_form()">
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <div class="input-group" style="margin-top: 20px;">
                    <span class="input-group-addon" id="basic-addon1">旧密码</span>
                    <input type="password" name="pwd_old" class="form-control" placeholder="OldPassword" autocomplete="off" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <span class="input-group-addon" id="basic-addon1">新密码</span>
                    <input type="password" name="c_pwd" id="pwd" class="form-control" autocomplete="off" placeholder="Password" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <span class="input-group-addon" id="basic-addon1">确认密码</span>
                    <input type="password" name="pwd" id="check" class="form-control" autocomplete="off" placeholder="CheckPassword" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary" id="btn" style="margin-left: 40%;;" >提交</button>
            </form>
        </div>
    </div>
    <script>
        function cheack_form(){
            if($('input[name="pwd_old"]').val() == ''){
                alert('新密码不能为空');
                return false;
            }else{
                var pwd=$('input[name="pwd_old"]').val();
                pwd=md5(pwd);
                $('input[name="pwd_old"]').val(pwd);
                //return true;
            }
            if($('input[name="c_pwd"]').val() == ''){
                alert('新密码不能为空');
                return false;
            }else{
                var pwd=$('input[name="c_pwd"]').val();
                pwd=md5(pwd);
                $('input[name="c_pwd"]').val(pwd);
                //return true;
            }
            if($('input[name="pwd"]').val() == ''){
                alert('新密码不能为空');
                return false;
            }else{
                var pwd=$('input[name="pwd"]').val();
                pwd=md5(pwd);
                $('input[name="pwd"]').val(pwd);
                //return true;
            }
        }
        $('#check').blur(function(){
            var pwd = $('#pwd').val();
            var pwd_c = $('#check').val();
            if(pwd == pwd_c){

            }else{
                alert('第二次密码输入错误！请重新输入');
                $('#pwd').val('');
                $('#check').val('');
            }
        })

    </script>
</block>