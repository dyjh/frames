<extend name="Public:base" />
<block name="content">

    <div class="page-header">
        <h1>图片管理<small><a href="{:U('Image/add')}">图片添加</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%; ">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th width="5%" align="center">编号</th>
                <th width="45%" style="text-align: center">模块</th>
                <th width="40%" style="text-align: center">图片</th>
                <th width="10%" style="text-align: center">操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 1">
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td width="5%" style="text-align: center">{$val.id}</td>
                        <td width="45%" align="center">{$val.ch}</td>
                        <td width="40%" align="center"><img src="{$val.url}" width="100px" height="60px"/></td>
                        <td width="10%" align="center">
                            <a href="javascript:del({$val.id})">删除</a>
                            <a href="{:U('Image/edit',array('id'=>$val['id']))}">修改</a>
                        </td>
                    </tr>
                </volist>
                <else/>
                <td colspan="4" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
            </if>
            </tbody>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state eq 1">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Image/index',array('p'=>$p-1))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$p eq $i">
                            <li class="active"><a href="{:U('Image/index',array('p'=>$i))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Image/index',array('p'=>$i))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Image/index',array('p'=>$p+1))}" aria-label="Next">
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
        function del(id) {
            if(confirm('确定要删除吗？')){
                $.post("{:U('Image/del')}",{id:id},function(msg){
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