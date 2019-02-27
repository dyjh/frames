
$(function(){
	//定时获取屏幕宽度并赋予给游戏界面 保持实时刷新
	setTimeout(function(){
		var boxHeight = window.screen.height;
		var boxWidth = window.screen.width;
		$(".box").height(boxHeight+"px");
		$(".box").width(boxHeight/1.78+"px");
		$(".box_hide").height(boxHeight+"px");
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
			if ("Safari" == mb) {
				var boxWidthpc = document.documentElement.clientWidth
				var boxHeightpc= document.documentElement.clientHeight;
				$(".box_hide").height(boxHeightpc+"px");
				$(".box").height(boxHeightpc*1+20);
				$(".box").width(boxWidthpc);
			}
	},100);

	//禁止键盘事件
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
	
	//点击出现农贸市场
	$("#farmproduct_market").click(function(){
		$(".tradingcenter_box").show();
	})
	//返回农贸市场
	$(".market_return").click(function(){
		$(".purchase_interface").hide();
		$(".tradingcenter_box").show();
	})

	//关闭弹窗
	$('.box_list_img').click(function(){
		$('.alert_box').hide();
		$('.box_hide').hide();
	})	

	
	//市价单
	/*
	$("#market_price").click(function(){
		$('.mp_alert').css('z-index',13);
		$(".box_hide").show();
		$(".mp_alert").show();
	})
	$(".mp_return").click(function(){
		$(".box_hide").hide();
		$(".mp_alert").hide();
	})
	*/	
	//市价单
	$('.mp_submit').click(function(){
		if($('#mp_number').val()=='' || $('#mp_number').val()==0 || $('#mp_price').val()=='' || $('#mp_price').val()==0){		
		      $('.mp_alert').css('z-index',10);
              title('价格或数量不能为空');
			  return false;
		}	
		
		var data = "{";
		var url = _module+"Market"+ URL_PATHINFO_DEPR + "pay";
		var d = $('#sweeping').serialize().split("&");
		var str = "";
		for(var i=0;i<d.length;i++){
			var e = d[i].split("=");
			if(e[0]=='type'){
				var type = e[1];
			}
			data+= e[0]+':'+e[1]+",";		
		}
		data+= 'seed:'+$('#seed').val()+',sweeping:1}';
		//判断金币或数量是否足够
        if(type==1){
			 if($('#zygold').text()*1<$("#mp_number").val()*$("#mp_price").val()){
				  title('金币不足');
				  return false;
			 }
		}else if(type==2){
			 if($('#zynumber').text()*1<$("#mp_number").val()*1){
				  title($('#seed').val()+'不足');
				  return false;
			 }
		}
        
		$.post(url,data,function(s){
			 obj = eval("("+s+")");
			 if(obj.state==7 || obj.state==4 || obj.state==1){
				if(type==1){
					var mp_tprice = $("#mp_number").val()*$("#mp_price").val();
					$("#zygold").val($("#zygold").val()-mp_tprice);
					title("挂单成功");
					return false;
				}else if(type==2){
					$("#zynumber").text($("#zynumber").text()-$("#mp_number").val());
					title("挂单成功");
				}			
			}else{
				title(obj.content);
			}
		})
	})
	//市价单计算
	$(".mp_count").click(function(){
		var mp_number = $("#mp_number").val(); 
		var mp_price = $("#mp_price").val();
		var mp_tprice = mp_number*mp_price;
		$("#mp_tprice").val(mp_tprice);
	})
	
})//预加载function 括号

//加载K线图
function line(seed){
	var url = _module  + "Market"+URL_PATHINFO_DEPR+"ajax_k";
	KLine(url,seed);
}


//分时图
function Min(seed){
	var url = _module+ "Market"+URL_PATHINFO_DEPR+"ajax_min";
	Minute(seed,url);
}

//果实价格实时动态,5秒后刷新
function seed_market(){
	var url = _module+ "Market"+ URL_PATHINFO_DEPR +"friut_dynamic";
	var seed = $('#seed').val();
	var data = {seed:seed};
	$.post(url,data,function(d){
		
		if(d!==""){		
			obj = eval("("+d+")");
            (obj.start==0)?$('#start').text('--'):$('#start').text(obj.start);			
            (obj.max==0)?$('#max').text('--').fadeOut(200).fadeIn(200):$('#max').text(obj.max).fadeOut(200).fadeIn(200);	
			(obj.min==0)?$('#min').text('--').fadeOut(200).fadeIn(200):$('#min').text(obj.min).fadeOut(200).fadeIn(200);	
			(obj.count==0)?$('#count').text('--').fadeOut(200).fadeIn(200):$('#count').text(obj.count).fadeOut(200).fadeIn(200);
			
			$('#fruitvariety_prompt').text(obj.new).fadeOut(200).fadeIn(200);
			$('#present_price').text(obj.new).fadeOut(200).fadeIn(200);
				
			if($('#sell_5').val()==undefined){
				var ztjg = $('#the_price').text()*1;
				if((obj.new*1+0.00001*5)>=ztjg){
					$('#sell_jy_price').text('建议价格：'+ztjg.toFixed(5));
				}else{
					$('#sell_jy_price').text('建议价格：'+(obj.new*1+0.00001*5).toFixed(5));
				}   
			}else{
			    $('#sell_jy_price').text('建议价格：'+$('#sell_5').text());
			}
			
			$('#b_1').val(obj.new);
			$('#b_2').val(obj.new);
			
			if(obj.ga>0){
				$('#zde').css('color','red');
				$('#zde').text('涨额：');
				//$('#gad').text('+'+obj.ga.toFixed(5));
				$('#gad').text('+'+obj.ga);
			}else if(obj.ga==0){
				$('#zde').text('涨/跌额：');
				//$('#gal').text(obj.ga.toFixed(5));
				$('#gal').text(obj.ga);
			}else{
				$('#zde').css('color','#70D421');
				$('#zde').text('跌额：');
				//$('#gax').text(obj.ga.toFixed(5));
				$('#gax').text(obj.ga);
			}

			if(obj.gains>0){
				$('#zdf').css('color','red');
				$('#zdf').text('涨幅：');
				$('#gand').text('+'+obj.gains.toFixed(2)+'%');
			}else if(obj.gains==0){
				$('#zdf').text('涨/跌幅：');
				$('#ganl').text(obj.gains.toFixed(2)+'%');
			}else{
				$('#zdf').css('color','#70D421');
				$('#zdf').text('跌幅：');
				$('#ganx').text(obj.gains.toFixed(2)+'%');
			}
		}
	})
}

//买卖实时价委托价切换
function shift(msg){
	if(msg=="buy"){
		$('#b_1').removeAttr("id");
	}else if(msg=='sell'){
		$('#b_2').removeAttr("id");
	}
}

//刷新操作
function refresh_price(msg){
	if(msg=="buy"){
		$('.b_1').attr("id",'b_1');
		$('.b_1').val('正在获取......');
		setTimeout(function(){
			$('#mairu').val('限价买入');
		},5000)
	}else if(msg=='sell'){
		$('.b_2').attr("id",'b_2');
		$('.b_2').val('正在获取......');
		setTimeout(function(){
			$('#maichu').val('限价卖出');
		},5000)
	}else if(msg=='entrust'){
		entrust();
	}else if(msg=="trading"){
		trading();
	}
}

//定义买入计算器
var calculator;

function buy_count(){	
     
	 if(calculator==undefined && $('.b_1').val()!=='正在获取......'){
		 clearInterval(calculatortwo);
		 calculatortwo = undefined;
	     calculator = setInterval(buy_calculator,1000);
	 }
}

//定义卖出计算器
var calculatortwo;

function sell_count(){
	
	if(calculatortwo==undefined && $('.b_2').val()!=='正在获取......'){
		clearInterval(calculator);
		calculator = undefined;
	    calculatortwo = setInterval(sell_calculator,1000);
	}
}

//买入计算器
function buy_calculator(){

	var number = $("#c_1").val();
	var price = $(".b_1").val();
	if(price!==$('#present_price').text()){
		$('#mairu').val('委托买入');
	}
	var total =price*number;
	var jiequ = total.toFixed(4);
	$("#d_1").val(jiequ);
	//console.log('买1');
}

//卖出计算器
function sell_calculator(){
	var price2 = $(".b_2").val();
	var number2 = $("#c_2").val();
	if(price2!==$('#present_price').text()){
		$('#maichu').val('委托卖出');
	}
	var total2 = price2*number2;
	var jiequ2 = total2.toFixed(4);
	$("#d_2").val(jiequ2);
	var counterfee = jiequ2*0.02;
	var counterfeejq = counterfee.toFixed(4);
	$(".sxf").text(counterfeejq);
	//console.log("卖1");
}

//买卖
function goldno(id,num){
	var name = $("#product").text();
	//买入
	if(id==1){
		//消除买入计算器
		clearInterval(calculator);
		calculator = undefined;
		var mcount = $('#c_1').val();  //数量
		var xygold = $("#d_1").val();  //需要多少金币
		var zygold = $("#zygold").text();  //自身金币
		var mprice = $(".b_1").val();
		
		
		//是于是100的倍数
		if(mcount=="" ||　mcount%100!==0){
			title('输入100的倍数');
			exit;	
		}

		//金币是否足够
		if(xygold*1>zygold*1){
			title('金币不足');
			exit;		
		}else{
			if(num==1){
				$(".box_hide").show();
				$(".sure_box_list span").text("确定购买"+mcount+name);
				$(".sure_one").show();
				return false;
			}else if(num==2){
				between(mprice);
				if($(".box_hide").is(":hidden")){
					$(".sure_one").hide();
					$(".alert_boxs").hide();
					$(".box_hide").show();
					$(".alert_box").show();
					$(".all_box_list span").text("提交中，请稍候");
				};
				var seed = $('#seed').val();
				var data = {num:mcount,type:1,seed:seed,money:mprice,sweeping:0};
				var url = _module  + "Market"+ URL_PATHINFO_DEPR + "pay";
				$.post(url,data,function(d){
					obj = eval("("+d+")");
					if(obj.state==1){
						var xygold = $("#d_1").val();
						var zygold = $("#zygold").text();
						var sygold = zygold*1-xygold*1;
						$("#zygold").text(sygold.toFixed(5));
						$(".sure_one").hide();
						title(obj.content);
						
					}else{
						$(".sure_one").hide();
						title(obj.content);
					}
				})
			}
			

		}

	}else if(id==2){
		//消除买入计算器
		clearInterval(calculatortwo);
		calculatortwo = undefined;
		var sprice = $(".b_2").val();  //卖出价格
		var mcnumber = $("#c_2").val(); //卖出数量
		var zynumber = $("#zynumber").text();  //自身有的果实数量

		//是否数量
		if(mcnumber=="" || mcnumber%100!==0){
			title('输入100的倍数');
			exit;
		}
				
		//果实是否足够
		if(mcnumber*1>zynumber*1){
			title("果实不足");
			exit;
		}else{
			if(num==1){
				$(".box_hide").show();
				$(".sure_box_list span").text("确定出售"+mcnumber+name);
				$(".sure_two").show();
			}else if(num==2){
					//当前价格有没有在不在涨停跌停范围内
					between(sprice);
					if($(".box_hide").is(":hidden")){
						$(".sure_two").hide();
						$(".alert_boxs").hide();
						$(".box_hide").show();
						$(".alert_box").show();
						$(".all_box_list span").text("提交中，请稍候");
					};
					var seed = $('#seed').val();
					var data = {num:mcnumber,type:0,money:sprice,seed:seed,sweeping:0};
					var url = _module  + "Market"+ URL_PATHINFO_DEPR + "pay";
					$.post(url,data,function(d){				
						obj = eval("("+d+")");
						if(obj.state==1){
							var zynumber = $("#zynumber").text();
							var mcnumber = $("#c_2").val();
							var synumber = zynumber*1-mcnumber*1;
							$("#zynumber").text(synumber);
							$(".sure_two").hide();
							title(obj.content);
						}else{
							$(".sure_two").hide();
							title(obj.content);
						}			
				})
			}
			

		}
	}
}

//隐藏买卖确定
function sruehide(id){
	if(id==1){
		$(".box_hide").hide();
		$(".sure_one").hide();
	}else if(id==2){
		$(".box_hide").hide();
		$(".sure_two").hide();
	}
}

/*
//买卖
function goldno(id){
	//买入
	if(id==1){
		//消除买入计算器
		clearInterval(calculator);
		calculator = undefined;
		var mcount = $('#c_1').val();  //数量
		var xygold = $("#d_1").val();  //需要多少金币
		var zygold = $("#zygold").text();  //自身金币
		var mprice = $(".b_1").val();
		
		
		
		//是于是100的倍数
		if(mcount=="" ||　mcount%100!==0){
			title('输入100的倍数');
			exit;
		}

		//金币是否足够
		if(xygold*1>zygold*1){
			title('金币不足');
			exit;
		}else{
			//当前价格有没有在不在涨停跌停范围内
			between(mprice);
			if($(".box_hide").is(":hidden")){
				$(".box_hide").show();
				$(".alert_box").show();
				$(".all_box_list span").text("提交中，请稍候");
			};
			var seed = $('#seed').val();
			var data = {num:mcount,type:1,seed:seed,money:mprice,sweeping:0};
			var url = _module  + "Market"+ URL_PATHINFO_DEPR + "pay";
			$.post(url,data,function(d){
				obj = eval("("+d+")");
				if(obj.state==1){
					var xygold = $("#d_1").val();
					var zygold = $("#zygold").text();
					var sygold = zygold*1-xygold*1;
					$("#zygold").text(sygold.toFixed(5));
					
					title(obj.content);
					
				}else{
					title(obj.content);
				}
			})
		}

	}else if(id==2){
		//消除买入计算器
		clearInterval(calculatortwo);
		calculatortwo = undefined;
		var sprice = $(".b_2").val();  //卖出价格
		var mcnumber = $("#c_2").val(); //卖出数量
		var zynumber = $("#zynumber").text();  //自身有的果实数量

		//是否数量
		if(mcnumber=="" || mcnumber%100!==0){
			title('输入100的倍数');
			exit;
		}
				
		//果实是否足够
		if(mcnumber*1>zynumber*1){
			title("果实不足");
			exit;
		}else{
			//当前价格有没有在不在涨停跌停范围内
			between(sprice);
			if($(".box_hide").is(":hidden")){
				$(".box_hide").show();
				$(".alert_box").show();
				$(".all_box_list span").text("提交中，请稍候");
			};
			var seed = $('#seed').val();
			var data = {num:mcnumber,type:0,money:sprice,seed:seed,sweeping:0};
			var url = _module  + "Market"+ URL_PATHINFO_DEPR + "pay";
			$.post(url,data,function(d){				
				obj = eval("("+d+")");
				if(obj.state==1){
					var zynumber = $("#zynumber").text();
					var mcnumber = $("#c_2").val();
					var synumber = zynumber*1-mcnumber*1;
					$("#zynumber").text(synumber);
					title(obj.content);
				}else{
					title(obj.content);
				}
			})
		}
	}
}*/

//买卖排队，5秒刷新一次
function find_buy(){
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "find_buy";
	var seed = $('#seed').val();
	var data = {seed:seed};
	$.post(url,data,function(d){
		$('.entrust_list_box').html('');
		$('.entrust_list_box').html(d);
		$('.entrust_list_box').fadeOut(200).fadeIn(200);
	})
	
	var marketdate = new Date(); 
	var hours = marketdate.getHours();
	if(hours>=20 || hours<10){
		clearInterval(seed_markets);
		clearInterval(find_buys);
	}		  
}

//委托记录，六秒后刷新一次，之后手动刷新
function entrust(){
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "entrust";
	var seed = $('#seed').val();
	var data = {seed:seed};
	$.post(url,data,function(d){
		$('.history_entrust_list_wt').html('');
		$('.history_entrust_list_wt').html(d).fadeOut(200).fadeIn(200);
	})
}

//交易记录，六秒后刷新一次，之后手动刷新
function trading(){
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "trading";
	var seed = $('#seed').val();
	var data = {seed:seed};
	$.post(url,data,function(d){
		if(d!==""){
			$('.history_entrust_list_jy').html('');
			$('.history_entrust_list_jy').html(d).fadeOut(200).fadeIn(200);
		}
	})
}

//撤销委托
function undo(time,id){
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "undo";
	var data = {id:id,time:time};
	$.post(url,data,function(d){
		var d = JSON.parse(d);
		//alert(d);
		//alert(d.money);
		
		
			$('#entrust_'+id).remove();
			title(d.money);
		
	})
}


//提示弹窗
function title(content){
	$('.box_hide').show();
	$('.alert_box').show();
	$('.all_box_list span').text(content);
}

//涨、跌停价区间
function between(msg){    
	if(msg>zt){
		$(".sure_one").hide();
		$(".sure_two").hide();
		title('不能高于涨停价');	
		exit;
	}
	if(msg<dt){
		$(".sure_one").hide();
		$(".sure_two").hide();
		title('不能低于跌停价');
		exit;
	}
}

//网络连接中断
function refresh(time){
	//获取登陆时间
	if(time==undefined){
		last_time = Date.parse(new Date())/1000;
	}else{
		last_time = time;
	}
	
	now_time = Date.parse(new Date())/1000;
	if((now_time-last_time)/60>15){
		$(".box_hide").show();
		$('.internet_box').show();
		clearInterval(find_buys);
		clearInterval(seed_markets);
		if(calculator!==undefined){
			clearInterval(calculator);
		}
		if(calculatortwo!==undefined){
			clearInterval(calculatortwo);
		}
	}else{
		setTimeout('refresh(last_time)',960000);
	}
}

//买卖最大值
function max(type){
	var zygold = $("#zygold").text();
	var price = $(".b_1").val();
	var friutnumber = $("#zynumber").text();
	if(type=="buy"){
		var max = zygold/price%100;
		max = zygold/price*1-max*1;
		$("#c_1").val(max);	
	}else if(type=="sell"){
		var max = friutnumber%100;
		max = friutnumber*1-max;
		$("#c_2").val(max);
	}
}

function brush(type){
	var url = _module  + "Market"+ URL_PATHINFO_DEPR + "brush";
	$.post(url,datd={type:type,seed:$('#seed').val()},function(d){
		 if(type=="fruit"){
			 $('#zynumber').text(d).fadeOut(100).fadeIn(100);
		 }else{
			 $('#zygold').text(d).fadeOut(100).fadeIn(100);	
		 }
	})
}


//限制卖出价格小数点后5位
function clearNoNum(obj){    
      obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d\d\d\d).*$/,'$1$2.$3');//只能输入5个小数      
    }


var seed_markets = setInterval(seed_market,5000);
var find_buys = setInterval(find_buy,3000);
setTimeout(entrust,6000);
setTimeout(trading,6000);
setTimeout('refresh()',1000);

