<extend name="Public:base" />
<block name="content">
   
    <body style="height: 100%; margin: 0">
    <div class="col-md-9 col-md-offset-1 col-xs-8 col-xs-offset-1" >
        <form action="" method="get">
        
			<input type="hidden" name="TOKEN" value="{:session('TOKEN')}">
			<div>
				<div class="input-group" style="float: left; width: 23%;">
					<input value="{$start_user}" type="text" name="start_user" class="form-control" placeholder="请输入你要查询的用户" aria-describedby="basic-addon1">
				</div>

				<div style="float: left;">
					<button type="submit" class="btn btn-default">查询</button>
				</div>
				
			</div>
			  
			<div style="clear: both;height: 10px;"></div>
			
			<span class="field-validation-valid" data-valmsg-for="sel"></span>
			
		</form>
		
        <div style="clear: both;height: 10px;"></div>
		
		<volist name="counting" id="val">
		
			<table id="table_user" style="background-color: #fafafa;border:solid 1px #ddd !important; margin-top: 20px;" class="table">
				<thead>
				<tr>
					<if condition="$key eq '1'">
						草灾守护
					<elseif condition="$key eq '2'"/> 
						虫灾守护
					<elseif condition="$key eq '3'"/> 
						旱灾守护
					<elseif condition="$key eq '4'"/>
						丰收之心
					<elseif condition="$key eq '5'"/>
						稻草人
					<elseif condition="$key eq 'all'"/>
						整套守护	
					</if>					
				</tr>
				</thead>	

				<tbody>
				<tr>
					<td>生效人数<br/>{$val.0}</td>
					<td>未生效人数<br/>{$val.1}<br/></td>
					<td>购买人数<br/>{: ($val[0]+$val[1])}</td>
				</tr>
				</tbody>
			</table>
		
		</volist>
		
		
		
	   <div style="clear: both;"></div>
		       
    </div>

</block>
