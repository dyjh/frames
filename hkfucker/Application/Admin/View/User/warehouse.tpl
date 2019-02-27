<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="{:U('User/warehouse')}" method="post">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
        <div>
            <div style="float: left;">
                 <a href="{:U('User/warehouse_add')}" class="btn btn-default" style="margin-right: 20px;background: #ffffff;color: #3c8dbc;">
                    添加道具</a></div>
            <div class="input-group" style="float: left; width: 23%;">
                <input type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>
            <div style="float: left;">
                <button type="submit" class="btn btn-default">查询</button></div>
        </div>
            <span style="margin-left: 20px;color: #FF0000">注意:查询功能的只适用于用户条件来查询</span>
            </form>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>道具</th>
                <th>数量</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>


                <if condition="$state eq 0">
                    <td colspan="9" align="center">
                        <p style="padding: 15px;">暂无数据信息</p>
                    </td>
                    <else />
                    <volist name="ware_list" id="vo">
                        <tr>
                            <td>{$vo.user}</td>
                            <td>{$vo.props}</td>
                            <td>{$vo.num}</td>
                            <td><a href="{:U('User/warehouse_edit',array('user'=>$vo['user'],'props'=>$vo['props'],'num'=>$vo['num']))}">修改</a> <a href="javascript:del(id={$vo.user},props='{$vo.props}',num={$vo.num});">删除</a></td>
                        </tr>
                    </volist>
                </if>

            </tbody>


        </table>
        <if condition="$state eq 0">
            <else />
            <nav aria-label="Page navigation">
                <ul class="pagination" ">
                <li>
                    <a href="{:U('User/warehouse',array('p'=>$p-1))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('User/warehouse',array('p'=>$p-1))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/warehouse',array('p'=>$i))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/warehouse',array('p'=>$p+1))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
                </ul>
            </nav>
        </if>

    </div>
    <script>
        function del(id,props,num){
            if(confirm('确定删除吗？')){
                location.href="__URL__/warehouse_del/user/"+id+"/props/"+props+"/num/"+num;
            }
        }
    </script>
</block>