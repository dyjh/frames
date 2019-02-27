<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>站点统计</h1>
    </div>
    <div style="clear: both;"></div>
    <div style="clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <div class="chaxun">
            <div class="input-group" style="float: left; width: 20%;margin-right: 10px;margin-left: 20%">
                <input type="text" name="start_time" class="form-control" value="{$start_s}" placeholder="起始时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div class="input-group" style="float: left; width: 5%;margin-right: 10px;">
                <input type="text" name="start_hour" class="form-control" value="{$s_h}" placeholder="小时:21" aria-describedby="basic-addon1">
            </div>
            <div class="input-group" style="float: left;width: 20%; margin-right: 10px;">
                <input type="text" name="end_time" class="form-control" value="{$end_s}" placeholder="结束时间:2017-01-01" aria-describedby="basic-addon1">
            </div>
            <div class="input-group" style="float: left; width: 5%;margin-right: 10px;">
                <input type="text" name="end_hour" class="form-control" value="{$e_h}" placeholder="小时:21" aria-describedby="basic-addon1">
            </div>
            <div style="float: left; line-height: 2em; text-align: center;"><a href="javascript:time()">查询</a></div>
        </div>
        <div style="clear: both;"></div>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
            <thead>
            <tr>
                <th style="text-align: center;">用户ID</th>
                <th style="text-align: center;">充钻总额</th>
            </tr>
            </thead>
            <tbody>
            <volist name="data" id="val">
                <tr>
                    <td align="center">{$val.num_id}</td>
                    <td align="center">{$val.diamond}</td>
                </tr>
            </volist>
            </tbody>
        </table>
        <if condition="$state eq 0">
            <else/>
            <nav aria-label="Page navigation">
                <ul class="pagination" ">
                <li>
                    <a href="{:U('Statistics/diamond',array('p'=>$p-1,'h'=>$h,'e'=>$e))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('Statistics/diamond',array('p'=>$p-1,'h'=>$h,'e'=>$e))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Statistics/diamond',array('p'=>$i,'h'=>$h,'e'=>$e))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Statistics/diamond',array('p'=>$p+1,'h'=>$h,'e'=>$e))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
                </ul>
            </nav>
        </if>
    </div>
    <script type="text/javascript">

        function time(){
            var id=$('.id').val();
            var s=$("input[name='start_time']").val();
            var e=$("input[name='end_time']").val();
            var e_2=$("input[name='end_hour']").val();
            var s_2=$("input[name='start_hour']").val();
//alert('__URL__/id/'+id+'/e/'+e+'/s/'+s);
            if(s.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                if(e.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                    s+='-';
                    s+=s_2;
                    e+='-';
                    e+=e_2;
                    window.location="{:U('Statistics/diamond')}?e="+e+'&h='+s;
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