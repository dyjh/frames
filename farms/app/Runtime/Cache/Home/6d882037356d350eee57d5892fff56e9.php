<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="shortcut icon" href="http://www.google.com/favicon.ico"/>
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"/>
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<meta name="keywords" content="22">
<meta name="description" content="11">
<meta name="author" content="33">
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="full-screen" content="yes"/>
<meta name="screen-orientation" content="portrait"/>
<meta name="x5-fullscreen" content="true"/>
<meta name="360-fullscreen" content="true"/>
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="0">
<title>凯撒牧场</title>
<script>
</script>
<script type="text/javascript" src="/farms/Public/Home/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/pasture.js"></script>
<link rel="stylesheet" type="text/css" href="/farms/Public/Home/css/test.css?r=83333">
<script>

	URL_PATHINFO_DEPR = "<?php echo C('URL_PATHINFO_DEPR');?>";
	_module = "<?php echo PHP_FILE;?>/";
	_url = _module + "<?php echo CONTROLLER_NAME; echo C('URL_PATHINFO_DEPR');?>";
	
	if(<?php echo ($user_chicken_house_state); ?>==0){
    	 setTimeout(function(){
             hide_show('show');
             $('#ji_pop_box').show();
    	 },1000)    
	}
			
	var num = <?php echo ($num); ?>;
	var temp = 10;
	var imgurl = "/farms/Public/Home/images/ji/";

	setInterval(function(){

		for(var i=0;i<num;i++){
		
			var width = $('#body').width();
			var height = $('#body').height();
			var thischicken = $('#ji_'+i);
			var img = $('#img_'+i);
			var left = thischicken.position().left;
			var direction = thischicken.attr('direction');
			var type = thischicken.attr('type');
		
			if(direction=="left"){
				var randomnum = Math.floor(Math.random()*50);
				if(left+100>=width || randomnum==1){
					thischicken.attr('state',0);
					thischicken.attr('direction','right');
					img.attr('src',imgurl+type+'-left-0.png');
				}else{
				   var state = thischicken.attr('state');
				   if(state<4){
					   if(state*1!==0 && state*1%2==0){
						   if(type==1){					    
							  thischicken.animate({left:(left+5)+'px'}); 
						   }else{
							  thischicken.animate({left:left+temp+'px'}); 
						   }	
					   }
					   thischicken.attr('state',(state*1+1));
					   img.attr('src',imgurl+type+'-right-'+(state*1+1)+'.png');
				   }else{
					   if(type==1){
						   thischicken.animate({left:(left+5)+'px'});
					   }else{
						   thischicken.animate({left:left+temp+'px'});
					   }
					   thischicken.attr('state',1);
					   img.attr('src',imgurl+type+'-right-1.png'); 
				   }
				}	
			}else if(direction=="right"){
				var randomnum = Math.floor(Math.random()*50);
				if(left<=0 || randomnum==1){
					thischicken.attr('state',0);
					thischicken.attr('direction','left');
					img.attr('src',imgurl+type+'-right-0.png');
				}else{
					var state = thischicken.attr('state');
					if(state<4){
						if(state*1!==0 && state*1%2==0){
						   if(type==1){
							  thischicken.animate({left:(left-5)+'px'}); 
						   }else{
							  thischicken.animate({left:left-temp+'px'}); 
						   }	
						}
						thischicken.attr('state',(state*1+1));
						img.attr('src',imgurl+type+'-left-'+(state*1+1)+'.png');
					}else{
						if(type==1){
						   thischicken.animate({left:(left-5)+'px'});
						}else{
						   thischicken.animate({left:left-temp+'px'});
						}
						thischicken.attr('state',1);
						img.attr('src',imgurl+type+'-left-1.png');
					}
				}
			}
		}
	},300);

</script>

<body>
<div class="kj" id="kj">
	<div class="box">
		<div class="box_two" id="box_two">
			<div class="box_hide"></div>

			<!--通用弹窗-->
			<div class="all_insufficient" id="prompt_box">
				<div class="all_box">
				  <div class="all_box_list">
					<span></span>
				  </div>
				  <div class="all_box_list" onclick="pop_return('prompt_box')" style="width: 39%;margin-left: 35%;"></div>
				</div>
			</div>
			<!--通用弹窗结束-->

			<!--返回农场-->
			<a href="<?php echo U('Index/index');?>" rel="external"><div class="return_farm" id="return_farm"></div></a>
				
			<!--返回农场结束-->

			<!--鸡舍没买弹窗-->
			<div class="ji_pop" id="ji_pop_box">
				<div class="ji_text">你还没有鸡舍，请先进行购买</div>
				<div class="ji_box">					
					<!--<div class="ji_sure" onclick="buy_jihome('ji_pop_box','ji_buy_box')"></div>-->
					<div class="ji_sure" onclick="pages('house')"></div>
					<a href="<?php echo U('Index/index');?>" rel="external"><div class="ji_return"></div></a>									
				</div>  				
			</div>
			<!--鸡舍没买弹窗结束-->

			<!--购买鸡舍弹窗开始-->
			<div class="maintain_box" id="ji_buy_box">
				<div class="maintain_body"></div>
				<div class="maintain_footer">
					<div class="maintain_sure" onclick="house()"></div>
					<div class="maintain_return" onclick="buy_jihome('ji_buy_box','ji_pop_box')"></div>
				</div>
				
			</div>
			<!--购买鸡舍弹窗结束-->

			<!--维护时间-->
			<div class="Maintain_time">
				<?php echo ($end_time); ?>
			</div>
			<!--维护时间结束-->


			<!--抢购弹窗-->
			<div class="panic_pop" id="panic_box">
				<div class="pop_list_box">
				<!--
					<div class="pop_list">
						<div class="panic_time">10:00</div>
						<div class="panic_type"><img src="/farms/Public/Home/images/ji/shop_1.png"></div>
					</div>					
					<div class="panic_firult">
						<div class="panic_firult_list">
							<div class="firult_list">
								<img src="/farms/Public/Home/images/ji/shop_1.png"><span>999</span>
							</div>
							<div class="firult_list">
								<img src="/farms/Public/Home/images/ji/shop_1.png"><span>999</span>
							</div>
						</div>					
					</div>
					<div class="panic_ji_num">商店剩余数量：584</div>
					-->
				</div>
				<div class="panic_shop_box">			
					<div class="panic_num_box">
						<div class="panic_dorp" onclick="count('drop')"></div>
						<div class="panic_input"><input type="number" id="panic_input" value="1" oninput="checkNum(this.value)" id="input"/></div>
						<div class="panic_add" onclick="count('add')"></div>					
					</div>
					<div class="panic_sure_shop" onclick="buy()"></div>
				</div>
				<div class="pop_return" id="pop_return" onclick="pop_return('panic_box')"></div>
			</div>
			<!--抢购弹窗结束-->

			<!--维护弹窗开始-->
			<div class="maintain_box" id="maintain_box">
				<div class="maintain_body"></div>
				<div class="maintain_footer">
					<div class="maintain_sure" onclick="maintain()"></div>
					<div class="maintain_return" onclick="pop_return('maintain_box')"></div>
				</div>
				
			</div>
			<!--维护弹窗结束-->

			<!--日志弹窗开始-->
			<div class="manage_box" id="manage_box">
				<div class="manage"></div> 
				<div class="manage_return" onclick="pop_return('manage_box')"></div>
			</div>
			<!--日志弹窗结束-->

			<!--出售弹窗开始-->
			<div class="sell_box" id="sell_box">
				<div class="sell_body_box"></div>
				<div class="sell_return" onclick="pop_return('sell_box')"></div>
			</div> 
			<!--出售弹窗结束-->
			<!--收入弹窗开始-->
			<div class="income_box" id="income_box">
				<div class="income_body">
				    <!--
					<div class="income_list_sum">
						<div class="income_trends">998</div>
						<div class="income_statics">12</div>
						<div class="income_all_sum">1225</div>
					</div>
					
					<div class="income_list_sum">
						18年9月11日获得动态资金100
					</div>
					<div class="income_page_box">
						<div class="income_lastpage" onclick="pages('income','pre')"></div>
						<div class="income_page_text" onclick="pages('income','next')">1/1</div>
						<div class="income_nextpage"></div>
					</div>
	                 -->
				</div>
				<div class="income_return" onclick="pop_return('income_box')"></div>
			</div>
			<!--收入弹窗结束-->

			<div class="header"></div>
			<div class="body_box">
				<div class="body" id="body">
					<?php $__FOR_START_61821525__=0;$__FOR_END_61821525__=$num;for($i=$__FOR_START_61821525__;$i < $__FOR_END_61821525__;$i+=1){ $direction = array('left','right'); shuffle($direction); $index = rand(10,90); ?>
						 <?php if($direction[0] == 'left'): ?><div onclick="ChickenState(<?php echo ($i); ?>)" id="ji_<?php echo ($i); ?>" state=0 direction="<?php echo ($direction[0]); ?>" harvest_time=<?php echo ($res[$i]['harvest_time']); ?> type=<?php echo ($res[$i]['chicken_id']); ?> class="donghua chicken_<?php echo ($res[$i]['id']); ?>" style="z-index:<?php echo ($index-9); ?>;top:<?php echo ($index); ?>%;left:<?php echo rand(10,85);?>%;">
							   <span id="StateTitle_<?php echo ($i); ?>"></span>
							   <img id="img_<?php echo ($i); ?>" src="/farms/Public/Home/images/ji/<?php echo ($res[$i]['chicken_id']); ?>-right-0.png">
							</div>
						 <?php else: ?>
							<div onclick="ChickenState(<?php echo ($i); ?>)" id="ji_<?php echo ($i); ?>" state=0 direction="<?php echo ($direction[0]); ?>" harvest_time=<?php echo ($res[$i]['harvest_time']); ?> type=<?php echo ($res[$i]['chicken_id']); ?> class="donghua chicken_<?php echo ($res[$i]['id']); ?>" style="z-index:<?php echo ($index-9); ?>;top:<?php echo ($index); ?>%;left:<?php echo rand(10,85);?>%;">
							   <span id="StateTitle_<?php echo ($i); ?>"></span>
							   <img id="img_<?php echo ($i); ?>" src="/farms/Public/Home/images/ji/<?php echo ($res[$i]['chicken_id']); ?>-left-0.png">
							</div><?php endif; } ?>
				</div>
			</div>
			<div class="footer">
				<div class="footer_list_box footer_list_box_drop">
					
				</div>
				<div class="footer_list_box">
					<div class="footer_list" id="panic" onclick="pages('panic')"></div>
					<div class="footer_list" onclick="pages('maintain')"></div>
					<div class="footer_list" onclick="pages('manage')"></div>
					<div class="footer_list" onclick="pages('sell')"></div>
					<div class="footer_list" onclick="pages('income')"></div>
				</div>
			</div>		    	
		</div>
	</div>
</div>
		
</body>
<script type="text/javascript">
</script>
</html>