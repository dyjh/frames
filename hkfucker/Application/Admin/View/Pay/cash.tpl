<extend name="Public:base" xmlns="http://www.w3.org/1999/html"/>
<block name="content">
  
  <script src="__PUBLICHOME__/laydate/laydate.js" ></script>
  
  	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
		<form action="{:U('Pay/cash')}" method="get">
			<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
			<div>
				<div class="input-group" style="float: left; width: 40%;">
					<input value="{$start_user}" type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
				</div>

				<div class="input-group" style="float: left; width: 40%;">
					<select name="state" class="form-control">
						<option value="0" selected="selected">未完成</option>
						<option value="all" <if condition="$state eq 'all' "> selected="selected" </if> >全部</option>
						<option value="1,9" <if condition="$state eq '1,9' "> selected="selected" </if> >已完成</option>
						<option value="2" <if condition="$state eq '2' "> selected="selected" </if> >取消订单</option>
					</select>
				</div>
				
				<div style="clear: both; height:10px;"></div>
				
				<div class="input-group" style="float: left; width: 40%;">
					<input value="{$start_time|date='Y-m-d',###}" type="text" id="start" name="start_time" class="form-control" placeholder="请输入你要查询的开始时间">
				</div>
				
				<div class="input-group" style="float: left; width: 40%;">
					<input value="{$end_time|date='Y-m-d',###}"   type="text" id="end"   name="end_time"   class="form-control" placeholder="请输入你要查询的结束时间">
				</div>	
				
				<div style="clear: both; height:10px;"></div>
				
				<div class="input-group" style="float: left; width: 40%;">
					<input value="{$pay_bank}" type="text" name="pay_bank_name" class="form-control" placeholder="请输入你要查询的支付宝账户或银行账户">
				</div>
				

				<div style="float: left;"> <button type="submit" class="btn btn-default">查询</button></div>
			</div>
			<div style="clear: both;"></div>
		   
			<span class="field-validation-valid" data-valmsg-for="sel"></span>
		</form>		
	</div>
				
	<div style="clear: both;"></div>
 
			
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>用户可提现总金币</th>
                <th>用户总金币数</th>
            </tr>
            </thead>	

            <tbody>
            <tr>
                <td>{$coin.can_cash}</td>
                <td>{$coin.user_coin}</td>
            </tr>
            </tbody>
        </table>
		
	
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					
					<div class="content table-responsive table-full-width">
					
						<input value="1" type="hidden" name="batch">
					    <table class="table table-striped" style="border:solid 1px #ddd !important;margin-top: 2%;"  id="myTable" >
							<thead style="background-color: #fafafa;">
							 <tr>
								<th>
									<input value="" type="checkbox">全选
								</th>
								<th>用户电话</th>
								<th>用户姓名</th>
								<th>订单号</th>
								<th>提现金额</th>
								<th>打款金额</th>
								<th>提交时间</th>
								<th>状态</th>
								<th>交易方式</th>
								<th>管理操作</th>
							 </tr>
							</thead>
							<tbody  id="mytbody">
							<if condition="$user_info eq ''">
								<td colspan="7" align="center">
									<p style="padding: 15px;">暂无数据信息</p>
								</td>
								<else />
								<volist name="user_info" id="vo">
									<tr id='tr{$vo.id}'>
										<td>
											<input value="{$vo.id}_{$vo.user}" type="checkbox" name="batch_id">
										</td>
										<td>{$vo.user}</td>
										<td>{$vo.name}</td>
										<td>{$vo.order_num}</td>
										<td>{$vo.money}</td>
										<td>￥{$vo.hook}元</td>
										<td><if condition="$vo.add_time == 0">无
												<else />
												{$vo.add_time|date="Y-m-d",###}
											</if></td>
										<td><if condition="$vo.state == 0">未完成<elseif condition="$vo.state == 1 || 9"/>完成<elseif condition="$vo.state == 2"/>取消订单</if></td>
										<td>
											<if condition="$vo.pay_bank == 2001">
												网银汇款
												<elseif condition="is_numeric($vo['pay_bank'])" />
												   {$shunfoo_banktype_now_support[$vo['pay_bank']]}
												<else/>
												{$vo.pay_bank }
											</if>
										</td>
										<td>
											
											<if condition="$vo.state == 0">
												<a href="JavaScript:void(0)" onclick="confirm_order(this,'1','{$vo.id}','{$vo.user}')">确认</a><br/>
												<a href="JavaScript:void(0)" onclick="confirm_order(this,'2','{$vo.id}','{$vo.user}')">取消</a><br/>
											<elseif condition="$vo.state == 1"/>
												已确认<br/>
												
												<if condition="($vo['add_time']+3600*24*7) gt time()">
												<a href="JavaScript:void(0)" onclick="confirm_order(this,'2','{$vo.id}','{$vo.user}')">提现失败点击取消</a><br/>
												</if>
												<elseif condition="$vo.state == 2"/>
												订单被取消
											</if>
											
										</td>
									</tr>
								</volist>
							</if>
							</tbody>
						</table>
						
						 <div > <button id="batch_submit" class="btn btn-default">批量确认</button></div>
						
				
					</div>
					
						
					<nav aria-label="Page navigation">			
						<ul class="pagination" >
						
							<li>
								<a href="{:U('Pay/cash','p='.$result['last_page'].'&'.$get_url_str)}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
							</li>
							<for start="$start" end="$end">
								<if condition="$now_oage eq $i">
									<li class="active">
										<a href="{:U('Pay/cash','p='.$i.'&'.$get_url_str)}">{$i}</a>
									</li>
								<else/>
									<li>
										<a href="{:U('Pay/cash','p='.$i.'&'.$get_url_str)}">{$i}</a>
									</li>
								</if>
							</for>
							<li>
								<a href="{:U('Pay/cash','p='.$result['next_page'].'&'.$get_url_str)}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
        </div>
			
		<script>
			$("#batch_submit").click(function(){
							 
				var material = document.getElementById("mytbody");

				var SonInput = material.getElementsByTagName("input");

				var AjaxObj = new Object();

				for(var i=0; i<SonInput.length; i++){
					if(SonInput[i].checked == true){
						//var _name = SonInput[i].name;
						AjaxObj[i] = SonInput[i].value;	
					}					
				}
					
				$.post(
					"{:U('Pay/batch_order')}",
					{OrderType:1,OrderId:AjaxObj},
					function(result){
						$alert_note  = "成功"+result.success+"条\n";
						$alert_note += "未成功"+result.error+"条\n";
						alert($alert_note);
					},
					"json"
				)
                
			})
		</script>
		
		
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
		
		
        function confirm_order(_this,OrderType,OrderId,OrderUser){

                if(OrderType == 1){
                    var ComfirmMsg = "是否确认，确认后将扣除用户相应金币?";
                    var OrderUrl   = "{:U('Pay/dousercash')}"
                }else{
                    var ComfirmMsg = "是否取消，取消后该提现单状态将变更?";
                    var OrderUrl   = "{:U('Pay/dousercash')}"
                }

                if(confirm(ComfirmMsg)){
                    $.post(
                            OrderUrl,
                            {OrderType:OrderType,OrderId:OrderId,OrderUser:OrderUser},
                            function(result){
                                if(result.status == 1){
                                    $(_this).parent().html(result.button)
                                }
                                alert(result.msg)
                            },
                            "json"
                    )
                }

            }
			
			
			
		(function($){
				$.fn.tableCheck = function(allCheckboxClass){
					var allCheck = $(this).find("th").find(':checkbox');
					var checks = $(this).find('td').find(':checkbox');
					var defaults = {
						selectedRowClass:"active",
					}
					var settings = $.extend(defaults,allCheckboxClass);
					if(allCheckboxClass)
						settings.selectedRowClass = allCheckboxClass;
					/*所有checkbox初始化*/
					$(this).find(":checkbox").prop("checked",false);
					/*全选/反选*/
					allCheck.click(function(){
						var set = $(this).parents('table').find('td').find(':checkbox');
						if($(this).prop("checked")){
							$.each(set,function(i,v){
								$(v).prop("checked",true);
								$(v).parents('tr').addClass(settings.selectedRowClass);
							});
						}else{
							$.each(set,function(i,v){
								$(v).prop("checked",false);
								$(v).parents('tr').removeClass(settings.selectedRowClass);
							});
						}
					});

					/* 监听全选事件 */
					checks.click(function(e){
						e.stopPropagation();//阻止冒泡
						var leng = $(this).parents("table").find('td').find(':checkbox:checked').length;
						/*勾选后该行active*/
						if($(this).prop('checked')){
							$(this).parents('tr').addClass(settings.selectedRowClass);
						}else{
							$(this).parents('tr').removeClass(settings.selectedRowClass);
						}
						if(leng == checks.length){
							allCheck.prop('checked',true);
						}else{
							allCheck.prop("checked",false);
						}
					});
					/*点击table触发复选框*/
					$(this).find("td").click(function(){
						var _tr = $(this).parents('tr');
						_tr.find(":checkbox").trigger("click");
					});
				}
			})(jQuery);
			
			
		$("#myTable").tableCheck("warning");
        </script>
 	
		

</block>
