<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>账户封号</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/freeze_edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['user']}</span>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">账号状态</span>
                <select name="state" class="btn btn-default btn-sm dropdown-toggle" id="option">
                    <if condition="$data['state'] eq 1">
                        <option value="1">封号</option>
                        <option value="0">正常</option>
                        <else />
                        <option value="0">正常</option>
                        <option value="1">封号</option>
                    </if>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">封号时间</span>
                <span class="form-control" style="color: #FF0000;" aria-describedby="basic-addon1">    <if condition="$data.freeze_time eq 0 ">账号正常
    <else /> {$data.freeze_time|date="Y-m-d",###}
    </if></span>
            </div>
            <input type="hidden" name="user" value="{$data['user']}">
            <input type="hidden" name="id" id="id" value="{$data.id}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>