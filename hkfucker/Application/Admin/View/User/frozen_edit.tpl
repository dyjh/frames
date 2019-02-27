<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>账户冻结金币返还</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/frozen_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['user']}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">姓&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp名</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['name']}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">昵&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp称</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['nickname']}</span>
            </div>

			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">冻结金币</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['coin_freeze']}</span>
            </div>
			
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">返还数量</span>
                <input  class="form-control" aria-describedby="basic-addon1" name="coin_num" value="{$data['coin_freeze']}"/>
            </div>
			
            <input type="hidden" name="user" value="{$data['user']}">
            <input type="hidden" name="id" id="id" value="{$data.id}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>