<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }

?>

<?php if(isset($e['file'])) {?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>系统发生错误</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
html{ overflow-y: scroll; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
img{ border: 0; }
.error{ padding: 24px 48px; }
.face{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
h1{ font-size: 32px; line-height: 48px; }
.error .content{ padding-top: 10px}
.error .info{ margin-bottom: 12px; }
.error .info .title{ margin-bottom: 3px; }
.error .info .title h3{ color: #000; font-weight: 700; font-size: 16px; }
.error .info .text{ line-height: 24px; }
.copyright{ padding: 12px 48px; color: #999; }
.copyright a{ color: #000; text-decoration: none; }
</style>
</head>
<body>
<div class="error">
	<p class="face">:(</p>
	<h1><?php echo strip_tags($e['message']);?></h1>
	<div class="content">
		<div class="info">
			<div class="title">
				<h2>错误位置</h2>
			</div>
			<div class="content">
				<p>FILE: <?php echo $e['file'] ;?> &#12288;LINE: <?php echo $e['line'];?></p>
			</div>
		</div>
		<?php if(isset($e['trace'])) {?>
		<div class="info">
		<div class="title">
		<h3>TRACE</h3>
		</div>
		<div class="text">
		<p><?php echo nl2br($e['trace']);?></p>
		</div>
		</div>
		<?php }?>
		<?php }else{	?>
		<!doctype html>
		<html lang="en">
		<head>
		<meta charset="utf-8" />
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>撸游</title>

		<link href="./Public/Home/FILE/content/css/style.css"  rel="stylesheet" />

		<!--[if lt IE 9]>
		<script src="./Public/Home/FILE/content/js/main.js" ></script>
		<style>
		.header { background:url("./Public/Home/FILE/content/images/transparent.10.png") !important; }
		.sticky { background:#FFF!important; }
		input[type=text],input[type=password] { line-height:40px; }
		.uc-home .face .cov { display:none; }
		</style>
		<![endif]-->

		<!-- Bootstrap core CSS     -->
		<link href="./Public/Home/assets/css/bootstrap.min.css" rel="stylesheet" />

		</head>

		<body>


		<div class="header sticky">
			<div class="container clear">
				<a href="{:U('Index/index')}"  class="logo"></a>
			</div>
		</div>

		<link href="./Public/Home/FILE/content/css/style-login.css" rel="stylesheet" />
		<link href="./Public/Home/FILE/content/css/popup-box.css" rel="stylesheet" />
		<div class="height_120"></div>
		<div class="w3layouts">
			<!-- Sign in -->
			<div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1" >
				<div class="signin-agile" style="margin-bottom: 10px;">




					<CENTER>
						<h2>404</h2>
						<p class="error">你访问的页面好像不对。</p>
					</CENTER>
					<label class="bar-w3-agile"></label>
					<div class="clear"></div>
					<input type="submit" value="首页" onclick="location='<?php echo U("Index/index"); ?>'">


				</div>
			</div>
			<!-- //Sign in -->
			<div class="clear"></div>
		</div>
		<?php }?>

		</body>
		</html>