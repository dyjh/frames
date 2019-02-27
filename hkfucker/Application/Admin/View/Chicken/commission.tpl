<extend name="Public:base" />
<block name="content">
    <script src="__PUBLICHOME__/laydate/laydate.js" ></script>
    <div class="page-header">
        <h1>牧场返佣</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
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
                <th>来源</th>
                <th>返佣类型</th>
                <th>金额</th>
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
                        <td>{$vo.source}</td>
                        <if condition="$vo['type'] eq '1'">
                            <td>静态资金</td>
                            <else/>
                            <td>动态资金</td>
                        </if>
                        <td>{$vo.money}</td>
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
                        <a href="{:U('Chicken/commission',array('o'=>$o-1,'user'=>$user,'start_time'=>$start_time,'end_time'=>$end_time))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$o eq $i">
                            <li class="active">
                                <a href="{:U('Chicken/commission',array('o'=>$o-1,'user'=>$user,'start_time'=>$start_time,'end_time'=>$end_time))}">{$i}</a>
                            </li>
                            <else/>
                            <li>
                                <a href="{:U('Chicken/commission',array('o'=>$i,'user'=>$user,'start_time'=>$start_time,'end_time'=>$end_time))}">{$i}</a>
                            </li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Chicken/commission',array('o'=>$o+1,'user'=>$user,'start_time'=>$start_time,'end_time'=>$end_time))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                    </li>
                </ul>
            </nav>
        </if>

    </div>
    <div class="clear"></div>
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
</block>