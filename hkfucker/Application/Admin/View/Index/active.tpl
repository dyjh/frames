<extend name="Public:base" />
<block name="content">
	<div class="page-header">
        <h1>满足要求的人</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>名字</th>
				<th>新注册6级</th>
				<th>已领物品</th>
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
						<td>{$vo.name}</td>
						<td>{$vo.activity_info}</td>
						<td>{$vo.act_prop}</td>
						<td><a href="javascript:del({$vo.id})">处理</a></td>
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
                    <a href="{:U('Index/active',array('o'=>$o-1,'p'=>$p))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Index/active',array('o'=>$i,'p'=>$p))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Index/active',array('o'=>$i,'p'=>$p))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Index/active',array('o'=>$o+1,'p'=>$p))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
    <div class="clear"></div>
	<div class="page-header">
        <h1>达到6级的人</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>该用户直推人数</th>
				<th>推荐人</th>
				<th>推荐人等级</th>
				<th>推荐人活动点数</th>
            </tr>
            </thead>
            <tbody>
                <if condition="$state_m eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="member" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.user}</td>
						<td>{$vo.count}</td>
						<td>{$vo.referees}</td>
						<td>{$vo.level}</td>
						<td>{$vo.num}</td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </table>
		<if condition="$state_m eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('Index/active',array('p'=>$p-1,'o'=>$o))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start_m" end="$end_m">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('Index/active',array('p'=>$i,'o'=>$o))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Index/active',array('p'=>$i,'o'=>$o))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Index/active',array('p'=>$p+1,'o'=>$o))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
    <div class="clear"></div>
    <script>
        function del(id){
            if(confirm('确定要发放吗吗？')){
                $.post("{:U('Index/deel')}",{id:id},function(msg){
                    if(msg == 0){
                        alert('发放失败');
                    }else if(msg == -1){
                        alert('ajax请求错误');
                    }else if(msg == 1){
                        alert('发放成功，请刷新');
                    }
                })
            }
        }
    </script>
</block>