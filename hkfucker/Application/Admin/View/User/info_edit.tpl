<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户信息修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/User_info_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['user']}</span>
                <input type="hidden" name="user" value="{$data['user']}"/>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户昵称</span>
                <input type="text" name="nickname" class="form-control" aria-describedby="basic-addon1" value="{$data['nickname']}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户等级</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['level']}</span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">真实姓名</span>
                <input type="text" name="name" class="form-control" aria-describedby="basic-addon1" value="{$data['name']}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">身份证号</span>
                <input type="text" name="id_card" class="form-control" aria-describedby="basic-addon1" value="{$data['id_card']}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">推&nbsp&nbsp荐&nbsp&nbsp人</span>
                <!--<span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;"><if condition="$data[0]['referees'] == ''">无<else /> {$data[0]['referees']}</if></span>-->
				<input name="referees" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['referees']}"/>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">团队</span>
				<input name="team" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['team']}"/>
            </div>
			
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">金币数量</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['coin']}</span>
				<!--<input name="coin" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['coin']}"/>-->
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">金币冻结数量</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['coin_freeze']}</span>
				<!--<input name="coin" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['coin']}"/>-->
            </div>
			
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">宝石数量</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['diamond']}</span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">注册时间</span>
                <span class="form-control" style="color: #FF0000;" aria-describedby="basic-addon1"><if condition="$time.regis_time == 0">未知<else />{$time.regis_time|date="Y-m-d",###}</if></span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">实名状态</span>
                <select name="real_name_state" class="btn btn-default btn-sm dropdown-toggle" id="option">
                    <if condition="$data['real_name_state'] eq 0">
                        <option value="0">未通过实名认证</option>
                        <option value="1">实名</option>
                        <else />
                        <option value="1">实名</option>
                        <option value="0">未通过实名认证</option>
                    </if>
                </select>
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>