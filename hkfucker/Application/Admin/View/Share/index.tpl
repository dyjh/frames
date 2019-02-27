<extend name="Public:base" />
<block name="content">
    <div class="t_content">
        <div class="tcontent ">
            <span id="span">×</span>
            <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" style="margin-top: 10px; margin-bottom: 10px;">
                <form method="post" enctype="multipart/form-data" action="" onsubmit="return cheack_form()">
                    <div class="input-group">
                        <label class="input-group-addon" id="basic-addon1">系统密码</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="password" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group" style="margin-top: 10px;">
                        <label class="input-group-addon"  id="basic-addon1">价格</label>
                        <input type="text" id="price" name="price" class="form-control" placeholder="price" autocomplete="off" aria-describedby="basic-addon1">
                    </div>
                    <input type="hidden" name="check" value="{$num}"/>
                    <div class="btn-group" role="group" aria-label="" style="margin-left: 35%; margin-top: 20px;;">
                        <button type="submit" class="btn btn-primary">分红</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="page-header">
        <h1>分红宝</h1>
    </div>
    <div id="total_td" style="margin-left: 25%; margin-top: 30px;width: 40%;">当前存在分红宝<span class="red">{$num}</span>个</div>
    <div class="btn-group" role="group" aria-label="" style="margin-left: 45%; margin-top: 20px;;">
        <button type="button" class="btn btn-default" onclick="lookContent()">分红</button>
    </div>
    <script>
        $('.t_content').hide();
        function lookContent(content){
            //alert('1');
            $('.t_content').show();
            $('.tcontent p').text(content);
        }
        $('.tcontent span').click(function(){
            $('.t_content').hide();
        });
        function cheack_form(){
            if($('input[name="check"]').val() == '0'){
                alert('当前无分红宝');
                return false;
            }
            if($('#password').val() == ''){
                alert('密码不能为空');
                return false;
            }
            if($('#price').val() == ''){
                alert('价格不能为空');
                return false;
            }
        }
    </script>
</block>