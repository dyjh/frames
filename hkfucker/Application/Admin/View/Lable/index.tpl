<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>标签管理<small><a href="{:U('Lable/add')}">添加</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>标签名称</th>
                <th>可否使用</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 1">
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.id}</td>
                        <td>{$val.label_name}</td>
                        <if condition="$val['label_status'] eq 0">
                            <td>是</td>
                            <else/>
                            <td>否</td>
                        </if>
                        <td><a href="{:U('Lable/edit',array('id'=>$val['id']))}">修改</a>
                            <a href="javascript:del({$val.id})">删除</a>
                        </td>
                    </tr>
                </volist>
                <else/>
                <tr>
                    <td colspan="6" align="center">
                        <p style="padding: 15px;">暂无数据信息</p>
                    </td>
                </tr>
            </if>
            </tbody>
        </table>
        <if condition="$state eq 1">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Lable/index',array('k'=>$k-1))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$k eq $i">
                            <li><a style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Lable/index',array('k'=>$i))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Lable/index',array('k'=>$i))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Lable/index',array('k'=>$k+1))}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <else/>
        </if>
    </div>
    <div class="clear"></div>
    <script>
        function del(id){
            if(confirm('确定要删除吗？')){
                $.post("{:U('Lable/del')}",{id:id},function(msg){
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