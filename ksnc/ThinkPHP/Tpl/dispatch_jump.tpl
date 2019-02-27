
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>撸游</title>

	<link href="__ROOT__/Public/Home/FILE/content/css/style.css"  rel="stylesheet" />

	<!--[if lt IE 9]>
	<script src="__ROOT__/Public/Home/FILE/content/js/main.js" ></script>
	<style>
		.header { background:url("__ROOT__/Public/Home/FILE/content/images/transparent.10.png") !important; }
		.sticky { background:#FFF!important; }
		input[type=text],input[type=password] { line-height:40px; }
		.uc-home .face .cov { display:none; }
	</style>
	<![endif]-->

	<!-- Bootstrap core CSS     -->
	<link href="__ROOT__/Public/Home/assets/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>


<div class="header sticky">
	<div class="container clear">
		<a href="/ksnc/"  class="logo"></a>
	</div>
</div>


<link href="__ROOT__/Public/Home/FILE/content/css/style-login.css" rel="stylesheet" />
<link href="__ROOT__/Public/Home/FILE/content/css/popup-box.css" rel="stylesheet" />
<div class="height_120"></div>
<div class="w3layouts">
	<!-- Sign in -->
	<div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1" >
		<div class="signin-agile" style="margin-bottom: 10px;">

			<CENTER>
				<?php if(isset($message)) {?>
				<h2>SUCCESS</h2>
				<p class="success"><?php echo($message); ?></p>
				<?php }else{?>
				<h2>ERROR</h2>
				<p class="error"><?php echo($error); ?></p>
				<?php }?>
			</CENTER>
			<label class="bar-w3-agile"></label>
			<b style="display: none" id="wait"><?php echo($waitSecond); ?></b>
			<div class="clear"></div>
			<input type="submit" value="跳转" onclick="location='<?php echo($jumpUrl); ?>'">
			<a href="<?php echo($jumpUrl); ?>" style="display: none;"  id="href" class="logo"></a>
		</div>
	</div>
	<!-- //Sign in -->
	<div class="clear"></div>
</div>

<script type="text/javascript">
	(function(){
		var wait = document.getElementById('wait'),href = document.getElementById('href').href;
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
	})();
</script>

</body>
</html>