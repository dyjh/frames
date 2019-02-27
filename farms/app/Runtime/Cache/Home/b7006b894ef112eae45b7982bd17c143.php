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
<script>
URL_PATHINFO_DEPR = "<?php echo C('URL_PATHINFO_DEPR');?>";
_module = "<?php echo PHP_FILE;?>/";
_url      = _module + "<?php echo CONTROLLER_NAME; echo C('URL_PATHINFO_DEPR');?>";
</script>
<script type="text/javascript" src="/farms/Public/Home/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/jquery_mobile.1.4.5.min.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/index.js?r=83333"></script>
<script type="text/javascript" src="/farms/Public/Home/js/code.js?r=83333"></script>
<link rel="stylesheet" type="text/css" href="/farms/Public/Home/css/land.css" />
<link rel="stylesheet" type="text/css" href="/farms/Public/Home/css/index.css?r=84444" />
</head>
<script>

function sel_headimg(){

      var img = "<?php echo $user_message[0]['headimg']; ?>";
	  
      if(img==""){
          $('.select_head').show();
          mask('open');
		  return false;
      }
	  
	  if(<?php echo ($no_read_title); ?>!==0){
	     $('.game_announcement').show();
		 mask('open');
		 return false;
	  }
}
setTimeout(function(){
    sel_headimg();
},1000);


//网络连接
function refresh(time){

    //获取登陆时间
   if(time==undefined){
       //last_time = <?php echo session('last_login');?>;	   
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
        mask('open');
        $('.internet_box').show();
        clearInterval(scoll);
        clearInterval(planting);
   }else{
        setTimeout('refresh(last_time)',960000);
   }
}

setTimeout('refresh()',1000);


function toggleSound(){
   var audio = document.getElementById('music'); 
   if(audio!==null){             
       //检测播放是否已暂停.audio.paused 在播放器播放时返回false.
        //alert(audio.paused);
      if(audio.paused)                     {                 
        audio.play();//audio.play();// 这个就是播放  
      }else{
        audio.pause();// 这个就是暂停
      }
   } 
}

/*$(function(){
   setTimeout(function(){
       $('#colsemusic').trigger("click");
   },2000)
})*/


</script>

<!--<body oncontextmenu=return(false)>-->
<body>
<div class="kj" id="kj">
  <div class="box">	
	<div class="box_two">
	<div class="box_hide"></div>
	<!--新手指引弹窗-->
	<div class="guidance"></div>


	<div class="newguidance">
		<div class="guidance_center"><b>是否结束新手指引</b></div>
		<div class="guidance_btn" id="guidance_sure"></div>
		<div class="guidance_btn" style="left: 50%" id="guidance_return"></div>
	</div>

	<!--前往牧场-->
	<a href="<?php echo U('Pasture/index');?>" rel="external">
		<div class="go_pasture">
			<img src="/farms/Public/Home/images/index/go_pasture.png" style="height: 100%;width: 100%">
		</div>
	</a>
	
	<!--文字翻滚-->
	<div class="text_box">
		<div class="text_boxx" id="text_boxx">
		    <?php echo ($arc_str); ?>
		</div>
	</div>
	<!--通用提示框三秒消失-->
	<div class="cue_box" id="cue_box">
		<div class="cue" id="cue_span">
			<span></span>
		</div>
	</div>
	<!--手机活动-->
	<div class="phone_box"></div>
	<!--稻草人-->
	<?php if($scarecrow['end_time'] != ''): ?><div class="scarecrow" id="scarecrow" end_time="<?php echo ($scarecrow['end_time']); ?>"></div>
	<?php else: endif; ?>
	<!--系统公告-->
	<div class="game_announcement">
		<!--<div class="ga_title"><b>钻石充值送种子</b></div>-->
		<div class="ga_body"> 
		   <?php $__FOR_START_461629742__=0;$__FOR_END_461629742__=count($no_read);for($i=$__FOR_START_461629742__;$i < $__FOR_END_461629742__;$i+=1){ ?><div class="ga_box">
				  <b class="ga_box_title"><?php echo ($no_read[$i]['title']); ?></b>
				  <p style="text-align:center"><?php echo (date('Y-m-d H:i:s',$no_read[$i]['time'])); ?></p>
				  <p><span><?php echo htmlspecialchars_decode(htmlspecialchars_decode($no_read[$i]['content'])); ?></span></p>
			  </div><?php } ?>
		</div>
		<div class="ga_return"></div>
	</div>
	  <!--头像选择开始-->
		<div class="select_head">
			<div class="head_1 positon" onclick="select_head(1)"></div>
			<div class="head_2 positon" onclick="select_head(2)"></div>
			<div class="head_3 positon" onclick="select_head(3)"></div>
			<div class="head_4 positon" onclick="select_head(4)"></div>
			<div class="head_5 positon" onclick="select_head(5)"></div>
			<div class="head_6 positon" onclick="select_head(6)"></div>
			<div class="head_title"><span></span></div>
			<input type="text" name="" class="head_user" maxlength="10" placeholder="六个字以内">
			<div class="head_sure" onclick="head_sure()"></div>
		</div>
		<!--头像选择结束-->
	  <!--开启宝箱界面-->
	  <div class="chest_box box_window">
		<div class="chest_center">
		
		  <div class="chest_body" style="margin-left: 8%">
			   <img src="" class="chest_img">
			<div class="chest_open" onclick="open_box(1)"></div>
		  </div>
		   <div class="chest_body">
				<div class="chest_list">
				    <span></span>
				</div>
				<div class="rate_box">
				    <span>当前倍数</span>
				</div>
				<div class="rate_box"><input type="number" style="margin-top:3%" class="rate_number" value="1"></div>
				<div class="rate_box rate_height">
				    <div class="rate_lost" onclick="rate(1)"></div>
				    <div class="rate_add" onclick="rate(2)"></div>
				</div>
		  </div>

		  <div class="chest_strip">
			 <img src="/farms/Public/Home/images/box/cheststrip.gif">
		  </div>
		  <div class="chest_footer"><span></span></div>
		  <div class="chest_return" onclick="close_windows(1)"></div>
		</div>
	  </div>
	  <!--开启宝箱结束-->
	  <!--签到大礼包-->
	  <div class="sign_box">
		 <?php if($user_message[0]['sign_state'] == 1): else: ?>
			 <div class="sign">
			   <img src="/farms/Public/Home/images/index/qiandao.png" onclick="menus('signs',1,0)">
			   <div class="sign_title"><span></span></div>
			 </div><?php endif; ?>
		 <?php if($user_message[0]['gift_state'] == 1): else: ?>
		   <div class="spree">
			 <img src="/farms/Public/Home/images/index/dalibao.png" onclick="menus('spree',1,0)">
		   </div><?php endif; ?>
		 <div class="ly_service">
			 <img src="/farms/Public/Home/images/index/ly_service.png" onclick="menus('service',1,0)">
		 </div>
		  <div class="ly_gonggao">
		     <a href="/ksnc/User-login-content-1" rel="external"><img src="/farms/Public/Home/images/index/ly_gonggao.png"></a>
		 </div>
		 <!--<div id="colsemusic" onclick="toggleSound()">关闭音乐</div>-->
	  </div>
	  <!--签到大礼包结束-->
	  <div class="spree_show"></div>
	  <div class="service_show"></div>
  <!--宝箱中奖滚动条-->
  <div class="scroll_bar">
     <div class="scroll_img"><img src="/farms/Public/Home/images/fruit/shiny.gif"></div>
     <div class="scroll_text"><span></span></div>
  </div>
  <!--宝箱中奖滚动条结束-->
	  <!--界面头部-->
			<div class="header">
			  <div class="head_portrait">
				  <?php if($user_message[0]['headimg'] == ''): else: ?>
					 <img src="<?php echo ($user_message[0]['headimg']); ?>"><?php endif; ?>
				<!--<div class="user_information">
				  <div class="user_return" ></div>
				  <div class="user_box">
					<div class="user_head">
					  <img src="images/user.png">
					</div>
					<div class="user_information_box">
					  <div class="game_name"><span>南中临江</span></div>
					  <div class="user_id"><span>33998</span></div>
					  <div class="house_level"><span>LV.1</span></div>
					  <div class="user_money"><span>888</span></div>
					</div>
				  </div>
				</div>-->
			  </div>
			  <div class="statistics">
				<div class="user_moneyshow"><input type="text" id="diamond" value="<?php echo ($user_message[0]['diamond']); ?>" readonly="readonly" ></div>
				<div class="user_moneyshow"><input type="text" id="coin" value="<?php echo ($user_message[0]['coin']); ?>" readonly="readonly" ></div>
			  </div>
			  <div class="_option">
				<div class="market" onclick="menus('log',1,0)">
				</div>
				<div class="ranking_List" onclick="menus('ranking',1,0)"></div>
				<div class="recharge"></div>
				<div class="set_up">
				</div>
			  </div>
		   </div>
			<!--界面头部结束-->
			<!--金币充值界面-->
			<div class="recharge_interface">
				<a href="/ksnc/User-login-is_pay-1" rel="external"><div class="change_gold"></div></a>
				<div class="change_diamonds">
					<div class="diamonds_click" id="twothousand_diamonds" onclick="topup(20)"></div>
					<div class="diamonds_click" id="twentythousand_diamonds" onclick="topup(200)"></div>
				</div>
				<div class="recharge_return"></div>
			</div>
			<!--金币充值界面结束-->
			<!--设置界面-->
			<div class="setup_alert">
				<div class="setup_return"></div>
				<div onclick="menus('cut',1,0)" class="switch_account"></div>
			</div>
			<!--设置界面结束-->
			<!--日志-->
			<div class="market_box market_window">
			  <div class="market_list">

			  </div>

			  <div class="marketbox_return" onclick="close_windows(1)"></div>
			</div>
			<!--日志结束-->
			<!--排行板-->
			<div class="ranking_box ranking_window">
			  <div class="ranking_heade">
				<div class="ranking_heade_list">名次</div>
				<div class="ranking_heade_list">昵称</div>
				<div class="ranking_heade_list">等级</div>
				<div class="ranking_heade_list">财富</div>
			  </div>
			  <div class="ranking_body">

			  </div>
			  <div class="rankingList_return" onclick="close_windows(1)"></div>
			</div>
			<!--排行板结束-->
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
	  <!--通用弹窗-->
	  <div class="all_insufficient prompt_window">
			<div class="all_box">
			  <div class="all_box_list">
				<span></span>
			  </div>
			  <div class="all_box_list">
				  <div class="box_list_img" onclick="close_windows()"></div>
				 <!--  <div class="box_list_img" onclick="$('.all_insufficient').hide()"></div>-->
			  </div>
			</div>
	  </div>
	  <!--通用弹窗结束-->
	  <!--头像点击框-->
	  <div class="head_show head_window">
	  	<div class="user_nike"><span><?php echo ($user_message[0]['nickname']); ?></span></div>
	  	<div class="user_level"><span><?php echo ($user_message[0]['level']); ?></span></div>
	  	<div class="user_number"><span><?php echo ($user_message[0]['num_id']); ?></span></div>
	  	<div class="user_jb">
		    <?php if($user_message[0]['identity'] == ''): ?><span>普通用户</span>
			<?php else: ?><span><?php echo ($user_message[0]['identity']); ?></span><?php endif; ?>		
	    </div>
	  	<div class="head_return" onclick="close_windows(1)"></div>
	  </div>
	  <!--头像点击框结束-->
	  <!--房屋-->
	  <div class="user_house">
		  <img id="house_level" src="/farms/Public/Home/images/index/house_<?php echo ($land_available); ?>.png">
	  </div>
	  <!--土地状态提示信息-->
	    <?php $__FOR_START_1563843672__=1;$__FOR_END_1563843672__=13;for($i=$__FOR_START_1563843672__;$i < $__FOR_END_1563843672__;$i+=1){ ?><div id="land_prompt_<?php echo ($i); ?>" class="land_prompt"></div><?php } ?>
		<!--升级弹窗-->
		<div class="upgrade_window">
			<div class="upgrade_box"></div>
			<button id="a_return" onclick="close_windows(1)"></button>
		</div>
		<!--除灾施肥弹窗-->
		<div class="other_window">
			<img src="/farms/Public/Home/images/index/addition_disaster.png" onclick="User_action('disaster')">
			<img src="/farms/Public/Home/images/index/feed_bg.png" onclick="User_action('fertilization')">
		</div>
		<!--种植弹窗-->
		<div class="planting_window">
			<img src="/farms/Public/Home/images/index/sow_bg.png" onclick="User_action('planting')">
		</div>
		<!--收获弹窗-->
		<div class="harvest_window">
			<img src="/farms/Public/Home/images/index/addition_disaster.png" onclick="User_action('disaster')">
			<img src="/farms/Public/Home/images/index/shouhuo.png" onclick="User_action('harvest')">
		</div>

		<div class="land_sum_layer">
				 <?php
 for($i=0;$i<$land_sum/4;$i++){ if($i==0){ echo '<div class="land_sum_list">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="land_sum" id="land_sum_'.$j.'""><img src="/farms/Public/Home/images/lutu.png"/></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($j==$next_level){ echo '<div class="land_sum" id="land_sum_'.$j.'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/upgrade.png"/></div>'; }else{ echo '<div class="land_sum" id="land_sum_'.$j.'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/lutu.png"/></div>'; } } } echo '</div>'; } if($i==1){ echo '<div class="land_sum_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<5;$j++){ if($j==1){ if($j+4==$next_level){ echo '<div class="land_sum" id="land_sum_'.($j+4).'"><img src="/farms/Public/Home/images/upgrade.png"/></div>'; }else{ echo '<div class="land_sum" id="land_sum_'.($j+4).'"><img src="/farms/Public/Home/images/lutu.png"/></div>'; } }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($j+4==$next_level){ echo '<div class="land_sum" id="land_sum_'.($j+4).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/upgrade.png"/></div>'; }else{ echo '<div class="land_sum" id="land_sum_'.($j+4).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/lutu.png"/></div>'; } } } echo '</div>'; } if($i==2){ echo '<div class="land_sum_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<5;$j++){ if($j==1){ if($j+8==$next_level){ echo '<div class="land_sum" id="land_sum_'.($j+8).'"><img src="/farms/Public/Home/images/upgrade.png"/></div>'; }else{ echo '<div class="land_sum" id="land_sum_'.($j+8).'"><img src="/farms/Public/Home/images/lutu.png"/></div>'; } }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($j+8==$next_level){ echo '<div class="land_sum" id="land_sum_'.($j+8).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/upgrade.png"/></div>'; }else{ echo '<div class="land_sum" id="land_sum_'.($j+8).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/lutu.png"/></div>'; } } } echo '</div>'; } } ?>
				</div>
		<div class="land_available_layer">
		  <?php
 for($i=0;$i<ceil($land_available/4);$i++){ if($i==0){ if($land_available>=4){ $block = 5; }else{ $block = $land_available+1; } echo '<div class="land_available_list">'; for($j=1;$j<$block;$j++){ if($j==1){ echo '<div class="land_sum"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; echo '<div class="land_sum" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; } } echo '</div>'; } if($i==1){ if($land_available>=8){ $block = 5; }else{ $block = $land_available-4+1; } echo '<div class="land_available_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<$block;$j++){ if($j==1){ echo '<div class="land_sum"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; echo '<div class="land_sum" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; } } echo '</div>'; } if($i==2){ if($land_available>=12){ $block = 5; }else{ $block = $land_available-8+1; } echo '<div class="land_available_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<$block;$j++){ if($j==1){ echo '<div class="land_sum"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; echo '<div class="land_sum" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/zhongtu.png"/></div>'; } } echo '</div>'; } } ?>
		</div>
		<div class="disaster_layer">
			 <?php	 for($i=0;$i<ceil(count($planting_state)/4);$i++){ if($i==0){ if(count($planting_state)>=4){ $block = 5; }else{ $block = count($planting_state)+1; } echo '<div class="disaster_list">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j]=="" || $planting_state[$j]['disasters_state']==0){ echo '<div class="disaster_state" id="disaster_'.$j.'"></div>'; }else{ if($planting_state[$j]['disasters_state']==1 || $planting_state[$j]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.$j.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.$j.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j]['disasters_state'].'.gif"/></div>'; } } }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($planting_state[$j]=="" || $planting_state[$j]['disasters_state']==0){ echo '<div class="disaster_state" id="disaster_'.$j.'" style="margin-left:'.$land_css.'"></div>'; }else{ if($planting_state[$j]['disasters_state']==1 || $planting_state[$j]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.$j.'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.$j.'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j]['disasters_state'].'.gif"/></div>'; } } } } echo '</div>'; } if($i==1){ if(count($planting_state)>=8){ $block = 5; }else{ $block = count($planting_state)-4+1; } echo '<div class="disaster_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j+4]=="" || $planting_state[$j+4]['disasters_state']==0){ echo '<div class="disaster_state" id="disaster_'.($j+4).'"></div>'; }else{ if($planting_state[$j+4]['disasters_state']==1 || $planting_state[$j+4]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.($j+4).'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+4]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j+4]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.($j+4).'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+4]['disasters_state'].'.gif"/></div>'; } } }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($planting_state[$j+4]=="" || $planting_state[$j+4]['disasters_state']==0){ echo '<div class="disaster_state" id="disaster_'.($j+4).'" style="margin-left:'.$land_css.'"></div>'; }else{ if($planting_state[$j+4]['disasters_state']==1 || $planting_state[$j+4]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.($j+4).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+4]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j+4]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.($j+4).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+4]['disasters_state'].'.gif"/></div>'; } } } } echo '</div>'; } if($i==2){ if(count($planting_state)>=12){ $block = 5; }else{ $block = count($planting_state)-8+1; } echo '<div class="disaster_list" style="margin-left:-9%;margin-top:0;">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j+8]=="" || $planting_state[$j+8]['disasters_state']==0){ echo '<div class="disaster_state" id="disaster_'.($j+8).'"></div>'; }else{ if($planting_state[$j+8]['disasters_state']==1 || $planting_state[$j+8]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.($j+8).'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+8]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j+8]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.($j+8).'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+8]['disasters_state'].'.gif"/></div>'; } } }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; if($planting_state[$j+8]=="" || $planting_state[$j+8]['disasters_state']==0){ echo '<div class="disaster_state" style="margin-left:'.$land_css.'" id="disaster_'.($j+8).'"></div>'; }else{ if($planting_state[$j+8]['disasters_state']==1 || $planting_state[$j+8]['disasters_state']==3){ echo '<div class="disaster_state" id="disaster_'.($j+8).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+8]['disasters_state'].'.png"/></div>'; }else if($planting_state[$j+8]['disasters_state']==2){ echo '<div class="disaster_state" id="disaster_'.($j+8).'" style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/disasters_'.$planting_state[$j+8]['disasters_state'].'.gif"/></div>'; } } } } echo '</div>'; } } ?>
		</div>
		<div class="planting_state_layer">
			 <?php
 for($i=0;$i<ceil(count($planting_state)/4);$i++){ if($i==0){ if(count($planting_state)>=4){ $block = 5; }else{ $block = count($planting_state)+1; } echo '<div class="planting_state_list" style="margin-left: 2%">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j]==""){ echo '<div class="planting_state" id="planting_num_'.$j.'" style="margin-left: 10%" planting_state=1></div>'; }else{ if($planting_state[$j]['seed_state']==0){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left: 10%"><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j]['seed_state']==1){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left: 10%"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; }else if($planting_state[$j]['seed_state']==2){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left: 10%"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; }else if($planting_state[$j]['seed_state']==3){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2  seed_state=3 style="margin-left: 10%"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; } } }else{ $land_css = ($j-1)*35+10; $land_css = $land_css.'%'; if($planting_state[$j]==""){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=1 style="margin-left:'.$land_css.'"></div>'; }else{ if($planting_state[$j]['seed_state']==0){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j]['seed_state']==1){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; }else if($planting_state[$j]['seed_state']==2){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; }else if($planting_state[$j]['seed_state']==3){ echo '<div class="planting_state" id="planting_num_'.$j.'" planting_state=2 seed_state=3 style="margin-left:'.$land_css.'"><img src="/farms/Public/Home/images/'.$planting_state[$j]['seed_img_name'].$planting_state[$j]['seed_state'].'.png"/></div>'; } } } } echo '</div>'; } if($i==1){ if(count($planting_state)>=8){ $block = 5; }else{ $block = count($planting_state)-4+1; } echo '<div class="planting_state_list" style="margin-left:-6%;margin-top: 0%">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j+4]==""){ echo '<div class="planting_state" id="planting_num_'.($j+4).'" planting_state=1></div>'; }else{ if($planting_state[$j+4]['seed_state']==0){ echo '<div class="planting_state" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==1){ echo '<div class="planting_state" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==2){ echo '<div class="planting_state" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==3){ echo '<div class="planting_state" id="planting_num_'.($j+4).'" planting_state=2 seed_state=3><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; } } }else{ $land_css = ($j-1)*36; $land_css = $land_css.'%'; if($planting_state[$j+4]==""){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+4).'" planting_state=1></div>'; }else{ if($planting_state[$j+4]['seed_state']==0){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==1){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==2){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+4).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+4]['seed_state']==3){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+4).'" planting_state=2 seed_state=3><img src="/farms/Public/Home/images/'.$planting_state[$j+4]['seed_img_name'].$planting_state[$j+4]['seed_state'].'.png"/></div>'; } } } } echo '</div>'; } if($i==2){ if(count($planting_state)>=12){ $block = 5; }else{ $block = count($planting_state)-8+1; } echo '<div class="planting_state_list" style="margin-left:-10%;margin-top:0">'; for($j=1;$j<$block;$j++){ if($j==1){ if($planting_state[$j+8]==""){ echo '<div class="planting_state" id="planting_num_'.($j+8).'" planting_state=1 style="margin-left: 7%"></div>'; }else{ if($planting_state[$j+8]['seed_state']==0){ echo '<div class="planting_state" id="planting_num_'.($j+8).'" planting_state=2 style="margin-left: 7%"><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==1){ echo '<div class="planting_state" id="planting_num_'.($j+8).'" planting_state=2 style="margin-left: 7%"><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==2){ echo '<div class="planting_state" id="planting_num_'.($j+8).'" planting_state=2 style="margin-left: 7%"><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==3){ echo '<div class="planting_state" id="planting_num_'.($j+8).'" planting_state=2 seed_state=3 style="margin-left: 7%"><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; } } }else{ $land_css = ($j-1)*36; $land_css = $land_css.'%'; if($planting_state[$j+8]==""){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+8).'" planting_state=1></div>'; }else{ if($planting_state[$j+8]['seed_state']==0){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+8).'" planting_state=2><img src="/farms/Public/Home/images/zhongzi.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==1){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+8).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==2){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+8).'" planting_state=2><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; }else if($planting_state[$j+8]['seed_state']==3){ echo '<div class="planting_state" style="margin-left:'.$land_css.'" id="planting_num_'.($j+8).'" planting_state=2 seed_state=3><img src="/farms/Public/Home/images/'.$planting_state[$j+8]['seed_img_name'].$planting_state[$j+8]['seed_state'].'.png"/></div>'; } } } } echo '</div>'; } } ?>
		</div>
		<div class="seeds_layer">
		  <?php
 for($i=0;$i<$land_sum/4;$i++){ if($i==0){ echo '<div class="seeds_list" style="margin-left:2%">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="seeds_state" id="seeds_'.$j.'"" style="margin-left:10%"></div>'; }else{ $land_css = ($j-1)*35+10; $land_css = $land_css.'%'; echo '<div class="seeds_state" id="seeds_'.$j.'" style="margin-left:'.$land_css.'"></div>'; } } echo '</div>'; } if($i==1){ echo '<div class="seeds_list" style="margin-left:-6%;margin-top:0%">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="seeds_state" id="seeds_'.($j+4).'"></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; echo '<div class="seeds_state" id="seeds_'.($j+4).'" style="margin-left:'.$land_css.'"></div>'; } } echo '</div>'; } if($i==2){ echo '<div class="seeds_list" style="margin-left:-10%;margin-top:0">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="seeds_state" id="seeds_'.($j+8).'"></div>'; }else{ $land_css = ($j-1)*35; $land_css = $land_css.'%'; echo '<div class="seeds_state" id="seeds_'.($j+8).'" style="margin-left:'.$land_css.'"></div>'; } } echo '</div>'; } } ?>
		</div>
		<div class="interaction_layer" data-role="page" id="pageone">
		  <?php
 for($i=0;$i<$land_sum/4;$i++){ $land_sum_list_css = $i*5; $land_sum_list_css = $land_sum_list_css."px"; if($i==0){ echo '<div class="interaction_list">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="interaction" id='.$j.' style="margin-left: -10%"></div>'; }else{ $land_css = ($j-1)*34-10; $land_css = $land_css.'%'; $land_top = $j+1; $land_top = $land_top.'%'; echo '<div class="interaction" style="margin-left:'.$land_css.';margin-top: '.$land_top.'" id='.$j.'></div>'; } } echo '</div>'; } if($i==1){ echo '<div class="interaction_list" style="margin-left: -10%;margin-top: 0%">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="interaction" id='.($j+4).' style="margin-left: -9%"></div>'; }else{ $land_css = ($j-1)*34-10; $land_css = $land_css.'%'; $land_top = $j+1; $land_top = $land_top.'%'; echo '<div class="interaction" style="margin-left:'.$land_css.';margin-top: '.$land_top.'" id='.($j+4).'></div>'; } } echo '</div>'; } if($i==2){ echo '<div class="interaction_list" style="margin-left: -8%;margin-top: 0px;">'; for($j=1;$j<5;$j++){ if($j==1){ echo '<div class="interaction" id='.($j+8).' style="margin-left: -10%"></div>'; }else{ $land_css = ($j-1)*34-10; $land_css = $land_css.'%'; $land_top = $j+1; $land_top = $land_top.'%'; echo '<div class="interaction" style="margin-left:'.$land_css.';margin-top: '.$land_top.'" id='.($j+8).'></div>'; } } echo '</div>'; } } ?>
		</div>

		<!-- 兑换中心-->
		<div class="exchange_window">
		  <div class="exchange_body">
		 </div>
		 
		 <!--<div class="exchange_page_box">
		   <div class="exchange_page" onclick="page('pre')">上一页</div>
		   <div class="exchange_page">1</div>
		   <div class="exchange_page" onclick="page('next')">下一页</div>
		 </div>
		 -->
		 <div class="exchange_return" onclick="close_windows(1)"></div>
	  </div>
		<!-- 兑换中心结束-->

		<!--仓库界面开始-->
		<div class="warehouse_box warehouse_box1 warehouse_window">
		  <div class="warehouse_classification">
			<div class="warehouse_classificationbox">
			  <div class="warehouse_fruit" onclick="menus('warehouse',1,'fruit')"><span>果实</span></div>
			  <div class="warehouse_material" onclick="menus('warehouse',1,'material')"><span>材料</span></div>
			  <div class="warehouse_prop" onclick="menus('warehouse',1,'prop')"><span>道具</span></div>
			  <div class="warehouse_utreasure" onclick="menus('warehouse',1,'utreasure')"><span>宝箱</span></div>
			  <div class="warehouse_huodong" onclick="menus('warehouse',1,'huodong')"><span>活动</span></div>
			</div>
			<div class="warehouse_show">
			</div>
            <div class="ser_btn" id="ser_btn" onclick="menus('shop',1,'exchange')"></div>
		  </div>
		  <!--
		  <div class="warehouse_nextpage">
			  <div class="nextpage_box">
				<div class="nextpage_box_list" onclick="page('pre')"><span>上一页</span></div>
				<div class="nextpage_box_list"><span>1</span></div>
				<div class="nextpage_box_list"><span onclick="page('next')">下一页</span></div>
			  </div>
		  </div>
		  -->
		  <div class="warehouse" onclick="close_windows(1)"></div>
		</div>
		<!--仓库界面结束-->
		<!--果实兑换服务界面-->	
		<div class="firut_ser_window" id="firut_ser_window">
			<div class="firut_ser_box">
				<div class="ser_exchange_box">
					<div class="ser_hed">
										
					</div>
					<div class="ser_fir_text" id="ser_fir_text"></div>
				    <div class="ser_body">
				    	<div class="ser_drop" onclick="count('ser_firt',1,1)"></div>
						<div class="ser_b_num">
							<input type="number" value="1" class="ser_firt_1">
						</div>
						<div class="ser_add" onclick="count('ser_firt',2,1)"></div>
				    </div>
				    <div class="ser_foot" onclick="exchange_service()"></div>
				</div>
				<div class="ser_return" onclick="close_windows(1)">			
				</div>
			</div>
		</div>
		<!--果实兑换服务界面结束-->
		<!--果实重生-->
		<div class="reborn_box reborn_window">
			<div class="reborn_bac"></div>
			<div class="seed_fruit"><img src=""></div>
			<div class="reborn_text"></div>
			<div class="reborn_footer">
			    <div class="seed_down" onclick="reborncot(1)"></div>
			    <div class="seed_number"><input id="seed_number" value="1000" readonly="readonly"></div>
			    <div class="seed_up" onclick="reborncot(2)"></div>
			    <div class="seed_crit" id="seed_crit">
			    	<button onclick="crit()">暴击</button>
			    </div> 
			    <div class="seed_btn" id="seed_btn">
			    	<button onclick="rebornsub()">重生</button>
			    </div>
			</div>
			<div class="reborn_quit" onclick="close_windows(1)"></div>	
		</div>
		<!--果实重生结束-->
		<!--商店-->
		<div class="shop_window">
		   <div class="shop_heade">
			 <div class="shop_heade_list shop_seed" onclick="menus('shop',1,'seed')"><span>种植</span></div>
			 <div class="shop_heade_list shop_shopprop" onclick="menus('shop',1,'shopprop')"><span>道具</span></div>
			 <div class="shop_heade_list shop_treasure" onclick="menus('shop',1,'treasure')"><span>宝箱</span></div>
			 <div class="shop_heade_list shop_service" onclick="menus('shop',1,'service')" id="services"><span>功能</span></div>
		   </div>
		   <div class="shop_body">


		   </div>
		   <!--
		   <div class="warehouse_nextpage">
				 <div class="nextpage_box">
				   <div class="nextpage_box_list"><span onclick="page('pre')">上一页</span></div>
				   <div class="nextpage_box_list"><span>1</span><span>/</span><span>1</span></div>
				   <div class="nextpage_box_list"><span onclick="page('next')">下一页</span></div>
				 </div>
		   </div>
		   -->
		   <div class="shop_return" onclick="close_windows(1)"></div>
		 </div>

	   <!--商店结束-->

		<!--底部列表-->
		<div class="footer">
			 <div class="footer_menu"></div>
			 <div class="footer_menu2 footer_menu"></div>
			 <div class="footer_menubar">
			   <a href="<?php echo U('Market/index');?>" rel="external"><div class="footer_classification" id="farmproduct_market" style="margin-left: 0"></div></a>
			   <div id="material" class="footer_classification" onclick="menus('exchange',1)"></div>
			   <div class="footer_classification" onclick="menus('warehouse',1,'fruit')"></div>
			   <div class="footer_classification" id="shop_menu" onclick="menus('shop',1,'seed')"></div>
			   <a href="/ksnc/User-login" rel="external"><div class="footer_classification" id="usercenter_menu"></div></a>
			 </div>
		  </div>
		   <!--底部列表结束-->
	</div>
  </div>
</div>


<input type="hidden" value="" id="type">
<input type="hidden" value="" id="page">
<input type="hidden" value="<?php echo ($land_available); ?>" id="level">
<input type="hidden" value="" id="land_number">
<input type="hidden" value="" id="head">
<input type="hidden" value="" id="box">
<input type="hidden" value="<?php echo ($token); ?>" id="token">

<!--<audio src="/farms/Public/Home/music/bgmusic<?php echo ($music); ?>.mp3" preload="preload" loop="loop" id="music"></audio>-->


</body>
</html>