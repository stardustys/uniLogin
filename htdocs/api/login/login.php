<?php
require(dirname(__FILE__).'/../../config/db.php');

if(!isset($_POST["phone"])||!isset($_POST['pw'])){exit();}

session_start();//开始session

$phone=$_POST['phone']; //接收前台传来的post数据
$password=$_POST['pw'];

if (empty($phone) || empty($password)){
    $arr = array('ec' => -1, 'msg' => '请完成必填');
    exit(json_encode($arr));
} elseif (strlen($password) < 6) {
    $arr = array('ec' => -1, 'msg' => '密码过短');
    exit(json_encode($arr));
} elseif (strlen($password) > 64) {
    $arr = array('ec' => -1, 'msg' => '密码过长');
    exit(json_encode($arr));
} elseif (!preg_match('/^1(3|4|5|6|7|8|9)\d{9}$/', $phone)) {
    $arr = array('ec' => -1, 'msg' => '非法手机号');
    exit(json_encode($arr));
} else{
    try {
        $DB_HOST=DB_HOST;
        $DB_NAME=DB_NAME;
        $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", DB_USER, DB_PASS);
        // 设置 PDO 错误模式为异常
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        // 预处理 SQL 并绑定参数、查询是否已有此帐号
        $stmt1 = $conn->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 5");
        $stmt1->bindParam(':phone', $phone);
        $stmt1->execute();
        // 设置结果集为关联数组
        $result = $stmt1->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt1->fetch();
        // 使用 PHP 5.5's password_verify() 方法检查用户输入密码是否符合数据库hash密码
        if (password_verify($password, $rows['pwd'])) {
            $_SESSION['user_uid'] = $rows['id'];
            $_SESSION['user_phone'] = $rows['phone'];
            $_SESSION['user_password'] = $rows['pwd'];
            $_SESSION['user_regtime'] = $rows['regtime'];
            $_SESSION['user_login_status'] = 1;
            $user= array('phone' => $rows['phone'],'regtime' => $rows['regtime'] );//返回你需要的基本信息
            $arr = array('ec' => 0,'msg' => 'success','user' => $user);
            exit(json_encode($arr));
        }else{
            $arr = array('ec' => -1, 'msg' => '密码错误');
            exit(json_encode($arr));
        }


    }
    catch(PDOException $e)
    {
        $arr = array('ec' => -99,'msg' => '数据库错误：'.$e->getMessage());
        exit(json_encode($arr));
    }
    //$conn = null; //使用其手动关闭连接
}