<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('Zrsz/index')}" method="post">
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
                <th>用户</th>
                <th>果实</th>
                <th>种植时间</th>
                <th>收获时间</th>
                <th>种植状态</th>
                <th>是否收获</th>
                <th>土地位置</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$user_info eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">请在左上方输入用户信息，进行查询</p>
                </td>
                <else />
                <volist name="user_info" id="vo">
                    <!--<tr id='tr{$vo.id}'>-->
                    <tr>
						<td>{$vo.user}</td>
                        <td>{$vo.seed_type}</td>
                        <td>{$vo.time|date="Y-m-d H:i:s",###}</td>
                        <td>{$vo.harvest_time|date="Y-m-d H:i:s",###}</td>
                        <td>{$vo.seed_state}</td>
						<td><if condition="$vo.harvest_state == 1">已收获<else /> 未收</if></td>
                        <td>{$vo.number}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
    </div>
</block>
