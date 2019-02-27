<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<h1>游戏果实统计</h1>	
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
                        <td>{$key}</td>
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
	
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<h1>昨日今日比例</h1>	
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
				<th style="text-align:center;">状态</th>
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
			<volist name="bilie" id="bi" >
					<tr style="color:#ff0000;">
					<td>{$bi.type}</td>
					<td>{$bi.tudou}</td>
					<td>{$bi.caomei}</td>
					<td>{$bi.yingtao}</td>
					<td>{$bi.daomi}</td>
					<td>{$bi.fanqie}</td>
					<td>{$bi.putao}</td>
					<td>{$bi.boluo}</td>
					<td>{$bi.zongshu}</td>
					<td>{$bi.zhongzi}</td>
					</tr>
			</volist>
            </tbody>
        </table>
        <div style="clear: both;"></div>

    </div>
    <div style="clear: both;"></div>
    
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<h1>股东果实统计</h1>	
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th style="text-align:center;">账号</th>
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
				<volist name="gudong" id="gd" >
                    <tr>
                        <td>{$gd.user}</td>
                        <td>{$gd.tudou}</td>
                        <td>{$gd.caomei}</td>
                        <td>{$gd.yingtao}</td>
                        <td>{$gd.daomi}</td>
                        <td>{$gd.fanqie}</td>
                        <td>{$gd.putao}</td>
                        <td>{$gd.boluo}</td>
						<td>{$gd.zongshu}</td>
						<td>{$gd.zhongzi}</td>
                    </tr>
				</volist>
					<tr style="color:#ff0000;">
					<td>总计</td>
					<td>{$arrt.tudou}</td>
					<td>{$arrt.caomei}</td>
					<td>{$arrt.yingtao}</td>
					<td>{$arrt.daomi}</td>
					<td>{$arrt.fanqie}</td>
					<td>{$arrt.putao}</td>
					<td>{$arrt.boluo}</td>
					<td>{$arrt.zongshu}</td>
					<td>{$arrt.zhongzi}</td>
					</tr>
            </tbody>
        </table>
        <div style="clear: both;"></div>

    </div>
	<div style="clear: both;"></div>
    
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<h1>扣除股东果实剩余数据</h1>	
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
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
					<tr style="color:#ff0000;">
					<td>{$sy.tudou}</td>
					<td>{$sy.caomei}</td>
					<td>{$sy.yingtao}</td>
					<td>{$sy.daomi}</td>
					<td>{$sy.fanqie}</td>
					<td>{$sy.putao}</td>
					<td>{$sy.boluo}</td>
					<td>{$sy.zongshu}</td>
					<td>{$sy.zhongzi}</td>
					</tr>
            </tbody>
        </table>
        <div style="clear: both;"></div>

    </div>
	<div style="clear: both;"></div>
</block>
