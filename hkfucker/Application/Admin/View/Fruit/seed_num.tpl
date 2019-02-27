<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>种植概率</h1>
    </div>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>等级</th>
                <th>几率</th>
				<th>注释</th>
				<th>操作</th>
            </tr>
            </thead>
            <tbody>
                <volist name="data" id="val">
                    <tr id="tr{$val.id}">
                        <td>{$val.level}</td>
                        <td>{$val.seed_level}</td>
                        <td>{$val.note}</td>
                        <td>
							<a href="{:U('Fruit/edit_num',array('id'=>$val['id']))}">修改</a>  
                        </td>
                    </tr>
                </volist>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</block>