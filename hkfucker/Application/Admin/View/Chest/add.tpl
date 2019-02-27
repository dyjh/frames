<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">宝箱名称</span>
                <input type="text" name="name" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">中奖几率</span>
                <input type="text" name="chance" class="form-control" placeholder="请输入大于1小于100的整数" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">每天中奖人数</span>
                <input type="text" name="number" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">倍率</span>
                <input type="text" name="multiple" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">中奖果子</span>
                <select name="seed" id="id" style="height:30px; "  onfocus="display_id()">
                    <option value="">全部</option>
                    <volist name="data" id="val">
                        <option value="{$val.varieties}">{$val.varieties}</option>
                    </volist>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">中奖果子数量（不含倍数）</span>
                <input type="text" name="seed_num" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">中奖倍率</span>
                <input type="text" name="multiple" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">未中奖赠送果实（不含倍数）</span>
                <select name="gift" id="id" style="height:30px; "  onfocus="display_id()">
                    <option value="">全部</option>
                    <volist name="data" id="val">
                        <option value="{$val.varieties}">{$val.varieties}</option>
                    </volist>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">赠送果实数量</span>
                <input type="text" name="gift_num" class="form-control" placeholder="" aria-describedby="basic-addon1" value="">
            </div>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>