<extend name="Public:base" />
<block name="content">

    <div class="page-header">
        <h1>修改礼包</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action=" " method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 10px; width: 30%; margin-top: 20px;">
                <span class="input-group-addon" id="basic-addon1">数量</span>
                <input type="text" name="num" id="name" class="form-control" value="{$data.num}" placeholder="道具数量" aria-describedby="basic-addon1"/>
            </div>
            <input type="hidden" name="id" value="{$data.id}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>