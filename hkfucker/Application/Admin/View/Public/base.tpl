<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>凯撒庄园后台管理</title>
<link rel="icon" href="__PUBLIC__/Home/images/logo.png" type="image/gif" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- CSS文件 -->
<link rel="stylesheet" href="__CSS__/bootstrap.min.css">
<css file="__CSS__/AdminLTE.min.css" />
<css file="__CSS__/all-skins.min.css" />
<css file="__CSS__/fruit.css" />									
<css file="__CSS__/share.css" />
<css file="__CSS__/user.css" />
<css file="__CSS__/problem.css" />
<!--<css file="__CSS__/bootstrap-select.css" />-->
<!-- JS文件 -->														
<js file="__JS__/jquery-1.11.2.min.js" />
<js file="__JS__/bootstrap.min.js" />
<js file="__JS__/app.min.js" />
<!--<js file="__JS__/echarts-all-3.js" />-->

</head>
<script>

    $(function(){

    	//时间显示
    	setInterval(changetime,1000);
    	function changetime(){
        	var date = new Date();
        	var Month = convn(date.getMonth()+1);
        	var Day = convn(date.getDate());
        	var Hours = convn(date.getHours());
        	var Minutes = convn(date.getMinutes());
        	var Seconds = convn(date.getSeconds());
        	$('#CurrentTime').html('当前系统时间：'+date.getFullYear()+'-'+Month+'-'+Day+'&nbsp;&nbsp;'+Hours+' : '+Minutes+" : "+Seconds);
    	}

    	function convn(cs){
    		if(cs<10){
    			return '0'+cs;
    		}else{
    			return cs;
    		}
    	}


 	    $('#money_code').click(function(){
            var url = "{:U('Personal/verification_code')}";
            var data = {};
            $.post(url,data,function(d){})
     		var wait = 60;
     		countdown(wait);
     	})


     	function countdown(wait){
     		if(wait==0){
     			$('#money_code').text('重新发送');
     		}else{
     			$('#money_code').text('发送中'+wait+'s');
     			wait--;
     			setTimeout(function(){
     				countdown(wait);
     	        },
     	        1000)
     		}
     	}

    })
</script>

<body class="hold-transition skin-blue sidebar-mini" id="body">
	<div class="wrapper">
		<header class="main-header">
			<!-- session获取用户名 -->
			<a href="javascript:void(0)" class="logo"> <span
				class="glyphicon glyphicon-th-list" data-toggle="offcanvas">&nbsp;<b>{$_SESSION['name']}</b></span>
			</a>
		</header>

		<aside class="main-sidebar">
			<section class="sidebar">
				<ul class="sidebar-menu">
					<!--<li class="active treeview"><a href="{:U('index/login')}">
					     <i class="glyphicon glyphicon-globe"></i> <span>返回主页</span>
					</a></li>-->
					<li class="treeview">
						<a href="{:U('Index/index')}">
							<i class="glyphicon glyphicon-home"></i><span>主页</span>
						</a>
					</li>
					<li class="treeview">
						<a href="{:U('Index/active')}">
							<i class="glyphicon glyphicon-home"></i><span>活动页面</span>
						</a>
					</li>
					<li class="treeview">
						<a href="{:U('Image/index')}">
							<i class="glyphicon glyphicon-picture"></i><span>图片管理</span>
						</a>
					</li>
					<li class="treeview">
						<a href="{:U('Package/index')}">
							<i class="glyphicon glyphicon-briefcase"></i><span>大礼包</span>
						</a>
					</li>
					<li class="treeview">
						<a href="{:U('Share/index')}">
							<i class="glyphicon glyphicon-credit-card"></i><span>分红宝</span>
						</a>
					</li>
					<li class="treeview">
						<a href="{:U('Disasters/index')}">
							<i class="glyphicon glyphicon-flash"></i><span>灾难降临</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-grain"></i> <span>种植管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/directional')}">
									<i class="glyphicon glyphicon-triangle-right"></i>种子短期定向</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/directional_long')}">
									<i class="glyphicon glyphicon-triangle-right"></i>种子长期定向</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Plant/all_plant_harvest')}">
									<i class="glyphicon glyphicon-triangle-right"></i>果实收货信息</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Back/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>回本控制</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Back/level')}">
									<i class="glyphicon glyphicon-triangle-right"></i>等级统计</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-transfer"></i> <span>牧场管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chicken/conf')}">
									<i class="glyphicon glyphicon-triangle-right"></i>配置修改</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chicken/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>小鸡主页</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chicken/house_record')}">
									<i class="glyphicon glyphicon-triangle-right"></i>购买记录</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chicken/chickens')}">
									<i class="glyphicon glyphicon-triangle-right"></i>小鸡统计</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chicken/commission')}">
									<i class="glyphicon glyphicon-triangle-right"></i>返佣记录</a>
							</li>
						</ul>
					</li>
					
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-transfer"></i> <span>交易管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>果实主页</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/seed_num')}">
									<i class="glyphicon glyphicon-triangle-right"></i>果实概率</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/sellzz')}">
									<i class="glyphicon glyphicon-triangle-right"></i>种子收购</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Fruit/add_matching')}">
									<i class="glyphicon glyphicon-triangle-right"></i>果实收购</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Rebate/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>机构列表</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Rebate/edit')}">
									<i class="glyphicon glyphicon-triangle-right"></i>返佣设置</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Rebate/record')}">
									<i class="glyphicon glyphicon-triangle-right"></i>返佣记录</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Rebate/exchange')}">
									<i class="glyphicon glyphicon-triangle-right"></i>重生统计</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Rebate/deta')}">
									<i class="glyphicon glyphicon-triangle-right"></i>重生详情</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-file"></i> <span>文章管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Lable/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>攻略标签</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Raiders/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>攻略审核</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Essay/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>公告管理</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Essay/notice_add')}">
									<i class="glyphicon glyphicon-triangle-right"></i>公告添加</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-file"></i> <span>帮助管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Guide/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>指引管理</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Problem/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>问题管理</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a>
							<i class="glyphicon glyphicon-user"></i> <span>用户管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('User/User_info')}">
									<i class="glyphicon glyphicon-th"></i>用户基础信息</a>
							</li>
							<li>
								<a href="{:U('User/Frozen')}">
									<i class="glyphicon glyphicon-th"></i>用户金币冻结信息</a>
							</li>
							<li>
								<a href="{:U('User/level')}">
									<i class="glyphicon glyphicon-th"></i>用户果实仓库</a>
							</li>
							<li>
								<a href="{:U('User/PPG')}">
									<i class="glyphicon glyphicon-th"></i>用户果实冻结信息</a>
							</li>
							<li>
								<a href="{:U('User/warehouse')}">
									<i class="glyphicon glyphicon-th"></i>用户道具仓库</a>
							</li>
							<li>
								<a href="{:U('User/freeze')}">
									<i class="glyphicon glyphicon-th"></i>用户封号</a>
							</li>
						</ul>
					</li>

					<li class="treeview">
						<a href="JavaScript:void(0)">
							<i class="glyphicon glyphicon-file"></i> <span>订单管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Pay/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>充值订单</a>
							</li>
							<li>
								<a href="{:U('Pay/cash')}">
									<i class="glyphicon glyphicon-triangle-right"></i>提现订单</a>
							</li>
							<li>
								<a href="{:U('Pay/pay_all')}">
									<i class="glyphicon glyphicon-triangle-right"></i>订单总计</a>
							</li>
							<li>
								<a href="{:U('Pay/hundred')}">
									<i class="glyphicon glyphicon-triangle-right"></i>可提现用户预览（>100）</a>
							</li>
							<li>
								<a href="{:U('Pay/groupuser')}">
									<i class="glyphicon glyphicon-triangle-right"></i>小团队用户</a>
							</li>
							<li>
								<a href="{:U('Pay/record_download')}">
									<i class="glyphicon glyphicon-triangle-right"></i>记录导出</a>
							</li>
						</ul>
					</li>

					<li class="treeview">
						<a href="{:U('Recharge/index')}">
							<i class="glyphicon glyphicon-file"></i> <span>金币充值功能</span>
						</a>
					</li>
					
					<li class="treeview">
						<a href="{:U('Zrsz/index')}">
							<i class="glyphicon glyphicon-file"></i> <span>自然生长功能</span>
						</a>
					</li>
					
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-th"></i> <span>商店管理</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Goods/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>商店列表</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Protect/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>守护记录</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chest/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>宝箱管理</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Chest/record')}">
									<i class="glyphicon glyphicon-triangle-right"></i>宝箱中奖记录</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Goods/items_add')}">
									<i class="glyphicon glyphicon-triangle-right"></i>商品添加</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="{:U('Seippd/index')}">
							<i class="glyphicon glyphicon-object-align-bottom"></i><span>碎片管理</span>
						</a>
					</li>
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-th"></i> <span>统计管理</span>
						</a>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/bltj')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i>每日数据统计功能</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/bltj_567')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i>5~7级果实统计功能</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Statistics/index')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i><span>站点统计</span>
								</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Statistics/diamond')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i><span>钻石统计</span>
								</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/level')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i><span>果实统计</span>
								</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/active')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i>不活跃用户统计</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/back')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i>种子回购统计</a>
							</li>
						</ul>
						
						<ul class="treeview-menu">
							<li class="treeview">
								<a href="{:U('Backer/zt')}">
									<i class="glyphicon glyphicon-object-align-bottom"></i>用户种植情况</a>
							</li>
						</ul>
					</li>
					


					
					<li class="treeview">
						<a href="#">
							<i class="glyphicon glyphicon-dashboard"></i> <span>配置</span>
						</a>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Global/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>系统配置</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Adm/admin')}">
									<i class="glyphicon glyphicon-triangle-right"></i>修改密码</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('Material/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>升级材料管理</a>
							</li>
						</ul>
						<ul class="treeview-menu">
							<li>
								<a href="{:U('House/index')}">
									<i class="glyphicon glyphicon-triangle-right"></i>房屋等级管理</a>
							</li>
						</ul>
					</li>
				</ul>
			</section>
		</aside>
		<!-- 公共区域  -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					<small id="CurrentTime"></small>
				</h1>
				<a style="float: right; margin-top: -25px; margin-right: 15px"
					class="btn btn-primary btn-sm" role="button" href="{:U('Login/logout')}">退出</a>
				<a style="float: right; margin-top: -25px; margin-right: 15px"
					class="btn btn-primary btn-sm" role="button" href="{:U('Index/run')}">清除缓存</a>
				<a style="float: right; margin-top: -25px; margin-right: 15px"
					class="btn btn-primary btn-sm control_volume" role="button" >关闭声音</a>
			</section>
			<block name="content"></block>
		</div>
		
		
		
		<!-- /*  2017-08-01 
	建军节快乐
	
          __     __       
         /  \~~~/  \    
   ,----(     ..    ) 
  /      \__     __/   
 /|         (\  |(
^ \   /___\  /\ |   
   |__|   |__|-" 
   
   增加提现 提示
*/ -->
	<link rel="stylesheet" type="text/css" href="__CSS__/ns-default.css" />
	<!-- <link rel="stylesheet" type="text/css" href="__CSS__/ns-style-bar.css" /> -->
	<link rel="stylesheet" type="text/css" href="__CSS__/ns-style-other.css" />
	<script src="__JS__/modernizr.custom.js"></script>

	<div style="display:none;">
		<audio id="audio_notice"  controls="controls" loop="loop" src="__COM__/mp3/1.mp3"></audio>

		<div id="notice_bar"><span class="icon icon-megaphone"></span><p>你有新的提现订单<br/>点击 <a href="{:U('Pay/cash')}">查看</a></p></div>
		
		<button id="notification-trigger" class="progress-button">
				<span class="content">显示提示框</span>
				<span class="progress"></span>
		</button>	
	</div>

	<script src="__JS__/classie.js"></script>
	<script src="__JS__/notificationFx.js"></script>
	<if condition="$cash_notice neq true">
	<script>
		(function() {
			var bttn = document.getElementById( 'notification-trigger' );

			// make sure..
			bttn.disabled = false;

			var message_notice = document.getElementById("notice_bar").innerHTML;					
				
			setInterval(function (){
				$.post(
					"{:U('Pay/get_new_cash')}",
					function(result){
						if(result==1){
							do_notice();
						}else{
							document.getElementById("audio_notice").pause();		
						}
						
					}
				)
			},10000);		
			//do_notice();
			
			function do_notice(){
				document.getElementById("audio_notice").play();						

					classie.remove( bttn, 'active' );
					
					var message_notice = document.getElementById("notice_bar").innerHTML;
					
					// create the notification
					var notification = new NotificationFx({
						message : message_notice,
						layout : 'other',
						effect : 'cornerexpand',
						type    : 'notice', 		//// notice, warning or error
						onClose : function() {
							//bttn.disabled = false;
						}
					});

					// show the notification
					notification.show();
				
				// disable the button (for demo purposes only)
				this.disabled = true;
			}
			
			bttn.addEventListener( 'click', function() {
				do_notice();
			} );
		})();
		$(".control_volume").click(function(){
		
			_volume = document.getElementById("audio_notice").volume;
			
			new_volume = 1 - _volume ; 
			
			_content = new_volume == 1 ? "关闭声音" : "开启声音";
			
			document.getElementById("audio_notice").volume =  new_volume;
			
			$(".control_volume").html(_content);
		}) 
	</script>

	</if>
	
	
</body>
</html>




