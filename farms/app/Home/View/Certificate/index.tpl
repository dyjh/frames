<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
</head>
<style type="text/css">
*{
	padding: 0;
	margin: 0;
}
.a{
	min-height: 100%;
	width: 100%;
	margin-bottom:60px; 
}
.a img{
	height: 100%;
	width: 100%;
}
.get{
	position: fixed;
	left: 0;
	bottom: 0;
	border: 1px #3083ff solid;
    border-radius: 4px;
    background-color: #3487ff;
    box-shadow: 0 5px 8px 0 rgba(24,95,255,.1);
    color: #fff;
    text-align: center;
    font-weight: lighter;
    background-image: linear-gradient(0deg,#398bff,#3083ff);
    width: 100%;
    height: 60px;
    font-size: 24px;
    line-height: 60px;
}

a{
   text-decoration: none;
}
</style>
<body>
<div class="a">
	<!--
    <if condition="$type eq 1"><img src="__PUBLIC__/Home/images/login/permit.png">
	<elseif condition="$type eq 2"/><img src="__PUBLIC__/Home/images/login/business.png">
	<elseif condition="$type eq 3"/><img src="__PUBLIC__/Home/images/login/culture.png">
	<else /><img src="__PUBLIC__/Home/images/login/copyright.png"> 
	</if>
	-->
	<if condition="$type eq 1"><img src="__PUBLIC__/Home/images/login/permit.png">
	<elseif condition="$type eq 2"/><img src="__PUBLIC__/Home/images/login/business.png">
	<elseif condition="$type eq 3"/><img src="__PUBLIC__/Home/images/login/culture.png">
	<elseif condition="$type eq 4"/><img src="__PUBLIC__/Home/images/login/copyright.png">

	<elseif condition="$type eq 5"/><img src="__PUBLIC__/Home/images/login/silver_big.png">
	<else /><img src="__PUBLIC__/Home/images/login/sincerity_big.png">
	</if>
	<a href="{:U('login/index')}" class="get">返回</a>
</div>
</body>
<script type="text/javascript">
     


</script>
</html>
