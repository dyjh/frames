<extend name="Public:base" />
<block name="content">

    <div class="page-header">
        <h1>添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action=" " method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <select class="btn btn-default btn-sm dropdown-toggle" name="id" id="option">
                <volist name="data_prop" id="val">
                    <option value="{$val.id}">{$val.name}</option>
                </volist>
            </select>
            <div class="input-group" style="margin-bottom: 10px; width: 30%; margin-top: 20px;">
                <span class="input-group-addon" id="basic-addon1">数量</span>
                <input type="text" name="num" id="name" class="form-control" value="0" placeholder="道具数量" aria-describedby="basic-addon1"/>
            </div>
            <div class="input-group" style="margin-bottom: 20px;width: 30%;">
                <span class="input-group-addon" id="basic-addon1">钻石</span>
                <input type="text" name="diamond" class="form-control" placeholder="" aria-describedby="basic-addon1" value="0">
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>

    <script>


    </script>
</block>