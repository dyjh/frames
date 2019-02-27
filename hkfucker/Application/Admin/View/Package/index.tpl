<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>大礼包设置<small><a href="{:U('Package/add')}">内容添加</a></small></h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 10%; margin-right:10%;padding: 0px;">
    <!--<div class="col-md-10 col-md-offset-2 col-xs-10 col-xs-offset-1" style="margin-left: 10px; padding: 0px;">-->
        <div class="">
            <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>物品名</th>
                    <th>数量</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <volist name="data" id="val">
                        <tr id="tr{$val.id}">
                            <td>{$val.id}</td>
							<if condition="$val['name'] eq 'diamond'">
							<td>钻石</td>
							<else/>
                                <td>{$val.name}</td>
							</if> 
                            <td>{$val.num}</td>
                            <td><a href="{:U('Package/edit',array('id'=>$val['id']))}">修改</a>
                                <a href="javascript:del({$val.id})">删除</a>
                            </td>
                        </tr>
                    </volist>
                </tbody>
            </table>
        </div>
    </div>
<script>
    function del(id) {
        $.post("{:U('Package/del')}",{id:id},function (msg) {
            if(msg==1){
                alert('删除成功！');
                $('#tr'+id).remove();
            }else if(msg==0){
                alert('删除失败！');
            }else if(msg==-1){
                alert('请求错误！');
            }
        })
    }
</script>

</block>