<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户{$user}团队</h1>
    </div>
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <span style="">该用户直推{$num_st}人，团队有{$num_team}人<small><a href="{:U('User/freeze_count',array('user'=>$user))}">查询直推</a></small></span>
        <div style="clear: both;"></div>
        <span class="field-validation-valid" data-valmsg-for="sel"></span>
		
		<table style="background-color: #fafafa;border:solid 1px #ddd !important;margin-top: 2%;" class="table">
            <thead>
            <tr>
                <if condition="$dengji['yi'] neq ''"><th style="text-align:center;">1级</th></if>
                <if condition="$dengji['er'] neq ''"><th style="text-align:center;">2级</th></if>
                <if condition="$dengji['san'] neq ''"><th style="text-align:center;">3级</th></if>
                <if condition="$dengji['si'] neq ''"><th style="text-align:center;">4级</th></if>
				<if condition="$dengji['wu'] neq ''"><th style="text-align:center;">5级</th></if>
				<if condition="$dengji['liu'] neq ''"><th style="text-align:center;">6级</th></if>
				<if condition="$dengji['qi'] neq ''"><th style="text-align:center;">7级</th></if>
				<if condition="$dengji['ba'] neq ''"><th style="text-align:center;">8级</th></if>
				<if condition="$dengji['jiu'] neq ''"><th style="text-align:center;">9级</th></if>
				<if condition="$dengji['shi'] neq ''"><th style="text-align:center;">10级</th></if>
				<if condition="$dengji['shiyi'] neq ''"><th style="text-align:center;">11级</th></if>
				<if condition="$dengji['shier'] neq ''"><th style="text-align:center;">12级</th></if>
            </tr>
            </thead>
            <tbody style="text-align:center;">
            <if condition="$dengji eq ''">
                <td colspan="9" align="center">
                    <p style="padding: 15px;">暂无数据信息</p>
                </td>
                <else />
                    <tr>
                        <if condition="$dengji['yi'] neq ''"><td>{$dengji.yi}</td></if>
                        <if condition="$dengji['er'] neq ''"><td>{$dengji.er}</td></if>
                        <if condition="$dengji['san'] neq ''"><td>{$dengji.san}</td></if>
                        <if condition="$dengji['si'] neq ''"><td>{$dengji.si}</td></if>
                        <if condition="$dengji['wu'] neq ''"><td>{$dengji.wu}</td></if>
                        <if condition="$dengji['liu'] neq ''"><td>{$dengji.liu}</td></if>
                        <if condition="$dengji['qi'] neq ''"><td>{$dengji.qi}</td></if>
                        <if condition="$dengji['ba'] neq ''"><td>{$dengji.ba}</td></if>
                        <if condition="$dengji['jiu'] neq ''"><td>{$dengji.jiu}</td></if>
                        <if condition="$dengji['shi'] neq ''"><td>{$dengji.shi}</td></if>
                        <if condition="$dengji['shiyi'] neq ''"><td>{$dengji.shiyi}</td></if>
                        <if condition="$dengji['shier'] neq ''"><td>{$dengji.shier}</td></if>
                    </tr>
            </if>
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
            </tr>
            </thead>
            <tbody>
            <if condition="$data eq ''">
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
                    </tr>
                </volist>
            </if>
            </tbody>
        </table>
        <div style="clear: both;"></div>
        <if condition="$data eq ''">
            <else/>
        <nav aria-label="Page navigation">
            <ul class="pagination" ">
                <li>
                    <a href="{:U('User/freeze',array('p'=>$p-1,'user'=>$user))}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                </li>
            <for start="$start" end="$end">
                    <if condition="$p eq $i">
                        <li class="active">
                            <a href="{:U('User/team',array('p'=>$p-1,'user'=>$user))}">{$i}</a>
                        </li>
                        <else/>
                        <li>
                            <a href="{:U('User/team',array('p'=>$i,'user'=>$user))}">{$i}</a>
                        </li>
                    </if>
                </for>
                <li>
                    <a href="{:U('User/team',array('p'=>$p+1,'user'=>$user))}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                </li>
            </ul>
        </nav>
        </if>
    </div>
    <script>
        function del(user){

             if(confirm('确定删除吗？')){
             location.href="{:U('User/User_info_del')}?user="+user;
             }
        }
    </script>
</block>
