<extend name="Public:base" />
<block name="content">

    <style>
        .page{width:800px;height:50px;}
        .page p{text-align:center;}
        .page p span{font-size:20px;background:blue;margin-left:20px;}
        .page p lable{width:25px;height:25px;font-size:20px;background:blue;margin-left:20px;}
        .pages a,.pages span {
            display:inline-block;
            padding:2px 5px;
            margin:0 1px;
            border:1px solid #f0f0f0;
            -webkit-border-radius:3px;
            -moz-border-radius:3px;
            border-radius:3px;
        }
        .pages a,.pages li {
            display:inline-block;
            list-style: none;
            text-decoration:none; color:#58A0D3;
        }
        .pages a.first,.pages a.prev,.pages a.next,.pages a.end{
            margin:0;
        }
        .pages a:hover{
            border-color:#50A8E6;
        }
        .pages span.current{
            background:#50A8E6;
            color:#FFF;
            font-weight:700;
            border-color:#50A8E6;
        }
    </style>

    <input type="hidden" class="id" value="{$id}"/>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>公告管理</h1>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>标题</th>
                <th>时间</th>
                <th>审核</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$notice neq ''">
            <foreach name="notice" item="res">
                <tr>
                    <td>{$res['id']}</td>
                    <td>{$res['title']}</td>
                    <td><?php echo date("Y-m-d H:i:s",$res['time']) ?></td>
                    <td>
                        <if condition="$res.type eq 0">
                            未通过
                        <else/>
                            通过
                        </if>

                    </td>
                    <td>
                        <a href="{:U('Essay/index_content?id='.$res['id'].'')}">查看</a>
                        <a href="{:U('Essay/audit?id='.$res['id'].'')}">通过</a>
                        <a href="javascript:del({$res.id})">删除</a>
                    </td>
                </tr>
            </foreach>
            <else/>
                <tr>
                    <td colspan="9" align="center">
                        <p style="padding: 15px;">暂无数据信息</p>
                    </td>
                </tr>
            </if>
            </tbody>
        </table>
        <div style="clear: both;"></div>
        <if condition="$notice neq ''">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Essay/index',array('p'=>$p-1))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$p eq $i">
                            <li class="active"><a style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Essay/index',array('p'=>$i))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Essay/index',array('p'=>$i))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Essay/index',array('p'=>$p+1))}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <else/>
        </if>
    </div>
    <div style="clear: both;"></div>
    <script>
        function del(id) {
            if(confirm('确定要删除吗？')){
                $.post("{:U('Essay/del')}",{id:id},function(msg){
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