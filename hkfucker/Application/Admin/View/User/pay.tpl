<extend name="Public:base" />
<block name="content">
	<div class="page-header">  
		<h1>个人交易记录<small><a href="{:U('User/entrust',array('user'=>$user))}">挂单记录</a></small></h1>
    </div>
	<script src="__JS__/WdatePicker.js"></script>
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
		<form method="post" enctype="multipart/form-data" action="" onsubmit="">
		<div class="row">
			<div class="col-md-2">
				<div class="input-group">
					<div class="form-group-addon no-right-border">产品</div>
					<div class="input-group">
						<select name="id" id="id" style=" border:none;" class="form-control">
							<option value="">全部</option>                                          
							<if condition="$id eq ''">                                             
								<volist name="data_seed" id="val">
									<option value="{$val.id}">{$val.varieties}</option>
								</volist>
								<else/>
								<volist name="data_seed" id="val">
									<if condition="$val['id'] eq $id">
										<option value="{$val.id}" selected>{$val.varieties}</option>
										<else/>
										<option value="{$val.id}">{$val.varieties}</option>
									</if>
								</volist>                                             
							</if>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group" >
					<div class="form-group-addon no-right-border">类型</div>
					<div class="input-group">
						<select name="type" id="type" class="form-control" style=" border:none;">
							<if condition="$type eq ''">
								<option value="">全部</option>
								<option value="1">买入</option>
								<option value="2">卖出</option>
								<elseif condition="$type eq 1" />
								<option value="1">买入</option>
								<option value="">全部</option>
								<option value="2">卖出</option>
								<elseif condition="$type eq 2" />
								<option value="2">卖出</option>
								<option value="">全部</option>
								<option value="1">买入</option>
							</if>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<div class="form-group-addon no-right-border">开始</div>
					<div class="form-group-addon form-group-addon-white stretch">
						<input type="text" name="start" id="StartDate" onfocus="WdatePicker();" onblur="check();" placeholder="" value="{$start}" class="form-control">
					</div>
				</div>
			</div>
			<div class="col-md-3"  >
				<div class="form-group">
					<div class="form-group-addon no-right-border">结束</div>
					<div class="form-group-addon form-group-addon-white stretch">
						<input type="text" name="end" id="EndDate"  onblur="check();" onfocus="WdatePicker({dateFmt:&#39;yyyy-MM-dd&#39;});" placeholder="" value="{$end}" class="form-control">
						<input type="hidden" id="hidden" value="{$end}"/>
					</div>
				</div>
			</div>
			<div class="col-md-1" style="margin-top:22px;">
				
				<div class="form-group">
					<input type="submit" class="form-control border-input"  placeholder="Username"  value="查询">
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</form>
	<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>对方用户</th>
				<th>类型</th>
				<th>数量</th>
				<th>单价</th>
				<th>总额</th>
				<th>手续费</th>
				<th>交易果实</th>
				<th>交易时间</th>
				
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="data" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$user}</td>
                        <if condition="$vo['buy_user'] eq $user">
							<td>{$vo.sell_user}</td>
							<else/>
							<td>{$vo.buy_user}</td>
						</if>
						<if condition="$vo['buy_user'] eq $user">
							<td>买入</td>
							<else/>
							<td>卖出</td>
						</if>
						<td>{$vo.num}</td>
						<td>{$vo.money}</td>
						<td>{$vo.total}</td>
						<td>{$vo.poundage}</td>
						<td>{$vo.seed}</td>
						<td>{$vo.time|date="Y-m-d H:i:s",###}</td>
						
						
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('User/pay',array('o'=>$o-1,'user'=>$user,'id'=>$id,'type'=>$type,'start'=>$start,'end'=>$end))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start_page" end="$end_page">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('User/pay',array('o'=>$o-1,'user'=>$user,'id'=>$id,'type'=>$type,'start'=>$start,'end'=>$end))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/pay',array('o'=>$i,'user'=>$user,'id'=>$id,'type'=>$type,'start'=>$start,'end'=>$end))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/pay',array('o'=>$o+1,'user'=>$user,'id'=>$id,'type'=>$type,'start'=>$start,'end'=>$end))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
	</div>
    <div style="clear: both;"></div>
	<script type="text/javascript">
        function check() {
            if ($("#StartDate").val() != "" && $("#EndDate").val() != "") {
                if (new Date($("#StartDate").val().replace(/-/g, "/")) > new Date($("#EndDate").val().replace(/-/g, "/"))) {
                    alert("开始时间不能大于结束时间");
                    $("#EndDate").onfocus;
                }
            }
        }
		
    </script>
</block>
