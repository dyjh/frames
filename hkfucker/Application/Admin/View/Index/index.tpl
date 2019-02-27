<extend name="Public:base" />
<block name="content">
	<div style="border: solid 1px #FAFAFA; text-align: center;margin-top: 5%;margin-left: 35%; width: 400px;line-height:80px;height: 80px; background-color: #f9fdff;">当前剩余撤单数量为<span style="color:red;">{$count}</span></div>
	<div style="border: solid 1px #FAFAFA; text-align: center;margin-top: 5%;margin-left: 35%; width: 400px;line-height:80px;height: 160px; background-color: #f9fdff;">
		<div style='width:100%; margin-left:5%;'>
			<form action="" method="post" onsubmit="return cheack_form()">
				<select name="level" class="form-control" style='width:20%; float:left; margin:23px 15px;'>
					<option value="0" >全部</option>
					<volist name="data" id='val'>
						<if condition="$val['level'] eq $level">
							<option value="{$val.level}" selected>{$val.level}级</option>
							<else/>
							<option value="{$val.level}" >{$val.level}级</option>
						</if>
					</volist>
				</select>
				<div style="float: left;width:40%; margin:23px 15px;">
					<input type="text" name="time" class="form-control" placeholder="起始时间:2017-01-01" value="{$today}" aria-describedby="basic-addon1">
				</div>
				<div style="float: left;">
					<button type="submit" class="btn btn-default">查询</button>
				</div>
			</form>
			<div style="clear:both;"></div>
		</div>
		
		当天登录数为<span style="color:red;">{$p}</span>
	</div>
	<script type="text/javascript">
        function cheack_form(){
            var s=$("input[name='time']").val();
//alert('__URL__/id/'+id+'/e/'+e+'/s/'+s);
            if(s.match(/^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/)) {
                
            } else {
                alert('开始日期格式错误，请重新输入！');
                return false;
            }
        }
    </script>
</block>