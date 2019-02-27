<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="shortcut icon" href="/farms/Public/Home/images/apple-icon.png"/>
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
<title>凯撒庄园</title>
<link rel="stylesheet" type="text/css" href="/farms/Public/Home/css/market.css" />
<script type="text/javascript" src="/farms/Public/Home/js/jquery-3.1.1.min.js"></script>
<!--<script type="text/javascript" src="/farms/Public/Home/js/market.js"></script>-->
</head>
<script>
	URL_PATHINFO_DEPR = "<?php echo C('URL_PATHINFO_DEPR');?>";
	_module = "<?php echo PHP_FILE;?>/";
	_url      = _module + "<?php echo CONTROLLER_NAME; echo C('URL_PATHINFO_DEPR');?>";

$(function(){
   setTimeout(function(){
		var boxHeight = document.documentElement.clientHeight;
		var boxWidth = document.documentElement.clientWidth;
		var boxWidthyd = window.screen.width;
		$(".box").height(boxHeight+"px");
		$(".box").width(boxWidthyd+"px");
		if(boxWidthyd*1>768){
			$(".box").height(boxHeight+"px");
			$(".box").width(boxHeight/1.78+"px");
		}
		//$("#container").width(boxWidth/1.78+"px");
		//$("#myCanvas").height(boxHeight+"px");
		//$("#myCanvas").width(boxHeight/1.78+"px");
		
		function myBrowser(){
			    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
			    var isOpera = userAgent.indexOf("Opera") > -1;
			    if (isOpera) {
			        return "Opera"
			    }; //判断是否Opera浏览器
			    if (userAgent.indexOf("Firefox") > -1) {
			        return "FF";
			    } //判断是否Firefox浏览器
			    if (userAgent.indexOf("Chrome") > -1){
			  return "Chrome";
			 }
			    if (userAgent.indexOf("Safari") > -1) {
			        return "Safari";
			    } //判断是否Safari浏览器
			    if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
			        return "IE";
			    }; //判断是否IE浏览器
			}
			//以下是调用上面的函数
			var mb = myBrowser();
			/*if ("IE" == mb) {
			    alert("我是 IE");
			}
			if ("FF" == mb) {
			    alert("我是 Firefox");
			}
			if ("Chrome" == mb) {
			    alert("我是 Chrome");
			}
			if ("Opera" == mb) {
			    alert("我是 Opera");
			}*/
			if ("Safari" == mb) {
				var boxWidthpc = document.documentElement.clientWidth
				var boxHeightpc= document.documentElement.clientHeight;
				$(".box_hide").height(boxHeightpc+"px");
				$(".box").height(boxHeightpc*1+20);
				$(".box").width(boxWidthpc);
			}
	},100);
	
	
	$('body').click(function(){
	     refresh(Date.parse(new Date())/1000);
	})
	//网络链接中断返登录界面
	$('.internet_right').click(function(){
//		window.location.href="http://"+window.location.host+"/farms/index.php/Home/Login";
		window.location.href= _module  + "Login";
	})
})


//农贸市场
function market_dynamic(){

//	var url = "http://"+window.location.host+"/farms/index.php/Home/Market/market_dynamic";
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "market_dynamic";

		var data = {};
		$.post(url,data,function(d){
			  var Json = eval(d);
			  for(var i=0;i<Json.length;i++){
			  //$('#unit_'+i).text(Json[i].new);
					if(Json[i].gains*1>0){
						  $('#rise_'+i).css('color','red');
						  $('#rise_'+i).text('+'+Json[i].gains.toFixed(2)+'%');
						  $('#unit_'+i).css('color','red');
						  $('#unit_'+i).text(Json[i].new);
					}else if(Json[i].gains*1<0){
						  $('#rise_'+i).css('color','#70D421');
						  $('#rise_'+i).text(Json[i].gains.toFixed(2)+'%');
						  $('#unit_'+i).css('color','#70D421');
						  $('#unit_'+i).text(Json[i].new);
					}else{
						  $('#rise_'+i).css('color','#FFF');
						  $('#rise_'+i).text(Json[i].gains.toFixed(2)+'%');
						  $('#unit_'+i).css('color','#FFF');
						  $('#unit_'+i).text(Json[i].new);
					}
					
				 $('#unit_'+i).fadeOut(200).fadeIn(200);
				 $('#rise_'+i).fadeOut(200).fadeIn(200);
			  }	

              var marketdate = new Date(); 
			  var hours = marketdate.getHours();
              if(hours>=20 || hours<10){
			      clearInterval(market);
			  }		  
		})
}
market_dynamic();
var market = setInterval(market_dynamic,5000);

//网络连接中断
function refresh(time){
    //获取登陆时间
   if(time==undefined){
       last_time = Date.parse(new Date())/1000;	   
   }else{
       last_time = time;
   }
   //当前时间
   now_time = Date.parse(new Date())/1000;
   //如果在于15分钟未操作将断开链接
   //console.log('最后操作时间:'+last_time);
   //console.log('当前时间:'+now_time);
   //ss = (now_time-last_time)/60;
   //console.log('未操作时间:'+ss);
   if((now_time-last_time)/60>15){
        clearInterval(market);
        $(".market_box_hide").show();
        $('.internet_box').show();
        clearInterval(scoll);
        clearInterval(planting);
   }else{
        setTimeout('refresh(last_time)',960000);
   }
}

setTimeout('refresh()',1000);

$(function(){
	$("body").keydown(function(){
			return key(arguments[0])});
			function key(e){var keynum;
			if(window.event){
				keynum=e.keyCode;
			}else if(e.which){
				keynum=e.which;
			} 
			if(keynum==123){
				window.close();return false;
			}
	}
})

</script>
<body oncontextmenu=return(false)>
<style type="text/css">
</style>
<div class="kj" id="kj">
	<div class="box">
	<div class="market_box_hide"></div>
		<!--网络连接中断-->
        <div class="internet_box">
  				<div class="internet_body">
            <div class="inter_list">
  					    <span>请重新登录</span>
  				  </div>
            <div class="internet_footer">
               <div class="internet_right"></div>
            </div>
          </div>
  		  </div>
        <!--网络连接中断结束-->
		<!--交易中心界面开始-->
		<div class="tradingcenter_box">
			<div class="tradingcenter">
				<?php if(is_array($data)): foreach($data as $key=>$v): ?><a href="<?php echo U('Market/pay');?>?id=<?php echo ($v['id']); ?>"><div class="trading_body">
							<div class="data_list" style="margin-left: 6%">
									<div class="data_classify"><span id="unit_<?php echo ($key); ?>">正在获取...</span></div>
									<div class="data_classify" style="margin-top: 2%"><span id="rise_<?php echo ($key); ?>">正在获取...</span></div>
									<div class="trading_fruits">
										<img src="/farms/Public/Home/images/market/<?php echo ($v['img_name']); ?>.png">
									</div>
									<div class="trading_titlefruits"><span><?php echo ($v['varieties']); ?></span></div>
							</div>
						</div></a><?php endforeach; endif; ?>	
			</div>
		</div>
		<a href="<?php echo U('Index/index');?>"><div class="tradingcenter_returna tradingcenter_return"></div></a>
	</div>
</div>
</body>
</html>