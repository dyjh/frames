<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实主页<small><a href="{:U('Fruit/fruit_add')}">果实添加</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>果实名称</th>
                <th>初始价格</th>
				<th>开盘价格</th>
                <th>发芽时间(小时)</th>
                <th>成株时间（小时）</th>
                <th>成熟时间（小时）</th>
                <th>总时间（小时）</th>
                <th>预计果实数</th>
                <th>可否交易</th>
                <th>操作</th>
                <th>果实信息</th>
            </tr>
            </thead>
            <tbody>
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.id}</td>
                        <td>{$val.varieties}</td>
                        <td>{$val.first_price}</td>
						<td>{$val.open_price}</td>
                        <td>{$val.first_time}</td>
                        <td>{$val.second_time}</td>
                        <td>{$val.third_time}</td>
                        <td>{$val.harvest_hours}</td>
                        <td>{$val.fruit_number}</td>
                        <if condition="$val['state'] eq 0">
                            <td>是</td>
                            <else/>
                            <td>否</td>
                        </if>
                        <td><a href="{:U('Fruit/fruit_edit',array('id'=>$val['id']))}">修改</a>
                            <a href="javascript:del({$val.id})">删除</a>
                        </td>
                        <td><a href="{:U('Fruit/find_fruit',array('id'=>$val['id']))}">查看</a>
							<a href="{:U('Fruit/money_fruit',array('seed'=>$val['varieties']))}">查看价位</a>
						</td>
                    </tr>
                </volist>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
	<div class="page-header">
        <h1>数据错误的单<small><a href="{:U('Fruit/check_safe')}">恢复</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>提交数量</th>
				<th>剩余数量</th>
				<th>单价</th>
				<th>果实</th>
				<th>挂单类型</th>
				<th>交易状态</th>
				<th>挂单状态</th>
				<th>挂单时间</th>
            </tr>
            </thead>
            <tbody>
                <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="data_s" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.user}</td>
						<td>{$vo.submit_num}</td>
						<td>{$vo.num}</td>
						<td>{$vo.money}</td>
						<td>{$vo.seed}</td>
						<if condition="$vo['type'] eq '1'">
							<td>买入</td>
							<else/>
							<td>卖出</td>
						</if>
						<if condition="$vo['state'] eq '0'">
							<td style="color: gray;">未交易</td>
							<elseif condition="$vo['state'] eq '1'"/>
							<td style="color: red">交易中</td>
							<elseif condition="$vo['state'] eq '2'"/>
							<td style="color: green">交易完成</td>
							<else/>
							<td style="color: gray;">已撤销</td>
						</if>
						<if condition="$vo['queue'] eq 0">
							<td style="color: gray;">空闲</td>
							<elseif condition="$vo['queue'] eq 1"/>
							<td style="color: green">队列中</td>
							<else/>
							<td style="color: red">数据错误</td>
						</if>
						<td>{$vo.time|date="Y-m-d H:i:s",###}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </table>
		<if condition="$state eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('Fruit/index',array('o'=>$o-1,'p'=>$p))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Fruit/index',array('o'=>$i,'p'=>$p))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Fruit/index',array('o'=>$i,'p'=>$p))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Fruit/index',array('o'=>$o+1,'p'=>$p))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
    <div class="clear"></div>
	<div class="page-header">
        <h1>交易状态错误的单<small><a href="{:U('Fruit/check_safe_pay')}">恢复</a></small></h1>
    </div>
	<div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>提交数量</th>
				<th>剩余数量</th>
				<th>单价</th>
				<th>果实</th>
				<th>挂单类型</th>
				<th>交易状态</th>
				<th>挂单状态</th>
				<th>挂单时间</th>
            </tr>
            </thead>
            <tbody>
                <if condition="$state_e eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="data_err" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.user}</td>
						<td>{$vo.submit_num}</td>
						<td>{$vo.num}</td>
						<td>{$vo.money}</td>
						<td>{$vo.seed}</td>
						<if condition="$vo['type'] eq '1'">
							<td>买入</td>
							<else/>
							<td>卖出</td>
						</if>
						<if condition="$vo['state'] eq '0'">
							<td style="color: gray;">未交易</td>
							<elseif condition="$vo['state'] eq '1'"/>
							<td style="color: red">交易中</td>
							<elseif condition="$vo['state'] eq '2'"/>
							<td style="color: green">交易完成</td>
							<else/>
							<td style="color: gray;">已撤销</td>
						</if>
						<if condition="$vo['queue'] eq 0">
							<td style="color: gray;">空闲</td>
							<elseif condition="$vo['queue'] eq 1"/>
							<td style="color: green">队列中</td>
							<else/>
							<td style="color: red">交易状态错误</td>
						</if>
						<td>{$vo.time|date="Y-m-d H:i:s",###}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </table>
		<if condition="$state_e eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('Fruit/index',array('p'=>$p-1,'o'=>$o))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
				<for start="$start_e" end="$end_e">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('Fruit/index',array('p'=>$i,'o'=>$o))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Fruit/index',array('p'=>$i,'o'=>$o))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Fruit/index',array('p'=>$p+1,'o'=>$o))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
	<div class="clear"></div>
    <script>
        function del(id){
            if(confirm('确定要删除吗？')){
                $.post("{:U('Fruit/del')}",{id:id},function(msg){
                    if(msg == 0){
                        alert('删除失败');
                    }else if(msg == -1){
                        alert('ajax请求错误');
                    }else if(msg == 1){
                        alert('删除成功');
                        $('#tr'+id).remove();

                    }
                })
            }
        }
    </script>
</block>