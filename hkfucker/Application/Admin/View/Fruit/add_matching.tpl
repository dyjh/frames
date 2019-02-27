<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实收购</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>果实名称</th>
                <th>跌停价格</th>
				<th>涨停价格</th>
            </tr>
            </thead>
            <tbody>
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.id}</td>
                        <td>{$val.seed}</td>
                        <td>{$val.min}</td>
						<td>{$val.max}</td>
                    </tr>
                </volist>
            </tbody>
        </table>
		<form action=" " method="post"  enctype="multipart/form-data" onsubmit="">
			<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">果实</span>
                <select name="seed" class="btn btn-default btn-sm dropdown-toggle" id="option">
					<volist name="seed" id="val">
						<option value="{$val.varieties}">{$val.varieties}</option>
					</volist>
                </select>
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">排单类型</span>
                <select name="type" class="btn btn-default btn-sm dropdown-toggle" id="option">
					<option value="1">买入</option>
					<option value="0">卖出</option>
                </select>
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">排单号码</span>
                <input type="text" name="user" class="form-control" placeholder="排单手机号" aria-describedby="basic-addon1" value="18382077208">
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">价格间隔</span>
                <input type="text" name="money" class="form-control" placeholder="一次排单价位" aria-describedby="basic-addon1" value="0.00001">
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">排单数量</span>
                <input type="text" name="num" class="form-control" placeholder="排单数量" aria-describedby="basic-addon1" value="5000">
            </div>
			<div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
		</form>
    </div>
    <div class="clear"></div>
    
</block>