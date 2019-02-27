<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实主页</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>编号</th>
                <th>名称</th>
                <th>价格</th>
				<th>周期</th>
                <th>收益</th>
                <th>换算比例</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.id}</td>
                        <td>{$val.name}</td>
                        <td>{$val.price}</td>
                        <td>{$val.cycle}</td>
						<td>{$val.earnings}</td>
                        <td>{$val.conversion}</td>
                        <td>{$val.num}</td>
                        <td><a href="{:U('Chicken/chicken_edit',array('id'=>$val['id']))}">修改</a>
                            <a href="javascript:del({$val.id})">删除</a>
                        </td>
                    </tr>
                </volist>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</block>