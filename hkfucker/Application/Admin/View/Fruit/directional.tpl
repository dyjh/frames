<extend name="Public:base" />
<block name="content">
    <!--<div class="page-header">
        <h1>种子定向</h1>
    </div>
	<div class="form-group" style="margin-left:10%;">
        <form enctype="multipart/form-data" method="post" action="">
            <!-- <a class="button input-file" style="text-align: center;vertical-align: middle;" href="javascript:void(0);">上传要发送的号码文件<input  size="80" type="file" name="file1" id="file1" onchange="check1()" /></a>!-->
            <!--<h3>导入Excel表：</h3><input  type="file" name="file_stu" />
            <input type="submit"  value="导入" />
        </form>
	</div>-->
	<div class="page-header">
        <h1>普通定向用户<small><a href="{:U('Fruit/directional_del',array('num'=>'0'))}">清除数据</a></small></h1>
    </div>
	<div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
	<p>当前完成人数<span style="color:red;">{$num_l}</span>人,总共<span style="color:red;">{$num_all}</span>人<small><a href="{:U('Fruit/directional_del',array('num'=>'1'))}">清除已完成数据</a></small></p>
		<div style=" margin-bottom: 20px;">
            <form method="post" enctype="multipart/form-data" action="" onsubmit="">
				<div class="row">
					<div class="col-md-2">
						<div class="input-group">
							<div class="form-group-addon no-right-border">用户</div>
							<div class="input-group">
								<input type="text" name="user" value="{$user}" class="form-control">
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
        </div>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>种子</th>
				<th>开始时间</th>
				<th>结束时间</th>
				<th>定向类型</th>
				<th>今日剩余数量</th>
				<th>今日定向数量</th>
				<th>出产数量</th>
				<th>操作</th>
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
                        <td>{$vo.user}</td>
						<td>{$vo.seed}</td>
						<td>--</td>
					    <td>--</td>
						<if condition="$vo['type'] eq 1">
							<td>活动定向</td>
						<else/>
							<td>定量定向</td>
						</if>
						<td>{$vo.num}</td>
						<td>{$vo.imm_num}</td>
						<td>{$vo.count}</td>
						<td><a href="javascript:del({$vo.id});">删除</a></td>
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
                    <a href="{:U('Fruit/directional',array('o'=>$o-1,'user'=>$user))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
				<for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Fruit/directional',array('o'=>$i,'user'=>$user))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Fruit/directional',array('o'=>$i,'user'=>$user))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Fruit/directional',array('o'=>$o+1,'user'=>$user))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
	<div class="clear"></div>
    <script>
        function del(id){
            if(confirm('确定要删除吗？')){
                $.post("{:U('Fruit/direction_one')}",{id:id},function(msg){
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