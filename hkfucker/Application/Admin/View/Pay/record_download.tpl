<extend name="Public:base" />
<block name="content">

	<script src="__PUBLICHOME__/laydate/laydate.js" ></script>

    <body style="height: 100%; margin: 0">
    
	
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
		<form action="" method="get">
		
			<div class="input-group" style="float: left; width: 40%;">
				<input value="{$start_time|date='Y-m-d',###}" type="text" id="start" name="start_time" class="form-control" placeholder="请输入你要查询的开始时间">
		    </div>
			
			<div class="input-group" style="float: left; width: 40%;">
				<input value="{$end_time|date='Y-m-d',###}"   type="text" id="end"   name="end_time"   class="form-control" placeholder="请输入你要查询的结束时间">
		  	</div>	
			
			<div style="clear: both; height:20px;"></div>

			<div class="input-group" style="float: left; width: 40%;">
                <select name="pay_cash" id="pay_cash" class="form-control">
					<option value="1" selected="selected">充值</option>
                    <option value="2" <if condition="$state eq '2' "> selected="selected" </if> >提现</option>
                </select>
            </div>
			
			<div class="input-group" style="float: left; width: 40%;">
                <select name="pay_state" id="pay_state" class="form-control">
					<option value="1" selected="selected">已完成</option>
                    <option value="0" <if condition="$pay_state eq '0' "> selected="selected" </if> >未完成</option>
                </select>
            </div>
			
			<div style="clear: both; height:20px;"></div>
			
			<div class="input-group" style="float: left; width: 40%;">
				<button type="button" onclick="get_order_num()" class="btn btn-default">查询</button>
			</div>
			  
			<div style="clear: both; height:20px;"></div>
									
			<span class="field-validation-valid" data-valmsg-for="sel">订单导出只能导出已完成订单</span>
		
		
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
				<tr>
					<th>当前条件可导出 总订单数</th>
				</tr>
				</thead>	

				<tbody>
				<tr>
					<td id="all_reult">{$all_reult.all_number}</td>
				</tr>
				</tbody>
			</table>
			
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
				<tr>
					<th>当前条件可导出 支付宝订单数 <br/>（提现或充值）</th>
				</tr>
				</thead>	

				<tbody>
				<tr>
					<td id="ali_reult">{$all_reult.ali_number}</td>
				</tr>
				</tbody>
			</table>
			
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
				<tr>
					<th>当前条件可导出 其他渠道订单数 <br/>（支付为：微信或扫码；提现为：银行提现）</th>
				</tr>
				</thead>	

				<tbody>
				<tr>
					<td id="wechat_reult">{$all_reult.wechat_number}</td>
				</tr>
				</tbody>
			</table>
		
			<div class="input-group" style="float: left;">
				<button type="button" class="btn btn-default">导出类型</button>
			</div>
		
			<div class="input-group" style="float: left; width: 40%;">
				
                <select name="export_type" id="export_type" class="form-control">
					<option value="1" selected="selected">全部</option>
                    <option value="2" >仅支付宝</option>
                    <option value="3" >仅银行（微信）</option>
                </select>
            </div>
		
			<button type="submit" name="record_export" value="record_export" class="btn btn-default">导出</button></div>
		</form> 
    </div>
	
	<script>
		var start = {
		  elem: '#start',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59', //设定最小日期为当前日期
		  max: '2099-06-16 23:59:59', //最大日期
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			 end.min = datas; //开始日选好后，重置结束日的最小日期
			 end.start = datas //将结束日的初始值设定为开始日
		  }
		};
		var end = {
		  elem: '#end',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59',
		  max: '2099-06-16 23:59:59',
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			start.max = datas; //结束日选好后，重置开始日的最大日期
		  }
		};
		laydate(start);
		laydate(end);
		
		
		//  异步获取查询时间内 可以导出的订单数目
		function get_order_num(){
		
			$start 		 = $("#start").val();
			$end   		 = $("#end").val();
			$pay_cash    = $("#pay_cash").val();
			$pay_state   = $("#pay_state").val();
			$.get(
				'{:U("Pay/record_download")}',
				{start_time:$start,end_time:$end,pay_cash:$pay_cash,is_ajax:1,pay_state:$pay_state},
				function(res){
					$("#all_reult").html(res.all_number);
					$("#ali_reult").html(res.ali_number);
					$("#wechat_reult").html(res.wechat_number);
				
					//alert(111);
				},"json"				
			)
		
		}
		
	</script>

</block>
