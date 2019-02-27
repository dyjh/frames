<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>标签修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">可否交易</span>
                <select name="label_status" class="btn btn-default btn-sm dropdown-toggle" id="option">
                    <if condition="$data['label_status'] eq 0">
                        <option value="0">是</option>
                        <option value="1">否</option>
                        <else />
                        <option value="1">否</option>
                        <option value="0">是</option>
                    </if>
                </select>
            </div>
            <input type="hidden" name="id" value="{$data.id}"/>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">标签名称</span>
                <input type="text" name="label_name" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.label_name}">
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