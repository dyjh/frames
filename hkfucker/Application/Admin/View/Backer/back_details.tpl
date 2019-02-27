<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户{$u}种子回购详情</h1>
    </div>
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span style="">该用户种子回购<b style="color:#ff0000;">{$c}</b>次，种子回购总量<b style="color:#ff0000;">{$d}</b>颗</span>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
		
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
               <th style="text-align:center;">用户</th>
               <th style="text-align:center;">种子回购数量</th>
               <th style="text-align:center;">种子回购价格</th>
               <th style="text-align:center;">所得金币</th>
               <th style="text-align:center;">时间</th>
            </tr>
            </thead>
            <tbody style="text-align:center;">
            <if condition="$r eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
			<volist name="r" id="r">
				<tr>
				<td>{$r.user}</td>
				<td>{$r.num}</td>
				<td>{$r.money}</td>
				<td><b style="color:#ff0000;">{$r.total}</b></td>
				<td>{$r.time|date="Y-m-d H:i:s",###}</td>
				</tr>
			</volist>
            </if>
            </tbody>
        </table>
        <div style="clear: both;"></div>

    </div>
</block>
