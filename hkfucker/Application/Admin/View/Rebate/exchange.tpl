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
        <h1>重生统计</h1>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>土豆</th>
                <th>草莓</th>
                <th>樱桃</th>
                <th>稻米</th>
                <th>葡萄</th>
                <th>番茄</th>
            </tr>
            </thead>
            <tbody>
            
                <tr>
                    <td>数量</td>
                    <td>{$tudou}</td>
                    <td>{$caomei}</td>
                    <td>{$yingtao}</td>
                    <td>{$daomi}</td>
                    <td>{$putao}</td>
                    <td>{$fanqie}</td>
                 
                </tr>
        
            </tbody>
        </table>
		
		
		<h1>当天统计</h1>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>土豆</th>
                <th>草莓</th>
                <th>樱桃</th>
                <th>稻米</th>
                <th>葡萄</th>
                <th>番茄</th>
            </tr>
            </thead>
            <tbody>
            
                <tr>
                    <td>数量</td>
                    <td>{$day_tudou}</td>
                    <td>{$day_caomei}</td>
                    <td>{$day_yingtao}</td>
                    <td>{$day_daomi}</td>
                    <td>{$day_putao}</td>
                    <td>{$day_fanqie}</td>
                 
                </tr>
        
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