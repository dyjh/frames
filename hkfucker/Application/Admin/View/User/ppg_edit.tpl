<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>账户冻结果实返还</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/ppg_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data.user}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户姓名</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data.name}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户昵称</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data.nickname}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户等级</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data.level}</span>
            </div>
			
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">冻结果实</span>
                <span type="text" name="num" id="num" class="form-control" aria-describedby="basic-addon1" style="width: 15%;"/>{$list.seed}
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">时&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp间</span>
                <span type="text" name="num" id="num" class="form-control" aria-describedby="basic-addon1" style="width: 15%;"/>{$list.time|date="Y-m-d H:i:s",###}
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">价&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp位</span>
                <span type="text" name="num" id="num" class="form-control" aria-describedby="basic-addon1" style="width: 15%;"/>￥{$list.money}
            </div>
			
            <div class="input-group" id="div_num" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">果实数量</span>
                <input type="text" name="num" id="num" class="form-control" aria-describedby="basic-addon1" style="width: 172px;" value="{$list.num}"/>
            </div>
            <input type="hidden" name="user" id="user" value="{$data.user}"/>
			<input type="hidden" name="id"  value="{$list.id}"/>
			<input type="hidden" name="seeds" value="{$list.seed}"/>
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
            $.post("{:U('User/ppgNum')}",{user:user,guoshi:guoshi,},function(msg){
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