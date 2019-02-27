<extend name="Public:base" />
<block name="content">
    <body style="height: 100%; margin: 0">
    <div class="page-header">
        <h1>种子回购统计</h1>
    </div>
    <!--<form action="" method="post">
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
    </form>-->
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span class="field-validation-valid" data-valmsg-for="sel">当前种子回购总量为<b style="color:#ff0000;">{$zs}</b></span>
		
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
			<volist name="row" id="r">
                <th style="text-align:center;">日期</th>
                <th style="text-align:center;">数量</th>
			</volist>
            </tr>
            </thead>
            <tbody style="text-align:center;">
                    <tr>
					<volist name="row" id="r">
                        <td>{$r.time|date='Y-m-d',###}</td>
                        <td>{$r.sum(num)}</td>
					</volist>
                    </tr>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
		
        <table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <th>用户ID</th>
                <th>用户</th>
                <th>昵称</th>
                <th>姓名</th>
                <th>等级</th>
                <th>管理操作</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$state eq 0">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                <volist name="m" id="vo">
                    <tr>
                        <td>{$vo.num_id}</td>
                        <td>{$vo.user}</td>
                        <td>{$vo.nickname}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.level}</td>
                        <td>
                            <a href="{:U('Backer/back_details',array('user'=>$vo['user']))}">设置</a>
                        </td>
                    </tr>
                </volist>
            </if>
            </tbody>
        </form>
        </table>
        <div style="clear: both;"></div>
        <if condition="$state neq 0">
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
    </div>
    <div style="clear: both;"></div>
    
</block>
