<?php

    date_default_timezone_set("PRC");// 设置时区，非必须，因为在此demo使用了时间戳转换
    session_start();

    if(isset($_GET['logout']))
    {
		// 删除session，退出登录状态
		$_SESSION = array();
		session_destroy();
	}

    if (!isset($_SESSION['user_login_status']) Or !$_SESSION['user_login_status'] == 1)
    {
        echo('当前session状态：未登录');
    }else{
        echo('当前session状态：已登录；');
        echo('手机号：'.$_SESSION['user_phone']);
        echo('； 注册时间：'. date("Y-m-d H:i:s", $_SESSION['user_regtime']));
        echo('<br/><a href="/test2.php?logout">退出登录状态</a>');
    }