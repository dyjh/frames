<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="shortcut icon" href="/Public/Home/images/apple-icon.png"/>
<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="format-detection" content="telephone=yes"/>
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


<script type="text/javascript" src="/Public/Home/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="/Public/Home/js/md5.js"></script>



<script src="/Public/Home/js/login.js?v=<?php echo rand(1000,9000);?>"></script>
<script src="/Public/Home/js/code.js?v=<?php echo rand(1000,9000);?>"></script>



<link rel="stylesheet" type="text/css" href="/Public/Home/css/login.css?v=<?php echo rand(1000,9999);?>"/>







<!--<script src="http://www.lyogame.cn/farms/Public/Home/js/jquery-3.1.1.min.js"></script>
<script src="http://www.lyogame.cn/farms/Public/Home/js/login.js"></script>
<script src="http://www.lyogame.cn/farms/Public/Home/js/code.js"></script>
<script src="http://www.lyogame.cn/farms/Public/Home/js/ceshi.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.lyogame.cn/farms/Public/Home/css/login.css">-->
</head>
<script>

	  var verify_url = "<?php echo U('Login/yzm');?>";
	 function IEAndFF(){
		 document.getElementsByName("password").onpropertychange = MaskPassword();
	 }
	 
	 function MaskPassword(){
		 var inputPassword = document.getElementById("login_password").value;		 
		 var plainPassword = document.getElementsByName("password")[0].value;

		 
	     //plainPassword = (plainPassword.length == inputPassword.length-1) ? plainPassword : "";
		  
		 //console.log(plainPassword)
		 //var plainPassword = document.getElementsByName("password")[0].value;
		 //plainPassword = inputPassword;
		 var encodePassword = '';
		 
		 if (inputPassword != '') {
			 for (var i=0; i<inputPassword.length; i++) {
				 if (inputPassword.charAt(i) == "\u25CF"){ // has been encode
					 encodePassword += inputPassword.charAt(i);
				 } else{
					 plainPassword += document.getElementById("login_password").value.charAt(i)
					 encodePassword += "\u25CF";
				 }
			 }
		 } 
		 else{
			 plainPassword = inputPassword;
		 }
		 
		 document.getElementsByName("password")[0].value = plainPassword;
		 document.getElementById("login_password").value = encodePassword;
	 }
	  
</script>
<!--<body oncontextmenu=return(false)> -->
<body>
<div class="kj" id="kj">
	<div class="kj_box">
		<div class="box">
			<div class="box_hide"></div>
			<div class="loading"><div class="loading_title"></div></div>	
			<!--登录界面-->		
			<div class="sign_in">
				<div class="login_alert"><span></span></div>
				<form id="login_from" action="" method="post">
					<div class="login_box">
						<div class="accounts">
							<div class="accounts_user"></div>
							<div class="accounts_pass">
								<input type="text" name="user" id="login_username" value="<?php echo ($cook_user); ?>" AutoComplete="off">
							</div>
						</div>
						<div class="password_box">
							<div class="password_user"></div>
							<div class="password_pass">
							   <!--新加-->
							   <?php if($cook_pass != ''): ?><input id="login_password" type="password" value="凯撒农场用户"  autocomplete="new-password"/>
								  <?php else: ?>
								    <input id="login_password" type="password" value="" autocomplete="new-password"/><?php endif; ?>	   						
							</div>
							<input type="hidden" name="password"  value="<?php echo ($cook_pass); ?>" />
						</div>
						<div class="account_box">
							<div class="account" onclick="record('account')">
							    <?php if($cook_user != ''): ?><img style="display:block" src="/Public/Home/images/login/xz.png">
									<input type="hidden" id="record_account" value="1"/>
								<?php else: ?>
							        <img src="/Public/Home/images/login/xz.png">
									<input type="hidden" id="record_account" value="0"/><?php endif; ?> 
							</div>
							<div class="password" onclick="record('password')">
							    <?php if($cook_pass != ''): ?><img style="display:block" src="/Public/Home/images/login/xz.png">
									<input type="hidden" id="record_password" value="1"/>
								<?php else: ?>
								    <img src="/Public/Home/images/login/xz.png">
									<input type="hidden" id="record_password" value="0"/><?php endif; ?>
							</div>
						</div>
						<input type="hidden" id="token" value="<?php echo ($token); ?>"/>
						<div class="login_footer">
							<div class="lg_box">
								<div class="et_nber">
									<?php echo ($number['first_number']); ?> + <?php echo ($number['secord_number']); ?> = &nbsp;?
								</div>
								<div class="enter_box">
									<?php $__FOR_START_1295393896__=0;$__FOR_END_1295393896__=3;for($i=$__FOR_START_1295393896__;$i < $__FOR_END_1295393896__;$i+=1){ if($confuse_number[$i] == $_SESSION['login_number']): ?><div class="enter_game"><?php echo ($confuse_number[$i]); ?></div>
											  <?php else: ?><div class="et_error"><?php echo ($confuse_number[$i]); ?></div><?php endif; } ?>
								</div>
							</div>
							<div class="et_errors">点击正确答案进入游戏</div>
							<div class="loading_img"></div>
						</div>
						<div class="forget_box">	
							<div class="forget"></div>
							<div class="register"></div>
						</div>	
					</div>
				</form>
			 </div>
			<!--登录界面结束-->	
		        <!--忘记密码界面-->
			    <div class="forgot_password">
				   <form id="forget_form" action="" method="post">
						<div class="input_list input_background1" style="margin-top: 40%">
							   <input type="text" name="tel" id="phone_number" placeholder="输入手机号码" AutoComplete="off">
						</div>
						<div class="input_list input_background2">
								 <input type="text" name="find_code" id="message_code" placeholder="输入验证码" AutoComplete="off">
								 <input type="button" id="phone_code" value="获取">
						</div>
						<div class="input_list" style="height:11%">
								 <div class="footer_box" style="margin-left: 5%" id="return_login"></div>
								 <div class="footer_box" id="next_step"></div>
						</div>
					</form>
				</div>
			
			<!--重置密码-->
			<div class="reset_password">
				<form id="reset_form" action="" method="post">
					<div class="validation_hints_rs"><span></span></div>
					<div class="validation_hints_rh"><span></span></div>
					<div class="alert_success">
						<div class="success_rompt">
							<span></span>
						</div>
						<div class="success_rompt" id="success">
							   <img src="/Public/Home/images/login/fanghui.png">
						</div>
						 </div>
						 <div class="new_box new_password" style="margin-top: 40%">
								<input type="text" id="new_password" placeholder="输入密码(至少六位)" onfocus="this.type='password'" autocomplete="off" AutoComplete="off" maxlength="18">
						 </div>
						 <div class="new_box repeat_password">
								 <input type="text" id="repeat_password" placeholder="再次输入密码" onfocus="this.type='password'" autocomplete="off" AutoComplete="off" maxlength="18">
						 </div>
						  <input type="hidden" id="forget_password"  name="password"  >
						 <div class="new_box" style="margin-top: -2%; margin-left: 33%;">
								<div class="submit_backgr submit" style="margin-left:-23%"></div>
								<div class="submit" id="submit"></div>
						</div>
					</form>
		    	</div>
				<!--重置密码结束-->				
			<!--注册-->
			<div class="register_interface">
				<form id="form" action="" method="post">
					<div class="register_box">
						<div class="mobile_account">
							<div class="prompt_list prompt_list1"></div>
							<div class="prompt_input">
								<input type="text" name="user" placeholder="输入手机号码"  id="mobile_account" AutoComplete="off">
							</div>
						</div>
						<div class="full_name">
							<div class="prompt_list prompt_list2"></div>
							<div class="prompt_input">
								<input type="text" name="name" id="full_name" placeholder="输入真实姓名" AutoComplete="off">
							</div>
						</div>
						<div class="id_card">
							<div class="prompt_list prompt_list3"></div>
							<div class="prompt_input">
								<input type="text" name="id_card" id="id_card" placeholder="输入身份证号码" AutoComplete="off">
							</div>
						</div>
						<div class="input_password">
							<div class="prompt_list prompt_list4"></div>
							<div class="prompt_input">
							    <input type="text" name="password" id="input_password" placeholder="输入至少六位密码" onfocus="this.type='password'" autocomplete="off" AutoComplete="off" maxlength="18">
							</div>
						</div>
						<div class="confirm">
							<div class="prompt_list prompt_list5"></div>
							<div class="prompt_input">
							    <input type="text" id="confirm_password" placeholder='确认输入密码' onfocus="this.type='password'" autocomplete="off" AutoComplete="off" maxlength="18">
							</div>   
						</div>
						<div class="recommended_code">
							<div class="prompt_list prompt_list6"></div>
							<div class="prompt_input">
								<input type="text" name="referees" id='referees_referees' placeholder="推荐人手机号" AutoComplete="off">
							</div>
						</div>
						<div class="verification_code">
							<div class="prompt_list prompt_list7"></div>
							<div class="prompt_input prompt_input2">
						        <div class="verification_img"><img id="img_code" width="100%" height="90%" src="<?php echo U('Login/yzm');?>" onclick="this.src='<?php echo U("Login/yzm")?>?r='+Math.random()"/></div>
						        <input type="text" name="ver_code" id="verification_code" placeholder="图片验证码" AutoComplete="off">
						    </div>
						</div>
						<div class="message">
							<div class="prompt_list prompt_list8"></div>
							<div class="prompt_input prompt_input3">
								<div class="request">
									<input type="button" id="btn" value="获取">
								</div>
								<div class="request_input">
									<input type="text" name="sms_code" placeholder="短信验证码" AutoComplete="off">
								</div>
							</div>	
						</div>
						<div class="return_box">
							<div class="_return"></div>
							<div class="register_account"></div>
						</div>
						<div class="validation_hints"><span></span></div>
					</div>	
				<form>
			</div>
				 <!--用户协议-->
			<div class="agreement_show">
				<div class="container xieyi_container">
					<div class="panel panel-default statement-content-cover">
						<div class="main-statement-body">
							<div>
								<p style="text-indent: 2rem;"><b>为使用撸游科技的服务，您应当阅读并遵守《用户协议》（以下简称“本协议”）。请您务必审慎阅读、充分理解各条款内容，特别是免除或者限制责任的条款、管辖与法律适用条款，以及开通或使用某项服务的单独协议。限制、免责条款可能以黑体加粗或加下划线的形式提示您重点注意。除非您已阅读并接受本协议所有条款，否则您无权使用撸游科技提供的服务。您使用撸游科技的服务即视为您已阅读并同意上述协议的约束。</b></p>
								<p style="text-indent: 2rem"><b>如果您未满18周岁，请在法定监护人的陪同下阅读本协议，并特别注意未成年人使用条款。</b></p>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">一、协议的范围</p>
								<p>1.1 本协议是您与撸游科技之间关于用户使用撸游科技相关服务所订立的协议。“撸游科技”是指撸游科技有限公司及其相关服务可能存在的运营关联单位。“用户”是指使用撸游科技相关服务的使用人，在本协议中更多地称为“您”。</p><br>
								<p>1.2 本协议项下的服务是指撸游科技现在正在提供和将来可能向您提供的游戏服务和其他网络服务（以下简称“本服务”）。</p>
								<br>
								<p>1.3 <b>本协议内容同时包括《隐私政策》 </b>,且您在使用撸游科技某一特定服务时，该服务可能会另有单独的协议、相关业务规则等（以下统称为“单独协议”）。上述内容一经正式发布，即为本协议不可分割的组成部分，您同样应当遵守。您对前述任何业务规则、单独协议的接受，即视为您对本协议全部的接受。</p>
								<br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">二、账号与密码安全</p>
								<p>2.1 撸游科技账号注册资料包括但不限于您的撸游科技账号名称、头像、密码、注册或更新撸游科技账号时输入的所有信息以及您使用撸游科技各单项服务时输入的名称、头像等所有信息。</p><br>
								<p>2.2 您在注册撸游科技账号时承诺遵守法律法规、社会主义制度、国家利益、公民合法权益、公共秩序、社会道德风尚和信息真实性等七条底线，不得在撸游科技账号注册资料中出现违法和不良信息，且您保证其在注册和使用账号时，不得有以下情形：</p><br>
								<p style="text-indent: 2rem">（1）违反宪法或法律法规规定的；</p>
								<p style="text-indent: 2rem">（2）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p>
								<p style="text-indent: 2rem">（3）损害国家荣誉和利益的，损害公共利益的；</p>
								<p style="text-indent: 2rem">（4）煽动民族仇恨、民族歧视，破坏民族团结的；</p>
								<p style="text-indent: 2rem">（5）破坏国家宗教政策，宣扬邪教和封建迷信的；</p>
								<p style="text-indent: 2rem">（6）散布谣言，扰乱社会秩序，破坏社会稳定的；</p>
								<p style="text-indent: 2rem">（7）散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</p>
								<p style="text-indent: 2rem">（8）侮辱或者诽谤他人，侵害他人合法权益的；</p>
								<p style="text-indent: 2rem">（9）含有法律、行政法规禁止的其他内容的。</p>
								<p>2.3 若您提供给撸游科技的账号注册资料不准确，不真实，含有违法或不良信息的，撸游科技有权不予注册，并保留终止您使用撸游科技各项服务的权利。若您以虚假信息骗取账号注册或账号头像、个人简介等注册资料存在违法和不良信息的，撸游科技有权采取通知限期改正、暂停使用、注销登记等措施。对于冒用关联机构或社会名人注册账号名称的，撸游科技有权注销该账号，并向政府主管部门进行报告。</p><br>
								<p>2.4 根据相关法律、法规规定以及考虑到撸游科技产品服务的重要性，您理解并同意：</p>
								<p style="text-indent: 2rem">（1）在注册撸游科技账号时提交个人有效身份信息进行实名认证；</p>
								<p style="text-indent: 2rem">（2）提供及时、详尽及准确的账号注册资料；</p>
								<p style="text-indent: 2rem">（3）不断更新注册资料，符合及时、详尽准确的要求，对注册撸游科技账号时填写的身份证件信息不能更新。</p><br>
								<p>2.5 您理解并同意，您提供的真实、准确、合法的撸游科技账号注册资料是作为认定您与您撸游科技账号的关联性以及用户身份的唯一证据。您在享用撸游科技各项服务的同时，理解并同意接受撸游科技提供的各类信息服务。<b>撸游科技提醒您，您注册撸游科技账号或更新注册信息时填写的证件号码，在注册撸游科技账号成功或补充填写后将无法进行修改，请您慎重填写各类注册信息。</b></p><br>
								<p>2.6 为使您及时、全面了解撸游科技提供的各项服务，<b>您理解并同意，撸游科技可以多次、长期向您发送各类商业性短信息而无需另行获得您的同意。</b></p><br>
								<p>2.7 <b>您理解并同意与注册、使用撸游科技账号相关的一切资料、数据和记录，包括但不限于撸游科技账号、注册资料、所有登录、消费记录和相关的使用统计数字等归撸游科技所有。发生争议时，您同意以撸游科技的系统数据为准，撸游科技保证该数据的真实性。</b></p><br>
								<p>2.8 <b>尽管有前述约定，对于您使用撸游科技账号享受撸游科技旗下单项服务时产生的一切数据，包括但不限于产品登录记录、消费记录及其他产品日志、产品客户服务记录、您在产品中创造的社会网络内容等，归具体产品运营主体所有。发生争议时，您同意以具体产品运营主体的系统数据为准。但是如果单项条款存在与前述不同的约定，则以单项条款约定为准。</b></p><br>
								<p>2.9 <b>撸游科技特别提醒您应妥善保管您的账号和密码。当您使用完毕后，应安全退出。因您保管不善可能导致遭受盗号或密码失窃，责任由您自行承担。</b></p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">三、用户个人信息保护</p>
								<p>3.1 <b>保护用户个人信息是撸游科技的一项基本原则。撸游科技将按照本协议及《隐私政策》的规定收集、使用、储存和分享您的个人信息。本协议对个人信息保护规定的内容与上述《隐私政策》有相冲突的，及本协议对个人信息保护相关内容未作明确规定的，均应以《隐私政策》的内容为准。</b></p><br>
								<p>3.2 您在注册账号或使用本服务的过程中，可能需要填写一些必要的信息。若国家法律法规有特殊规定的，您需要填写真实的身份信息。若您填写的信息不完整，则无法使用本服务或在使用过程中受到限制。</p><br>
								<p>3.3 一般情况下，您可随时浏览、修改自己提交的信息，但出于安全性和身份识别（如号码申诉服务）的考虑，您可能无法修改注册时提供的初始注册信息及其他验证信息。<br>
								<p>3.4 撸游科技将运用各种安全技术和程序建立完善的管理制度来保护您的个人信息，以免遭受未经授权的访问、使用或披露。</p>
								<br>
								<p>3.5 <b>撸游科技不会将您的个人信息转移或披露给任何非关联的第三方，除非：</b></p>
								<br>
								<p style="text-indent: 2rem"><b>（1）相关法律法规或法院、政府机关要求；</b></p>
								<p style="text-indent: 2rem"><b>（2）为完成合并、分立、收购或资产转让而转移；或</b></p>
								<p style="text-indent: 2rem"><b>（3）为提供您要求的服务所必需。</b></p>
								<p>3.6 撸游科技非常重视对未成年人个人信息的保护。若您是18周岁以下的未成年人，在使用撸游科技的服务前，应事先取得您家长或法定监护人（以下简称"监护人"）的书面同意。</p>
								<br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">四、使用本服务的方式</p>
								<p>4.1 除非您与撸游科技另有约定，您同意本服务仅为您个人非商业性质的使用。</p><br>
								<p>4.2 您应当通过撸游科技提供或认可的方式使用本服务。您依本协议条款所取得的权利不可转让。</p><br>
								<p>4.3 您不得使用未经撸游科技授权的插件、外挂或第三方工具对本协议项下的服务进行干扰、破坏、修改或施加其他影响。</p>
								<br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">五、按现状提供服务</p>
								<p>您理解并同意，撸游科技的服务是按照现有技术和条件所能达到的现状提供的。撸游科技会尽最大努力向您提供服务，确保服务的连贯性和安全性；<b>但撸游科技不能随时预见和防范法律、技术以及其他风险，包括但不限于不可抗力、病毒、木马、黑客攻击、系统不稳定、第三方服务瑕疵、政府行为等原因可能导致的服务中断、数据丢失以及其他的损失和风险。</b></p>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">六、自备设备</p>
								<p>6.1 <b>您应当理解，您使用撸游科技的服务需自行准备与相关服务有关的终端设备（如电脑、调制解调器等装置），并承担所需的费用（如电话费、上网费等费用）。</b></p><br>
								<p>6.2 <b>您理解并同意，您使用本服务时会耗用您的终端设备和带宽等资源。</b></p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">七、广告</p>
								<p>7.1 您同意撸游科技可以在提供服务的过程中自行或由第三方广告商向您发送广告、推广或宣传信息（包括商业与非商业信息），其方式和范围可不经向您特别通知而变更。</p><br>
								<p>7.2 撸游科技可能为您提供选择关闭广告信息的功能，但任何时候您都不得以本协议未明确约定或撸游科技未书面许可的方式屏蔽、过滤广告信息。</p><br>
								<p>7.3 撸游科技依照法律的规定对广告商履行相关义务，您应当自行判断广告信息的真实性并为自己的判断行为负责，<b>除法律明确规定外，您因依该广告信息进行的交易或前述广告商提供的内容而遭受的损失或损害，撸游科技不承担责任。</b></p>
								<p><br>7.4 您同意，对撸游科技服务中出现的广告信息，您应审慎判断其真实性和可靠性，除法律明确规定外，您应对依该广告信息进行的交易负责。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">八、收费服务</p>
								<p>8.1 撸游科技的部分服务是以收费方式提供的，如您使用收费服务，请遵守相关的协议。</p><br>
								<p>8.2 撸游科技可能根据实际需要对收费服务的收费标准、方式进行修改和变更，撸游科技也可能会对部分免费服务开始收费。前述修改、变更或开始收费前，撸游科技将在相应服务页面进行通知或公告。如果您不同意上述修改、变更或付费内容，则应停止使用该服务。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">九、第三方提供的产品或服务</p>
								<p><b>您在撸游科技平台上使用第三方提供的产品或服务时，除遵守本协议约定外，还应遵守第三方的用户协议。撸游科技和第三方对可能出现的纠纷在法律规定和约定的范围内各自承担责任。</b></p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十、基于软件提供服务</p>
								<p>若撸游科技依托“软件”向您提供服务，您还应遵守以下约定：</p>
								<p>10.1 您在使用本服务的过程中可能需要下载软件，对于这些软件，撸游科技给予您一项个人的、不可转让及非排他性的许可。您仅可为访问或使用本服务的目的而使用这些软件。</p><br>
								<p>10.2 为了改善用户体验、保证服务的安全性及产品功能的一致性，撸游科技可能会对软件进行更新。您应该将相关软件更新到最新版本，否则撸游科技并不保证其能正常使用。</p><br>
								<p>10.3 撸游科技可能为不同的终端设备开发不同的软件版本，您应当根据实际情况选择下载合适的版本进行安装。您可以直接从撸游科技的网站上获取软件，也可以从得到撸游科技授权的第三方获取。如果您从未经撸游科技授权的第三方获取软件或与软件名称相同的安装程序，撸游科技无法保证该软件能够正常使用，并对因此给您造成的损失不予负责。</p><br>
								<p>10.4 除非撸游科技书面许可，您不得从事下列任一行为： </p><br>
								<p style="text-indent: 2rem">（1）删除软件及其副本上关于著作权的信息； </p>
								<p style="text-indent: 2rem">（2）对软件进行反向工程、反向汇编、反向编译，或者以其他方式尝试发现软件的源代码；</p>
								<p style="text-indent: 2rem">（3）对撸游科技拥有知识产权的内容进行使用、出租、出借、复制、修改、链接、转载、汇编、发表、出版、建立镜像站点等； </p>
								<p style="text-indent: 2rem">（4）对软件或者软件运行过程中释放到任何终端内存中的数据、软件运行过程中客户端与服务器端的交互数据，以及软件运行所必需的系统数据，进行复制、修改、增加、删除、挂接运行或创作任何衍生作品，形式包括但不限于使用插件、外挂或非经撸游科技授权的第三方工具/服务接入软件和相关系统； </p>
								<p style="text-indent: 2rem">（5）通过修改或伪造软件运行中的指令、数据，增加、删减、变动软件的功能或运行效果，或者将用于上述用途的软件、方法进行运营或向公众传播，无论这些行为是否为商业目的； </p>
								<p style="text-indent: 2rem">（6）通过非撸游科技开发、授权的第三方软件、插件、外挂、系统，登录或使用撸游科技软件及服务，或制作、发布、传播非撸游科技开发、授权的第三方软件、插件、外挂、系统。</p>
							</div>

							<br>
						   <div class="block-sub">
								<p class="text-lead">十一、知识产权声明</p>
								<p>11.1 撸游科技在本服务中提供的内容（包括但不限于网页、文字、图片、音频、视频、图表等）的知识产权归撸游科技所有，用户在使用本服务中所产生的内容的知识产权归用户或相关权利人所有。</p><br>
								<p>11.2 除另有特别声明外，撸游科技提供本服务时所依托软件的著作权、专利权及其他知识产权均归撸游科技所有。</p><br><br>
								<p>11.3 撸游科技在本服务中所使用的“撸游科技”及心形标识等商业标识，其著作权或商标权归撸游科技所有。</p><br>
								<p>11.4 上述及其他任何本服务包含的内容的知识产权均受到法律保护，未经撸游科技、用户或相关权利人书面许可，任何人不得以任何形式进行使用或创造相关衍生作品。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十二、用户违法行为</p>
								<p>12.1 您在使用本服务时须遵守法律法规，不得利用本服务从事违法违规行为，包括但不限于：</p><br>
								<p style="text-indent: 2rem">（1）发布、传送、传播、储存危害国家安全统一、破坏社会稳定、违反公序良俗、侮辱、诽谤、淫秽、暴力以及任何违反国家法律法规的内容；</p><br>
								<p style="text-indent: 2rem">（2）发布、传送、传播、储存侵害他人知识产权、商业秘密等合法权利的内容；</p>
								<p style="text-indent: 2rem">（3）恶意虚构事实、隐瞒真相以误导、欺骗他人；</p>
								<p style="text-indent: 2rem">（4）发布、传送、传播广告信息及垃圾信息；</p>
								<p style="text-indent: 2rem">（5）其他法律法规禁止的行为。</p>
								<p>12.2 如果您违反了本条约定，相关国家机关或机构可能会对您提起诉讼、罚款或采取其他制裁措施，并要求撸游科技给予协助。<b>造成损害的，您应依法予以赔偿，撸游科技不承担任何责任。</b></p><br>
								<p>12.3 如果撸游科技发现或收到他人举报您发布的信息违反本条约定，撸游科技有权进行独立判断并采取技术手段予以删除、屏蔽或断开链接。同时，撸游科技有权视用户的行为性质，采取包括但不限于暂停或终止服务，限制、冻结或终止撸游科技账号使用，追究法律责任等措施。</p><br>
								<p>12.4 <b>您违反本条约定，导致任何第三方损害的，您应当独立承担责任；撸游科技因此遭受损失的，您也应当一并赔偿。</b></p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十三、遵守当地法律监管</p>
								<p>13.1 您在使用本服务过程中应当遵守当地相关的法律法规，并尊重当地的道德和风俗习惯。<b><br>如果您的行为违反了当地法律法规或道德风俗，您应当为此独立承担责任。</b></p>
								<p>13.2 您应避免因使用本服务而使撸游科技卷入政治和公共事件，否则撸游科技有权暂停或终止对您的服务。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十四、用户发送、传播的内容与第三方投诉处理</p>
								<p>14.1 <b>您通过本服务发送或传播的内容（包括但不限于网页、文字、图片、音频、视频、图表等）均由您自行承担责任。</b></p><br>
								<p>14.2 <b>您发送或传播的内容应有合法来源，相关内容为您所有或您已获得权利人的授权。</b></p><br>
								<p>14.3 <b>您同意撸游科技可为履行本协议或提供本服务的目的而使用您发送或传播的内容。</b></p><br>
								<p>14.4 <b>如果撸游科技收到权利人通知，主张您发送或传播的内容侵犯其相关权利的，您同意撸游科技有权进行独立判断并采取删除、屏蔽或断开链接等措施。 </b></p><br>
								<p>14.5 <b>您使用本服务时不得违反国家法律法规、侵害他人合法权益。您理解并同意，如您被他人投诉侵权或您投诉他人侵权，撸游科技有权将争议中相关方的主体、联系方式、投诉相关内容等必要信息提供给其他争议方或相关部门，以便及时解决投诉纠纷，保护他人合法权益。</b></p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十五、不可抗力及其他免责事由</p>
								<p>15.1 <b>您理解并同意，在使用本服务的过程中，可能会遇到不可抗力等风险因素，使本服务发生中断。不可抗力是指不能预见、不能克服并不能避免且对一方或双方造成重大影响的客观事件，包括但不限于自然灾害如洪水、地震、瘟疫流行和风暴等以及社会事件如战争、动乱、政府行为等。出现上述情况时，撸游科技将努力在第一时间与相关单位配合，及时进行修复，但是由此给您造成的损失撸游科技在法律允许的范围内免责。</b></p><br>
								<p>15.2 <b>在法律允许的范围内，撸游科技对以下情形导致的服务中断或受阻不承担责任：</b></p><br>
								<p style="text-indent: 2rem"><b>（1）受到计算机病毒、木马或其他恶意程序、黑客攻击的破坏；</b></p>
								<p style="text-indent: 2rem"><b>（2）用户或撸游科技的电脑软件、系统、硬件和通信线路出现故障；</b></p>
								<p style="text-indent: 2rem"><b>（3）用户操作不当；</b></p>
								<p style="text-indent: 2rem"><b>（4）用户通过非撸游科技授权的方式使用本服务；</b></p>
								<p style="text-indent: 2rem"><b>（5）其他撸游科技无法控制或合理预见的情形</b>。</p><br>
								<p>15.3 <b>您理解并同意，在使用本服务的过程中，可能会遇到网络信息或其他用户行为带来的风险，撸游科技不对任何信息的真实性、适用性、合法性承担责任，也不对因侵权行为给您造成的损害负责。这些风险包括但不限于：</b></p>
								<b>（1）来自他人匿名或冒名的含有威胁、诽谤、令人反感或非法内容的信息；</b>
								<b>（2）因使用本协议项下的服务，遭受他人误导、欺骗或其他导致或可能导致的任何心理、生理上的伤害以及经济上的损失；</b>
								<b>（3）其他因网络信息或用户行为引起的风险。</b>
								<p>15.4 <b>您理解并同意，本服务并非为某些特定目的而设计，包括但不限于核设施、军事用途、医疗设施、交通通讯等重要领域。如果因为软件或服务的原因导致上述操作失败而带来的人员伤亡、财产损失和环境破坏等，撸游科技不承担法律责任。</b></p><br>
								<p>15.5 <b>撸游科技依据本协议约定获得处理违法违规内容的权利，该权利不构成撸游科技的义务或承诺，撸游科技不能保证及时发现违法行为或进行相应处理。</b></p><br>
								<p>15.6 <b>在任何情况下，您不应轻信借款、索要密码或其他涉及财产的网络信息。涉及财产操作的，请一定先核实对方身份，并请经常留意撸游科技有关防范诈骗犯罪的提示。</b></p><br>
							</div>
							<div class="block-sub">
								<p class="text-lead">十六、协议的生效与变更</p>
								<p>16.1 <b>您使用撸游科技的服务即视为您已阅读本协议并接受本协议的约束。</b></p><br>
								<p>16.2 撸游科技有权在必要时修改本协议条款。您可以在相关服务页面查阅最新版本的协议条款。</p><br>
								<p>16.3 本协议条款变更后，如果您继续使用撸游科技提供的软件或服务，即视为您已接受修改后的协议。如果您不接受修改后的协议，应当停止使用撸游科技提供的软件或服务。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十七、服务的变更、中断、终止</p>
								<p>17.1 撸游科技可能会对服务内容进行变更，也可能会中断、中止或终止服务。</p><br>
								<p>17.2 <b>您理解并同意，撸游科技有权自主决定经营策略。在撸游科技发生合并、分立、收购、资产转让时，撸游科技可向第三方转让本服务下相关资产；撸游科技也可在单方通知您后，将本协议下部分或全部服务转交由第三方运营或履行。具体受让主体以撸游科技通知的为准。</b></p><br>
								<p>17.3 <b>如发生下列任何一种情形，撸游科技有权不经通知而中断或终止向您提供的服务：</b></p>
								<p style="text-indent: 2rem"><b><br>（1）根据法律规定您应提交真实信息，而您提供的个人资料不真实、或与注册时信息不一致又未能提供合理证明；</b></p>
								<p style="text-indent: 2rem"><b>（2）您违反相关法律法规或本协议的约定；</b></p>
								<p style="text-indent: 2rem"><b>（3）按照法律规定或主管部门的要求；</b></p>
								<p style="text-indent: 2rem"><b>（4）出于安全的原因或其他必要的情形。</b></p>
								<p>17.4 撸游科技有权按本协议8.2条的约定进行收费。若您未按时足额付费，撸游科技有权中断、中止或终止提供服务。</p><br>
								<p>17.5 您有责任自行备份存储在本服务中的数据。如果您的服务被终止，撸游科技可以从服务器上永久地删除您的数据,但法律法规另有规定的除外。服务终止后，撸游科技没有义务向您返还数据。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十八、管辖与法律适用</p>
								<p>18.1 <b>本协议的成立、生效、履行、解释及纠纷解决，适用中华人民共和国大陆地区法律（不包括冲突法）。</b></p><br>
								<p>18.2 <b>本协议签订地为中华人民共和国上海市闵行区。</b></p><br>
								<p>18.3 <b>若您和撸游科技之间发生任何纠纷或争议，首先应友好协商解决；协商不成的，您同意将纠纷或争议提交本协议签订地（即中国上海市闵行区）有管辖权的人民法院管辖。</b></p><br>
								<p>18.4 本协议所有条款的标题仅为阅读方便，本身并无实际涵义，不能作为本协议涵义解释的依据。</p><br>
								<p>18.5 本协议条款无论因何种原因部分无效或不可执行，其余条款仍有效，对双方具有约束力。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">十九、未成年人使用条款</p>
								<p>19.1 若用户未满18周岁，则为未成年人，应在监护人监护、指导下阅读本协议和使用本服务。</p><br>
								<p>19.2 未成年人用户涉世未深，容易被网络虚象迷惑，且好奇心强，遇事缺乏随机应变的处理能力，很容易被别有用心的人利用而又缺乏自我保护能力。因此，未成年人用户在使用本服务时应注意以下事项，提高安全意识，加强自我保护：</p>
								<p style="text-indent: 2rem">（1）认清网络世界与现实世界的区别，避免沉迷于网络，影响日常的学习生活；</p>
								<p style="text-indent: 2rem">（2）填写个人资料时，加强个人保护意识，以免不良分子对个人生活造成骚扰；</p>
								<p style="text-indent: 2rem">（3）在监护人或老师的指导下，学习正确使用网络；</p>
								<p style="text-indent: 2rem">（4）避免与陌生网友随意会面，以免不法分子有机可乘，危及自身安全。</p>
								<br>
								<p>19.3 监护人、学校均应对未成年人使用本服务时多做引导。特别是家长应关心子女的成长，注意与子女的沟通，指导子女上网应该注意的安全问题，防患于未然。</p><br>
							</div>
							<br>
							<div class="block-sub">
								<p class="text-lead">二十、其他</p>
								<p>如果您对本协议或本服务有意见或建议，可与撸游科技客户服务部门联系，我们会给予您必要的帮助。（正文完）</p><br>
							</div>

							<p style="float: right;"><b>撸游科技</b></p>
						</div>
					</div>
				</div>
				<div class="agreement_sure"></div>
			</div>
			<!--用户协议结束-->
			<!--公司资质图片-->
			<div class="firm_img">
				<div class="firm_box" style="margin-left: 10%">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=1" target="_blank">
						<img src="/Public/Home/images/login/icp_icon.png">
					</a>
				</div>
				<div class="firm_box">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=2" target="_blank">
						<img src="/Public/Home/images/login/gs_icon.png">
					</a>
				</div>
				<div class="firm_box">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=3" target="_blank">
						<img src="/Public/Home/images/login/cultrue_icon.png">
					</a>
				</div>
				<div class="firm_box">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=4" target="_blank">
						<img src="/Public/Home/images/login/copyrights.png">
					</a>
				</div>
				<div class="firm_box" style="margin-left: 21%;width: 23%;">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=5" target="_blank">
						<img src="/farms/Public/Home/images/login/silver.png" style="margin-top: 3.5%;">
					</a>
				</div>
				<div class="firm_box" style="margin-left: 10%;width: 23%;">
					<a href="<?php echo U('Certificate/index');?>?Certificate_type=6" target="_blank">
						<img src="/farms/Public/Home/images/login/sincerity.png" style="margin-top: 3.5%;">
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>