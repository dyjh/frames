<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="page-header">
        <h1>果实统计</h1>
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
            <div style="float: left;">
                <button type="submit" class="btn btn-default">查询</button>
            </div>
        </div>
    </form>
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span class="field-validation-valid" data-valmsg-for="sel">当前果实总量为<b style="color:#ff0000;">{$seeds.zongshu}</b></span>
		
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th style="text-align:center;">土豆</th>
                <th style="text-align:center;">草莓</th>
                <th style="text-align:center;">樱桃</th>
                <th style="text-align:center;">稻米</th>
                <th style="text-align:center;">番茄</th>
                <th style="text-align:center;">葡萄</th>
                <th style="text-align:center;">菠萝</th>
				<th style="text-align:center;">总量</th>
				<th style="text-align:center;">种子</th>
            </tr>
            </thead>
            <tbody style="text-align:center;">
                    <tr>
                        <td>{$seeds.tudou}</td>
                        <td>{$seeds.caomei}</td>
                        <td>{$seeds.yingtao}</td>
                        <td>{$seeds.daomi}</td>
                        <td>{$seeds.fanqie}</td>
                        <td>{$seeds.putao}</td>
                        <td>{$seeds.boluo}</td>
						<td>{$seeds.zongshu}</td>
						<td>{$seeds.zhongzi}</td>
                    </tr>
            </tbody>
        </table>
        <div style="clear: both;"></div>
		
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户</th>
                <th>昵称</th>
                <th>姓名</th>
                <th>等级</th>
                <th>金币</th>
                <th>宝石</th>
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
                        <td>{$vo.diamond}</td>
                        <td>
                            <a href="{:U('User/level_edit',array('user'=>$vo['user']))}">设置</a>
                        </td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state eq 0">
        <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" ">
                <li>
                    <a href="{:U('Backer/level',array('o'=>$o-1,'p'=>$p,'level'=>$level))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$o eq $i">
                        <li class="active">
                            <a href="{:U('Backer/level',array('o'=>$o-1,'p'=>$p,'level'=>$level))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('Backer/level',array('o'=>$i,'p'=>$p,'level'=>$level))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('Backer/level',array('o'=>$o+1,'p'=>$p,'level'=>$level))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
		<div style="clear: both;"></div>
		
		<if condition="$data_arr neq null"> 
	<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
	<h1>共计{$l}人</h1>
            <thead>
            <tr>
                <th>序号</th>
                <th>用户</th>
                <th>姓名</th>
                <th>等级</th>
                <th>类型</th>
                <th>数量</th>
            </tr>
            </thead>
            <tbody>
                <volist name="data_arr" id="ar">
                    <tr id='tr{$ar.id}'>
                        <td>{$key}</td>
                        <td>{$ar.user}</td>
                        <td>{$ar.name}</td>
                        <td>{$ar.level}</td>
                        <td>{$ar.props}</td>
                        <td>{$ar.num}</td>
                    </tr>
                </volist>
            </tbody>
			<tr style="color:#ff0000;">
					<td>合计:{$data_num.renshu}人 &nbsp果实：{$data_num.zongshu}</td>
					<if condition="$data_num.yi neq null">
					<td>1级：{$data_num.yi}人 &nbsp果实：{$data_num.yi_num}</td>
					</if>
					<if condition="$data_num.er neq null">
					<td>2级：{$data_num.er}人 &nbsp果实：{$data_num.er_num}</td>
					</if>
					<if condition="$data_num.san neq null">
					<td>3级：{$data_num.san}人 &nbsp果实：{$data_num.san_num}</td>
					</if>
					<if condition="$data_num.si neq null">
					<td>4级：{$data_num.si}人 &nbsp果实：{$data_num.si_num}</td>
					</if>
					<if condition="$data_num.wu neq null">
					<td>5级：{$data_num.wu}人 &nbsp果实：{$data_num.wu_num}</td>
					</if>
					<if condition="$data_num.liu neq null">
					<td>6级：{$data_num.liu}人 &nbsp果实：{$data_num.liu_num}</td>
					</if>
					<if condition="$data_num.qi neq null">
					<td>7级：{$data_num.qi}人 &nbsp果实：{$data_num.qi_num}</td>
					</if>
					<if condition="$data_num.ba neq null">
					<td>8级：{$data_num.ba}人 &nbsp果实：{$data_num.ba_num}</td>
					</if>
					<if condition="$data_num.jiu neq null">
					<td>9级：{$data_num.jiu}人 &nbsp果实：{$data_num.jiu_num}</td>
					</if>
					<if condition="$data_num.shi neq null">
					<td>10级：{$data_num.shi}人 &nbsp果实：{$data_num.shi_num}</td>
					</if>
					<if condition="$data_num.sy neq null">
					<td>11级：{$data_num.sy}人 &nbsp果实：{$data_num.sy_num}</td>
					</if>
					<if condition="$data_num.se neq null">
					<td>12级：{$data_num.se}人 &nbsp果实：{$data_num.se_num}</td>
					</if>
					</tr>
        </table>
	</if>
    </div>
    <div style="clear: both;"></div>
   
</block>
