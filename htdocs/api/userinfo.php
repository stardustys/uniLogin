<?php
date_default_timezone_set("PRC");// 设置时区，非必须，因为在此demo使用了时间戳转换
session_start();
if (!isset($_SESSION['user_login_status']) Or !$_SESSION['user_login_status'] == 1)
{
    $arr = array('ec' => 2,'msg' => 'not login');
    exit(json_encode($arr));
}else{

    $user= array('phone' => $_SESSION['user_phone'],'regtime' => date("Y-m-d H:i:s", $_SESSION['user_regtime']) );
    $arr = array('ec' => 1,'msg' => 'success','user' => $user);//返回你需要的基本信息
    exit(json_encode($arr));
}