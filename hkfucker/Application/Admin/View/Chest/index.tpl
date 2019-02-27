<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>宝箱主页<small><a href="{:U('Chest/add')}">宝箱添加</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>宝箱名称</th>
                <th>中奖获得果实（不含倍数）</th>
                <th>果实数量</th>
                <th>中奖几率</th>
                <th>每天中奖人数</th>
                <th>倍率</th>
                <th>未中奖所得果实（不含倍数）</th>
                <th>果实数量</th>  
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 1">
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.id}</td>
                        <td>{$val.name}</td>
                        <td>{$val.seed}</td>
                        <td>{$val.seed_num}</td>
                        <td>{$val.chance}%</td>
                        <td>{$val.number_max}</td>
                        <td>{$val.multiple}</td>
                        <td>{$val.gift}</td>
                        <td>{$val.gift_num}</td>
                        <td><a href="{:U('Chest/edit',array('id'=>$val['id']))}">修改</a>
                            <a href="javascript:del({$val.id})">删除</a>
                        </td>
                    </tr>
                </volist>
                <else/>
                <td colspan="6" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
            </if>
            </tbody>
        </table>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%; width: 40%; float: left;">
        <span>开箱果实信息</span>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
        <thead>
        <tr>
            <th>果实</th>
            <th>果实数量</th>
        </tr>
        </thead>
        <tbody>
        <if condition="$state_o eq 1">
            <volist name="open" id="val">
                <tr>
                    <td>{$val.seed}</td>
                    <td>{$val.num}</td>
                </tr>
            </volist>
            <else/>
            <td colspan="2" align="center">
                <p style="padding: 15px;">暂无数据信息</p>
            </td>
        </if>.
        </table>
        <div style="clear: both;"></div>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%; width: 40%; float: left">
        <span>买箱果实信息</span>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>果实</th>
                <th>果实数量</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state_b eq 1">
                <volist name="buy" id="val">
                    <tr>
                        <td>{$val.seed}</td>
                        <td>{$val.num}</td>
                    </tr>
                </volist>
                <else/>
                <td colspan="2" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
            </if>.
        </table>
        <div style="clear: both;"></div>
    </div>
    <div class="clear"></div>
    <script>
        function del(id){
            if(confirm('确定要删除吗？')){
                $.post("{:U('Chest/del')}",{id:id},function(msg){
                    if(msg == 0){
                        alert('删除失败');
                    }else if(msg == -1){
                        alert('ajax请求错误');
                    }else if(msg == 1){
                        alert('删除成功');
                        $('#tr'+id).remove();

                    }
                })
            }
        }
    </script>
</block>