<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户果实修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/level_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$ser.user}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户姓名</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$ser.name}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户昵称</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$ser.nickname}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户等级</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$ser.level}</span>
            </div>
			
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">其他果实</span>
                <select name="seeds" id="seeds" class="btn btn-default btn-sm dropdown-toggle" style="background: #ffffff;color:#3c8dbc;"><option><if condition="$list eq null ">该用户仓库没有果实<else />《—请选择果实种类—》</if></option>
                        <volist name="list" id="li">
                            <option value="{$li.seeds}">{$li.seeds}</option>
                        </volist>
                </select>
            </div>
            <div class="input-group" id="div_num" style="margin-bottom: 20px;display: none;">
                <span class="input-group-addon" id="basic-addon1">果实数量</span>
                <input type="text" name="num" id="num" class="form-control" aria-describedby="basic-addon1" style="width: 172px;" value=""/>
            </div>
            <input type="hidden" name="user" id="user" value="{$list[0]['user']}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $('select').bind('change',function(){
            //var reg = /[0-9]{1,11}/;
            //var user = reg.exec($(this).attr('name'));
            var user = $('#user').val();
            var guoshi = $('#seeds').val();
            $.post("{:U('User/getNum')}",{user:user,guoshi:guoshi,},function(msg){
                if(msg == -1){
                    alert('请求错误');
                }else{
                    $("#div_num").show();
                    $('#num').val(msg);
                }
            })
        })
    </script>
</block>