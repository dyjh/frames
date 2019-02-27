<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('Recharge/index')}" method="post">
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
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$user_info eq ''">
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
                        <td><a href="{:U('Recharge/coin_edit',array('user'=>$vo['user']))}">金币添加</a></td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
		<span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>原有金币</th>
                <th>添加金币</th>
                <th>现有金币</th>
                <th>时间</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$res['0'] eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else/>
                <volist name="res" id="re">
                    <tr>
						<td>{$re.user}</td>
						<if condition="$re.coin_front eq ''"><td style="color:red;">历史太久远</td><else/><td>{$re.coin_front}</td></if>
						<td>{$re.coin}</td>
						<if condition="$re.coin_after eq ''"><td style="color:red;">历史太久远</td><else/><td>{$re.coin_after}</td></if>
						<td>{$re.time|date="Y-m-d H:i:s",###}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
    </div>
	<div style="clear: both;"></div>
</block>
