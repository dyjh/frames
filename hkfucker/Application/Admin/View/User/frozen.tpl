<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('User/frozen')}" method="post">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>
            <div class="input-group" style="float: left; width: 23%;">
                <input type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>
            <div style="float: left;">
                <button type="submit" class="btn btn-default">查询</button></div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
				<th>用户ID</th>
                <th>用户</th>
                <th>昵称</th>
                <th>姓名</th>
                <th>等级</th>
				<th>金币</th>
                <th>冻结金币</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="user_info" id="vo">
                    <tr id='tr{$vo.id}'>
						<td>{$vo.num_id}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.nickname}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.level}</td>
						<td>{$vo.coin}</td>
                        <td>{$vo.coin_freeze}</td>
                        <td><a href="{:U('User/frozen_edit',array('id'=>$vo['user']))}">返还金币</a></td>
                        <!--<td><a href="javascript:Return(id={$vo.user});">返还金币</a></td>-->
                    </tr>
                </volist>
            </if>



            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state_p eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" ">
                <li>
                    <a href="{:U('User/frozen',array('p'=>$p-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('User/frozen',array('p'=>$p-1))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/frozen',array('p'=>$i))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/frozen',array('p'=>$p+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>

    <script>
        function Return(id){
            if(confirm('确定返还金币吗？')){
                location.href="__URL__/frozen_return/user/"+id;
            }
        }
		
		function add(user){
            if(confirm('确定要添加吗？')){
                $.post("{:U('User/gg_add')}",{user:user},function(msg){
                    if(msg == 0){
                        alert('添加失败');
                    }else if(msg == -2){
                        alert('添加错误');
                    }else if(msg == 1){
                        alert('添加成功');
                    }else if(msg == -1){
                        alert('该用户已添加，请勿重复');
                    }
                })
            }
        }
    </script>
</block>
