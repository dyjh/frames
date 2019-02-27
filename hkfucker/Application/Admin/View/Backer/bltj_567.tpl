<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<h1>5，6，7级游戏果实统计</h1>	
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th style="text-align:center;">等级</th>
                <th style="text-align:center;">土豆</th>
                <th style="text-align:center;">草莓</th>
                <th style="text-align:center;">樱桃</th>
                <th style="text-align:center;">稻米</th>
                <th style="text-align:center;">番茄</th>
                <th style="text-align:center;">葡萄</th>
                <th style="text-align:center;">菠萝</th>
				<th style="text-align:center;">总量</th>
				<th style="text-align:center;">种子</th>
            </tr>
            </thead>
            <tbody style="text-align:center;">
				<volist name="seeds" id="se" >
                    <tr>
                        <td>{$se.level}</td>
                        <td>{$se.tudou}</td>
                        <td>{$se.caomei}</td>
                        <td>{$se.yingtao}</td>
                        <td>{$se.daomi}</td>
                        <td>{$se.fanqie}</td>
                        <td>{$se.putao}</td>
                        <td>{$se.boluo}</td>
						<td>{$se.zongshu}</td>
						<td>{$se.zhongzi}</td>
                    </tr>
				</volist>
					<tr style="color:#ff0000;">
					<td>总计</td>
					<td>{$arr.tudou}</td>
					<td>{$arr.caomei}</td>
					<td>{$arr.yingtao}</td>
					<td>{$arr.daomi}</td>
					<td>{$arr.fanqie}</td>
					<td>{$arr.putao}</td>
					<td>{$arr.boluo}</td>
					<td>{$arr.zongshu}</td>
					<td>{$arr.zhongzi}</td>
					</tr>
            </tbody>
        </table>
        <div style="clear: both;"></div>

    </div>
	
	
	<div style="clear: both;"></div>
</block>
