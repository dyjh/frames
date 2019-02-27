<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>站点统计</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1">
        <div class="input-group" style="margin: 20px auto">
            <form method="post" enctype="multipart/form-data" action="">
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <div class="input-group" style="float: left;">
                    <input type="text" name="user" class="form-control" placeholder="输入用户名" aria-describedby="basic-addon1">
                </div>
                <div class="btn-toolbar" role="toolbar" aria-label="..." style="float: left;">
                    <button type="submit" class="btn btn-default"  >提交</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <if condition="$data['state'] eq 0">
            <else/>
            <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
                <thead>
                <tr>
                    <th>用户</th>
                    <th>总金额</th>
                    <th>总数量</th>
                    <th>提现单数</th>
                    <th>充值单数</th>
                    <th>提现总额</th>
                    <th>充值总额</th>
                    <volist name="name" id="val">
                        <th>{$val}</th>
                    </volist>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{$data.user}</td>
                    <td>{$data.money}</td>
                    <td>{$data.order_number}</td>
                    <td>{$data.deposit_record}</td>
                    <td>{$data.top_record}</td>
                    <td>{$data.deposit_money}</td>
                    <td>{$data.top_money}</td>
                    <volist name="seed_num" id="val">
                        <td>{$val}</td>
                    </volist>
                </tr>
                </tbody>
            </table>
        </if>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>会员数</th>
                <th>全站收入</th>
                <th>充值单数</th>
                <th>提现单数</th>
                <th>会员钻石总数</th>
                <th>该时间段注册人数</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{$data_total.member_num}</td>
                <td>{$data_total.income}</td>
                <td>{$data_total.top_num}</td>
                <td>{$data_total.deposit_num}</td>
                <td>{$money}</td>
                <td>{$var}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <div class="chaxun">
            <div class="input-group" style="float: left; width: 20%;margin-right: 10px;margin-left: 20%">
                <input type="text" name="start_time" class="form-control" placeholder="起始时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div class="input-group" style="float: left;width: 20%; margin-right: 10px;">
                <input type="text" name="end_time" class="form-control" placeholder="结束时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div style="float: left; line-height: 2em; text-align: center;"><a href="javascript:time()">查询</a></div>
        </div>
        <div style="clear: both;"></div>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>充值总金额</th>
                <th>提现总金额</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{$data_record.top}</td>
                <td>{$data_record.deposit}</td>
            </tr>
            </tbody>
        </table>
		
		<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>种子发行总量</th>
				<th>种子当前发行总量</th>
                <th>种子商店已售出数量</th>
                <th>当前批次种子剩余数量</th>
				<th>新人礼包已送出数量</th>
				<th>种子当前剩余数量</th>
            </tr>
            </thead>
            <tbody>
            <tr>
				<td>{$seeds_array.zonlian}</td>
                <td>{$seeds_array.total_num}</td>
                <td>{$seeds_array.shop_num}</td>
                <td>{$seeds_array.shop_out}</td>
                <td>{$seeds_array.gift_num}</td>
                <td>{$seeds_array.surplus}</td>
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