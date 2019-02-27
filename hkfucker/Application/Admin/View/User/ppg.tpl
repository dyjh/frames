<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('User/ppg')}" method="post">
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
                <th>id</th>
                <th>用户</th>
				
				<!--<volist name="AllSeeds" id="vo"><th>{$vo.varieties}</th></volist>-->
				<th>果实</th>
				<th>数量</th>
				<th>时间</th>
				<th>价位</th>
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
						<td>{$vo.id}</td>
						<td>{$vo.user}</td>
						<td>{$vo.seed}</td>
						<td>{$vo.num}</td>
						<td>{$vo.time|date="Y-m-d H:i:s",###}</td>
						<td>{$vo.money}</td>
						<!--<volist name="vo" id="val">-->
							<!--<td>{$val.num}</td>-->
						<!--</volist>-->
                        <td><a href="{:U('User/ppg_edit',array('id'=>$vo['id'],'user'=>$vo['user']))}">返还果实</a></td>
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
                    <a href="{:U('User/ppg',array('p'=>$p-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('User/ppg',array('p'=>$p-1))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/ppg',array('p'=>$i))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/ppg',array('p'=>$p+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
</block>
