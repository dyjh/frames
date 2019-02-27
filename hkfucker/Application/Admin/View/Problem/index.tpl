<extend name="Public:base" />
<block name="content">
    <div class="t_content">
        <div class="tcontent ">
            <span>×</span>
            <p></p>
        </div>
    </div>
    <div class="page-header">
        <h1>问题管理<small class="add"><a href="{:U('Problem/add')}">添加问题</a></small></h1>
        <div class="clear"></div>
    </div>
    <div class=".table-responsive">
        <div class="col-md-12 column">
            <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>标题</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <if condition="$state eq 1">
                    <volist name="data" id="val">
                        <tr id="tr{$val.id}">
                            <td>{$val.id}</td>
                            <td>{$val.title}</td>
                            <td>
                                <a href="{:U('Problem/pedit',array('id'=>$val['id']))}">修改</a>
                                <a href="javascript:del({$val.id});">删除</a>
                            </td>
                        </tr>
                    </volist>
                    <else/>
                    <td colspan="9" align="center">
                        <p style="padding: 15px;">暂无数据信息</p>
                    </td>

                </if>
                </tbody>
            </table>

        </div>
        <if condition="$state eq 1">
        <nav aria-label="Page navigation">
            <ul style="margin-left: 35%;" class="pagination">
                <li>
                    <a href="{:U('Problem/index',array('p'=>$p-1))}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active"><a class="active_nav"  href="{:U('Problem/index',array('p'=>$i))}">{$i}</a></li>
                        <else />
                        <li><a href="{:U('Problem/index',array('p'=>$i))}">{$i}</a></li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Problem/index',array('p'=>$p+1))}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <else/>
        </if>
    </div>
    <script>
        $('.t_content').hide();
        function lookContent(content){
            //alert('1');
            $('.t_content').show();
            $('.tcontent p').text(content);
        }
        $('.tcontent span').click(function(){
            $('.t_content').hide();
        });
        function del(id){
            $.post("{:U('Problem/del')}",{id:id},function(msg){
                if(msg == 0){
                    alert('删除失败！');
                }else if(msg == 1){
                    $('#tr'+id).remove();
                    alert('删除成功！');
                }else if(msg==-1){
                    alert('请求失败！');
                }
            })
        }
    </script>
    <div style="clear: both;"></div>
</block>