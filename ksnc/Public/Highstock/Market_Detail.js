/**
 * Created by QHP on 2017/6/7.
 * http://blog.csdn.net/xiaojiang0829/article/details/28265833
 */
var  intFormatFloat = 4;

function GetStartTime(datetime) {
    var dt = new Date(datetime);
    dt.setHours(9);
    dt.setMinutes(0);
    dt.setSeconds(0);
    return dt.getTime();
}

function GetEndTime(datetime) {
    var dt = new Date(datetime);
    dt.setHours(18);
    dt.setMinutes(0);
    dt.setSeconds(0);
    return dt.getTime();
}

function Fill(data, summary, sysdatetime) {

    //从默认开始时间到当前服务器时间段的数据
    var result = [];


    if (data.length > 0) {
        //如果有分时数据
        var length = data.length
        for (i = 0; i < length; i++) {
            var currenttime = data[i].Time * 1000;
            var currentprice = data[i].Price;
            var currentvolume = data[i].Volume;
            var lasttime, lastprice;
            if (i == 0) //第一条数据
            {
                lasttime = GetStartTime(currenttime);
                lastprice = summary.OpenPrice;
                //第一条的时间大于默认开始时间 则补齐默认时间到开始时间的数据
                if (currenttime > GetStartTime(currenttime)) {
                    for (t = GetStartTime(currenttime) ; t < currenttime; t = t + 60000) {
                        result.push([t, lastprice, 0]);
                    }
                }
                result.push([currenttime, currentprice, currentvolume]);

            }
            else //从第二条数据开始
            {
                lasttime = data[i - 1].Time * 1000;
                lastprice = data[i - 1].Price;
                //如果时间差大于一分钟
                if (currenttime - lasttime > 60000) {
                    for (t = lasttime + 60000; t < currenttime; t = t + 60000) {
                        result.push([t, lastprice, 0]);
                    }
                }
                result.push([currenttime, currentprice, currentvolume]);
            }
        }

        var lastitem = result[result.length - 1];
        var lastitemtime = lastitem[0];
        var lastitemprice = lastitem[1];



        //补齐数据
        if (GetStartTime(lastitemtime) == GetStartTime(sysdatetime)) { //当天
            //补齐最后一条数据时间到当前时间的数据
            for (t = lastitemtime + 60000; t <= sysdatetime ; t = t + 60000) {
                result.push([t, lastitemprice, 0]);
            }
            //补齐当前时间到当天收盘时间的空数据 （让分时图时间轴显示的时间区间是整个交易时间）
            var len = result.length;
            for (t = result[len - 1][0] + 60000; t <= GetEndTime(sysdatetime) ; t = t + 60000) {
                result.push([t, null, null]);
            }
        } else {  //不是当天
            //补齐最后一条数据时间到收盘时间的数据
            for (t = lastitemtime + 60000; t <= GetEndTime(lastitemtime) ; t = t + 60000) {
                result.push([t, null, null]);
            }
        }

    } else {
        //如果没有分时数据
        if (isOpen) {   //开盘
            //开盘价到当前时间的数据
            for (t = GetStartTime(sysdatetime) ; t <= sysdatetime; t = t + 60000) {
                result.push([t, summary.OpenPrice, 0]);
            }
            //补齐当前时间到当天收盘时间的空数据（让分时图时间轴显示的时间区间是整个交易时间）
            var len = result.length;
            for (t = result[len - 1][0] + 60000; t <= GetEndTime(sysdatetime) ; t = t + 60000) {
                result.push([t, null, null]);
            }
        } else { //没开盘
            $("#loading").html("暂未开放交易").css("color", "#FF0000").css("font-size", "12px");;
        }
    }

    return result;
}

function Fill_Five_Min(data, summary, sysdatetime) {

    //从默认开始时间到当前服务器时间段的数据
    var result = [];

    if (data.length > 0) {
        //如果有分时数据
        var length = data.length
        for (i = 0; i < length; i++) {
            var currenttime = data[i].Time * 1000;
            var currentprice = data[i].Price;
            var currentvolume = data[i].Volume;
            var lasttime, lastprice;
            if (i == 0) //第一条数据
            {
                lasttime = GetStartTime(currenttime);
                lastprice = summary.OpenPrice;
                //第一条的时间大于默认开始时间 则补齐默认时间到开始时间的数据
                if (currenttime > GetStartTime(currenttime)) {
                    for (t = GetStartTime(currenttime) ; t < currenttime; t = t + 60000 * 5) {
                        result.push([t, lastprice, 0]);
                    }
                }
                result.push([currenttime, currentprice, currentvolume]);

            }
            else //从第二条数据开始
            {
                lasttime = data[i - 1].Time * 1000;
                lastprice = data[i - 1].Price;
                //如果时间差大于一分钟
                if (currenttime - lasttime > 60000 * 5) {
                    for (t = lasttime + 60000 * 5; t < currenttime; t = t + 60000 * 5) {
                        result.push([t, lastprice, 0]);
                    }
                }
                result.push([currenttime, currentprice, currentvolume]);
            }
        }

        var lastitem = result[result.length - 1];
        var lastitemtime = lastitem[0];
        var lastitemprice = lastitem[1];



        //补齐数据
        if (GetStartTime(lastitemtime) == GetStartTime(sysdatetime)) { //当天
            //补齐最后一条数据时间到当前时间的数据
            for (t = lastitemtime + 60000 * 5; t <= sysdatetime ; t = t + 60000 * 5) {
                result.push([t, lastitemprice, 0]);
            }
            //补齐当前时间到当天收盘时间的空数据 （让分时图时间轴显示的时间区间是整个交易时间）
            var len = result.length;
            for (t = result[len - 1][0] + 60000 * 5; t <= GetEndTime(sysdatetime) ; t = t + 60000 * 5) {
                result.push([t, null, null]);
            }
        } else {  //不是当天
            //补齐最后一条数据时间到收盘时间的数据
            for (t = lastitemtime + 60000 * 5; t <= GetEndTime(lastitemtime) ; t = t + 60000 * 5) {
                result.push([t, null, null]);
            }
        }

    } else {
        //如果没有分时数据
        if (isOpen) {   //开盘
            //开盘价到当前时间的数据
            for (t = GetStartTime(sysdatetime) ; t <= sysdatetime; t = t + 60000 * 5) {
                result.push([t, summary.OpenPrice, 0]);
            }
            //补齐当前时间到当天收盘时间的空数据（让分时图时间轴显示的时间区间是整个交易时间）
            var len = result.length;
            for (t = result[len - 1][0] + 60000 * 5; t <= GetEndTime(sysdatetime) ; t = t + 60000 * 5) {
                result.push([t, null, null]);
            }
        } else { //没开盘
            $("#loading").html("暂未开放交易").css("color", "#FF0000").css("font-size", "12px");;
        }
    }

    return result;
}

function Minute(code,url) {

    $(".Highcharts").hide();
    $(".kline-ma").hide();
    $("#kline").show();

    $.ajax({
        url: url,
        data:{procode:code},
        type: "Post",
        dataType: 'json',
        success: function (result) {

            var summary = result.data.MarketInfo;
            var timeshare = result.data.TimeShare;
            var sysdatetime = result.data.SysDT * 1000;
			var intFormatFloat = 5;
            //分时区间
            var fuckMin = summary.LimitDown;
            var fuckMax = summary.LimitUp;

            var data = Fill_Five_Min(timeshare, summary, sysdatetime);

            Highcharts.theme = {
                xAxis: {
                    gridLineColor: '#E7F2F8',
                    gridLineWidth: 1,
                },
                global: {
                    useUTC: false
                }

            };
            Highcharts.setOptions(Highcharts.theme);

            var prices = [];
            var volumes = [];

            for (i=0; i < data.length; i++) {

                prices.push([
                    data[i][0], // the date
                    parseFloat(data[i][1]), // price
                ]);

                volumes.push([
                    data[i][0], // the date
                    parseFloat(data[i][2]) // the volume
                ]);

            }


            // create the chart
            $('#kline').highcharts('StockChart', {
                chart: {
                    plotBorderColor: '#E7F2F8',
                    plotBorderWidth: 0,

                },

                tooltip: {
                    formatter: function() {
                        if(this.y == undefined){
                            return;
                        }

                        var price = this.points[0].point.y.toFixed(intFormatFloat);
                        var volume = this.points[1] ? this.points[1].point.y : 0;
                        var date = Highcharts.dateFormat('%Y-%m-%d', this.x);
                        var time = Highcharts.dateFormat('%H:%M', this.x);

                        if(volume > 10000*10000){
                            volume = (volume * 0.0001 * 0.0001).toFixed(intFormatFloat) + "亿";
                        } else if (volume > 10000) {
                            volume = (volume * 0.0001).toFixed(intFormatFloat) + "万";
                        }

                        var tip = '日期：' + date + '<br/>';
                        tip = tip +  '时间：'+ time + '<br/>';
                        tip = tip + '价格：' + price + '<br/>';
                        tip = tip + '成交：' + volume + '<br/>';

                        return tip;
                    }
                },

                credits: {
                    enabled: false
                },
                title: {
                    enabled: false
                },
                subtitle: {
                    enabled: false
                },

                yAxis: {
                    gapGridLineWidth: 1,
                    gridLineColor: '#E7F2F8',
                },

                exporting: {
                    enabled: false
                },
                scrollbar: {
                    enabled: false
                },
                navigator: {
                    enabled: false
                },
                rangeSelector: {
                    enabled: false
                },

                yAxis: [
                    {

                        height: '80%',
                        lineWidth: 1,
                        gapGridLineWidth: 1,
                        gridLineColor: '#E7F2F8',
                        min: fuckMin,
                        max: fuckMax,
                    }, {

                        top: '85%',
                        height: '15%',
                        offset: 0,
                        lineWidth: 1,
                        gapGridLineWidth: 1,
                        gridLineColor: '#E7F2F8',
                    }],

                series: [{
                    type: 'area',
                    name: 'prices',
                    data: prices,
                    lineWidth:1,
                    gapSize: 5,
                    tooltip: {
                        valueDecimals: 4
                    },
                    fillColor : {
                        linearGradient : {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops : [
                            [0, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0.3).get('rgba')]
                        ]
                    },

                    dataGrouping:{
                        enabled:false
                    },
                    animation: false


                }, {
                    gapSize: 5,
                    type: 'column',
                    name: 'Volume',
                    data: volumes,
                    yAxis: 1,
                    color: '#7CB5EC',
                    dataGrouping:{
                        enabled:false
                    },
                    animation: false


                }]
            });




        },
		
		
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert("获取数据异常，请稍候再试！")
        }
    });
}

function Minute_new(code,url) {

    $(".Highcharts").hide();
    $(".kline-ma").hide();
    $("#kline").show();

    $.ajax({
        url: url,
        data:{procode:code},
        type: "Post",
        dataType: 'json',
        success: function (result) {

            var summary = result.data.MarketInfo;
            var timeshare = result.data.TimeShare;
            var sysdatetime = result.data.SysDT * 1000;	
			var intFormatFloat = 5;
            //分时区间
            var fuckMin = summary.LimitDown;
            var fuckMax = summary.LimitUp;

            var data = Fill_Five_Min(timeshare, summary, sysdatetime);

            Highcharts.theme = {
                xAxis: {
                    gridLineColor: '#E7F2F8',
                    gridLineWidth: 1,
                },
                global: {
                    useUTC: false
                }

            };
            Highcharts.setOptions(Highcharts.theme);

            var prices = [];
            var volumes = [];

            // for (i=0; i < data.length; i++) {
            for (i=0; i < 20; i++) {

                prices.push([
                    data[i][0], // the date
                    parseFloat(data[i][1])+0.0001, // price
                ]);

                volumes.push([
                    data[i][0], // the date
                    parseFloat(data[i][2]) // the volume
                ]);

            }

        data = [
            // [1500771600000,0.0012000000000000001],
            [1500771604000,0.0012000000000000001],
            [1500771900000,0.0001],
            [1500772200000,0.0001],
            [1500772500000,0.0001],
            [1500772800000,0.0001],
            [1500773100000,0.00015],
            [1500773400000,0.0001],
            [1500773700000,0.0001],
            [1500774000000,0.00055],
            // [1500692700000,0],
            // [1500693000000,0],
        ];
		// alert(prices);
		console.log(prices);
		console.log(data);
            // create the chart
       $('#kline').highcharts('StockChart', {
                chart: {
                    plotBorderColor: '#E7F2F8',
                    plotBorderWidth: 0,

                },
                tooltip: {
                    formatter: function() {
                        if(this.y == undefined){
                            return;
                        }

                        var price = this.points[0].point.y.toFixed(intFormatFloat);
                        var volume = this.points[1] ? this.points[1].point.y : 0;
                        var date = Highcharts.dateFormat('%Y-%m-%d', this.x);
                        var time = Highcharts.dateFormat('%H:%M', this.x);

                        if(volume > 10000*10000){
                            volume = (volume * 0.0001 * 0.0001).toFixed(intFormatFloat) + "亿";
                        } else if (volume > 10000) {
                            volume = (volume * 0.0001).toFixed(intFormatFloat) + "万";
                        }

                        var tip = '日期：' + date + '<br/>';
                        tip = tip +  '时间：'+ time + '<br/>';
                        tip = tip + '价格：' + price + '<br/>';
                        tip = tip + '成交：' + volume + '<br/>';

                        return tip;
                    }
                },
                credits: {
                    enabled: false
                },
                title: {
                    enabled: false
                },
                subtitle: {
                    enabled: false
                },
                yAxis: {
                    gapGridLineWidth: 1,
                    gridLineColor: '#E7F2F8',
                },
                exporting: {
                    enabled: false
                },
                scrollbar: {
                    enabled: false
                },
                navigator: {
                    enabled: false
                },
                rangeSelector: {
                    enabled: false
                },
				yAxis: [
                    {
                        height: '80%',
                        lineWidth: 1,
                        gapGridLineWidth: 1,
                        gridLineColor: '#E7F2F8',
                        min: 0,
                        max: fuckMax,
                    }, {
                        top: '85%',
                        height: '15%',
                        offset: 0,
                        lineWidth: 1,
                        gapGridLineWidth: 1,
                        gridLineColor: '#E7F2F8',
                    }],
				series : [{
                    type: 'area',
                    name: 'prices',
                    data: prices,
                    lineWidth:1,
                    gapSize: 5,
                    tooltip: {
                        valueDecimals: 4
                    },
                    fillColor : {
                        linearGradient : {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops : [
                            [0, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0.3).get('rgba')]
                        ]
                    },

                    dataGrouping:{
                        enabled:false
                    },
                    animation: false


                }, {
                    gapSize: 5,
                    type: 'column',
                    name: 'Volume',
                    data: volumes,
                    yAxis: 1,
                    color: '#7CB5EC',
                    dataGrouping:{
                        enabled:false
                    },
                    animation: false


                }]

		 });
		
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert("获取数据异常，请稍候再试！")
        }
    });
}


function KLine(url,code){

    $(".Highcharts").hide();
    $("#container").show();

    Highcharts.setOptions({
        lang: {
            rangeSelectorZoom: ''
        }
    });

    $.get(url,{procode:code}, function (data) {

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
                rangeSelector: {
                    enabled: false,
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

}