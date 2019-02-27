<extend name="Public:base" />
<block name="content">
    <input type="hidden" class="id" value="{$id}"/>
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" style="">
        <h1>价格设置</h1>
        <div style="margin-left: 20%; margin-bottom: 20px;">
            <form method="post" action="" enctype="Rebate/set">
                <input type="hidden" name="TOKEN" value="{:session('TOKEN')}">

                <div class="input-group" style="display:none; width: 20%; float: left;margin-left: 10px;">
                    <lbale>商品率种类ID</lbale><input type="text" name="id" value="{$shop[0]['id']}" id="id" class="form-control" placeholder="<{$shop[0]['id']}>" aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>价     格</lbale><input type="text" name="price" id="price" value="{$shop[0]['price']}" class="form-control"  aria-describedby="basic-addon1">
                </div>
                <div class="input-group" style="width: 10%; float: left;margin-left: 10px;">
                    <lbale>单     位</lbale>
                    <select name="buy">
                        <if condition="$shop[0]['buy'] eq '宝石'">
                            <option value="宝石">宝石</option>
                            <volist name="data" id="val">
                                <option value="{$val.varieties}">{$val.varieties}</option>
                            </volist>
                            <else/>
                            <volist name="data" id="val">
                                <if condition="$val['varieties'] eq $data['buy']">
                                    <option value="{$val.varieties}">{$val.varieties}</option>
                                    <else/>
                                    <option value="{$val.varieties}">{$val.varieties}</option>
                                </if>
                            </volist>
                            <option value="宝石">宝石</option>
                        </if>
                    </select>
                </div>
                <div class="input-group" style="width: 20%; float: left;margin-left: 10px;">
                    <lbale>数     量</lbale><input type="text" name="num" id="num" class="form-control" value="{$shop[0]['num']}" aria-describedby="basic-addon1">
                </div>
				<div style="clear:both;"></div>
				<div class="input-group" style="width: 40%; float: left;margin-left: 10px;">
                    <lbale>注     释</lbale><input type="text" name="note" class="form-control" value="{$shop[0]['note']}" aria-describedby="basic-addon1">
                </div>
                <hr/>
                <div class="btn-group" id="group" aria-label="...">
                    <button id="btn" type="submit" class="btn btn-default">设置</button>
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>
        <div style="clear: both;"></div>
</block>