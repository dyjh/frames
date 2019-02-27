<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
<style>
.input-group select{width:90%;height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;}
.field-validation-valid{display:inline-block;width: 200px;height: auto; margin-top: 1%;color:#ff0000;}
</style>
    <script>
        function Psubmit() {
            var sel = $('#select_k1').val();
            if(sel == '选择前三位标识'){
                $("span[data-valmsg-for=sel]").html("*请选择用户前三位标识数字");
                return false;
            }else {
                //$("#sel").attr('selected','f');
                //$("#usel").attr('selected',true);
                $('form').submit();

            }
        }
    </script>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('User/level')}" method="post">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>
            <div style="float: left;">
                <a href="{:U('User/level_add')}" class="btn btn-default" style="color: #3c8dbc;margin-right: 20px;background: #ffffff;">
                   添加果实</a></div>
            <div class="input-group" style="float: left; width: 23%;">
                <input type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">

            </div>
            <div style="float: left;">
                <button type="button" class="btn btn-default" onclick="return Psubmit()">查询</button></div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
            </form>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
			<th>用户ID</th>
                <th>用户</th>
                <th>昵称</th>
                <th>总果实数量</th>
                <th>分红宝数量</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="level_list" id="vo">
                    <tr>
					<td>{$vo.num_id}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.nickname}</td>
                        <td id="shu_{$vo.user}">
                            <a href="javascript:sett('{$vo.user}');">点击查看</a>
                            <span></span>
                        </td>
                        <td id="fhb_{$vo.user}">
                            <a href="javascript:fhb('{$vo.user}');">点击查看</a>
                            <span></span>
                        </td>
                        <td><a href="javascript:edit({$vo.user});">修改</a> <a href="javascript:deis({$vo.user});">删除</a></td>
                    </tr>
                </volist>
            </if>

            </tbody>
        </table>
        <if condition="$state eq 0">
            <else />
        <nav aria-label="Page navigation">
            <ul class="pagination" ">
            <li>
                <a href="{:U('User/level',array('p'=>$p-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
            </li>
            <for start="$start" end="$end">
                <if condition="$p eq $i">
                    <li class="active">
                        <a href="{:U('User/level',array('p'=>$p-1))}">{$i}</a>
                    </li>
                    <else/>
                    <li>
                        <a href="{:U('User/level',array('p'=>$i))}">{$i}</a>
                    </li>
                </if>
            </for>
            <li>
                <a href="{:U('User/level',array('p'=>$p+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
            </li>
            </ul>
        </nav>
            </if>
    </div>
<script type="text/javascript">
    function sett(user){
        $.post("{:U('User/level_Ajax')}",{id:user},function(msg){
            if(msg !==false){
                $("#shu_"+user).children("a").hide();
                $("#shu_"+user).children("span").html(msg);
            }else {
                $("#shu_"+user).children("a").hide();
                $("#shu_"+user).children("span").html(msg);
            }
        });
    }

    function fhb(user){
        $.post("{:U('User/level_Ajax_fhb')}",{id:user},function(msg){
            if(msg !==false){
                $("#fhb_"+user).children("a").hide();
                $("#fhb_"+user).children("span").html(msg);
            }else {
                $("#fhb_"+user).children("a").hide();
                $("#fhb_"+user).children("span").html(msg);
            }
        });
    }

    function edit(user){
            location.href="{:U('User/level_edit')}?user="+user;
    }

    function deis(user){
        if(confirm('确定删除吗？注意当前操作会把该用户所有果实及分红宝清空，请谨慎操作！')){
            location.href="__URL__/level_del/user/"+user
        }
    }
</script>
</block>