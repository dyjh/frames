<extend name="Public:base" />
<block name="content">
    <!--<link rel="stylesheet" type="text/css" href="__CSS__/normalize.css" />-->
    <!--<link type="text/css" rel="stylesheet" href="__CSS__/style.css" />-->
	<style>
		.detail_table{display:none;}
	</style>
	
	<script src="__PUBLICHOME__/laydate/laydate.js" ></script>
   
	<div style="width:90%; margin:0 auto">
	
		<form action="{:U('Pay/pay_all')}" method="get">
			<div class="input-group" style="float: left; width: 23%;">
				<input value="{$start_time|date='Y-m-d',###}" type="text" id="start" name="start_time" class="form-control" placeholder="请输入你要查询的开始时间">
		    </div>
			<div class="input-group" style="float: left; width: 23%;">
				<input value="{$end_time|date='Y-m-d',###}"   type="text" id="end"   name="end_time"   class="form-control" placeholder="请输入你要查询的结束时间">
		  	</div>		

			<div style="clear: both; height:10px;"></div>
			
			<div class="input-group" style="float: left; width: 40%;">
				<input value="{$start_user}" type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
			</div>	
			
			<div>
				<div style="float: left;"> <button type="submit" class="btn btn-default">查询</button></div>
			</div>
			  
			<div style="clear: both;"></div>
			
			
			
			<span class="field-validation-valid" data-valmsg-for="sel"></span>
		
		</form> 
				
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>充值总金额</th>
                <th>充值总单数</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$all_reult.all_money}</td>
                <td>{$all_reult.all_number}</td>
            </tr>
            </tbody>
        </table>
				
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>微信扫码支付总金额</th>
                <th>微信扫码支付总单数</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$all_reult.wechat_money}</td>
                <td>{$all_reult.wechat_number}</td>
            </tr>
            </tbody>
        </table>
				
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>支付宝支付总金额</th>
                <th>支付宝支付总单数</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$all_reult.ali_money}</td>
                <td>{$all_reult.ali_number}</td>
            </tr>
            </tbody>
        </table>
				
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>提现总金额</th>
                <th>提现总单数</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$all_reult.all_cash_money}</td>
                <td>{$all_reult.all_cash_number}</td>
            </tr>
            </tbody>
        </table>
		
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th> 800 礼包完成人数<a onclick="show_table(800)">查看详细</a></th>
                <th>1500 礼包完成人数<a onclick="show_table(1500)">查看详细</a></th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$all_reult.800_number} * 800 =  <b> {$all_reult.800_money} </b></td>
                <td>{$all_reult.1500_number} * 1500 = <b> {$all_reult.1500_money} </b></td>
            </tr>
            </tbody>
        </table>
				
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="detail_table table" id="800">
            <thead>
            <tr>
				<!-- <th>全选</th> -->
                <th>用户ID</th>
                <th>用户电话</th>
                <th>充值姓名</th>
                <th>订单编号</th>
                <th>充值时间</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$all_reult['800']  eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="all_reult.800" id="vo">
                    <tr>
                        <td>{$vo.id}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.order_num}</td>
                        <td>{$vo.pay_time|date='Y-m-d H:i:s',###}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
				
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="detail_table table" id="1500">
            <thead>
            <tr>
				<!-- <th>全选</th> -->
                <th>订单ID</th>
                <th>用户电话</th>
                <th>充值姓名</th>
                <th>订单编号</th>
                <th>充值时间</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$all_reult['1500']  eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="all_reult.1500" id="vo">
                    <tr>
                        <td>{$vo.id}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.order_num}</td>
                        <td>{$vo.pay_time|date='Y-m-d H:i:s',###}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>	

		<div style="float: left;"> <button type="submit" onclick="$('.detail_table').hide();" class="btn btn-default">关闭</button></div>
		
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
	function show_table(_id){
		$('.detail_table').hide();
		$("#"+_id).toggle();
	}
</script>

</block>
