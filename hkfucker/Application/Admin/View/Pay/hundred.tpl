<extend name="Public:base" />
<block name="content">
   
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
       
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
				<!-- <th>全选</th> -->
                <th>用户电话</th>
                <th>用户姓名</th>
                <th>可提现佣金</th>
                <th>可提现交易金币</th>
                <th>提现总金币</th>
                <th>目前金币</th>
                <th>目前冻结金币</th>
                <th>差额</th>
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
                        <td>{$vo.user_fees}</td>
                        <td>{$vo.buy_and_sell}</td>
                        <td>{:($vo['buy_and_sell'] + $vo['user_fees'])}</td>
                        <td>{$vo.coin}</td> 
                        <td>{$vo.coin_freeze}</td> 
						<td>{:number_format(( ($vo['coin'] + $vo['coin_freeze']) - ($vo['buy_and_sell'] + $vo['user_fees'])) , 5)}</td> 
                       </tr>
                </volist>
            </if>
            </tbody>
        
        </table>
        <div style="clear: both;"></div>
		
        
           
        <nav aria-label="Page navigation">
			
            <ul class="pagination" ">
                <li>
                    <a href="{:U('Pay/hundred',array('p'=>$result['last_page'],'start_user'=>$start_user,'state'=>$state))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
				<for start="$start" end="$end">
                   
				   <if condition="$now_oage eq $i">
                        <li class="active">
                            <a href="{:U('Pay/hundred',array('p'=>$i,'start_user'=>$start_user,'state'=>$state))}">{$i}</a>
                        </li>
                    <else/>
                        <li>
                            <a href="{:U('Pay/hundred',array('p'=>$i,'start_user'=>$start_user,'state'=>$state))}">{$i}</a>
                        </li>
                    </if>
					
                </for>
                <li>
                    <a href="{:U('Pay/hundred',array('p'=>$result['next_page'],'start_user'=>$start_user,'state'=>$state))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
		
		
		
        
		
    </div>

</block>
