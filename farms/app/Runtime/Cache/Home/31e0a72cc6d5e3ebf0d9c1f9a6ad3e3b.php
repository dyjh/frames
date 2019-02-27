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
<meta naentrust_listme="screen-orientation" content="portrait"/>
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
<link rel="stylesheet" type="text/css" href="/farms/Public/Home/css/market.css" />
<script type="text/javascript" src="/farms/Public/Home/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/highstock.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/Market_Detail.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/exporting.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/utilities.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/wow.min.js"></script>
<script type="text/javascript" src="/farms/Public/Home/js/market.js?r=777865"></script>

<script>
var seed = '<?php echo ($seed); ?>';
</script>
</head>
<script>

	var sale_time_open = <?php echo ($OpenCloseTime["Open"]); ?>;
	var h = new Date().getHours();
	var sale_time_close = <?php echo ($OpenCloseTime["Close"]); ?>;

	if(h>sale_time_open && h < sale_time_close){
		isOpen = true;
	}else{
		isOpen = false;
	}

	$(function(){
		$('body').click(function(){
			refresh(Date.parse(new Date())/1000);
		})
		//网络链接中断返登录界面
		$('.internet_right').click(function(){
//			window.location.href="http://"+window.location.host+"/farms/index.php/Home/Login";
			window.location.href= _module  + "Login";
		})
	})

    var zt = <?php echo ($max_entrust); ?>;
	var dt = <?php echo ($min_entrust); ?>;

</script>
<!--<body oncontextmenu=return(false)>-->
<body>
<div class="kj" id="kj">
	<div class="box_hide"></div>
	<div class="box">
		<!--市价单-->
		<div class="mp_alert">
			<form id="sweeping">
				<input class="mp_count" type="button" value="计算">
				<div class="mp_number"><input type="text" name="num" id="mp_number"></div>
				<div class="mp_price"><input type="text" name="money" id="mp_price"></div>
				<div class="mp_tprice"><input type="text" id="mp_tprice"></div>
				<select name="type">
					<option value="1">买</option>
					<option value="2">卖</option>
				</select>
				<div class="mp_submit"></div>
			</form>	
			<div class="mp_return"></div>
		</div>
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
		<!--交易中心界面开始-->
		<!--K线图界面开始-->
		<div class="alert_box">
			<div class="alert_title"><span>提示</span></div>
			<div class="all_box">
				<div class="all_box_list">
					<span></span>
				</div>
				<div class="all_box_list">
					<div class="box_list_img"></div>
				</div>
			</div>
		</div>
		<!--购买果实确定-->
		<div class="sure_one">
			<div class="sure_title"><span>提示</span></div>
			<div class="sure_box">
				<div class="sure_box_list">
					<span></span>
				</div>
				<div class="sure_box_list">
					<div class="sure_list_img" onclick="goldno(1,2)"></div>
					<div class="sure_list_img sure_hide" onclick="sruehide(1)"></div>
				</div>
			</div>
		</div>
		<!--出售果实确定-->
		<div class="sure_two">
			<div class="sure_title"><span>提示</span></div>
			<div class="sure_box">
				<div class="sure_box_list">
					<span></span>
				</div>
				<div class="sure_box_list">
					<div class="sure_list_img" onclick="goldno(2,2)"></div>
					<div class="sure_list_img sure_hide" onclick="sruehide(2)"></div>
				</div>
			</div>
		</div>
		<div class="purchase_interface">
			<div class="purchase_box">
				<div class="purchase_interface_box">
					<div class="purchase_heade">
						<div class="purchase_fruitvariety">
							<div class="fruitvariety_img">
								<img src="/farms/Public/Home/images/fruit/<?php echo image_name_icover($seed); ?>.png">
							</div>
							<div class="fruitvariety_prompt">
								<span id="fruitvariety_prompt"><?php echo ($today['new']); ?></span>
							</div>
							<div class="fruitvariety_right">
								<div class="fruit_right_list"><span id="zde">涨/跌额：</span>
									<?php if($today['ga'] < 0): ?><span style="color:green" id="gax"><?php echo ($today['ga']); ?></span>
										<?php elseif($today['ga'] == 0): ?><span id="gal"><?php echo ($today['ga']); ?></span>
										<?php else: ?><span style="color:red" id="gad">+<?php echo ($today['ga']); ?></span><?php endif; ?>
								</div>
								<div class="fruit_right_list"><span id="zdf">涨/跌幅：</span>
									<?php if($today['gains'] < 0): ?><span style="color:green" id="ganx"><?php echo (msubstr($today['gains'],0,6)); ?>%</span>
										<?php elseif($today['gains'] == 0): ?><span id="ganl"><?php echo (msubstr($today['gains'],0,6)); ?>%</span>
										<?php else: ?><span style="color:red" id="gand">+<?php echo (msubstr($today['gains'],0,6)); ?>%</span><?php endif; ?>
								</div>
							</div>
						</div>
						<div class="purchase_initialbox">
							<div class="purchase_list">
								<div class="pur_listbox" style="margin-left: 0"><span>今开</span></div>
								<div class="pur_listbox"><span>最高</span></div>
								<div class="pur_listbox"><span>最低</span></div>
								<div class="pur_listbox"><span>成交</span></div>
							</div>
							<div class="purchase_list">
								<div class="pur_listbox" style="margin-left: 0" id="start"><span><?php echo ($today['start']); ?></span></div>
								<div class="pur_listbox" id="max"><span><?php echo ($today['max']); ?></span></div>
								<div class="pur_listbox" id="min"><span><?php echo ($today['min']); ?></span></div>
								<div class="pur_listbox" id="count"><span><?php echo ($today['count']); ?></span></div>
							</div>
							<div class="purchase_list" style="margin-top:4%;">
								<div class="purchase_fs" onclick="Min(seed)"><span>分时</span></div>
								<div class="purchase_rk" onclick="line(seed)"><span>日K</span></div>
							</div>
						</div>
					</div>
					<div class="candlestick_chart">
						<div id="container" style="width:100%;height: 360px;display:none"></div>
						<div id="kline" style="width:100%;height: 360px"></div>
					</div>
					<div class="purchase_interface_box2">
						<div class="purchase_heade2">
							<div class="table">
								<?php if(is_array($seeddata)): foreach($seeddata as $key=>$v): ?><a href="<?php echo U('Market/pay');?>?id=<?php echo ($v['id']); ?>"><div class="fruit_link"><span><?php echo ($v['varieties']); ?></span></div></a><?php endforeach; endif; ?>
							</div>
							<div class="market_return">
								<a href="<?php echo U('Market/index');?>" rel="external"><input type="button" name="" value="返回市场"></a>
							</div>
						</div>
						<div class="fruit_information">
							<br>
							<p><span style="color:#000">产品:</sapn><span id="product"><?php echo ($seed); ?></span>
									<span style="color:#000">现价:</sapn><span id="present_price"><?php echo ($today['new']); ?></span>
									<span style="color:#000">涨停:</sapn><span id="the_price"><?php echo ($max_entrust); ?></span>
									<span style="color:#000">跌停:</sapn><span id="drop_price"><?php echo ($min_entrust); ?></span>
							</p>
						</div>
						<div class="deal">
							<div class="deal_list">
								<div class="deal_list_box">
									<div class="deal_list_left" style="margin-top:10%">
										<!--<select id="sell_buy" style="height:97%">
                                            <option value="买入"><span>买入</span></option>
                                            <option value="委托买入">委托买入</option>
                                        </select>-->
										<b>买入</b>						
									</div>
								</div>
								<div class="deal_list_box">
									<div class="deal_list_right"><span onclick="refresh_price('buy')">刷新实时价格</span></div>
								</div>
							</div>
							<div class="deal_form">
								<form>
									<div class="deal_input" style="margin-top: 0">
										<div class="form_left"><span>买入价格</span></div>
										<div class="form_center"><input type="text" name="" value="<?php echo ($today['new']); ?>" class="b_1" id="b_1" onclick="shift('buy')" onkeyup="clearNoNum(this)"></div>
										<div class="form_right"><span>金币</span></div>
									</div>
									<div class="deal_input" onclick="buy_count()">
										<div class="form_left"><span>买入数量</span></div>
										<div class="form_center"><input type="text" name="" value="" id="c_1"></div>
										<div class="form_right" id="buy_max" onclick="max('buy')"><span>最多</span></div>
									</div>
									<div class="deal_input">
										<div class="form_left"><span>总计金币</span></div>
										<div class="form_center"><input type="text" name="" value="" readonly="readonly" id="d_1"></div>
										<div class="form_right"><span>金币</span></div>
									</div>
									<div class="deal_list" style="height: 20%">
										<div class="deal_list_box">
											<div class="deal_list_left">
												<div class="deal_list_left_box">
													<span>可用额：</span><span style="color:red" id="zygold"><?php echo ($money); ?>&nbsp;</span><span>金币</span>
													<div class="gold_brush" onclick="brush('gold')"><span>&nbsp;刷新&nbsp;</span></div>
												</div>
											</div>
										</div>
										<div class="deal_list_box">
											<div class="deal_list_right"><span>无</span><span>手续费：</span></div>
										</div>
									</div>
									<div class="submit"><input type="button" name="" value="限价买入" onclick="goldno(1,1)" id="mairu"></div>
								</form>
							</div>
							<div class="deal_form" style="margin-top:5%">
								<form>
									<div class="deal_list">
										<div class="deal_list_box">
											<div class="deal_list_left" style="margin-top: 0">
												<b class="maichu">卖出</b>
												<span id="sell_jy_price" style="position:absolute;font-size:1.3rem;left:22%;color:#FF6601"></span>
												<!--<select id="sell_sel" style="height:150%">
                                                    <option value="卖出"><span>卖出</span></option>
                                                    <option value="委托卖出">委托卖出</option>
                                                </select>-->
											</div>
										</div>
										<div class="deal_list_box">
											<div class="deal_list_right" style="margin-top: 0"><span onclick="refresh_price('sell')">刷新实时价格</span></div>
										</div>
									</div>
									<div class="deal_input" style="margin-top: 5%">
										<div class="form_left"><span>卖出价格</span></div>
										<div class="form_center"><input type="text" name="" value="<?php echo ($today['new']); ?>" class="b_2" id="b_2" onclick="shift('sell')" onkeyup="clearNoNum(this)"></div>
										<div class="form_right"><span>金币</span></div>
									</div>
									<div class="deal_input" onclick="sell_count()">
										<div class="form_left"><span>卖出数量</span></div>
										<div class="form_center"><input type="text" name="" value="" id="c_2"></div>
										<div class="form_right" id="sell_max" onclick="max('sell')"><span>最多</span></div>
									</div>
									<div class="deal_input">
										<div class="form_left"><span>总计金币</span></div>
										<div class="form_center"><input type="text" name="" value="" readonly="readonly" id="d_2"></div>
										<div class="form_right"><span>金币</span></div>
									</div>
									<div class="deal_list" style="height: 20%">
										<div class="deal_list_box">
											<div class="deal_list_left">
												<div class="deal_list_left_box">
													<span>持有量：</span><span style="color:#f00" id="zynumber"><?php echo ($fruit_ware); ?></span><span></span>
													<div class="gold_brush" style="margin-top:0%" onclick="brush('fruit')"><span>&nbsp;刷新&nbsp;</span></div>
												</div>
											</div>
										</div>
										<div class="deal_list_box">
											<div class="deal_list_right"><span class="sxf">0.0000</span><span> 手续费：</span></div>
										</div>
									</div>
									<div class="submit"><input type="button" name="" value="限价卖出" onclick="goldno(2,1)" id="maichu"></div>
								</form>
							</div>
							<div class=""></div>
						</div>
					</div>
					<div class="entrust_box">
						<div class="entrust_list" style="border: none;">
							<div class="entrust_list_heade">
								<span style="width:30%;height:100%;float:left">委托信息</span>
								<div class="market_price">
									<!--<span id="market_price">市价单</span>-->
								</div>
							</div>
						</div>
						<div class="entrust_list" style="border-bottom: 1px solid #DDDDDD;border-top: 1px solid #DDDDDD;background:#F6F6F6;">
							<div class="entrust_list_center"><span>买/卖</span></div>
							<div class="entrust_list_center"><span>价格</span></div>
							<div class="entrust_list_center"><span>委托量</span></div>
						</div>
						<div class="entrust_list_box"><span style="display:block;margin-top:40%;color:#888888;margin-left:40%">正在加载中......</span></div>
						<div class="history_entrust">
							<div class="history_entrust_box">
								<div class="history_entrust_list">
									<div class="history_entrust_heade">
										<span>当前委托</span>
									</div>
									<div class="history_entrust_heade history_entrust_right">
										<span onclick="refresh_price('entrust')">刷新</span>
									</div>
								</div>
								<div class="history_entrust_list" style="border-bottom:1px solid #E0E0E0;border-top:1px solid #E0E0E0;background:#F6F6F6;height:15%;padding: 3% 2% 2% 2%;">
									<div class="history_entrust_title color" style="width:14%;"><span style="margin-left: -10%; display:block;width:130%">委托时间</span></div>
									<div class="history_entrust_title color"><span>产品</span></div>
									<div class="history_entrust_title color"><span>类型</span></div>
									<div class="history_entrust_title color"><span>价格</span></div>
									<div class="history_entrust_title color"><span>数量</span></div>	
									<div class="history_entrust_title color"><span>状态</span></div>
									<div class="history_entrust_title color"><span>操作</span></div>
								</div>
								<div class="history_entrust_list_overflow history_entrust_list_wt" style="margin-top:17%">


								</div>
							</div>
							<div class="history_entrust_box">
								<div class="history_entrust_list" style="margin-top:12%">
									<div class="history_entrust_heade">
										<span>交易记录</span>
									</div>
									<div class="history_entrust_heade history_entrust_right">
										<span onclick="refresh_price('trading')">刷新</span>
									</div>
								</div>
								<div class="history_entrust_list" style="border-bottom:1px solid #E0E0E0;border-top:1px solid #E0E0E0;background:#F6F6F6;height:15%;padding: 3% 2% 2% 2%;" >
									<div class="history_entrust_title color" style="width:14%"><span style="margin-left: -10%;">交易时间</span></div>
									<div class="history_entrust_title color"><span>产品</span></div>
									<div class="history_entrust_title color"><span>类型</span></div>
									<div class="history_entrust_title color"><span>价格</span></div>
									<div class="history_entrust_title color"><span>数量</span></div>
									<div class="history_entrust_title color"><span>状态</span></div>
								</div>
								<div class="history_entrust_list_overflow history_entrust_list_jy" style="margin-top:29%">


								</div>
							</div>
						</div>
						<div class="footer" style="margin-top:25%;background:#F4F5F8;color:#8C98B6">
							<div class="footer_list" style="margin-top:4%"><span>温馨提示：</span></div>
							<div class="footer_list"><span>1. 果实交易以整百倍进行</span></div>
							<div class="footer_list"><span>2. 果实出售手续费2%</span></div>
							<div class="footer_list"><span>3. 当土地等级达到5级之后才可出售果实</span></div>
						</div>
					</div>
				</div>
			</div>
			<a href="<?php echo U('Index/index');?>" rel="external"><div class="index_return"></div></a>
		</div>
	</div>
</div>
<input type="hidden" value="<?php echo ($seed); ?>" id="seed">
</body>
</html>
<script>
line(seed);
</script>