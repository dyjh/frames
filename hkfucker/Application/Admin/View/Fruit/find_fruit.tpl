<extend name="Public:base" />
<block name="content">
    <script src="__HIGHSTOCK__/highstock.js"></script>
    <script src="__HIGHSTOCK__/Market_Detail.js"></script>
	<script src="__PUBLICHOME__/laydate/laydate.js" ></script>
    <script src="__HIGHSTOCK__/modules/exporting.js"></script>
    <body style="height: 100%; margin: 0">
    <div id="container" class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="height: 350px;margin-top: 20px; " ></div>
    <div style="clear: both;"></div>

    <script>
        function MovingAverage(data, i, n) {
            if (i >= n - 1) {
                var sum = 0;

                for (j = 0; j <= n - 1; j++) {
                    //sum = sum + parseFloat(data[i - j].ClosingPrice);       //  此处应取 数据逇收盘价格
                    sum = sum + parseFloat(data[i - j].min_money);
                }

                return sum / n;
            }
            else {
                return null;
            }
        }

        $(function () {
            Highcharts.setOptions({
                lang: {
                    rangeSelectorZoom: ''
                }
            });
            /**************************************************************************/
            $.get("{:U('Fruit/ajax_k')}",{procode:'{$seed}'}, function (data) {
                        /**************************************************************************/

                        var ohlc = [],      //   蜡烛图  数据
                                volume = [],        // 柱状图数据
                                dataLength = data.length,


                                groupingUnits = [[
                                    'week',                         // unit name
                                    [1]                             // allowed multiples
                                ], [
                                    'month',
                                    [1, 2, 3, 4, 6]
                                ]],
                                MA5Array = [], MA10Array = [], MA30Array = [],
                                intFormatFloat = 4,
                                i = 0;
                        var open, high, low, close, y, zde, zdf;

                        for (i; i < dataLength; i += 1) {
                            ohlc.push([
                                parseInt(data[i].time * 1000), // the date
                                parseFloat(data[i].start_money),             // open
                                parseFloat(data[i].max_money),               // high
                                parseFloat(data[i].min_money),               // low
                                parseFloat(data[i].end_money)                // close
                            ]);
                            volume.push([
                                parseInt(data[i].time  * 1000), // the date
                                parseInt(data[i].num) // the volume
                            ]);
                            MA5Array.push([
                                parseInt(data[i].time * 1000),            // the date
                                MovingAverage(data, i, 5)
                            ]);

                            MA10Array.push([
                                parseInt(data[i].time * 1000),
                                MovingAverage(data, i, 10)
                            ]);

                            MA30Array.push([
                                parseInt(data[i].time * 1000),
                                MovingAverage(data, i, 30)
                            ]);

                        }

                        // create the chart
                        $('#container').highcharts('StockChart', {
                            rangeSelector: {
//                            selected:0,
                                inputDateFormat: '%Y-%m-%d'
                            },
                            title: {
                                text: ''
                            },
                            chart:{
                                marginRight: 30
                            },
                            exporting:{
                                enabled:false //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
                            },
                            credits: {
                                enabled:false
                            },
                            xAxis: {
                                type: 'datetime',
                                tickLength: 0,
                                events: {
                                    afterSetExtremes: function (e) {
                                        var minTime = Highcharts.dateFormat("%Y-%m-%d", e.min);
                                        var maxTime = Highcharts.dateFormat("%Y-%m-%d", e.max);
                                        var chart = this.chart;
                                    }
                                },
                                labels: {
                                    formatter: function (e) {
                                        return Highcharts.dateFormat('%m-%d', this.value);
                                        alert('a')
                                    }
                                }
                            },
                            yAxis: [{
                                title: {
                                    enable: false
                                },
                                labels: {
                                    align: 'right',
                                    x: 25
                                },
                                height: '74%',
                                lineWidth: 1,
                                gridLineColor: '#346691',
                                gridLineWidth: 0.1,
                                opposite: true,
                            }, {
                                title: {
                                    enable: false
                                },
                                labels: {
                                    enabled: false,
                                },
                                top: '80%',
                                height: '20%',
                                offset: 0,
                                gridLineColor: '#346691',
                                gridLineWidth: 0.1,
                                lineWidth: 1,
                            }],
                            tooltip: {
                                formatter: function () {
                                    if (this.y == undefined) {
                                        return;
                                    }

                                    for (var i = 0; i < data.length; i++) {
                                        if (this.x == data[i].time * 1000) {
                                            zde = parseFloat(data[i].end_money - data[i].start_money).toFixed(intFormatFloat);
                                            //zdf = parseFloat((data[i].ClosingPrice - data[i].OpeningPrice) / data[i].OpeningPrice).toFixed(intFormatFloat);
                                            zdf = (data[i].end_money - data[i].start_money) / data[i].start_money;
                                        }
                                    }

                                    var tip = '<b>' + Highcharts.dateFormat('%Y-%m-%d %A', this.x) + '</b><br/>';

                                    open = this.points[0].point.open ? this.points[0].point.open.toFixed(intFormatFloat) : 0;
                                    high = this.points[0].point.high ? this.points[0].point.high.toFixed(intFormatFloat) : 0;
                                    low  = this.points[0].point.low  ? this.points[0].point.low.toFixed(intFormatFloat)  : 0;
                                    close= this.points[0].point.close ? this.points[0].point.close.toFixed(intFormatFloat): 0;
                                    y = this.points[1].point.y ? this.points[1].point.y : 0;

                                    tip += '开盘价：' + open + '<br/>';
                                    tip += '收盘价：' + close + '<br/>';
                                    tip += '最高价：' + high + '<br/>';
                                    tip += '最低价：' + low + '<br/>';

                                    if (open < close)
                                    {
                                        tip += '涨跌额：<span style="color:#F56363;">' + zde + '</span><br/>';
                                        tip += '涨跌幅：<span style="color:#F56363;">' + (zdf * 100).toFixed(2) + '%</span><br/>';
                                    } else if(open>close) {
                                        tip += '涨跌额：<span style="color:#5B910B;">' + zde + '</span><br/>';
                                        tip += '涨跌幅：<span style="color:#5B910B;">' + (zdf * 100).toFixed(2) + '%</span><br/>';
                                    } else {
                                        tip += '涨跌额：<span>' + zde + '</span><br/>';
                                        tip += '涨跌幅：<span>' + (zdf * 100).toFixed(2) + '%</span><br/>';
                                    }


                                    if (y > 10000 * 10000) {
                                        tip += "成交量：" + (y * 0.00000001).toFixed(intFormatFloat) + "亿<br/>";
                                    } else if (y > 10000) {
                                        tip += "成交量：" + (y * 0.0001).toFixed(intFormatFloat) + "万<br/>";
                                    } else {
                                        tip += "成交量：" + y + "<br/>";
                                    }

                                    return tip;
                                },
                                backgroundColor: '#FCFFC5',
                                crosshairs: {
                                    //dashStyle: 'dash'
                                },
                                borderColor: 'white',

                                shadow: true
                            },
                            series: [
                                {
                                    type: 'candlestick',
                                    name: '',
                                    color: 'green',
                                    lineColor: 'green',
                                    upColor: 'red',
                                    upLineColor: 'red',
                                    tooltip: {
                                        valueDecimals: 4
                                    },
                                    data: ohlc,
                                    dataGrouping: {
                                        enabled: false
                                    }
                                },
                                {
                                    type: 'column',
                                    name: '',
                                    data: volume,
                                    yAxis: 1,
                                    tooltip: {
                                        valueDecimals: 4
                                    },
                                    dataGrouping: {
                                        enabled: false
                                    }
                                },
                                {
                                    type: 'spline',
                                    name: 'MA5',
                                    color: '#1aadce',
                                    data: MA5Array,
                                    lineWidth: 1,
                                    tooltip: {
                                        valueDecimals: 4
                                    },
                                    dataGrouping: {
                                        enabled: false
                                    },
                                    animation: false
                                },
                                {
                                    type: 'spline',
                                    name: 'MA10',
                                    data: MA10Array,
                                    color: '#FF7F00',
                                    threshold: null,
                                    lineWidth: 1,
                                    tooltip: {
                                        valueDecimals: 4
                                    },
                                    dataGrouping: {
                                        enabled: false
                                    },
                                    animation: false
                                },
                                {
                                    type: 'spline',
                                    name: 'MA30',
                                    data: MA30Array,
                                    color: '#910000',
                                    threshold: null,
                                    lineWidth: 1,
                                    tooltip: {
                                        valueDecimals: 4
                                    },
                                    dataGrouping: {
                                        enabled: false
                                    },
                                    animation: false
                                }
                            ]
                        });
                    },"json"
            );
            /**************************************************************************/
        });
        /**************************************************************************/


    </script>

    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <div class="chaxun">
            <div class="input-group" style="float: left; width: 20%;margin-right: 10px;margin-left: 20%">
                <input type="text" name="starttime" class="form-control" value="{$starttime}" placeholder="起始时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div class="input-group" style="float: left;width: 20%; margin-right: 10px;">
                <input type="text" name="endtime" class="form-control" value="{$endtime}" placeholder="结束时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div style="float: left; line-height: 2em; text-align: center;"><a href="javascript:time()">查询</a></div>
        </div>
        <div style="clear: both;"></div>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th>总金额</th>
                <th>总数量</th>
                <th>总手续费</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{$data_record.money}</td>
                    <td>{$data_record.num}</td>
                    <td>{$data_record.poundage}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" class="id" value="{$id}"/>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <div style=" margin-bottom: 20px;">
            <form method="post" enctype="multipart/form-data" action="" onsubmit="">
				<div class="row">
					<div class="col-md-2">
						<div class="input-group">
							<div class="form-group-addon no-right-border">单价</div>
							<div class="input-group">
								<input type="text" name="money" value="{$money}" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group" >
							<div class="form-group-addon no-right-border">类型</div>
							<div class="input-group">
								<select name="type" id="type" class="form-control" style=" border:none;">
									<if condition="$type eq ''">
										<option value="">全部</option>
										<option value="1">买入</option>
										<option value="2">卖出</option>
										<elseif condition="$type eq 1" />
										<option value="1">买入</option>
										<option value="">全部</option>
										<option value="2">卖出</option>
										<elseif condition="$type eq 2" />
										<option value="2">卖出</option>
										<option value="">全部</option>
										<option value="1">买入</option>
									</if>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<div class="form-group-addon no-right-border">开始</div>
							<div class="form-group-addon form-group-addon-white stretch">
								<input value="" type="text" id="start" name="start_time" class="form-control" placeholder="请选择玩家新注册的开始时间">
							</div>
						</div>
					</div>
					<div class="col-md-3"  >
						<div class="form-group">
							<div class="form-group-addon no-right-border">结束</div>
							<div class="form-group-addon form-group-addon-white stretch">
								<input value=""   type="text" id="end" name="end_time" class="form-control" placeholder="请选择玩家新注册的结束时间">
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
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
				<th>提交数量</th>
				<th>剩余数量</th>
				<th>单价</th>
				<th>果实</th>
				<th>挂单类型</th>
				<th>交易状态</th>
				<th>撤销方式</th>
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
                <volist name="data" id="vo">
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
						<if condition="$vo['system'] eq '1'">
							<td style="color: gray;">系统</td>	
							<else/>
							<td></td>
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
        </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state eq 0">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('Fruit/find_fruit',array('o'=>$o-1,'money'=>$money,'id'=>$id,'type'=>$type,'start_time'=>$start_time,'end_time'=>$end_time,'starttime'=>$starttime,'endtime'=>$endtime))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Fruit/find_fruit',array('o'=>$o-1,'money'=>$money,'id'=>$id,'type'=>$type,'start_time'=>$start_time,'end_time'=>$end_time,'starttime'=>$starttime,'endtime'=>$endtime))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Fruit/find_fruit',array('o'=>$i,'money'=>$money,'id'=>$id,'type'=>$type,'start_time'=>$start_time,'end_time'=>$end_time,'starttime'=>$starttime,'endtime'=>$endtime))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Fruit/find_fruit',array('o'=>$o+1,'money'=>$money,'id'=>$id,'type'=>$type,'start_time'=>$start_time,'end_time'=>$end_time,'starttime'=>$starttime,'endtime'=>$endtime))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
	
    </div>
    <div style="clear: both;"></div>
	<script>
		
		
		var start = {
		  elem: '#start',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59', //设定最小日期为当前日期
		  max: '2099-06-16 23:59:59', //最大日期
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			 end.min = datas; //开始日选好后，重置结束日的最小日期
			 end.start = datas //将结束日的初始值设定为开始日
		  }
		};
		var end = {
		  elem: '#end',
		  format: 'YYYY/MM/DD hh:mm:ss',
		  min: '1099-06-16 23:59:59',
		  max: '2099-06-16 23:59:59',
		  istime: true,
		  istoday: false,
		  choose: function(datas){
			start.max = datas; //结束日选好后，重置开始日的最大日期
		  }
		};
		laydate(start);
		laydate(end);
	</script>
    <script type="text/javascript">

        function time(){
            var id=$('.id').val();
            var s=$("input[name='starttime']").val();
            var e=$("input[name='endtime']").val();
//alert('__URL__/id/'+id+'/e/'+e+'/s/'+s);
            if(s.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                if(e.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                   
					window.location="{:U('Fruit/find_fruit')}?endtime="+e+'&starttime='+s+'&id='+id;
                } else {
                    alert('结束日期格式错误，请重新输入！');
                    $("input[name='endtime']").val('');
                }
            } else {
                alert('开始日期格式错误，请重新输入！');
                $("input[name='starttime']").val('')
            }
        }
    </script>
</block>