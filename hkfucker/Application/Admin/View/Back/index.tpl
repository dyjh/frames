<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="page-header">
        <h1>待操作用户<small><a href="{:U('Back/conf',array('type'=>'all','level'=>$level))}">全部处理</a><a href="{:U('Back/level_del',array('level'=>$level))}">全部删除</a></small></h1>
    </div>
	<form action="" method="post">
        <div style="margin-left: 100px;">
            <div class="input-group" style="float: left; width: 23%;">
                <select name="level" class="form-control">
                    <option value="{$level}">{$level}级</option>
                    <option value="1">1级</option>
                    <option value="2">2级</option>
                    <option value="3">3级</option>
                    <option value="4">4级</option>
                    <option value="5">5级</option>
                    <option value="6">6级</option>
                    <option value="7">7级</option>
                    <option value="8">8级</option>
                    <option value="9">9级</option>
                    <option value="10">10级</option>
                    <option value="11">11级</option>
                    <option value="12">12级</option>
                </select>
            </div>
			<div class="input-group" style="float: left; width: 23%;">
                <input type="text" name="user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
            </div>
            <div style="float: left;">
                <button type="submit" class="btn btn-default">查询</button>
            </div>
        </div>
    </form>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span class="field-validation-valid" data-valmsg-for="sel">回本用户为{$count}人</span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>昵称</th>
                <th>姓名</th>
                <th>等级</th>
                <th>金币</th>
                <th>成本</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="data" id="vo">
                    <tr id='tr{$vo.id}'>
                        <td>{$vo.user}</td>
                        <td>{$vo.nickname}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.level}</td>
                        <td>{$vo.coin}</td>
                        <td>{$dd[$vo['user']]['income']}</td>
                        <td>
                            <a href="{:U('Back/conf',array('user'=>$vo['user'],'type'=>'one','level'=>$vo['level']))}">设置</a>
                        </td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state eq 0">
        <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" >
                <li>
                    <a href="{:U('Back/index',array('o'=>$o-1,'p'=>$p,'level'=>$level))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Back/index',array('o'=>$o-1,'p'=>$p,'level'=>$level))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Back/index',array('o'=>$i,'p'=>$p,'level'=>$level))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Back/index',array('o'=>$o+1,'p'=>$p,'level'=>$level))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
    <div style="clear: both;"></div>
    <div class="page-header">
        <h1>已操作用户</h1>
    </div>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>日期</th>
                <th>果实控制</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state_p eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="notice" id="vo">
                    <tr id='trs{$vo.id}'>
                        <td>{$vo.user}</td>
                        <td>{$vo.ban_cycle|replaceweek}</td>
                        <td>{$vo.allow_seed}</td>
                        <td>
                            <a href="{:U('Back/edit',array('user'=>$vo['user'],'type'=>'one'))}">设置</a>
							<a href="javascript:del({$vo.id});">删除</a>
                        </td>
                    </tr>
                </volist>
            </if>
            </tbody>
            </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state_p eq 0">
            <else/>
            <nav aria-label="Page navigation">
                <ul class="pagination" >
                <li>
                    <a href="{:U('Back/index',array('p'=>$p-1,'o'=>$o,'level'=>$level))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <for start="$start_p" end="$end_p">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('Back/index',array('p'=>$p-1,'o'=>$o,'level'=>$level))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Back/index',array('p'=>$i,'o'=>$o,'level'=>$level))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Back/index',array('p'=>$p+1,'o'=>$o,'level'=>$level))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
                </ul>
            </nav>
        </if>
    </div>
	<div style="clear: both;"></div>
	<script>
		function del(id){
            $.post("{:U('Back/del')}",{id:id},function(msg){
                if(msg == 0){
                    alert('删除失败！');
                }else if(msg == 1){
                    $('#trs'+id).remove();
                    alert('删除成功！');
                }else if(msg==-1){
                    alert('请求失败！');
                }
            })
        }
	</script>
</block>
