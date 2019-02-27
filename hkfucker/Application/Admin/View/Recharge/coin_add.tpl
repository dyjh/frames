<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户金币添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('Recharge/coin_add')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['user']}</span>
                <input type="hidden" name="user" value="{$data['user']}"/>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户昵称</span>
				<span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['nickname']}</span>
                <!--<input type="text" name="nickname" class="form-control" aria-describedby="basic-addon1" style="width:15%;" value="{$data['nickname']}">-->
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户等级</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['level']}</span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">真实姓名</span>
				<span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['name']}</span>
                <!--<input type="text" name="name" class="form-control" aria-describedby="basic-addon1" style="width:15%;" value="{$data['name']}">-->
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">金币数量</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['coin']}</span>
				<!--<input name="coin" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['coin']}"/>-->
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">冻结金币</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;width:15%;">{$data['coin_freeze']}</span>
				<!--<input name="coin" type="text" class="form-control" aria-describedby="basic-addon1" value="{$data['coin']}"/>-->
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">添加金币</span>
                <input type="text" name="coin" class="form-control" aria-describedby="basic-addon1" style="width:15%;" value="0">
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">                
                <input type="checkbox" name="can_cash"   value="1">同时添加可提现金币
            </div>
			
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>