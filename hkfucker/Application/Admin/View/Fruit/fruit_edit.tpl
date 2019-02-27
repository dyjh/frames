<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
		<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">可否交易</span>
                <select name="state" class="btn btn-default btn-sm dropdown-toggle" id="option">
					<if condition="$data['state'] eq 0">
						<option value="0">是</option>
						<option value="1">否</option>
						<else />
						<option value="1">否</option>
						<option value="0">是</option>
					</if>
                </select>
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">果实名称</span>
                <input type="text" name="varieties" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="{$data.varieties}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">交易初始价格</span>
                <input type="text" name="first_price" class="form-control" placeholder="初始价格" aria-describedby="basic-addon1" value="{$data.first_price}">
            </div>
			<div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">每日开盘价</span>
                <input type="text" name="open_price" class="form-control" placeholder="初始价格" aria-describedby="basic-addon1" value="{$data.open_price}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">种子期时间(小时)</span>
                <input type="text" name="first" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.first_time}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">发芽期时间(小时)</span>
                <input type="text" name="second" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.second_time}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">成熟期时间(小时)</span>
                <input type="text" name="third" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.third_time}">
            </div>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">成熟总时间(小时)</span>
                <input type="text" name="harvest" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.harvest_hours}">
            </div>
			<input type="hidden" value="{$max}" id="max"/>
			<input type="hidden" value="{$min}" id="min"/>
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">预计收成</span>
                <input type="text" name="fruit_number" class="form-control" placeholder="" aria-describedby="basic-addon1" value="{$data.fruit_number}">
            </div>
            <input type="hidden" name="id" id="id" value="{$data.id}"/>
            <div class="btn-group" role="group">
                <button type="submit" class="btn btn-default"  >提交</button>
            </div>
        </form>
    </div>
    <script>
        function cheack_form(){
			var max=$('#max').val();
			var min=$('#min').val();
			var open=$('input[name="open_price"]').val();
			if(open!=0){
				if(open>max || open<min ){
					alert('最高'+max+'最低'+min);
					return false;
				}
			}
            if($('input[name="state"]').val() == ''){
                alert('交易状态不能为空');
                return false;
            }
            if($('input[name="varieties"]').val() == ''){
                alert('果实名称不能为空');
                return false;
            }
            if($('input[name="first_price"]').val() == ''){
                alert('初始交易价格不能为空');
                return false;
            }
            if($('input[name="first"]').val() == ''){
                alert('发芽时间不能为空');
                return false;
            }
            if($('input[name="second"]').val() == ''){
                alert('成株时间不能为空');
                return false;
            }
            if($('input[name="third"]').val() == ''){
                alert('成熟时间不能为空');
                return false;
            }
            if($('input[name="harvest"]').val() == ''){
                alert('成熟总时间不能为空');
                return false;
            }
            if($('input[name="fruit_number"]').val() == ''){
                alert('预计收成不能为空');
                return false;
            }
        }
    </script>
</block>