<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="page-header">
        <h1>用户统计</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th style="text-align:center;">状态</th>
                <th style="text-align:center;">1级</th>
                <th style="text-align:center;">2级</th>
                <th style="text-align:center;">3级</th>
                <th style="text-align:center;">4级</th>
                <th style="text-align:center;">5级</th>
                <th style="text-align:center;">6级</th>
				<th style="text-align:center;">7级</th>
				<th style="text-align:center;">总量</th>
            </tr>
            </thead>
            <tbody style="text-align:center;">
			<volist name="jr" id="j">
					<tr>
                        <td style="color:#ff0000;">{$j.type}</td>
                        <td>{$j.yi}</td>
                        <td>{$j.er}</td>
                        <td>{$j.san}</td>
                        <td>{$j.si}</td>
                        <td>{$j.wu}</td>
                        <td>{$j.liu}</td>
						<td>{$j.qi}</td>
						<td>{$j.zongshu}</td>
                    </tr>
			</volist>
            </tbody>
        </form>
        </table>
	</div>	
        <div style="clear: both;"></div>
		
	<div class="page-header">
        <h1>不活跃用户统计</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th style="text-align:center;">状态</th>
                <th style="text-align:center;">1级</th>
                <th style="text-align:center;">2级</th>
                <th style="text-align:center;">3级</th>
                <th style="text-align:center;">4级</th>
                <th style="text-align:center;">5级</th>
                <th style="text-align:center;">6级</th>
				<th style="text-align:center;">7级</th>
				<th style="text-align:center;">总量</th>
            </tr>
            </thead>
            <tbody style="text-align:center;">
			<volist name="bhy_jr" id="b">
					<tr>
                        <td style="color:#ff0000;">{$b.type}</td>
                        <td>{$b.yi}</td>
                        <td>{$b.er}</td>
                        <td>{$b.san}</td>
                        <td>{$b.si}</td>
                        <td>{$b.wu}</td>
                        <td>{$b.liu}</td>
						<td>{$b.qi}</td>
						<td>{$b.zongshu}</td>
                    </tr>
			</volist>
            </tbody>
        </form>
        </table>
	</div>	
	
</block>
