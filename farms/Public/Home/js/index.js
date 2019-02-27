 $(function(){
	
	setTimeout(function(){
		var boxHeight = window.screen.height;
		var boxWidth = window.screen.width;
		var boxHeightpc= document.documentElement.clientHeight;
		var box_two = $(".box_two");		
		box_two.height(boxHeightpc).width(boxWidth);
	
		if(box_two.height()<=600){
			box_two.height(600+"px");
		}else if(boxWidth>768){
				var boxHeightpc= document.documentElement.clientHeight;
				var box = $(".box");
				box.height(boxHeightpc+"px").width(boxHeightpc/1.78+"px");
				box_two.height(boxHeightpc+"px").width(boxHeightpc/1.78+"px");
												
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
		}
		if(boxHeight<=568){
			box_two.height(548+"px");
		}else if(boxHeight*1==736&&boxWidth*1==414&&box_two.height()<=700){
			box_two.height(720+"px");
		}
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
				box_two.height(boxHeightpc*1+20);
				box_two.width(boxWidthpc);
				if(boxHeight*1==736&&boxWidth*1==414&&box_two.height()<=700){
					box_two.height(720+"px");
				}
			}
		
	},100)
    

    //点击公告
    $(".ga_return").click(function(){
		$(".game_announcement").hide();
        console.log($(".ga_box").length);
		mask('close');
		var url = _module  + "User"+ URL_PATHINFO_DEPR + "notice";
		$.post(url,data={length:$(".ga_box").length},function(d){})
	})


	//触摸或点击事件
	$(".interaction").touchend(function(){
		var id = this.id;
		setTimeout(function(){
			$("#land_prompt_"+id).hide();
		},1000);
		//点击
		if($('#land_prompt_'+id).is(':hidden')){
			$(".land_prompt").hide();
			GetId(id);
		}
	});
    
	//触摸释放
	/*$(".interaction").touchstart(function(){
		var id = $(this).attr('id');
	})*/

	//开启设置
	$(".set_up").click(function(){
		mask('open');
		$(".setup_alert").show();
	})
	
    //关闭设置
	$(".setup_return").click(function(){
		$(".setup_alert").hide();
		mask('close');
		return false;
	})

	//开启充值
	$(".recharge").click(function(){
		mask('open');
		$(".recharge_interface").show();
	})
    
	//关闭充值
	$(".recharge_return").click(function(){
		$(".recharge_interface").hide();
		mask('close');
		return false;
	})
	//点击头像出现用户信息
	$(".head_portrait").click(function(){
		mask('open');
		$(".head_show").show();
	})
	//开启底部菜单
	$(".footer_menu").click(function(){
		if($(".footer_menubar").is(':hidden')){
			$(".footer_menubar").show();
			$(".footer_menu2").show();
		}else{
			$(".footer_menubar").hide();
			$(".footer_menu").show();
		}
	})
	
	//关闭底部菜单
	$(".footer_menu2").click(function(){
		$(".footer_menu2").hide();
	})

	/*点击body隐藏土地弹出框*/
	$(".interaction").click(function(event){
		event.stopPropagation();
	})

	/*开宝箱倍数验证*/
	$("#fruit_rate").blur(function(){
		var rate = $("#fruit_rate").val();
		if(rate>999){
			$("#fruit_rate").val(10);
		}else if(rate<1){
			$("#fruit_rate").val(1);
		}
	});

	//网络中断返回
	$('.internet_right').click(function(){
		window.location.href= _module + "Login";
	})
	
	//PC点击土地
	$(".interaction").mousedown(function(id){
    	 start_time = Date.parse(new Date())/1000;
    })

    $(".interaction").mouseup(function(){
		
		 var id = this.id; 
    	 end_time = Date.parse(new Date())/1000;
    	 if(end_time==start_time){
    	 	  GetId(id);
    	 }else{  	
			  box_hide('undefined',id);
			  MonId(id);
    	 	  setTimeout(function(){
				  $(".land_prompt").hide();
    	 	  },1000);
    	 }
    })	
	
	//活动公告
	/*function myGod(id,w){
		 var boxWidth = window.screen.width;
		 var box=document.getElementById(id),can=true,w=w||1500,fq=fq||10;
		 if(boxWidth>768){
			var n=-1;
		 }else{
			var n=-0.6; 
		 }		 
		 new function(){ 
			 box.scrollTop==0?box.scrollTop=18:box.scrollTop+=n; 
			 setTimeout(arguments.callee,box.scrollTop%18?fq:w);
		 };
	};
	
	myGod('text_boxx',4000);*/
	//点击果实兑换服务按钮进入兑换界面
	$("#ser_btn").click(function(){
		$(".warehouse_window").hide();
		$(".box_hide").show();
		$("#firut_ser_window").show();	
	})

})//预加载


//触摸事件移动端
$(document).on("pagecreate","#pageone",function(){
	$(".interaction").on("taphold",function(){
		var id = this.id;
		box_hide('undefined',id);
		MonId(id);
	})
})

$(document).click(function(event){
	var windows = ["other","planting","harvest"];
	for(var i in windows){
		$('.'+windows[i]+"_window").hide();
	}
	//重置最后操作时间
	refresh(Date.parse(new Date())/1000);
});

function GetId(id){
	var land_sum_state = $('#land_sum_'+id).attr('state');
	if(land_sum_state==undefined){
	    var planting_state = $('#planting_num_'+id).attr('planting_state');
	    var seed_state = $('#planting_num_'+id).attr('seed_state');
	    if(planting_state==undefined){
		    if(id-$('#level').val()==1){
			    // if(id>=7){
				   // prompt('7级以上暂未开放'); 
			    // }else{
				   open_windows('upgrade',id); 
			    // ｝
		    }
	    }else if(planting_state==1){
		   open_windows('planting',id);
	    }else if(planting_state==2){
		   if(seed_state==3){
			   open_windows('harvest',id);
		   }else{
			   open_windows('other',id);
		   }
	    }
	}else if(land_sum_state==1){
		 open_windows('planting',id);
	}else if(land_sum_state==2){
		 open_windows('other',id);
	}else if(land_sum_state==3){
		 open_windows('harvest',id);
	} 
}

function MonId(id){
	
	 var seed_state = $('#seeds_'+id).attr('seed_state');
     var seed_type = $('#seeds_'+id).attr('seed_type'); 
	 var hour = ($('#seeds_'+id).attr('harvest_time')-Date.parse(new Date())/1000)/3600;
	 var hour = Math.floor(hour);
	 var min = (($('#seeds_'+id).attr('harvest_time')-Date.parse(new Date())/1000)-(hour*3600))/60;
	 var min = Math.floor(min);
	  
		if(id<=$('#level').val()*1 && ($('#planting_num_'+id+' img').length>0 || $('#seeds_'+id+' img').length>0)){		
			if(seed_state==1 || seed_state==2){
			   if(Date.parse(new Date())/1000<$('#seeds_'+id).attr('harvest_time')){
			        var str = '<p>['+seed_type+']正在生长期</p><span><img src="/farms/Public/Home/images/index/clock.png">离收获还有'+hour+'小时'+min+'分</span>';
			   }else{
					var str = '<p>正在冲刺中，果实即将成熟</p><span><img src="/farms/Public/Home/images/index/clock.png"></span>';
					var url = _module +"User"+ URL_PATHINFO_DEPR +"lateripe";
					var data = {number:id};
					$.post(url,data,function(){});
			   }
			}else if(seed_state==3){
				var str = '<p>['+seed_type+']</p><span><img src="/farms/Public/Home/images/index/clock.png">果实已成熟，请及时收获</span>';
			}else if(seed_state==0){
				var str = '<p>植物正在生长期</p><span><img src="/farms/Public/Home/images/index/clock.png">离收获还有'+hour+'小时'+min+'分</span>';  	
			}else{
				var str = '<p>正在获取植物的生长状态</p><span><img src="/farms/Public/Home/images/index/clock.png"></span>';
            }			
		    $("#land_prompt_"+id).html(str);
		    $("#land_prompt_"+id).show();			
		}	
}

//打开对应的弹窗
function open_windows(cases,id){
	$("."+cases+"_window").hide();
	
	box_hide(cases,id);
}

//窗口隐藏
function box_hide(cases,id){

	var windows = ["upgrade","other","planting","harvest"];
	if(cases==undefined){
		for(var i in windows){
			$("."+windows[i]+"_window").hide();
		}
	}else{
		for(var i in windows){
			if(windows[i]==cases){
				if(cases=="upgrade"){
					var url = _module +"User"+ URL_PATHINFO_DEPR +"upgrade_state";
					var data = {id:id,token:$('#token').val()};
					$.post(url,data,function(d){
                        $("."+cases+"_window").show();
						$(".upgrade_box").html(d);
						$('#land_number').val(id);
						mask("open");
					},"json")
				}else{
					$("."+cases+"_window").show(50);
					$('#land_number').val(id);
				}
			}else{
				$("."+windows[i]+"_window").hide();
			}
		}
	}
}

//用户操作
function User_action(cases){
	
	if(cases=="fertilization" || cases=="disaster"){
		$('.other_window').css('display','none');
	}else{
		$('.'+cases+'_window').css('display','none');
	}
	var number = $('#land_number').val();
	var level = $('#level').val();
	var url = _module+"User"+ URL_PATHINFO_DEPR+cases;
	var data = {number:number,level:level,token:$('#token').val()};
	$.post(url,data,function(d){
		return_code(d,number);
	})
}


//仓库点击出现提示信息
function click_warehouse(e){

	var e = parseInt(e)+1;
	var title_length = $(".warehose_prompt").size();

	for(var i=1;i<=title_length;i++){
		if(i==e){
			$(".warehose_prompt_"+i).show();
			var temp = i;
			setTimeout(function(){
				$(".warehose_prompt_"+temp).hide();
			},3000);
		}else{
			$(".warehose_prompt_"+i).hide();
		}
	}
}

//材料兑换加减方法
function count(cases,type,id){

	if(cases=="exchange"){
		var number = $(".deals_number_"+id).val();
		switch (type){
			case 1:
				var number = parseInt(number)-1;
				$('.deals_number_'+id).val(number);
				if(number<=1){
					$('.deals_number_'+id).val(1);
				}
				break;
			case 2:
				var number = parseInt(number)+1;
				$('.deals_number_'+id).val(number);
				break;
		}

	}else if(cases=="shop"){
		var number = $(".shop_number_"+id).val();
		switch (type) {
			case 1:
				var number = parseInt(number)-1;
				$('.shop_number_'+id).val(number);
				if(number<=1){
					$('.shop_number_'+id).val(1);
				}
				break;
			case 2:
				var number = parseInt(number)+1;
				$('.shop_number_'+id).val(number);
				if(number>=99999){
					$('.shop_number_'+id).val(99999);
				}
				break;
		}
	}
	else if(cases=="ser_firt"){
		var number = $(".ser_firt_"+id).val();
		switch (type){
			case 1:
				var number = parseInt(number)-1;
				$('.ser_firt_'+id).val(number);
				if(number<=1){
					$('.ser_firt_'+id).val(1);
				}
				break;
			case 2:
				var number = parseInt(number)+1;
				$('.ser_firt_'+id).val(number);
				break;
		}
	}
}

//兑换
function exchange_fruit(id,type){

	if(type==1){
		if(parseInt($('.have_fruita_'+id).text())>=parseInt($('.needfruita_'+id).text()*$(".deals_number_"+id).val()) && parseInt($('.have_fruitb_'+id).text())>=parseInt($('.needfruitb_'+id).text())*$(".deals_number_"+id).val()){
			var url = _module  + "User"+ URL_PATHINFO_DEPR + "Material";
			var count = $('.deals_number_'+id).val();
			var cases = 'material';
			var data = {id:id,count:count,cases:cases};
			$.post(url,data,function(d){
				return_code(d,id);
			})
		}else{
			mask('open');
			prompt('果实不足','fruit');
		}
	}else if(type==2){
		var need_coin = $(".gold_"+id).text()*$(".deals_number_"+id).val();
		if($("#coin").val()>=need_coin){
			var url = _module  + "User"+ URL_PATHINFO_DEPR + "Material";
			var count = $('.deals_number_'+id).val();
			var coin = $('#coin').val();
			var cases = 'coin';
			var data = {id:id,count:count,cases:cases,coin:coin};
			$.post(url,data,function(d){
				var s_coin = $("#coin").val()*1-need_coin;
				$("#coin").val(s_coin);
				var number = "";
				return_code(d,number);
			})
		}else{
			mask('open');
			prompt('金币不足','coin');
		}
	}
}


//购买
function buy(id){
	var numbermax = $(".shop_number_"+id).val();
	if(numbermax*1>10000){
        prompt('数量最多10000');
		return false;
	}
	var diamond = $('#diamond').val();
	var counts = $('.need_'+id).text()*$('.shop_number_'+id).val();

	if(id>=7 && id<=10){
		
		var url = _module  + "User"+ URL_PATHINFO_DEPR + "treasurebuy";
		var buy = $('#buy_'+id).val();
		var buy_count = $('.shop_number_'+id).val();
		var data = {id:id,buy:buy,counts:counts,buy_count:buy_count};
		$.post(url,data,function(d){
			var number = "";
			return_code(d,number);
		})

	}else if(id>10){

		if(diamond>=counts){
			var url = _module  + "User"+ URL_PATHINFO_DEPR + "servicebuy";
			var count = $('.shop_number_'+id).val();
			var data = {id:id,count:count};
			$.post(url,data,function(d){
				return_code(d,counts);
			})
		}else{
			mask('open');
			prompt('宝石不足','diamond');
		}
	}else{
		if(diamond>=counts){
			if(id==6 && counts*1>200000){
				mask('open');
				prompt('超过2000金币购买限制');
				exit;
			}
			var url = _module  + "User"+ URL_PATHINFO_DEPR + "shopbuy";
			var count = $('.shop_number_'+id).val();
			var data = {id:id,count:count};
			$.post(url,data,function(d){
				return_code(d,counts);
			})
		}else{
			mask('open');
			prompt('宝石不足','diamond');
		}
	}
}

//充值
function topup(msg){
	var coin = $('#coin').val();
	if(coin>=msg){
		var url = _module  + "User"+ URL_PATHINFO_DEPR + "diamond";
		var data = {coins:msg};
		$.post(url,data,function(d){
			return_code(d,msg);
		})
	}else{
		mask('open');
		prompt('金币不足','coin');
	}
}

//页数
function page(msg){

	if(msg=="next"){
		var page = $('#page').val()*1+1;
	}else if(msg=="pre"){
		var page = $('#page').val()*1-1;
		if(page<1){
			page = 1;
		}
	}

	var nowtype = $('#type').val();
	var list = nowtype.split("|");
	for(var i=0;i<list.length;i++){
		var cases = list[list.length-2];
		var type = list[list.length-1];
	}

	if(nowtype=="exchange" || nowtype=="ranking" || nowtype=="log"){
		var url = _module  + "Menudata"+ URL_PATHINFO_DEPR + nowtype;
	}else{
		var url = _module  + "Menudata"+ URL_PATHINFO_DEPR + cases;
	}

	var data = {page:page,type:type};
	$.post(url,data,function(d){

		if(d!==""){
			if(type=="fruit" || type=="material" || type=="prop"){
				$('.warehouse_show').html('').html(d);
			}

			if(type=="exchange"){
				$('.exchange_body').html('').html(d);;
			}

			if(type=="seed" || type=="shopprop" || type=="treasure" || type=="service"){
				$('.shop_body').html('').html(d);;
			}

			if(type=="ranking"){
				$('.ranking_body').html('').html(d);;
			}

			if(type=="log"){
				$('.market_list').html('').html(d);;
			}

			$('#page').val(page);

		}//else{
		//alert('数据加载完毕');
		//}
	},
	"json"
	)
}

//关闭窗口
function close_windows(id){
	
	if(id==1){
		mask('close');
		var windows = ["upgrade","other","planting","harvest","exchange","shop",'ranking','market','warehouse','prompt','box','head','reborn','firut_ser'];
		for(var i in windows){
			$('.'+windows[i]+"_window").hide();
		}
	}else{
		$(".prompt_window").hide();
			if($(".box_hide").is(":hidden")){
				mask('open');
			}else{
				mask('close');
			}
	}
	$('#page').val('');
}

//开宝箱
function open_box(type){

	if(type==1){
		
		if($('.rate_number').val()>1000){
			$('.chest_footer span').text('倍数最高为1000');
			exit;
		}
		
		$('.chest_footer span').text('');
		var boxname = $('#box').val();
		var rate_number = $('.rate_number').val();
		var url = _module  + "User"+ URL_PATHINFO_DEPR +"openbox";
		var data = {boxname:boxname,rate_number:rate_number};
		$('.chest_strip img').attr('src','/farms/Public/Home/images/box/cheststrip.gif');
		$.post(url,data,function(d){
			if(d.length>10){
				setTimeout(function(){
					obj = eval("("+d+")");
					$('.chest_footer span').text('获得'+obj.open_seed+'x'+obj.open_seed_num);
					$('.chest_open img').remove();
					$('.chest_open').append('<img src="/farms/Public/Home/images/box/goopen.png">');
				},500)
			}else{
				setTimeout(function(){
					$('.chest_footer span').text(d);
					$('.chest_open img').remove();
					$('.chest_open').append('<img src="/farms/Public/Home/images/box/goopen.png">');
				},500)
			}
		})

	}else{

		$('#box').val(type);
		$(".rate_number").val(1);
		$('.chest_footer span').text('增加倍数需消耗对应果实数量');

		var box = {'黄铜宝箱':'huangtong','白银宝箱':'baiying','黄金宝箱':'huangjing','钻石宝箱':'zhuanshi'};
		for(i in box){
			if(i==type){
				var name = box[i];
			}
		}

		switch(type){
			case '黄铜宝箱':
				$('.chest_list span').text('黄铜宝箱虽然便宜，但是也能开出不少好东西');
				break;
			case '白银宝箱':
				$('.chest_list span').text('连箱子都是白银做的，里面的东西当然更好');
				break;
			case '黄金宝箱':
				$('.chest_list span').text('博一博，单车变摩托；拼一拼，石头变黄金');
				break;
			case '钻石宝箱':
				$('.chest_list span').text('话不多说，给你一个胜天半子的机会');
				break;
		}

		$('.chest_img').attr('src','/farms/Public/Home/images/box/'+name+'.png');
		$('.chest_open img').remove();
		$('.chest_box').show();
	}
}

//头像相关
function select_head(id){
	for(var i=1;i<=6;i++){
		if(id==i){
			$('.head_'+i).css('border','2px solid #F00');
			$('#head').val(i);
		}else{
			$('.head_'+i).css('border','none');
		}
	}
}

//选择头像
function head_sure(){
	if($('#head').val()==""){
		$('.head_title span').text('请选择头像');
		$('.head_title').show();
		setTimeout(function(){
			$('.head_title').hide();
		},1000)
		return false;

	}else{
		var headnumber = $('#head').val();
	}

	if($('.head_user').val()==""){
		$('.head_title span').text('请填写昵称');
		$('.head_title').show();
		setTimeout(function(){
			$('.head_title').hide();
		},1000)
		return false;
	}else{
		if($(".head_user").val().length<7 && $(".head_user").val().length>2){
			var nickname = $('.head_user').val();
		}else{
			$('.head_title span').text('昵称请填写2-6位');
			$('.head_title').show();
			setTimeout(function(){
				$('.head_title').hide();
			},1000)
			return false;
		}
	}
	
	var url = _module  + "User"+ URL_PATHINFO_DEPR + "sel_head";
	var data = {headnumber:headnumber,nickname:nickname};
	$.post(url,data,function(d){
		if(d==1){
			$('.select_head').hide();
			mask('close');
			$('.head_portrait').append('<img src="/farms/Public/Home/images/headimg/head'+headnumber+'.png">');
			//出现新手指引
			$(".guidance").show();
			$(".guidance").click(function(){
				mask('open');
				$(".newguidance").show();
				$(".guidance").hide();
				return false;		
			})
			$("#guidance_sure").click(function(){
				mask('close');
				$(".newguidance").hide();
				return false;
			})
			$("#guidance_return").click(function(){
				mask('close');
				$(".newguidance").hide();
				$(".guidance").show();
				return false;
			})
		}else{
			$('.head_title span').text('系统故障，请稍后再试');
			$('.head_title').show();
			setTimeout(function(){
				$('.head_title').hide();
			},1000)
		}
	})
}


//通用弹窗
function prompt(content,operate){
	
	if($('#laber').length>0){
		$('#laber').remove();
	}
	
	if(operate=="seed" || operate=="fertilizer" || operate=="disasters"){
	    var str = "<laber id='laber' onclick='guide(\""+operate+"\")' style='margin-left:5%;padding:1% 3% 1% 3%;background:#CF9B50;color:#FFF;border-radius:5px'>购买</laber>";
	    $('.all_box_list span').after(str); 
	}else if(operate=="material" || operate=="diamond"){
		var str = "<laber id='laber' onclick='guide(\""+operate+"\")' style='margin-left:5%;padding:1% 3% 1% 3%;background:#CF9B50;color:#FFF;border-radius:5px'>兑换</laber>";
	    $('.all_box_list span').after(str); 
	}else if(operate=="fruit"){
		var str = "<laber id='laber' style='margin-left:5%;padding:1% 3% 1% 3%;background:#CF9B50;border-radius:5px'><a style='color:#FFF;text-decoration:none' href='/farms/Market/index.html' rel='external'>去购买</a></laber>";
	    $('.all_box_list span').after(str); 
	}else if(operate=="coin"){
		var str = "<laber id='laber' style='margin-left:5%;padding:1% 3% 1% 3%;background:#CF9B50;border-radius:5px'><a style='color:#FFF;text-decoration:none' href='/ksnc/User-login-is_pay-1' rel='external'>去充值</a></laber>";
	    $('.all_box_list span').after(str); 
	}
	
	$('.box_hide').show();
	$('.all_box_list span').text(content);
	$('.prompt_window').show();
}


//引导
function guide(type){
   
   $('.prompt_window').hide();
   
   switch(type){
	   case 'seed':
	   case 'fertilizer':
	   $('.shop_seed').click();
	   break;
	   case 'disasters':
	   $('.shop_window').show();
	   $('.shop_shopprop').click();
	   break;
	   case 'material':
	   $('#material').click();
	   break;
	   case 'diamond':
	   $('.shop_window').hide();
	   $('.recharge').click();
	   break;
   }
}

//遮罩
function mask(msg){
	if(msg=="open"){
		$('.box_hide').show();
	}else{
		$('.box_hide').hide();
	}
}


//底部菜单
function menus(cases,page,type){
	//签到
	if(cases=="signs"){
		$('.sign img').hide();
	    $('.sign_title span').text('正在领取.....');
	    $('.sign_title').show();
		var url = _module+"User"+ URL_PATHINFO_DEPR +"sign";
		$.post(url,data={token:$('#token').val()},function(d){
			if(d!==""){
				$('.sign_title span').text('获得'+d+'x1');
				setTimeout(function(){
					$(".sign").remove();
				},1500);
			}else{
				$('.sign_title span').text('签到失败');
				setTimeout(function(){
					$('.sign img').show(100);
					$(".sign_title").hide();
				},1500);
			}
		})
		return false;
    //切换帐号
	}else if(cases=="cut"){
	    mask('open');
		window.location.href= _module+"Login";
		return false;
    //大礼包		
	}else if(cases=="spree"){  
		var url = _module+"User"+ URL_PATHINFO_DEPR +"spree";
		$.post(url,data={token:$('#token').val()},function(d){
			$(".spree").hide();
			if(d==1){
				$(".spree_show").show();
				mask("open");
				$("#diamond").val($("#diamond").val()*1+800);
				$(".spree").remove();
				setTimeout(function(){	
					mask("close");
					$(".spree_show").remove();
				},4000)
			}else{
				prompt('领取失败');
			}
		})
		return false;
	}else if(cases=="service"){	
	
		var url = _module+"Menudata"+ URL_PATHINFO_DEPR +"service";
		$.post(url,data={token:$('#token').val()},function(d){
			  $('.service_show').show();
		      if(d!==""){	  
				  $('.service_show').html('');
				  $('.service_show').html(d);				 
			  }else{
				  $('.service_show').html('');
				  $('.service_show').html('还未购买任何服务');		
			  }	
			  setTimeout(function(){	
				 $(".service_show").hide();
			  },2000)     
		})
		return false;
	}else{
		//底部菜单
        mask('open');	
		var url = _module+ "Menudata"+ URL_PATHINFO_DEPR +cases;
		var data = {page:page,type:type};
		if(cases=='warehouse'){			
			var bgshow = ["fruit","material","prop","utreasure","huodong"];
			for(var i=0;i<bgshow.length;i++){
				if(type==bgshow[i]){
					$('.warehouse_'+type).css('background','#FFFADD');
				}else{
					$('.warehouse_'+bgshow[i]).css('background','#CBA077');
				}
			}
		}else if(cases=="shop"){
			var bgshow = ["seed","shopprop","treasure","service"];
			for(var k=0;k<bgshow.length;k++){
				if(type==bgshow[k]){
					if(type=="service"){
						cue('向下滑动查看更多服务','cue_box');
					}
					$('.shop_'+type).css('background','#FFFADD');
				}else{
					$('.shop_'+bgshow[k]).css('background','#CBA077');
				}
			}
		}
	}
	
	//直接打开
	if(cases=="ranking"){
		$(".ranking_box").show();
	}

	if(cases=="log"){
		$(".market_box").show();
	}
    
	var window_type = $("."+cases+"_window");
	//弹窗效果
	if(cases=='warehouse'){
		if(type=='fruit'){
			if($(".warehouse_box").is(":hidden")){
				window_type.show();
			}
		}
	}

	if(cases=='shop'){
		if(type=='seed'){
			if($(".shop_window").is(":hidden")){
				 window_type.show();
			}
		}
	}

	if(cases=='exchange'){
		window_type.show();
	}

	if(cases=="exchange" || cases=="ranking" || cases=="log"){
	    $('#type').val(cases);
	}else{
		$('#type').val(cases+'|'+type);
	}

	$.post(url,data,function(d){

			if(type=='fruit' || type=='material' || type=='prop' || type=='utreasure' || type=='huodong'){
				
				if(type=='huodong'){
					cue("点击任意碎片即可合成手机","cue_box")
				}else if(type=='fruit'){
					cue("点击果实可进行种子重生","cue_box")
				}
				$('.warehouse_show').html("").html(d);		
			}
			if(type=='fruit'){
				$(".ser_btn").show();
			}else{
				$(".ser_btn").hide();
			}
			if(cases=='exchange'){
				$('.exchange_body').html(d);
			}
			/*
			if(cases=='shop'){
				$('.shop_body').html('').html(d);
			}*/
			if(cases=='shop'){
				if(type=='exchange'){
					$('.ser_hed').html('').html(d);
					cue('点击选择需要兑换的服务种类','cue_box');
				}else{
					$('.shop_body').html('').html(d);
				}
				
			}

			if(cases=="ranking"){
				$('.ranking_body').html(d);
			}

			if(cases=="log"){
				$('.market_list').html(d);
			}

			$('#page').val(page);

		},
		"json"
	)
}

//宝箱倍率加减
function rate(type){
	var rate= $(".rate_number").val();
	if(type==1){
		if(rate>1){
			rate = rate*1-1;
			$(".rate_number").val(rate);
		}else{
			$(".rate_number").val(1);
		}
	}else{
		if(rate<=1000){
			rate = rate*1+1;
			$(".rate_number").val(rate);
		}else{
			$(".rate_number").val(1000);
		}
	}
}
//信息滚动条
function scroll(i,data,j){

    if(data[j].box_name==undefined && data[j].next_level!==undefined){
		var title = '恭喜'+data[j].user+'成功升到'+data[j].next_level+'级';
		$('.scroll_img img').remove();
	}else if(data[j].box_name==undefined && data[j].activity!==undefined){		
	    var title = '恭喜'+data[j].user+'碎片合成获得了'+data[j].activity+'x1部';
		$('.scroll_img img').remove();
		//活动公告(固定)
        var str = '<p><img src="/farms/Public/Home/images/index/laba.png">恭喜'+data[j].user+'获得'+data[j].activity+'一部</p>';
        $('#text_boxx p:last').after(str);				
	}else{
		var title = '恭喜'+data[j].user+'从'+data[j].box_name+'获得'+data[j].open_seed+"x"+data[j].open_seed_num;
	    $('.scroll_img').css('background','url("/farms/Public/Home/images/index/zhongjiang.png")');
		$('.scroll_img').css("background-size",'100% 100%');
	}

	$(".scroll_bar").show();
	$('.scroll_text span').text(title);
	var a = setInterval(function(){
		i++;
		if(i==100){
			clearInterval(a);
			$(".scroll_bar").hide();
			$(".scroll_bar").css("left",100+"%");
			if(j==(data.length-1)){
				return false;
			}else{
				j++;
				lineup(data,j);
			}
		}else{
			$(".scroll_bar").css("left",-i+"%");
		}
	},50);
}

var scoll = setInterval(function(){
	var url = _module  + "Login"+ URL_PATHINFO_DEPR +"treasure_show";
	var data = {};
	$.post(url,data,function(d){			
		if(d!==""){
			obj = eval(d);
			var j = 0;
			lineup(obj,j);
		}
	})
},30000)

function lineup(data,j){
	var i = 1-100;
	if($(".scroll_bar").is(':hidden')){
		scroll(i,data,j);
	}
}

//实时土地状态
var planting;

function planting_resfer(){

	planting = planting_state = setInterval(function(){
		
		var url = _module  + "Login"+ URL_PATHINFO_DEPR +"planting_state";
		$.post(url,data={},function(d){
			if(d!==""){
				
				var plannum = new Array();
				
				obj = eval(d);
				for(i=0;i<obj.length;i++){
					
					$('#seeds_'+obj[i].number).attr('seed_state',obj[i].seed_state);
				    $('#seeds_'+obj[i].number).attr('seed_type',obj[i].seed_type);
				    $('#seeds_'+obj[i].number).attr('harvest_time',obj[i].harvest_time);
					
					if(obj[i].seed_state==0){	
						 $('#land_sum_'+obj[i].number).attr('state',2);
			             if($('#planting_num_'+obj[i].number+' img').length>0){
							 $('#planting_num_'+obj[i].number+' img').remove();
						 }
						 if($('#seeds_'+obj[i].number+' img').length>0){
							 $('#seeds_'+obj[i].number+' img').remove();
						 } 
						 $('#seeds_'+obj[i].number).append('<img src="/farms/Public/Home/images/zhongzi.png"/>');
						 $('#seeds_'+obj[i].number).attr('seed_state',obj[i].seed_state);
						 $('#seeds_'+obj[i].number).attr('harvest_time',obj[i].harvest_time);
					}else if(obj[i].seed_state<3){	
                         if($('#planting_num_'+obj[i].number+' img').length>0){
						    $('#planting_num_'+obj[i].number+' img').attr('src','/farms/Public/Home/images/'+obj[i].seed_img_name+obj[i].seed_state+'.png');
					     }else{
						    $('#seeds_'+obj[i].number+' img').attr('src','/farms/Public/Home/images/'+obj[i].seed_img_name+obj[i].seed_state+'.png');
					     }
					}else if(obj[i].seed_state==3){
						
						var state = $('#land_sum_'+obj[i].number).attr('state');
						if(state==undefined || state==2){
							$('#land_sum_'+obj[i].number).attr('state',3);
						}
						
						if($('#planting_num_'+obj[i].number+' img').length>0){
						    $('#planting_num_'+obj[i].number+' img').attr('src','/farms/Public/Home/images/'+obj[i].seed_img_name+obj[i].seed_state+'.png');
					    }else{
						    $('#seeds_'+obj[i].number+' img').attr('src','/farms/Public/Home/images/'+obj[i].seed_img_name+obj[i].seed_state+'.png');
					    }
					}
					
                    //灾难
					if(obj[i].disasters_state!=0){
						
						if(obj[i].disasters_state==1 || obj[i].disasters_state==3){						
						    var hix = '.png';
					     }else if(obj[i].disasters_state==2){
						    var hix = '.gif';
					     }
						 
						 if($('#disaster_'+obj[i].number+' img').length==0){
						    $('#disaster_'+obj[i].number).append('<img src="/farms/Public/Home/images/disasters_'+obj[i].disasters_state+hix+'"/>');
					     }
					}else{
						 if($('#disaster_'+obj[i].number+' img').length>0){
							 $('#disaster_'+obj[i].number+' img').remove();
						 }
					}

                    plannum.push(obj[i].number*1);						    
				}
				
				var landnum = new Array();
				var level = $('#level').val();
				for(j=1;j<level*1+1;j++){
		             landnum.push(j);
				}

                //临时数组存放
				var tempArray1 = [];
				var tempArray2 = [];

				for(var i=0;i<plannum.length;i++){
				    tempArray1[plannum[i]]=true;
				}

				for(var i=0;i<landnum.length;i++){
				    if(!tempArray1[landnum[i]]){
				        tempArray2.push(landnum[i]);
				    }
				}

				//console.log(tempArray2);
				
				if(tempArray2.length>0){
					for(y=0;y<tempArray2.length;y++){
					    $('#planting_num_'+tempArray2[y]+' img').remove();
						$('#seeds_'+tempArray2[y]+' img').remove();
						$('#seeds_'+tempArray2[y]).removeAttr('seed_state');
					    $('#seeds_'+tempArray2[y]).removeAttr('harvest_time');
					    $('#seeds_'+tempArray2[y]).removeAttr('seed_type');	
					}
				}
				
				//监控稻草人
				var scarecrow_state = $('#scarecrow').attr('end_time');
				if(scarecrow_state!==undefined){
					 if(Date.parse(new Date())/1000>scarecrow_state){
						  $('#scarecrow').remove();
					 }
				}
			}
		})
	},5000)
}

planting_resfer();


//检测帐号是否同时登录
/*setInterval(function(){
	var url = _module  + "Login"+ URL_PATHINFO_DEPR +"prevent_login";
	var data = {};
	$.post(url,data,function(d){
		if(d==1){
			mask('open');
			$('.internet_box span').text('').text('帐号异地登录');
			$('.internet_box').show();
			clearInterval(scoll);
			clearInterval(planting);
		}
	})
},6000);*/


//合成碎片
function synthetic(){
	var url = _module  + "User"+ URL_PATHINFO_DEPR +"synthetic";
	var data = {};
	$.post(url,data,function(d){
		prompt(d);
	})
}

var seeds;
//显示重生界面
function reborn(seed){
	crits = 2;
    $("#seed_crit button").text("暴击");
    $("#seed_number").val(1000);
    seeds = seed;
    var seed_img = {"草莓":"caomei","土豆":"tudou","樱桃":"yingtao","稻米":"daomi","葡萄":"putao","番茄":"fanqie","菠萝":"boluo"};
    for(i in seed_img){
    	if(i == seed){
    		var seed_img = seed_img[i];
    		$('.seed_fruit img').attr('src','/farms/Public/Home/images/fruit/'+seed_img+'.png');
    	}
    }
    //console.log(seed_img[0]);
    $(".warehouse_box").hide();
	$(".box_hide").show();
	$(".reborn_box").show();
}


//重生果实加减
function reborncot(id){
	var seed_number = $("#seed_number").val()*1;
	if(id==1){
		if(seed_number==10000){
			$("#seed_number").val(1000);
		}else if(seed_number==1000){
			$("#seed_number").val(1000);
		}
	}else if(id==2){
		$("#seed_number").val(seed_number+1000);
		if(seed_number==1000){
			$("#seed_number").val(seed_number+9000);
		}else if(seed_number==10000){
			$("#seed_number").val(10000);
		}if(seed_number<1000){
			$("#seed_number").val(1000);
		}else if(seed_number>1000&&seed_number<10000){
			$("#seed_number").val(10000);
		}
	}

}

//是否暴击
var crits;
//重生暴击
function crit(){
	var crit = $("#seed_crit button").text();
	if(crit=="暴击"){
		$("#seed_crit button").text("取消暴击");
		crits = 1;
	}else{
		$("#seed_crit button").text("暴击");
		crits = 2;
	}
}
crit();

//重生提交
function rebornsub(){
	$("#seed_btn button").attr({"disabled":"disabled"});
	$(".reborn_bac").show();	
	var seed_number = $("#seed_number").val()*1;
	setTimeout(function(){
		$(".reborn_bac").hide();
		if(seed_number==1000||seed_number==10000){
			var url = _module  + "User"+ URL_PATHINFO_DEPR +"reborn";
			var data = {number:seed_number,seeds:seeds,crit:crits}; //种子种类
			$.post(url,data,function(d){
				var data = JSON.parse(d);
				var mydiamond = $("#diamond").val()*1;
				var mycoin = $("#coin").val()*1;
				if(data.content=="输入数量有误"){
					cue("输入数量有误","cue_box");
				}else if(data.content=="金币不足"){
					cue("金币不足","cue_box");
				}if(data.content=="钻石不足"){
					cue("钻石不足","cue_box");
				}else if(data.diamond==null&&data.content=="重生成功"){				
					//prompt("获得种子x"+(data.crit+data.num)+"<br/>"+" 扣除手续费："+data.money+"金币 ")
					var present = mycoin-data.money; 
					$("#coin").val(present.toFixed(5));
					cue("获得种子x"+(data.crit+data.num)+"<br>"+"手续费："+data.money+"金币 ","cue_box");
				}if(data.diamond!=null&&data.content=="重生成功"){
					//prompt("获得种子x"+(data.crit+data.num)+"<br/>"+"扣除手续费："+data.money+"金币"+"<br/>"+"钻石暴击费用："+data.diamond+"钻石")
					cue("获得种子x"+(data.crit+data.num)+"<br>"+"暴击费用："+data.diamond+"钻石"+"<br>"+"手续费："+data.money+"金币 ","cue_box");
					$("#diamond").val(mydiamond-data.diamond);
					var presents = mycoin-data.money;
					$("#coin").val(presents.toFixed(5));
				}else if(data.content=="果实不足"){
					cue("果实不足","cue_box");
				}if(data.content=="种子已经超出限制"){
					cue("种子数量已达3亿，暂停重生","cue_box");
				}else if(data.content=="不要搞事"){
					cue("请求错误","cue_box");
				}if(data.state==3003){
					cue("当前重生人数过多请稍后再试","cue_box");
				}
			})		   
		}else{
			cue("数量必须为1000或者10000","cue_box")
		}
		
		//cue("重生暂时未开放","cue_box");
		//$(".reborn_bac").hide();
		$("#seed_btn button").removeAttr("disabled");
	},2000)
	


}



//通用提示框 三秒消失
function cue(content,id){
	$("#cue_span span").html(content);
	$("#"+id).fadeIn(50);
	setTimeout(function(){
		$("#"+id).fadeOut(50)
	},3000);
}

//点击其他地方立即隐藏通用弹窗
$(function(){
	$("body").click(function(){
		$(".cue_box").hide();
	})
	$(".warehouse_huodong").click(function(event){
		event.stopPropagation();
	})
	$(".seed_btn").click(function(event){
		event.stopPropagation();
	})
	$(".footer_classification").click(function(event){
		event.stopPropagation();
	})
	$(".warehouse_fruit").click(function(event){
		event.stopPropagation();
	})
	$("#services").click(function(event){
		event.stopPropagation();
	})
})


//活动滚动
var pnum = 0;
setInterval(function(){
	var length = $('#text_boxx p').length;
	if(pnum==length){
		 pnum = 0;
		 $('#text_boxx p').hide();
		 $('#text_boxx p').eq(pnum).fadeOut(200).fadeIn(200);
		 pnum++;
	}else{
		 $('#text_boxx p').eq(pnum-1).hide();
		 $('#text_boxx p').eq(pnum).fadeOut(200).fadeIn(200);
         pnum++;		 
	}
},3000)



/*果实兑换选中效果*/

var exchange_id;

function ex_ser(id){	

	var ids = document.getElementById("ex_"+(id-10));
	
	exchange_id = id;
	
    switch(id){
    	case 11: 
    	var content = '兑换：草灾守护';
    	break;
    	case 12:
    	content = '兑换：虫灾守护';
    	break;
    	case 13:
    	content = '兑换：旱灾守护';
    	break;
    	case 14:
    	content = '兑换：丰收之心';
    	break;
    	case 15:
    	content = '兑换：稻草人';
    	break;
    }

    $('#ser_fir_text').html('').html(content);
	//id.style.border="2px solid #f00";	
	for(var i=1;i<=5;i++){
		if(id-10==i){
			ids.style.border="2px solid #f00";
		}else{
            var otherid = document.getElementById("ex_"+i);
			otherid.style.border="none";	
		}
	}
}
//果实兑换服务方法
function exchange_service(){

	var url = _module  + "User"+ URL_PATHINFO_DEPR + "exchange_service";
	var id = exchange_id;
	var count = $('.ser_firt_1').val();
	var data = {id:id,count:count};
	$.post(url,data,function(d){
		var number = "";
		return_code(d,number);
	})
}
