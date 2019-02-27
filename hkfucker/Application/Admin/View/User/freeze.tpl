<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">

    <js file="__JS__/common.js" />
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('User/freeze')}" method="post">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>

            <div class="input-group" style="float: left; width: 23%;">
                <input type="text" name="user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>
            <div style="float: left;">
                <button type="submit" class="btn btn-default" >查询</button></div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
            </form>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>提现状态</th>
				<th>交易状态</th>
                <th>账号状态</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>

            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="freeze" id="vo">
                    <tr id="all_material_{$vo.user}">
                        <td>{$vo.user}
						<input type="hidden" value="{:session('CASHTOKEN')}" name="token">
						</td>
						
						<td>
							
						<if condition="$vo['is_cash'] eq 1">
							<input type="hidden" value="" name="cash_cash">
							<i class="glyphicon glyphicon-ban-circle " onclick="change_status(this,'__URL__/update_ajax',{$vo.user},'pass')" title="点击允许该用户提现"></i>禁止提现
						<else/>
							<input type="hidden" value="" name="cash_cash">
							<i class="glyphicon glyphicon-ok " onclick="change_status(this,'__URL__/update_ajax',{$vo.user},'refuse')" title="点击禁止该用户提现"></i>允许提现	
						</if>											
						</td>
						<td><if condition="$vo.pay == 0"><span style="color:green;">允许</span><else /> <span style="color:red;">禁止</span></if>|<a href="{:U('User/pay_edit',array('id'=>$vo['id']))}">操作</a></td>
                        <td><if condition="$vo.state == 0">正常<else /> 封号</if></td>
                        <td><a href="{:U('User/freeze_edit',array('id'=>$vo['id']))}">修改</a></td>
                    </tr>
                </volist>
            </if>

            </tbody>
        </table>

        <if condition="$state eq 0">
            <else />
            <nav aria-label="Page navigation">
                <ul class="pagination">
                <li>
                    <a href="{:U('User/freeze',array('p'=>$p-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a class="active_na" href="{:U('User/freeze',array('p'=>$p-1))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/freeze',array('p'=>$i))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a class="active_na" href="{:U('User/freeze',array('p'=>$p+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
                </ul>
            </nav>
        </if>

    </div>
	<script>


		function change_status(_this,_url,id_num,type){
			if(type=='refuse'){
				var confirm_content = "是否禁止该用户提现？";
				var change_val = 1 ;
				var free_glyphicon_icon = 'glyphicon-ban-circle',
					notice    			= '点击允许该用户提现',
					types    			= "'pass'",
					is_free_content     = '禁止提现';
			}else  if(type=='pass'){
				var confirm_content = "是否允许该用户提现？"
				var change_val = 0 ;
				var free_glyphicon_icon = 'glyphicon-ok',
					notice   			= '点击禁止该用户提现',
					types	   			= "'refuse'",
					is_free_content     = '允许提现';
			}			

			if(confirm(confirm_content)){
				var  _html =  '<input type="hidden" value="'+change_val+'" name="cash_cash">'
							  +	'<i class="glyphicon '
							  + free_glyphicon_icon+ ' " onclick="change_status(this,\'__URL__/update_ajax\','
							  + id_num + ','
							  + types  +')" title="'
							  + notice +'"></i>'
							  + is_free_content	;
				
				$(_this).parent("td").html(_html);

				change_data(_url,id_num)
			}
		}
	</script>
	
</block>














