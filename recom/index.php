<?php

$user = $_GET['user'];

$url = 'http://lyogame.cn/ksnc/user-lyoregister?user='.$user;

header("location:".$url);