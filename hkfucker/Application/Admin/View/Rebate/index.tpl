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


    <input type="hidden" class="id" value="{$id}"/>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>机构账户</h1>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>登录名</th>
                <th>用户名</th>
                <th>身份证</th>
                <th>时间</th>
                <th>等级</th>
                <th>身份</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 1">
            <foreach name="organ" item="res">
                <tr>
                    <td>{$res['id']}</td>
                    <td>{$res['user']}</td>
                    <td>{$res['name']}</td>
                    <td>{$res['card']}</td>
                    <td><?php echo date("Y-m-d H:i:s",$res['time']) ?></td>
                    <td>{$res['level']}</td>
                    <td><a href="{:U('Rebate/team?user='.$res['user'].'')}">查看</a>
						/<a href="{:U('Rebate/remove?user='.$res['user'].'')}">删除</a>
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
        <if condition="$state eq 1">
            <nav aria-label="Page navigation">
                <ul style="margin-left: 35%;" class="pagination">
                    <li>
                        <a href="{:U('Rebate/index',array('p'=>$p-1))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$p eq $i">
                            <li class="active"><a style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Rebate/index',array('p'=>$i))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Rebate/index',array('p'=>$i))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Rebate/index',array('p'=>$p+1))}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <else/>
        </if>
    </div>
    <div style="clear: both;"></div>
</block>