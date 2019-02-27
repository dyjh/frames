<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>鸡舍购买记录</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('Chicken/house_record')}" method="post">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div>
                <div class="input-group" style="float: left; width: 23%;">
                    <input type="text" name="user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
                </div>
                <div style="float: left;">
                    <button type="submit" class="btn btn-default">查询</button></div>
            </div>
        </form>
    </div>

    <div style="width: 100%; margin-top: 10px; clear: both;"></div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>购买时间</th>
                <th>维护日期</th>
                <th>类型</th>
                <th>到期时间</th>
                <th>房屋名字</th>
                <th>花费果实</th>
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
                        <td>{$vo.buy_time}</td>
                        <td>{$vo.maintenance_day}</td>
                        <if condition="$vo['systems_give'] eq '1'">
                            <td>购买</td>
                            <else/>
                            <td>赠送</td>
                        </if>
                        <td>{$vo.end_time}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.cost_fruit}</td>
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
                        <a href="{:U('Chicken/house_record',array('o'=>$o-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$o eq $i">
                            <li class="active">
                                <a href="{:U('Chicken/house_record',array('o'=>$i))}">{$i}</a>
                            </li>
                            <else/>
                            <li>
                                <a href="{:U('Chicken/house_record',array('o'=>$i))}">{$i}</a>
                            </li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Chicken/house_record',array('o'=>$o+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                    </li>
                </ul>
            </nav>
        </if>
    </div>
    <div class="clear"></div>
</block>