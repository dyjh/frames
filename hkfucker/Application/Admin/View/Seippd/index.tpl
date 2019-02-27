<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>站点统计</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1">
        <div class="input-group" style="margin: 20px auto">
            <form method="post" enctype="multipart/form-data" action="">
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>手机中奖人</th>
				<th>手机类型</th>
                <th>手机中奖人</th>
                <th>手机类型</th>
            </tr>
            </thead>
            <tbody>
            <tr>
			<volist name="ppd" id="vo">
				<td>{$vo.user}</td>
				<td>{$vo.prize}</td>
				</volist>
            </tr>
            </tbody>
        </table>
    </div>
	
	<div style="clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>手机碎片1</th>
                <th>手机碎片2</th>
                <th>手机碎片3</th>
                <th>手机碎片4</th>
                <th>手机碎片5</th>
                <th>手机碎片6</th>
                <th>手机碎片</th>
            </tr>
            </thead>
            <tbody>
            <tr>
				<td>{$aar.yi}</td>
				<td>{$aar.er}</td>
				<td>{$aar.san}</td>
				<td>{$aar.si}</td>
				<td>{$aar.wu}</td>
				<if condition="$aar.liu eq 0"><td>0</td><else/><td>{$aar.liu}</td></if>
				<td>{$aar.lin}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">

        function time(){
            var id=$('.id').val();
            var s=$("input[name='start_time']").val();
            var e=$("input[name='end_time']").val();
//alert('__URL__/id/'+id+'/e/'+e+'/s/'+s);
            if(s.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                if(e.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                    window.location="{:U('Statistics/index')}?e="+e+'&h='+s;
                } else {
                    alert('结束日期格式错误，请重新输入！');
                    $("input[name='end_time']").val('');
                }
            } else {
                alert('开始日期格式错误，请重新输入！');
                $("input[name='start_time']").val('')
            }
        }
    </script>
</block>