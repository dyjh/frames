<extend name="Public:base" />
<block name="content">
    <div class="page-header">
        <h1>果实修改</h1>
    </div>
    <div  class=".table-responsive" style="margin:0 5%;width: 20%;">
        <form action=" " method="post"  enctype="multipart/form-data" onsubmit="return cheack_form()">
		<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
            <div class="input-group" style="margin-bottom: 20px;">
                <span class="input-group-addon" id="basic-addon1">{$data.level}级的果实概率</span>
                <input type="text" name="level" class="form-control" style="width:300px;" placeholder="" aria-describedby="basic-addon1" value="{$data.seed_level}">
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
			if(open>max || open<min){
				alert('最高'+max+'最低'+min);
                return false;
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