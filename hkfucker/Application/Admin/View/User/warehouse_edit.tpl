<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户道具修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/warehouse_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$ware_list[0]['user']}</span>

            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">道具名称</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$ware_list[0]['props']}</span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">道具数量</span>
                <input type="text" name="num" class="form-control" aria-describedby="basic-addon1" value="{$ware_list[0]['num']}">
            </div>
            <input type="hidden" name="props" id="id" value="{$ware_list[0]['props']}"/>
            <input type="hidden" name="user" id="id" value="{$ware_list[0]['user']}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>