<extend name="Public:base" />
<block name="content">
   
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('Pay/index')}" method="get">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>
            <div class="input-group" style="float: left; width: 23%;">
                <input value="{$start_user}" type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>

            <div class="input-group" style="float: left; width: 23%;">
                <select name="state" class="form-control">
					<option value="0" selected="selected">未完成</option>
                    <option value="all" <if condition="$state eq 'all' "> selected="selected" </if> >全部</option>
                    <option value="1,9" <if condition="$state eq '1,9' "> selected="selected" </if> >已完成</option>
                    <option value="2" <if condition="$state eq '2' "> selected="selected" </if> >取消订单</option>
                </select>
            </div>


            <div style="float: left;">
                <button type="submit" class="btn btn-default">查询</button></div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
				<!-- <th>全选</th> -->
                <th>用户电话</th>
                <th>用户姓名</th>
                <th>充值订单号</th>
                <th>充值金额</th>
                <th>订单提交时间</th>
                <th>支付状态</th>
                <th>支付时间</th>
                <th>交易方式</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$user_info eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="user_info" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.user}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.order_num}</td>
                        <td>{$vo.money}</td>
                        <td><if condition="$vo.add_time == 0">无
                                <else />
                                {$vo.add_time|date="Y-m-d H:i",###}
                            </if></td>
                        <td><if condition="$vo.state == 0">未完成<elseif condition="$vo.state == 1 || 9"/>完成<elseif condition="$vo.state == 2"/>取消订单</if></td>
                        <td><if condition="$vo.pay_time == 0">无
                                <else /> {$vo.pay_time|date="Y-m-d H:i",###}
                            </if></td>
                        <td>
                            <if condition="$vo.pay_bank == 2001">
                                网银汇款
                                <elseif condition="is_numeric($vo['pay_bank'])" />
                                   {$shunfoo_banktype_now_support[$vo['pay_bank']]}
                                <else/>
                                {$vo.pay_bank }
                            </if></td>

                        <td>


                            <if condition="$vo.pay_bank == 2001">
							
                                <if condition="$vo.state == 0">
                                        <a href="{:U('Pay/edit',array('user'=>$vo['user'],'i'=>$vo['id']))}" class="tc">添加金币</a>
                                    <elseif condition="$vo.state == 2" />
                                        订单被取消
                                    <elseif condition="$vo.state == 9" />
                                        已完成
                                </if>
								
							<!--  取消支付宝手动增加金币 -->
							<elseif condition="$vo.pay_bank == 992999"/>
							
                                <if condition="$vo.state == 0">
                                        <a href="{:U('Pay/edit',array('user'=>$vo['user'],'i'=>$vo['id']))}" class="tc">添加金币</a>
                                    <elseif condition="$vo.state == 2" />
                                        订单被取消
                                    <elseif condition="$vo.state == 9" />
                                        已完成
								</if>
								
                            <else/>
                                <if condition="$vo.state == 0">
                                    支付未完成
                                <elseif condition="$vo.state == 1"/>
                                    已完成
                                    <elseif condition="$vo.state == 2"/>
                                    订单被取消
                                </if>
                            </if>

                        </td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
		
        
           
        <nav aria-label="Page navigation">
			
            <ul class="pagination" ">
                <li>
                    <a href="{:U('Pay/index',array('p'=>$result['last_page'],'start_user'=>$start_user,'state'=>$state))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
				<for start="$start" end="$end">
                   
				   <if condition="$now_oage eq $i">
                        <li class="active">
                            <a href="{:U('Pay/index',array('p'=>$i,'start_user'=>$start_user,'state'=>$state))}">{$i}</a>
                        </li>
                    <else/>
                        <li>
                            <a href="{:U('Pay/index',array('p'=>$i,'start_user'=>$start_user,'state'=>$state))}">{$i}</a>
                        </li>
                    </if>
					
                </for>
                <li>
                    <a href="{:U('Pay/index',array('p'=>$result['next_page'],'start_user'=>$start_user,'state'=>$state))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
		
		
		
        
		
    </div>

</block>
