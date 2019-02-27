<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <input type="hidden" class="id" value="{$id}"/>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>添加商品</h1>
        <div style="margin-left: 20%; margin-bottom: 20px;">
            <form method="post" action="{:U('Goods/items_add')}" enctype="Rebate/set">
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>商  品  名</lbale><input type="text" name="name" id="name" class="form-control" placeholder="输入商品名" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>价     格</lbale><input type="text" name="price" id="price" class="form-control" placeholder="输入金额" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 10%; float: left;margin-left: 10px;">
                    <lbale>单     位</lbale>
                    <select name="buy">
                        <volist name="data" id="val">
                            <option value="宝石">宝石</option>
                            <option value="{$val.varieties}">{$val.varieties}</option>
                        </volist>
                    </select>
                </div>
                <hr/>
                <hr/>
                <hr/>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>数     量</lbale><input type="text" name="num" id="num" class="form-control" placeholder="输入数量" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>注     释</lbale><input style="width:400px;" type="text" name="note" id="note" class="form-control" placeholder="输入注释" aria-describedby="basic-addon1">
                </div>
                <hr/>
                <hr/>
                <hr/>
                <div class="input-group" style="margin-left:10px;" id="group" aria-label="...">
                    <button id="btn" type="submit" class="btn btn-default">设置</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>


        <div style="clear: both;"></div>
        <if condition="$state eq 1">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o-1,'id'=>$id))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$o eq $i">
                            <li><a class="active_na" style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o+1,'id'=>$id))}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <else/>
        </if>
    </div>
    <div style="clear: both;"></div>
    <script type="text/javascript" src="js/echarts-all-3.js"></script>

    <script type="text/javascript">
        var dom = document.getElementById("container");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;


        var colorList = ['#c23531','#2f4554', '#61a0a8', '#d48265', '#91c7ae','#749f83',  '#ca8622', '#bda29a','#6e7074', '#546570', '#c4ccd3'];
        var labelFont = 'bold 12px Sans-serif';

        function calculateMA(dayCount, data) {
            var result = [];
            for (var i = 0, len = data.length; i < len; i++) {
                if (i < dayCount) {
                    result.push('-');
                    continue;
                }
                var sum = 0;
                for (var j = 0; j < dayCount; j++) {
                    sum += data[i - j][1];
                }
                result.push((sum / dayCount).toFixed(2));
            }
            return result;
        }


        var dates = <?php echo $str ;?>;
        var data = <?php echo $str_2 ;?>;
        var volumns = <?php echo $str_3 ;?>;

        var dataMA5 = calculateMA(5, data);
        var dataMA10 = calculateMA(10, data);
        var dataMA20 = calculateMA(20, data);


        option = {
            animation: false,
            color: colorList,
            title: {
                left: 'center',
                text: '移动端 K线图'
            },
            legend: {
                top: 30,
                data: ['日K', 'MA5', 'MA10', 'MA20', 'MA30']
            },
            tooltip: {
                triggerOn: 'none',
                transitionDuration: 0,
                confine: true,
                bordeRadius: 4,
                borderWidth: 1,
                borderColor: '#333',
                backgroundColor: 'rgba(255,255,255,0.9)',
                textStyle: {
                    fontSize: 12,
                    color: '#333'
                },
                position: function (pos, params, el, elRect, size) {
                    var obj = {
                        top: 60
                    };
                    obj[['left', 'right'][+(pos[0] < size.viewSize[0] / 2)]] = 5;
                    return obj;
                }
            },
            axisPointer: {
                link: [{
                    xAxisIndex: [0, 1]
                }]
            },
            dataZoom: [{
                type: 'slider',
                xAxisIndex: [0, 1],
                realtime: false,
                start: 20,
                end: 70,
                top: 65,
                height: 20,
                handleIcon: 'M10.7,11.9H9.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4h1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                handleSize: '120%'
            }, {
                type: 'inside',
                xAxisIndex: [0, 1],
                start: 40,
                end: 70,
                top: 30,
                height: 20
            }],
            xAxis: [{
                type: 'category',
                data: dates,
                boundaryGap : false,
                axisLine: { lineStyle: { color: '#777' } },
                axisLabel: {
                    formatter: function (value) {
                        return echarts.format.formatTime('MM-dd', value);
                    }
                },
                min: 'dataMin',
                max: 'dataMax',
                axisPointer: {
                    show: true
                }
            }, {
                type: 'category',
                gridIndex: 1,
                data: dates,
                scale: true,
                boundaryGap : false,
                splitLine: {show: false},
                axisLabel: {show: false},
                axisTick: {show: false},
                axisLine: { lineStyle: { color: '#777' } },
                splitNumber: 20,
                min: 'dataMin',
                max: 'dataMax',
                axisPointer: {
                    type: 'shadow',
                    label: {show: false},
                    triggerTooltip: true,
                    handle: {
                        show: true,
                        margin: 30,
                        color: '#B80C00'
                    }
                }
            }],
            yAxis: [{
                scale: true,
                splitNumber: 2,
                axisLine: { lineStyle: { color: '#777' } },
                splitLine: { show: true },
                axisTick: { show: false },
                axisLabel: {
                    inside: true,
                    formatter: '{value}\n'
                }
            }, {
                scale: true,
                gridIndex: 1,
                splitNumber: 2,
                axisLabel: {show: false},
                axisLine: {show: false},
                axisTick: {show: false},
                splitLine: {show: false}
            }],
            grid: [{
                left: 20,
                right: 20,
                top: 110,
                height: 120
            }, {
                left: 20,
                right: 20,
                height: 40,
                top: 260
            }],
            graphic: [{
                type: 'group',
                left: 'center',
                top: 70,
                width: 300,
                bounding: 'raw',
                children: [{
                    id: 'MA5',
                    type: 'text',
                    style: {fill: colorList[1], font: labelFont},
                    left: 0
                }, {
                    id: 'MA10',
                    type: 'text',
                    style: {fill: colorList[2], font: labelFont},
                    left: 'center'
                }, {
                    id: 'MA20',
                    type: 'text',
                    style: {fill: colorList[3], font: labelFont},
                    right: 0
                }]
            }],
            series: [{
                name: 'Volumn',
                type: 'bar',
                xAxisIndex: 1,
                yAxisIndex: 1,
                itemStyle: {
                    normal: {
                        color: '#7fbe9e'
                    },
                    emphasis: {
                        color: '#140'
                    }
                },
                data: volumns
            }, {
                type: 'candlestick',
                name: '日K',
                data: data,
                itemStyle: {
                    normal: {
                        color: '#ef232a',
                        color0: '#14b143',
                        borderColor: '#ef232a',
                        borderColor0: '#14b143'
                    },
                    emphasis: {
                        color: 'black',
                        color0: '#444',
                        borderColor: 'black',
                        borderColor0: '#444'
                    }
                }
            }, {
                name: 'MA5',
                type: 'line',
                data: dataMA5,
                smooth: true,
                showSymbol: false,
                lineStyle: {
                    normal: {
                        width: 1
                    }
                }
            }, {
                name: 'MA10',
                type: 'line',
                data: dataMA10,
                smooth: true,
                showSymbol: false,
                lineStyle: {
                    normal: {
                        width: 1
                    }
                }
            }, {
                name: 'MA20',
                type: 'line',
                data: dataMA20,
                smooth: true,
                showSymbol: false,
                lineStyle: {
                    normal: {
                        width: 1
                    }
                }
            }]
        };
        ;
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }
        function time(){
            var id=$('.id').val();
            var s=$("input[name='start_time']").val();
            var e=$("input[name='end_time']").val();
//alert('__URL__/id/'+id+'/e/'+e+'/s/'+s);
            if(s.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                if(e.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                    window.location='__URL__/find_fruit/id/'+id+'/e/'+e+'/s/'+s;
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