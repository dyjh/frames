<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>用户金币添加</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;">
        <form action="{:U('Pay/edit')}" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户账号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['user']}</span>
                <input type="hidden" name="user" value="{$data['user']}"/>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">用户昵称</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['nickname']}</span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">当前金币数量</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$data['coin']}</span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">充值订单号</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">{$order.order_num}</span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">充值金额</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;">￥{$order.money}</span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">支付方式</span>
                <span  class="form-control" aria-describedby="basic-addon1" style="color: #FF0000;"><if condition="$order.pay_bank == 2001">网银汇款<else/>{$order.pay_bank}</if></span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">支付时间</span>
                <span class="form-control" style="color: #FF0000;" aria-describedby="basic-addon1"><if condition="$order.pay_time == 0">无
                                <else /> {$order.pay_time|date="Y-m-d H:i:s",###}
                            </if></span>
            </div>

            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">添加金币</span>
                <input type="text" name="coin" class="form-control" aria-describedby="basic-addon1" value="0">
            </div>
            <input type="hidden" name="id" value="{$order['id']}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
</block>