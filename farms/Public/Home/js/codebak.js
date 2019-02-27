
function return_code(code,number){

    var object = $.parseJSON(code);
    switch(object.state){
         /**种值返回码**/
         /************/
         case 10000: alert(object.content);break; //种子数量不够
         case 10001: alert(object.content);break; //种子数量不够
         case 10002: alert(object.content);break; //帐户等级有误
         case 10003: alert(object.content);break; //种子不存在
         case 10004: alert(object.content);break; //种植失败
         case 10005: alert(object.content);break; //仓库种子扣除失败
         case 10006: //种值成功
              $('#land_sum_'+number).attr("state",2);
              $('#seeds_'+number).append('<img src="/farms/Public/Home/images/zhongzi.png"/>');
              alert(object.content);
              break;
         case 10007: alert(object.content);break; //重复种植
          /**施肥返回码**/
          /************/
         case 20001: alert(object.content);break; //道具不够
         case 20002: alert(object.content);break; //该种子不存在
         case 20003: alert(object.content);break; //施肥失败
         case 20004: //施肥成功
              if(object.next_phase==1 || object.next_phase==2){
                  $('#planting_num_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
                  $('#seeds_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
              }else if(object.next_phase==3){
                  $('#planting_num_'+number+' img').attr('src','/farms/Public/Home/images/'+object.seed_type+object.next_phase+'.png');
                  $('#planting_num_'+number).attr('seed_state',3);
              }
              alert(object.content);
         break; //施肥成功
         case 20005: alert(object.content);break; //该阶段只能施肥一次


         /**除灾返回码**/
         /************/
         case 30001: alert(object.content);break; //不存在灾害
         case 30002: alert(object.content);break;//除灾道具不够
         case 30003: alert(object.content);break;//除灾失败
         case 30004: //除灾成功
              $('#disaster_'+number+' img').remove();
              alert(object.content);break;
         //收获
         case 40001: alert(object.content);break;//入库失败
         case 40002: alert(object.content);break;//入库失败
         case 40003:  //入库成功，并会返回相应的数值
               $('#planting_num_'+number).attr('planting_state',1);
               $('#planting_num_'+number+' img').remove();
               $('#disasters_num_'+number+' img').remove();
               alert(object.content);
         break;
         /**升级返回码**/
         /************/
         case 50001:alert(object.content);break; //等级错误
         case 50002:alert(object.content);break; //材料不足
         case 50003:alert(object.content);break; //钻石不足
         case 50004:alert(object.content);break; //升级失败，材料修改失败
         case 50005:alert(object.content);break; //升级失败，宝石修改失败
         case 50006:alert(object.content);break; //升级失败，升级修改失败
         case 50007:alert(object.content);  //升级成功
              $('#land_sum_'+number).attr("state",1);
              $('#level').val(object.next_house);
              $('#land_sum_'+number+' img').attr('src','/farms/Public/Home/images/zhongtu.png');
              $('#house_level').attr('src','/farms/Public/Home/images/index/house_'+object.next_house+'.png');

         break;

         /**材料兑换**/
         case 60001:alert(object.content);break; //材料不存在
         case 60002:alert(object.content);break; //数量格式有误
         case 60003:alert(object.content);break; //只能用果实或金币进行兑换
         case 60004:alert(object.content);break; //果实数量不足
         case 60005:alert(object.content);break; //兑换失败，果实仓库修改出错
         case 60006:alert(object.content);break; //金币不足
         case 60007:alert(object.content);break; //兑换失败，金币修改出错
         case 60008:alert(object.content);break; //兑换失败，材料增加出错
         case 60009: //兑换成功，材料已加入个人仓库
               var needfruita = $('.needfruita_'+number).text()*$(".deals_number_"+number).val();
               var needfruitb = $('.needfruitb_'+number).text()*$(".deals_number_"+number).val();
               $(".have_fruita_"+number).text($(".have_fruita_"+number).text()-needfruita);
               $(".have_fruitb_"+number).text($(".have_fruitb_"+number).text()-needfruitb);
               alert(object.content);
         break;
         case 60010:alert(object.content);break; //兑换记录修改失败
         case 60011:
               $('#coin').val($('#coin').val()*1-number);
               $('#diamond').val($('#diamond').val()*1+number*100);
               $('#money_number').text($('#coin').val());
               $('.recharge_interface').hide();
         alert(object.content);break; //兑换宝石成功

         /**商店购买**/
         case 70001:alert(object.content);break;  //数量已卖完
         case 70002:alert(object.content);break;  //商店修改失败
         case 70003:alert(object.content);break;  //宝石不够
         case 70004:alert(object.content);break;  //道具仓库修改失败
         case 70005:alert(object.content);break;  //宝石修改失败
         case 70006:alert(object.content);break;  //购买失败
         case 70007:
              $('#diamond').val($('#diamond').val()-number);
         alert(object.content);
         break;  //购买成功
         case 70008:alert(object.content);break;  //管家记录购买失败
         case 70009:alert(object.content);break;  //没有购买宝箱物品
         case 70010:alert(object.content);break;  //购买宝箱物品数量不足
         case 70011:alert(object.content);break;  //种子仓库修改失败
         case 70012:alert(object.content);break;  //宝箱仓库修改失败

         /**帐号类返回码**/
         //找回密码
         case 80000:
             $("#message_code").val(object.content); //验证码错误
         break;
         case 80001:
             alert(object.content);  //参数错误
         break;
         case 80002:  //验证成功
              $('.forgot_password').hide();
              $(".reset_password").show();
              $(".reset_password").css("z-index","3")
         break;
         case 80003: //用户不存在
              $('#phone_number').val(object.content);
         break;
         case 80004:  //重置失败
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
           })
         break;
         case 80005:  //重置成功
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
              })
         break;
         //登录
         case 80006:
             alert(object.content);  //网络连接失败
         break;
         case 80007:
             alert(object.content);  //登录失败
         break;
         case 80009:  //登录成功
             window.location.href="http://"+window.location.host+"/farms/Home/Index/index";
         break;


         //注册
         case 90000:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               $('#img_code').click();
         break; //手机验证码错误
         case 90001:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               $('#img_code').click();
         break; //图片验证码错误
         case 90002:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               $('#img_code').click();
         break; //手机号已注册
         case 90003:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               $('#img_code').click();
         break; //注册失败
         case 90004:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               setTimeout(function(){
                 $(".register_interface").hide();
                 $(".box_hide").hide();
               },3000)
         break; //注册成功
         case 90005:
               $(".validation_hints").show();
               setTimeout(function(){$(".validation_hints").hide();},2000);
               $(".validation_hints span").text(object.content);
               $('#img_code').click();
         break; //参数错误




         //session过期
         case 888888:alert(object.content);break;
    }


}
