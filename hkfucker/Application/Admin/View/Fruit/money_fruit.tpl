<extend name="Public:base" />
<block name="content">
   
    <body style="height: 100%; margin: 0">
    <div class="page-header">
        <h1>当天果实交易价位<small><a href="{:U('Fruit/index')}">返回</a></small></h1>
    </div>    
    <input type="hidden" class="id" value="{$id}"/>
    <div class="table-responsive" id="listDiv" style="margin-left: 5%; margin-right: 5%;">
	<div style=" margin-bottom: 20px;">
            <form method="post" enctype="multipart/form-data" action="{:U('Fruit/sell_num')}" onsubmit="">
				<div class="row">
					<div class="col-md-2">
						<div class="input-group">
							<div class="form-group-addon no-right-border">单价(总交易量为<span style="color:red;">{$num}</span>)</div>
							<div class="input-group">
								<input type="hidden" name="seed" value="{$seed}"/>
								<input type="text" name="num" value="" placeholder="要添加的成交量" class="form-control">
							</div>
						</div>
					</div>
					<div class="col-md-1" style="margin-top:22px;">
						<div class="form-group">
							<input type="submit" class="form-control border-input" value="提交">
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</form>
        </div>
        <table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important;" class="table">
            <thead>
            <tr>
                <th>果实</th>
                <th>价位</th>
                <th>数量</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 1">
                <volist name="data" id="val">
                    <tr>
                        <td>{$val.seed}</td>
                        <td>{$val.money}</td>
                        <td>{$val.num}</td>
                    </tr>
                </volist>
                <else/>
                <td colspan="9" align="center">
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
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o-1,'id'=>$id))}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <for start="$start" end="$end">
                        <if condition="$o eq $i">
                            <li><a class="active_na" style="background:#337ab7 !important;color:#FFFFFF !important; "  href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                            <else />
                            <li><a href="{:U('Fruit/find_fruit',array('o'=>$i,'id'=>$id))}">{$i}</a></li>
                        </if>
                    </for>
                    <li>
                        <a href="{:U('Fruit/find_fruit',array('o'=>$o+1,'id'=>$id))}" aria-label="Next">
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