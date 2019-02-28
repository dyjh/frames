<?php
return array(
	//'配置项'=>'配置值'
		'DB_TYPE'    => 'mysql', // 数据库类型
	//'DB_HOST'   => '59.56.111.78', // 服务器地址
		'DB_HOST'   => '127.0.0.1', // 服务器地址
		// 'DB_NAME'   =>'farms', // 数据库名
		// 2017年10月5日16:13:46
		'DB_NAME'   =>'test', // 数据库名
		'DB_USER'   => 'root', // 用户名
		// 'DB_PWD'   => 'root', // 密码
		'DB_PWD'    => 'qweqweqwe', // 密码
		'DB_PORT'   => 3306, // 端口
		'DB_PREFIX' => '', // 数据库表前缀
		'DB_CHARSET'=> 'utf8', // 字符集
		'SHOW_PAGE_TRACE'=>false,
		'DB_BIND_PARAM'    =>    true,
		'DB_DEBUG' => false, // 数据库调试模式
		//'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
		'LOG_EXCEPTION_RECORD' =>true
);
