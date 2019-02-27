<extend name="Public:base" />
<block name="content">
	<div class="page-header">  
		<h1>个人金币冻结</h1>
    </div>
	<script src="__JS__/WdatePicker.js"></script>
	<div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
	<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>冻结</th>
				<th>剩余</th>
				<th>时间</th>
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
						<td>{$vo.coin_freeze}</td>
						<td>{$vo.coin}</td>
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
                    <a href="{:U('User/entrust',array('o'=>$o-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start_page" end="$end_page">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('User/entrust',array('o'=>$o-1))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/entrust',array('o'=>$i))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/entrust',array('o'=>$o+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
	</div>
    <div style="clear: both;"></div>
	
</block>
