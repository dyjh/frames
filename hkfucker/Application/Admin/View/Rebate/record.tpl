<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('Rebate/record')}" method="get">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>
            <div class="input-group" style="float: left; width: 23%;">
                <input type="text" id="start_user" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>
			<div class="input-group" style="float: left;width: 8%;">
                <select class="form-control" id="time" name="time">
					<option value="1" selected>一天</option>                                                                   
					<option value="2">二天</option>                                                                   
					<option value="3">三天</option>                                                                   
					<option value="4">四天</option>                                                                   
					<option value="5">五天</option>                                                                   
					<option value="6">六天</option>                                                                   
					<option value="7">七天</option>                                                                   
				</select>
            </div>
			<div class="input-group" style="float: left;width: 8%;">
				<select class="form-control" id="type" name="type">
					<option value="1" selected>兑换</option>                                                                   
					<option value="2">交易</option>                                                                   
					<option value="3">重生</option>                                                                                                                                  
				</select>
			</div>
            <div style="float: left;">
                <button  type="submit" class="btn btn-default">查询</button>
			</div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
				<th>用户ID</th>
                <th>用户</th>
                <th>金额</th>
                <th>来源</th>
                <th>时间</th>
                <th>类别</th>                
            </tr>
            </thead>
            <tbody>
            <if condition="$list eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
			<else />
                <foreach name="list" item="res">
                    <tr id='tr{$res.id}'>
						<td>{$res.id}</td>
                        <td>{$res.user}</td>
                        <td>{$res.money}</td>
                        <td>{$res.source}</td>
                        <td><?php echo date("Y-m-d H:i:s",$res['time']) ?></td>
						<if condition="$res.type eq 1">
                        <td>兑换</td> 
						<elseif condition="$res.type eq 2" />
						<td>交易</td>
						<elseif condition="$res.type eq 3" />
						<td>重生</td>
						</if>						
                    </tr>
                </foreach>
            </if>



            </tbody>
        </table>
		</from>
		<div class="pager">

           

        </div>
        <div style="clear: both;"></div>
		
		<nav aria-label="Page navigation">
			
            <ul class="pagination" ">
                <li>
                    <a href="{:U('Rebate/record',array('p'=>$result['last_page'],'start_user'=>$start_user,'time'=>$state,'type'=>$type))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
				<for start="$start" end="$end">
                   
				   <if condition="$now_oage eq $i">
                        <li class="active">
                            <a href="{:U('Rebate/record',array('p'=>$i,'start_user'=>$start_user,'time'=>$state,'type'=>$type))}">{$i}</a>
                        </li>
                    <else/>
                        <li>
                            <a href="{:U('Rebate/record',array('p'=>$i,'start_user'=>$start_user,'time'=>$state,'type'=>$type))}">{$i}</a>
                        </li>
                    </if>
					
                </for>
                <li>
                    <a href="{:U('Rebate/record',array('p'=>$result['next_page'],'start_user'=>$start_user,'time'=>$state,'type'=>$type))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
		
    </div>
	<div style="clear:both;"></div>
	
	
    <script>		
		function select_record(){
			var start_user = $("#start_user").val();
			var time = $("#time").val();
			//alert(start_user);
			//alert(time);
			//return;
			$.ajax({
				type: "post",
				url: "{:U('Rebate/record')}",
				datatype: "json",
				data: { start_user:start_user,time:time},
				success: function (result){
					
				}
			});
		}
		
	
		
        
		function add(user){
            if(confirm('确定要添加吗？')){
                $.post("{:U('User/gg_add')}",{user:user},function(msg){
                    if(msg == 0){
                        alert('添加失败');
                    }else if(msg == -2){
                        alert('添加错误');
                    }else if(msg == 1){
                        alert('添加成功');
                    }else if(msg == -1){
                        alert('该用户已添加，请勿重复');
                    }
                })
            }
        }
    </script>
</block>
