<extend name="Public:base" />
<block name="content">

	<script src="__PUBLICHOME__/laydate/laydate.js" ></script>
	<style>
		.card{padding:10px 15px; border:1px solid gray; cursor:pointer}
		.select{color: #FF0000;background-color:#ffffff}
		.detial{display:none;}
	</style>
	
	 <div class="" style="width:90%; margin:0 auto"  >
        <form action="" method="get">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
			
			<div class="input-group" style="float: left; width: 40%;">
				<input value="{$start_time|date='Y-m-d',###}" type="text" id="start" name="start_time" class="form-control" placeholder="请输入你要查询的开始时间">
			</div>
			
			<div class="input-group" style="float: left; width: 40%;">
				<input value="{$end_time|date='Y-m-d',###}"   type="text" id="end"   name="end_time"   class="form-control" placeholder="请输入你要查询的结束时间">
			</div>	
				
			<div style="clear: both; height:10px;"></div>
			
			<div class="input-group" style="float: left; width: 40%;">
				<select name="seeds_cate" class="form-control">
					<option value="0" selected="selected">全部</option>
					 <volist name="SeedsList" id="vo">
						<option value="{$vo.varieties}" <if condition="$get_data['seeds_cate'] eq $vo['varieties']"> selected="selected" </if> >{$vo.varieties}</option>
					</volist>
				</select>
			</div>
	
			<div>
				<div class="input-group" style="float: left; width: 40%;">
					<input type="text" name="start_user" value="{$get_data.start_user}" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
				</div>
				<div style="float: left;">
					<button type="submit" class="btn btn-default">查询</button></div>
			</div>
			<div style="clear: both; height:10px;"></div>
		</form>
		
        <div style="clear: both; height:10px;"></div>
		
		<span class="field-validation-valid card select" onclick="show_div(this,'total')">总计</span>
		 
		<span class="field-validation-valid card " onclick="show_div(this,'detial')">详情</span>

		<div style="clear: both; height:10px;"></div>
		
		<div class="detial none">
			<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
				<thead>
				<tr>
					<th>用户</th>
					<th>宝箱名称</th>
					<th>中奖果实</th>
					<th>中奖数量</th>
					<th>中奖时间</th>
				</tr>
				</thead>
				<tbody>
				<if condition="!$result['array']">
					<td colspan="9" align="center">
						<p style="padding: 15px;">暂无数据信息</p>
					</td>
					<else />
					<volist name="result.array" id="vo">
						<tr id='tr{$vo.id}'>
							<td>{$vo.user}</td>
							<td>{$vo.name}</td>
							<td>{$vo.seed}</td>
							<td>{$vo.num}</td>
							<td>{$vo.time|date="Y-m-d H:i:s",###}</td>                       
						</tr>
					</volist>
				</if>
				</tbody>
			
			</table>
			<div style="clear: both;"></div>
			<nav aria-label="Page navigation">			
				<ul class="pagination" >
				
					<li>
						<a href="{:U('Chest/record','p='.$result['last_page'].'&'.$get_url_str)}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
					</li>
					<for start="$start" end="$end">
						<if condition="$now_oage eq $i">
							<li class="active">
								<a href="{:U('Chest/record','p='.$i.'&'.$get_url_str)}">{$i}</a>
							</li>
						<else/>
							<li>
								<a href="{:U('Chest/record','p='.$i.'&'.$get_url_str)}">{$i}</a>
							</li>
						</if>
					</for>
					<li>
						<a href="{:U('Chest/record','p='.$result['next_page'].'&'.$get_url_str)}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
					</li>
				</ul>
			</nav>
	   </div>
	   
	    <div class="total none">
		
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
					<tr>
						<th>获得果实总计</th>	
					</tr>
				</thead>	

				<tbody>
					<tr>
						<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
							<thead>
								<tr>
									<volist name="seed_total" id="vo">
										<th>{$key}</th>	
									</volist>
								</tr>
							</thead>	
						<tbody>				
							<tr>
								<volist name="seed_total" id="vo">
									<th>{$vo}</th>	
								</volist>
							</tr>				
						</tbody>
						</table>
					</tr>
				</tbody>
			</table>
			
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
					<tr>
						<th>投入果实总计</th>	
					</tr>
				</thead>	

				<tbody>
					<tr>
						<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
							<thead>
								<tr>
									<volist name="seed_open_total" id="vo">
										<th>{$key}</th>	
									</volist>
								</tr>
							</thead>	
						<tbody>				
							<tr>
								<volist name="seed_open_total" id="vo">
									<th>{$vo}</th>	
								</volist>
							</tr>				
						</tbody>
						</table>
					</tr>
				</tbody>
			</table>
			
			<volist name="total" id="vo">
		   
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
					<thead>
					<tr>
						<th>{$key}获得果实总计</th>	
					</tr>
					</thead>	

					<tbody>
					<tr>
						<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;width:80%;" class="table">
							<thead>	
							<tr>
								<volist name="vo" id="val">
									<th>{$key}</th>	
								</volist>
							</tr>
							</thead>	

							<tbody>
							<tr>
								<volist name="vo" id="val">
									<td>{$val}</td>
								</volist>						
							</tr>
							</tbody>
						</table>
					</tr>
					</tbody>
				</table>
			
			</volist>
						
		   </div>
		<div style="clear: both; height:10px;"></div>
	
	</div>
    

			
	    <script>
		
		function show_div(_this,_div){
			$(".none").hide();
			$("."+_div).show();
			$(".card").removeClass("select");
			$(_this).addClass("select");
			
		}
		
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
    </script>
</block>
