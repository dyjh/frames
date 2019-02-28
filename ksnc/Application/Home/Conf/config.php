<?php
return array(
		'DB_TYPE' => 'mysql', // 数据库类型
		'DB_HOST' => '127.0.0.1', // 服务器地址
		//'DB_NAME'   =>'farms', // 数据库名
//		'DB_USER'   => 'root', // 用户名
		// 'DB_PWD'    => 'root', // 密码
//		'DB_PORT'   => 3306, // 端口//
// 2017年10月5日16:13:22
		'DB_NAME'   =>'test', // 数据库名
		'DB_USER'   => 'root', // 用户名
		'DB_PWD'    => 'qweqweqwe', // 密码
		'DB_PORT'   => 3306, // 端口
		'DB_PREFIX' => '', // 数据库表前缀
		'DB_CHARSET'=> 'utf8', // 字符集
		'DB_DEBUG' =>false, // 数据库调试模式

		//'配置项'=>'配置值'
		'TMPL_PARSE_STRING' => array(
				'__FILEADD__' => __ROOT__ . '/Public/' . MODULE_NAME . '/FILE',
				'__ATADD__' => __ROOT__ . '/Public/' . MODULE_NAME . '/AT',
				'__HMADD__' => __ROOT__ . '/Public/' . MODULE_NAME . '/HM',
				'__ASSETS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/assets',
				'__UPLOAD__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Upload',
				'__COMMON__' => __ROOT__ . '/Public/Common',
				'__HIGHSTOCK__' => __ROOT__ . '/Public/Highstock',
		),
		'URL_MODEL'=>2,			// URL moshi
		'URL_HTML_SUFFIX'=>'',			// URL 后缀
		'URL_PATHINFO_DEPR'=>'-',		// 更改PATHINFO参数分隔符
		'URL_CASE_INSENSITIVE'  =>  true,
		'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
		'TOKEN_NAME'    =>    '__LYGAME__',    // 令牌验证的表单隐藏字段名称	
		'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
		'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
);