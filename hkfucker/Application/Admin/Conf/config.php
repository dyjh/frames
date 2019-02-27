<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_TEMPLATE_SUFFIX'=>'.tpl',
    'TMPL_PARSE_STRING'  =>array(
        '__JS__'			=> __ROOT__.'/Application/Admin/Common/js',
        '__CSS__' 			=> __ROOT__.'/Application/Admin/Common/css',
        '__IMG__' 			=> __ROOT__.'/Application/Admin/Common/img',
        '__PUBLICHOME__' 	=> __ROOT__.'/Public/Home',
        '__COM__' 			=> __ROOT__.'/Application/Admin/Common',
        '__HIGHSTOCK__' 	=> __ROOT__.'/Application/Admin/Common/Highstock'
    ),
    //'URL_MODEL'=>2,			// 隐藏入口文件
    //'URL_HTML_SUFFIX'=>'',			// URL 后缀
    //'URL_PATHINFO_DEPR'=>'-',
);
define('UC_AUTH_KEY', 'h@x.Mb^50W(TC:g?Xr_>4LjZ6|{k3]z"aE2vi1),'); //加密KEY