<extend name="Public:base" xmlns="http://www.w3.org/1999/html"/>
<block name="content">
  
  <script src="__PUBLICHOME__/laydate/laydate.js" ></script>
  
  	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" ><br/>
	    <p style="color:red">查询规则(1) 如根据【注册时间】查询，需要选择注册开始时间、结束时间、等级</p>
		<p style="color:red">查询规则(2) 如根据【某等级几天未种出】查询，需要选择等级、果实种类、天数</p>
		<form action="{:U('Backer/zt')}" method="post">
			<div style="margin-top:2%;">
			
			<div class="input-group" style="float: left; width: 30%;">
				<if condition="$state neq 1">
				<input value="" type="text" id="start" name="start_time" class="form-control" placeholder="请选择玩家新注册的开始时间">
				<else/>
				<input value="{$start_time|date='Y-m-d',###}" type="text" id="start" name="start_time" class="form-control" placeholder="请选择玩家新注册的开始时间">
				</if>
				</div>
				
				<div class="input-group" style="float: left; width: 30%;margin-left:2%;padding-bottom:2%;">
				<if condition="$state neq 1">
					<input value=""   type="text" id="end"   name="end_time"   class="form-control" placeholder="请选择玩家新注册的结束时间">
				<else/>	
					<input value="{$end_time|date='Y-m-d',###}"   type="text" id="end"   name="end_time"   class="form-control" placeholder="请选择玩家新注册的结束时间">
				</if>	
				</div>	
			
				<div class="input-group" style="float: left; width: 20%;margin-left:2%">
				<select name="level" class="form-control">
				<if condition="$level eq ''">
                    <option value='1'>请选择等级</option>
				<else/>
                    <option value="{$level}">{$level}级</option>
				</if>
                    <option value="1">1级</option>
                    <option value="2">2级</option>
                    <option value="3">3级</option>
                    <option value="4">4级</option>
                    <option value="5">5级</option>
                    <option value="6">6级</option>
                    <option value="7">7级</option>
                    <option value="8">8级</option>
                    <option value="9">9级</option>
                    <option value="10">10级</option>
                    <option value="11">11级</option>
                    <option value="12">12级</option>
                </select>
				</div>
				
				<div style="clear:both;"></div>
				
				<div class="input-group" style="float: left; width: 20%;margin-right:2%;">
				<select name="seed" class="form-control" placeholder="选择果实">
                <if condition="$state eq 1">
                    <option>新注册查询模式</option>
				<else/>
                    <option value="">选择果实种类</option>
				</if>
                    <option value="土豆">土豆</option>
                    <option value="草莓">草莓</option>
                    <option value="樱桃">樱桃</option>
                    <option value="稻米">稻米</option>
                    <option value="番茄">番茄</option>
                    <option value="葡萄">葡萄</option>
                    <option value="菠萝">菠萝</option>
                </select>
				</div>
				
				
				<div class="input-group" style="float: left; width: 20%;margin-right:2%;">
				<select name="ts" class="form-control" placeholder="选择天数">
				<if condition="$state eq 1">
                    <option>新注册查询模式</option>
				<else/>
				   <option value="">选择天数</option>
				</if>
                    <option value="1">1天</option>
                    <option value="2">2天</option>
                    <option value="3">3天</option>
                    <option value="4">4天</option>
                    <option value="5">5天</option>
                    <option value="6">6天</option>
                    <option value="7">7天</option>
                </select>
				</div>
				

				<div style="float: left;"> <button type="submit" class="btn btn-default">查询</button></div>
			</div>
			<div style="clear: both;"></div>
		   
			<span class="field-validation-valid" data-valmsg-for="sel"></span>
		</form>		
	</div><br/>
 
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="margin-top:1%">
	    <p style="color:red">添加规则(1) 如对【查询结果】进行添加，填写随机人数、果实可更改、定向数量</p>
		<p style="color:red">添加规则(2) 如对【某个人】进行添加，填写随机人数、果实可更改、定向数量、选择开始结束时间，两个号码以上用","分开</p>
		<form action="{:U('Backer/sjdy')}" method="post">
		    <input type="text" style="width:15%;float:left;height:32px;text-indent:10%;font-size:16px;" name="sj" placeholder="随机人数(必填)" required autofocus>
			<input type="text" style="width:10%;float:left;height:32px;text-indent:10%;margin-left:2%;font-size:16px;" name="seed_name" value="{$seed}" placeholder="种子" required autofocus/>
			<input type="text" style="width:10%;float:left;height:32px;text-indent:10%;margin-left:2%;font-size:16px;" name="num" value="" placeholder="数量" required autofocus/>
			<div class="input-group" style="float: left; width: 20%;margin-left:2%;">
				<input value="" type="text" id="start_sjdy" name="start_time" class="form-control" placeholder="长期选择定向开始时间">
			</div>
			<div class="input-group" style="float: left; width: 20%;margin-left:2%;">
				<input value="" type="text" id="end_sjdy" name="end_time" class="form-control" placeholder="长期选择定向结束时间">
			</div>
		    <select style="width:10%;float:left;height:32px;margin-left:2%;font-size:14px;" name="type">
			    <option>&nbsp;&nbsp;定向类型</option>
				<option value="0">&nbsp;&nbsp;&nbsp;定量</option>
				<option value="1">&nbsp;&nbsp;&nbsp;长期</option>
			</select>
			<br/>
			<textarea style="width:100%;height:50%;margin-top:1%" name="user_number"><?php  for($j=0;$j<count($er);$j++){ if($j==count($er)-1){ echo $er[$j]['user'];}else{ echo $er[$j]['user'].',';}}?></textarea>	 
			<div style="float: left;"> <button type="submit" class="btn btn-default">提交</button></div>
		</form>	
	</div>
	
	
				
	<div style="clear: both;"></div>
 
	
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					
					<div class="content table-responsive table-full-width">
					
						<input value="1" type="hidden" name="batch">
					    <table class="table table-striped" style="border:solid 1px #ddd !important;margin-top: 2%;"  id="myTable" >
							<thead style="background-color:#fafafa;">
							 <tr>
								<th><if condition="$state eq 0">未种出用户<else/>活动新注册{$level}级玩家</if>  <if condition="$u eq ''"><else />共<span style="color:#ff0000;">{$u}</span>人</if></th>
							 </tr>
							</thead>
							<tbody id="mytbody">
							<if condition="$er eq ''">
								<td colspan="7" align="center">
									<p style="padding: 15px;">暂无数据信息</p>
								</td>
								<else />
									<if condition="$type eq 0">
									<tr>
										<td>用户账号</td>
										<td>推荐人</td>
									</tr>
									</if>
									<volist name="er" id="vo">
									<tr>
										<td>{$vo.user}</td>
										<if condition="$vo.referees neq ''"><td>{$vo.referees}</td><else/><td>无推荐人</td></if>
									</tr>
									</volist>
									
								
							</if>
							</tbody>
						</table>
						
					</div>
					

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
		
		var start_sjdy = {
		  elem: '#start_sjdy',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59', //设定最小日期为当前日期
		  max: '2099-06-16 23:59:59', //最大日期
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			 end_sjdy.min = datas; //开始日选好后，重置结束日的最小日期
			 end_sjdy.start_sjdy = datas //将结束日的初始值设定为开始日
		  }
		};
		var end_sjdy = {
		  elem: '#end_sjdy',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59',
		  max: '2099-06-16 23:59:59',
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			start_sjdy.max = datas; //结束日选好后，重置开始日的最大日期
		  }
		};
		laydate(start_sjdy);
		laydate(end_sjdy);
		
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
