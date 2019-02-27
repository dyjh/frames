<extend name="Public:base" />
<block name="content">
    <style>
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
    <body style="height: 100%; margin: 0">
    <input type="hidden" class="id" value="{$id}"/>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>商品分类</h1>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>单价</th>
                <th>数量</th>
                <th>注释</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$shop neq ''">
            <foreach name="shop" item="shop">
                    <tr id="tr{$shop.id}">
                        <td>{$shop['id']}</td>
                        <td>{$shop['name']}</td>
                        <td>{$shop['price']}</td>
                        <td>{$shop['num']}</td>
                        <td>{$shop['note']}</td>
                        <td>{$shop['type']}</td>
                        <td>
                            <if condition="$shop.type eq 0">
                            <a href="{:U('Goods/up?id='.$shop['id'].'')}">上架</a>
                                <a href="javascript:del({$shop.id})">删除</a>
                            <a href="{:U('Goods/cost?id='.$shop['id'].'')}">修改</a>
                            <else/>
                            <a href="{:U('Goods/down?id='.$shop['id'].'')}">下架</a>
                                <a href="javascript:del({$val.id})">删除</a>
                            <a href="{:U('Goods/cost?id='.$shop['id'].'')}">修改</a>
                            </if>
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
        <if condition="$shop neq ''">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Goods/index',array('p'=>$p-1))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$p eq $i">
                            <li  class="active"><a style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Goods/index',array('p'=>$i))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Goods/index',array('p'=>$i))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Goods/index',array('p'=>$p+1))}" aria-label="Next">
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
        function del(id){
            if(confirm('确定要删除吗？')){
                $.post("{:U('Goods/del')}",{id:id},function(msg){
                    if(msg == 0){
                        alert('删除失败');
                    }else if(msg == -1){
                        alert('ajax请求错误');
                    }else if(msg == 2){
                        alert('请先下架');
                    }else if(msg == 1){
                        alert('删除成功');
                        $('#tr'+id).remove();

                    }
                })
            }
        }
    </script>
</block>