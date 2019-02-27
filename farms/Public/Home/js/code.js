
function return_code(code,number){
		
		var object = $.parseJSON(code);

			switch(object.state){

			 /**种值返回码**/
			 /************/
			 case 10000: prompt(object.content);break; //超过帐户级别
			 case 10001: prompt(object.content,'seed');break; //种子数量不足
			 case 10002: prompt(object.content);break; //帐户等级有误
			 case 10003: prompt(object.content);break; //种子不存在
			 case 10004: prompt(object.content);break; //种植失败
			 case 10005: prompt(object.content);break; //仓库种子扣除失败
			 case 10006: //种值成功
				  $('#land_sum_'+number).attr('state',2);
				  //$('#planting_num_'+number).attr('planting_state',2); 
				  $('#seeds_'+number).append('<img src="/farms/Public/Home/images/zhongzi.png"/>');
				  break;
			 case 10007: prompt(object.content);break; //重复种植
			  /**施肥返回码**/
			  /************/
			 case 20001: prompt(object.content,'fertilizer');break;  //道具不够
			 case 20002: prompt(object.content);break;  //该种子不存在
			 case 20003: prompt(object.content);break;  //施肥失败
			 case 20004: //施肥成功
			      
				  if(object.next_phase==1 || object.next_phase==2){
					   if($('#planting_num_'+number+' img').length>0){
						   $('#planting_num_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
					   }else{
						   $('#seeds_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
					   }				  
				  }else if(object.next_phase==3){	
                       if($('#planting_num_'+number+' img').length>0){
						  $('#planting_num_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
					   }else{
						  $('#seeds_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');  
					   }
					   $('#land_sum_'+number).attr('state',3);
					   clearInterval(planting);
					   //$('#planting_num_'+number).attr('seed_state',3);   
					   /*$('#planting_num_'+number).attr('seed_state',3);
					    $('#planting_num_'+number).attr('planting_state',2);*/
					   setTimeout(function(){
					     planting_resfer();
					   },3000)
				  }
				  prompt(object.content);break;
			 break; //施肥成功
			 case 20005: prompt(object.content);break;  //该阶段只能施肥一次
			 case 20006: prompt(object.content);break;  //植物已经成熟，不能再施肥

			 /**除灾返回码**/
			 /************/
			 case 30001: prompt(object.content);break;  //不存在灾害
			 case 30002: prompt(object.content,'disasters');break;  //除灾道具不够
			 case 30003: prompt(object.content);break;  //除灾失败
			 case 30004: //除灾成功
				  clearInterval(planting);
				  setTimeout(function(){
					   planting_resfer();
				  },1000)
				  $('#disaster_'+number+' img').remove();
			 //收获
			 case 40001: prompt(object.content);break; //入库失败
			 case 40002: prompt(object.content);break; //入库失败
			 case 40003:  //入库成功，并会返回相应的数值
			       if($('#planting_num_'+number+' img').length>0){   
				       $('#planting_num_'+number+' img').remove();
				   }else{
					   $('#seeds_'+number+' img').remove();
				   }
			       $('#land_sum_'+number).attr('state',1);
				   //$('#planting_num_'+number).attr('planting_state',1);
				   $('#disaster_'+number+' img').remove();          
				   //移除种子相关状态
				   $('#seeds_'+number).removeAttr('seed_state');
				   $('#seeds_'+number).removeAttr('harvest_time');
				   $('#seeds_'+number).removeAttr('seed_type');	
                 		 
				   if(object.activity!==undefined){
					   $(".box_hide").show();
					   $(".phone_box").show();
					   setTimeout(function(){
						   $(".phone_box").hide(); 
						   prompt(object.content);
					   },4000) 				  
				   }else{
					  prompt(object.content);
				   }
				   //活动结束后取消上列
				   //prompt(object.content);break;
			       break;
			 case 40004: prompt(object.content);break;
			 /**升级返回码**/
			 /************/
			 case 50001: prompt(object.content);break; //等级错误
			 case 50002: prompt(object.content,'material');break; //材料不足
			 case 50003: prompt(object.content,'diamond');break;  //钻石不足
			 case 50004: prompt(object.content);break;  //升级失败，材料修改失败
			 case 50005: prompt(object.content);break;  //升级失败，宝石修改失败
			 case 50006: prompt(object.content);break;  //升级失败，升级修改失败
			 case 50007:                                //升级成功
				  mask('close');
				  next_land = number*1+1;
				  $('#land_sum_'+number).attr("state",1);
				  $('#level').val(object.next_house);
				  $('.user_level span').text(object.next_house);
				  $('#land_sum_'+number+' img').attr('src','/farms/Public/Home/images/zhongtu.png');
				  $('#land_sum_'+next_land+' img').attr('src','/farms/Public/Home/images/upgrade.png');
				  $('#house_level').attr('src','/farms/Public/Home/images/index/house_'+object.next_house+'.png');
				  $('#diamond').val($('#diamond').val()-object.diamond_number);
				  prompt(object.content);
			 break;

			 /**材料兑换**/
			 case 60001: prompt(object.content);break;  //材料不存在
			 case 60002: prompt(object.content);break;  //数量格式有误
			 case 60003: prompt(object.content);break;  //只能用果实或金币进行兑换
			 case 60004: prompt(object.content,'fruit');break; //果实数量不足
			 case 60005: prompt(object.content);break; //兑换失败，果实仓库修改出错
			 case 60006: prompt(object.content);break; //金币不足
			 case 60007: prompt(object.content);break; //兑换失败，金币修改出错
			 case 60008: prompt(object.content);break; //兑换失败，材料增加出错
			 case 60009: //兑换成功，材料已加入个人仓库
				   var needfruita = $('.needfruita_'+number).text()*$(".deals_number_"+number).val();
				   var needfruitb = $('.needfruitb_'+number).text()*$(".deals_number_"+number).val();
				   $(".have_fruita_"+number).text($(".have_fruita_"+number).text()-needfruita);
				   $(".have_fruitb_"+number).text($(".have_fruitb_"+number).text()-needfruitb);
				   prompt(object.content);
			 break;
			 case 60010: prompt(object.content);break; //兑换记录修改失败
			 case 60011:                               //兑换宝石成功
				   $('#coin').val($('#coin').val()*1-number);
				   $('#diamond').val($('#diamond').val()*1+number*100);
				   $('#money_number').text($('#coin').val());
				   $('.recharge_interface').hide();
			 /**商店购买**/
			 case 70001: prompt(object.content);break;   //数量已卖完
			 case 70002: prompt(object.content);break;   //商店修改失败
			 case 70003: prompt(object.content,'diamond');break;   //宝石不够
			 case 70004: prompt(object.content);break;  //道具仓库修改失败
			 case 70005: prompt(object.content);break;   //宝石修改失败
			 case 70006: prompt(object.content);break;   //购买失败
			 case 70007:
				  $('#diamond').val($('#diamond').val()-number);
				  prompt(object.content);
			 break;  //购买成功
			 case 70008: prompt(object.content);break;   //管家记录购买失败
			 case 70009: prompt(object.content,'fruit');break;   //没有购买宝箱物品
			 case 70010: prompt(object.content);break;   //购买宝箱物品数量不足
			 case 70011: prompt(object.content);break;   //种子仓库修改失败
			 case 70012: prompt(object.content);break;  //宝箱仓库修改失败

			 
			 
			 /**帐号类返回码**/
			 
			 //找回密码
			 
			 case 80001:
				   $("#phone_number").val(object.content);
			 break;
			 case 80002:
				   var wait = 180; countdown(wait,'find');
			 break;
			 case 80003:
				   $("#message_code").val(object.content);
			 break;
			 case 80004:
				   $('.forgot_password').hide();
				   $(".reset_password").show();
				   $(".reset_password").css("z-index","3")
			 break;
			 case 80005:
				  $('.alert_success').show();
				  $('.success_rompt span').text(object.content);
				  $('#success').click(function(){
					  $(".reset_password").hide();
					  $(".box_hide").hide();
					  $(".forgot_password input").val("");
					  $(".reset_password input").val("");
					  $(".alert_success input").val("");
					  $("#phone_code").val("发送验证码");
					  $(".alert_success").hide();
					  $(".enter_game").show();
					  $('.box_show').show();
					  $(".sign_in").show();
				 })
			 break;

			 
			 //登录
			 case 80006:
				  $(".loading_img img").remove();
				  prompt(object.content);break;   //网络连接失败
			 break;
			 case 80007:
				  $(".loading_img img").remove();
				  //prompt(object.content);break;  //登录失败
				  $(".login_alert span").text(object.content);
				  $(".login_alert").show();
				  setTimeout(function(){$(".login_alert").hide();},2000);
			 break;
			 case 80009:  //登录成功
				  window.location.href= url+"Index";
				  //$(".sign_in").show();
			 break; 

			 //注册
			 case 90001:
				   $(".validation_hints").show();
				   $(".validation_hints span").text(object.content);
				   setTimeout(function(){$(".validation_hints").hide();},2000);             
				   $('#img_code').click();
			 break;
			 case 90002: //注册成功
				   $(".validation_hints").show();
				   $(".validation_hints span").text(object.content);
				   setTimeout(function(){$(".validation_hints").hide();},2000);
				   setTimeout(function(){
					   $(".register_interface").hide();
					   $(".box_hide").hide();
					   $('.box_show').show();
					   $(".sign_in").show();
				   },3000)
			 break; 
			
			
			 //session过期
			 case 888888:alert(object.content);break;
		}
			
		
	

   
}


