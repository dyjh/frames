<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>品种修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
		<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">名称</span>
                <input type="text" name="name" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="{$data.name}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">价格</span>
                <input type="text" name="price" class="form-control" placeholder="初始价格" aria-describedby="basic-addon1" value="{$data.price}">
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">成熟周期</span>
                <input type="text" name="earnings" class="form-control" placeholder="初始价格" aria-describedby="basic-addon1" value="{$data.earnings}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">收益</span>
                <input type="text" name="conversion" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.conversion}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">比例</span>
                <input type="text" name="num" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.num}">
            </div>
            <input type="hidden" name="id" id="id" value="{$data.id}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
    <script>

    </script>
</block>