<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
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
	<script>
		URL_PATHINFO_DEPR = "{:C('URL_PATHINFO_DEPR')}";
		_module = "{:PHP_FILE}/";
		_url      = _module + "{:CONTROLLER_NAME}{:C('URL_PATHINFO_DEPR')}";
	</script>
	<js file="__JS__/jquery-3.1.1.min.js"/>
	<js file="__JS__/login.js"/>
	<js file="__JS__/code.js"/>
	<css file="__CSS__/login.css"/>
</head>
<script>
	function IEAndFF(){
		document.getElementsByName("password").onpropertychange = MaskPassword();
	}
	function MaskPassword(){
		var inputPassword = document.getElementById("login_password").value;
		var plainPassword = document.getElementsByName("password")[0].value;
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
		} else{
			plainPassword = inputPassword;
		}
		document.getElementsByName("password")[0].value = plainPassword;
		document.getElementById("login_password").value = encodePassword;
	}
</script>

<body oncopy="alert('对不起，本网页禁止复制！');return false;">
<div class="loading"><div class="loading_title"></div></div>

<div class="kj" id="kj">
	<div class="kj_box">
		<div class="box">

		</div>
	</div>
</div>

</body>
</html>