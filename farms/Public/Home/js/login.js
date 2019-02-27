
var url = "/farms/";

$(function(){
	
	box();
	//获取屏幕宽、高
	function box(){
				
		var boxHeight = window.screen.height //获取分辨率高度
		var boxWidth = window.screen.width //获取分辨率宽度
		var boxWidthpc = document.documentElement.clientWidth;//获取浏览器可视宽度
		//var boxHeightpc= window.screen.availHeight//获取浏览器可视高度
		var boxHeightpc= document.documentElement.clientHeight;
		
		var box = $(".box");
        var kj_box = $(".kj_box");	
		var loading = $('.loading');
		
		box.height(boxHeightpc).width(boxWidth);
		if(box.height()<=600){
			box.height(600+"px");
		}else if(boxWidth>728){
			var boxHeightpc= document.documentElement.clientHeight;
			box.height(boxHeightpc).width(boxHeightpc/1.78+"px");
			kj_box.height(boxHeightpc).width(boxHeightpc/1.78+"px");
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
		}
		if(boxHeight<=568){
			box.height(568+"px");
		}else if(boxHeight*1==736&&boxWidth*1==414&&box.height()<=700){	
			$(".box").height(720+"px");
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
				$(".box").height(boxHeightpc*1+40);
				$(".box").width(boxWidthpc);
				if(boxHeight*1==736&&boxWidth*1==414&&box.height()<=700){	
					$(".box").height(720+"px");
				}
			}
		
		
	    loading.show();
	    kj_box.show();
	    box.show();

	}
	
	//加载
	var loading = setInterval(loading,100);
	var loading_time = 0;
	function loading(){
		 loading_time+=Math.ceil(Math.random()*5);
		 $('.loading_title').html('正在加载 '+loading_time+'%');
		 if(loading_time>=100){
			clearInterval(loading);
			$('.loading').remove();
		 }
	}

	
	var sign_in = $(".sign_in");
	var box_hide = $(".box_hide");
	var register_interface = $(".register_interface");
	var agreement_show = $(".agreement_show");
	var forget_password = $(".forgot_password");
	var enter_game = $('.enter_game');
	
	//显示用户协议
	$(".register").click(function(){
		//alert('系统正在维护中');              <!-----关闭注册---->
		//return false;
		sign_in.hide();
		box_hide.show();
		agreement_show.show();
	});
	//同意协议显示注册界面
	$(".agreement_sure").click(function(){
		register_interface.show();
		sign_in.hide();
		box_hide.show();
		agreement_show.hide();
	});
	//返回登录界面
	$("._return").click(function(){
		box_hide.hide();
		register_interface.hide();
		sign_in.show();
		$(".register_interface input").val('');
		$("#btn").val("获取");
	});

	//进入忘记密码界面
	$(".forget").click(function(){
		//alert('系统正在维护中');             <!-----关闭忘记密码---->
		//return false;
		forget_password.css("z-index","3").show();
		enter_game.hide();
		sign_in.hide();
		box_hide.show();
	})

	//返回登录界面
	$("#return_login").click(function(){
		forget_password.hide();
		box_hide.hide();
		sign_in.show();
		enter_game.show();
		$("#message_code").val("");
	})


	function title(msg){
		$(".validation_hints").show();
		setTimeout(function(){$(".validation_hints").hide();},1000);
			$(".validation_hints span").text(msg);
			$("#btn").css("disabled","false");
		    return false;
	}

	/**帐号操作**/
	
	//注册
	$(".register_account").click(function(){
			if(!/^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test($("#mobile_account").val())) {
					  title('手机号码输入有误');
			}else{
				if(!/^[\u4e00-\u9fa5]+$/gi.test($("#full_name").val())){
						title('姓名输入有误(中文)');
				}else{
					if(!/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test($("#id_card").val())){
							title('身份证号输入有误');
						}else{
							 /*if($("#referees_referees").val() == '' ){
								 title('请输入好友推荐码');
								 return false;
							 }*/
							var myDate = new Date();
							if(myDate.getFullYear()-$("#id_card").val().slice(6,10)<18){
								title('未满18岁,不允许注册');
							}else{
								 if($("#input_password").val()!==$("#confirm_password").val()){
									 title('两次密码输入不一致');
								 }else{
									if ($("#input_password").val().length<6&&$("#confirm_password").val().length<6) {										
										title('密码不能小于6位');
									}else{																														
										var verification_code = $("#verification_code").val();
										if(verification_code.length<4){
												title('验证码输入有误');
										}else{
											if($(".request_input input").val().length=="" || $(".request_input input").val().length<4){
												title('短信验证码不能为空');
											}else{												
												Nopa(data={formdata:$('#form').serialize()+'&token='+$('#token').val()},'Accounts%2FRegister','','');
											}
										}
								  }
							 }
						}
					}
				}
			}
	})
	
	//发送注册验证码
	$('.request').click(function(){	  
		  Nopa(data={tel:$('#mobile_account').val(),token:$('#token').val()},'Accounts%2Fcode','','');
		  var wait = 180;
		  countdown(wait,'reg');	
    })
	
	//忘记密码验证码
	$('#phone_code').click(function(){
		if($("#phone_number").val()=="" || $("#phone_number").val()=="手机号不能为空" || $("#phone_number").val()=="用户不存在"){
				$("#phone_number").val('手机号不能为空');
		};
		Nopa(data={tel:$('#phone_number').val(),token:$('#token').val()},'Accounts%2Ffind_code','',''); 
	})	
	
	//验证忘记密码步骤
	$("#next_step").click(function(){ 
		 //忘记密码 手机号码验证是否是正确号码
		 if(!/^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test($("#phone_number").val())){
				 $("#phone_number").val("手机号码错误");
		 }else{
			  if($("#message_code").val()=="" || $("#message_code").val()=="验证码不能为空"){
				  $("#message_code").val("验证码不能为空");
			  }else{			 
				  Nopa(data={formdata:$('#forget_form').serialize()+'&token='+$('#token').val()},'Accounts%2Ffind_password','',''); 	  
	 		 }
		 }
 	})
	
	//重置密码
	$("#submit").click(function(){

		 if($("#new_password").val().length<6 || $("#new_password").val()==""){
				 $(".validation_hints_rs").show();
					 setTimeout(function(){$(".validation_hints_rs").hide();},1000);
				 $(".validation_hints_rs span").text("请输入密码(至少6位)");
				 exit;
		 }else{
			   if($("#new_password").val()!==$("#repeat_password").val()){
					 $(".validation_hints_rh").show();
						 setTimeout(function(){$(".validation_hints_rh").hide();},1000);
					 $(".validation_hints_rh span").text("两次密码输入不一致");
					 exit;
				}else{

					$("#forget_password").val(hex_md5($("#repeat_password").val()));
					
					Nopa(data={formdata:$('#reset_form').serialize()+'&token='+$('#token').val()},'Accounts%2Freset_password','',''); 
				}
		}
	})	

	//进入游戏
	$(".enter_game").click(function(){

			if($(".loading_img img").length<0){
				return false;
			}
			
			var login_alert_span = $(".login_alert span");
			var login_alert = $(".login_alert");

			if($("#login_username").val()==""){
				 login_alert_span.text("账户不能为空");
				 login_alert.show();
				 setTimeout(function(){$(".login_alert").hide();},2000);
			}else{
				if($("#login_password").val()==""){
					 login_alert_span.text("密码不能为空");
					 login_alert.show();
					 setTimeout(function(){$(".login_alert").hide();},2000);
				}else{
					if($("#login_password").val()!="凯撒农场用户"){
						document.getElementsByName("password")[0].value = hex_md5(hex_md5(hex_md5($("#login_password").val())));
					}
					$(".loading_img").append("<img src='/farms/Public/Home/images/loading.gif'/>");
					Nopa(data={formdata:$('#login_from').serialize()+'&token='+$('#token').val(),record_com:$('#record_account').val(),record_pass:$('#record_password').val()},'Login%2Fenter_game','','');
				}
			}
	})
	  
	  
    /**清空提示**/

	//清空手机号码错误提示
	$("#message_code").focus(function(){
		if($(this).val()!=""){
			$(this).val("");
		}
	})

	//验证输入2次新密码是否相同
	$("#new_password").focus(function(){
		if($(this).val()!=""){
			 $(this).val("");
		}
	})

	$("#repeat_password").focus(function(){
		if($(this).val()!=""){
			$(this).val("");
		}
	})

	//点击提交重置密码按钮判断
	$(".submit_backgr").click(function(){
		 $(".reset_password").hide();
		 //$(".forgot_password").show();
		 forget_password.show();
	})
	
	$('.et_error').click(function(){
		$(".login_alert span").text("答案错误");
		$(".login_alert").show();
		setTimeout(function(){$(".login_alert").hide();},2000);
	})

	
})//预加载


	//验证计时
	var countdown_time;

	function countdown(wait,cases){
		
		var btn = $('#btn');
		var phone_code = $('#phone_code');
		var phone_number = $("#phone_number");
		
		if(wait==0){
			if(cases=="reg"){
				btn.val('重新发送').removeAttr("disabled");;
			}else if(cases=="find"){		 
			    phone_code.val('重新发送').removeAttr("disabled");
			    phone_number.removeAttr("readonly"); 
			}
		}else{
			if(cases=="reg"){
				btn.attr({"disabled":"disabled"}).val(wait+'s'); 
			}else if(cases=="find"){
				phone_number.attr({"readonly":"readonly"}); 
				phone_code.attr({"disabled":"disabled"}).val(wait+'s'); 
			}
			wait--;
			countdown_time = waits = setTimeout(function(){
				countdown(wait,cases);
			},1000)
		}
	}
	
	//ajax方法
	function Nopa(data,c,number,type){
		if(type=='j'){
			$.post(url+decodeURIComponent(c),data,function(d){});
		}else{
			$.post(url+decodeURIComponent(c),data,function(d){
				return_code(d,number); 
			});
		}
	}

//加载排行榜、日志
/*setTimeout(function(){
	Nopa(data={},'Menudata%2Flog','','j');
	Nopa(data={},'Menudata%2Franking','','j');
	var arr = ['seed','shopprop','treasure','service'];
	for(var i=0;i<arr.length;i++){
		var data = {type:arr[i],page:0};
		Nopa(data,'Menudata%2Fshop','','j');	
	}
},5000);*/

//记住帐号，密码
function record(msg){
	if($('#record_'+msg).val()==0){
		$('#record_'+msg).val(1);
	}else{
		$('#record_'+msg).val(0);
	}
	$("."+msg+" img").toggle();
}
