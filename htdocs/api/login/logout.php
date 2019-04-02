<?php
session_start();

// 删除session，退出登录状态
$_SESSION = array();
session_destroy();
$arr = array('ec' => 0);
exit(json_encode($arr));
