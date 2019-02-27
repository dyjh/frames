<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户道具添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('User/warehouse_add')}" method="post"  enctype="multipart/form-data" onsubmit="return click_form();">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <select name="user" id="user" class="btn btn-default btn-sm dropdown-toggle" style="background: #ffffff;color:#3c8dbc;"><option>《—请选择用户账号—》</option>
                    <volist name="user_data" id="us">
                        <option value="{$us.user}">{$us.user}</option>
                    </volist>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">系统道具</span>
                <select name="props" id="shop" class="btn btn-default btn-sm dropdown-toggle" style="background: #ffffff;color:#3c8dbc;"><option>《—请选择道具—》</option>
                    <volist name="shop" id="sh">
                        <option value="{$sh.name}">{$sh.name}</option>
                    </volist>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">道具数量</span>
                <input type="text" name="num" class="form-control" placeholder="" aria-describedby="basic-addon1" id="num">
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </form>
    </div>
    <script>
        function click_form() {
            
        }
    </script>
</block>