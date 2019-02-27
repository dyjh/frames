
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
		
		function box_show(){
			$(".box_two").show()
		}
		setTimeout(box_show,500);
		
	},100)

	
})//预加载
		//鸡弹窗
		function buy_jihome(hide,show){
			hide_show('show');
			$("#"+hide).hide();
			$("#"+show).show();
		}

		//弹窗打开
		function pop_show(id){
			hide_show('show');
			$("#"+id).show();
			pages('next','manage_box',1);	
		}
		function pop_return(id){
			var box = ["panic_box","ji_pop_box","prompt_box","maintain_box","manage_box","sell_box","income_box","ji_pop_box","ji_buy_box"]
			for(var key in box){
				if($("#"+box[key]).is(":hidden")){
					hide_show('hide');
				}else if(!$("#"+box[key]).is(":hidden")){	
					hide_show('show');
				}
			}
			$("#"+id).hide();		
		}
		//遮罩开关
		function hide_show(type){
			if(type=='hide'){
				$(".box_hide").hide();
			}else if(type=='show'){
				$(".box_hide").show();
			}
		}
		//禁止input框输入负数
		function checkNum (val) {
			var input_id = document.getElementById('panic_input');
	   		input_id.value = val >= 0 ? val : 0;
	   		if(input_id.value*1>9999){
	   			input_id.value=9999;
	   		}
		}
		//加减
		function count(type){
			var number = $("#panic_input").val()*1;
			var input = $("#panic_input");
			if(type=='drop'){
				input.val(number-1);
				if(input.val()*1<1){
					input.val(1);		
				}
			}else if(type=='add'){
				input.val(number+1);
				if(input.val()*1>9999){
					input.val(9999);
				}
			}
		}

		//页数
		var nowpage = 0;
 		function pages(type,msg,page){
 			$("#"+type+'_box').show();
            if(page==null && nowpage==0){               
                nowpage +=1;
            }else{
                if(msg==null){
                	nowpage=1;                
                }else{
                	if(msg=="next"){
                	   nowpage+=1;
                	}else if(msg=="pre"){
                       nowpage-=1;
                	}
                }
            }
            var url = _module+"Pasture"+URL_PATHINFO_DEPR+"menus";
			var data = {type:type,page:nowpage};
			$.post(url,data,function(d){
			    if(type=="manage"){
					$('.manage').html('').html(d);
			    }else if(type=="maintain"){
				    $('.maintain_body').html('').html(d);
				}else if(type=="panic"){
				    $('.pop_list_box').html('').html(d);
				}else if(type=="income"){
				    $('.income_body').html('').html(d);
				}else if(type=="sell"){
				    $('.sell_body_box').html('').html(d);
				}else if(type=="house"){
				    $('.maintain_body').html('').html(d);
				    buy_jihome('ji_pop_box','ji_buy_box');
					
				}
					 
			    hide_show('show');
			    $("#"+type+'_box').show();
				 
			},"json")

		}
			
	function ChickenState(id){
		var day = ($('#ji_'+id).attr('harvest_time')-Date.parse(new Date())/1000)/3600/24;
		var day = Math.floor(day);	
		var min = (($('#ji_'+id).attr('harvest_time')-Date.parse(new Date())/1000)-(day*3600*24))/3600;
		var min = Math.floor(min);
		$('#StateTitle_'+id).text('').text('离成熟还有'+day+'天'+min+'小时');
		$('#StateTitle_'+id).show();
		setTimeout(function(){
		   $('#StateTitle_'+id).hide();
		},3000);	
	}
	
	//抢购
	function buy(){
	    var url = _module+"Pasture"+URL_PATHINFO_DEPR+"buy";
		var data = {buynum:$('#panic_input').val()};
		$.post(url,data,function(d){
		     ReturnCode(d); 
		})
	}
	
	
	//出售
	function sell(id){
	   var url = _module+"Pasture"+URL_PATHINFO_DEPR+"sell";
	   var data = {id:id};
	   $.post(url,data,function(d){
	        ReturnCode(d); 
	   })
	}
	
	//维护
	function maintain(){
	   var url = _module+"Pasture"+URL_PATHINFO_DEPR+"maintain";
	   $.post(url,data={},function(d){
	        ReturnCode(d);
	   })	
	}
	
	//购买鸡舍
	function house(){
	    var url = _module+"Pasture"+URL_PATHINFO_DEPR+"house";
		$.post(url,data={},function(d){
	        ReturnCode(d);
	    })	
	}
	
	//返回码
	function ReturnCode(code,id,count){

		var object = $.parseJSON(code);
	    switch(object.state){
		    //果实不足
		    case 10001: 
			     var str = "<laber id='laber' style='margin-left:5%;padding:1% 3% 1% 3%;background:#CF9B50;border-radius:5px'><a style='color:#FFF;text-decoration:none' href='/farms/Market/index.html' rel='external'>去购买</a></laber>";
				 prompt(object.content);
			     if($('.all_box_list laber').length<1){
				    $('.all_box_list span').after(str); 
				 }
				 break;
			case 10002: 
			     prompt(object.content);
				 if($('.all_box_list laber').length>0){
				    $('.all_box_list laber').remove(); 
				 }
			     setTimeout(function(){
				     location.reload(); 
				 },2000)
			     break;
			case 10003: 
				 prompt(object.content);
				 $('#sell_chicken_'+object.id).remove();
				 $('.chicken_'+object.id).remove();
				 setTimeout(function(){
				     location.reload(); 
				 },3000)
				 break;
		    case 99999:
			     prompt(object.content);
				 if($('.all_box_list laber').length>0){
				    $('.all_box_list laber').remove(); 
				 }
				 break;
	    }
	}
	
	//通用弹窗
	function prompt(content){
	    $('.all_box_list span').text('').text(content);
		$('#prompt_box').show();
	    hide_show('show');
	}
