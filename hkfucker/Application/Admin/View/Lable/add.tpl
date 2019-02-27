<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>标签添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">可否可用</span>
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
                <select name="label_status" class="btn btn-default btn-sm dropdown-toggle" id="option">
                    <option value="1">否</option>
                    <option value="0">是</option>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">标签名称</span>
                <input type="text" name="label_name" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="">
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
    <script>
        function cheack_form(){
            if($('input[name="label_status"]').val() == ''){
                alert('状态不能为空');
                return false;
            }
            if($('input[name="label_name"]').val() == ''){
                alert('名称不能为空');
                return false;
            }
        }
    </script>
</block>