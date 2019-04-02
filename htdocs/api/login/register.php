<?php
require(dirname(__FILE__).'/../../config/db.php');

if(!isset($_POST["phone"])||!isset($_POST['pw'])){exit();}

session_start();//开始session

$phone=$_POST['phone'];
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
        $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE phone = :phone");
        $stmt1->bindParam(':phone', $phone);
        $stmt1->execute();
        $rows = $stmt1->fetch();
        $rowCount = $rows[0];
        if ($rowCount==1) {
            $arr = array('ec' => -1,'msg' => '手机号已存在');
            exit(json_encode($arr));
        }

        // 预处理 SQL 并绑定参数、新增帐号,使用非对称加密保存密码，预防脱库
        $stmt2 = $conn->prepare("INSERT INTO users (phone, pwd, regtime) VALUES (:phone, :pwd, :regtime)");
        $stmt2->bindParam(':phone', $phone);
        $stmt2->bindParam(':pwd', $user_password_hash);
        $stmt2->bindParam(':regtime', $regtime);
        $regtime=time();
        $user_password_hash = password_hash($password, PASSWORD_DEFAULT);// crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
        if($stmt2->execute()){
            //成功添加了 $stmt2->rowCount() 条记录
            $_SESSION['user_phone'] =$phone;
            $_SESSION['user_regtime'] =$regtime;
            $_SESSION['user_login_status'] = 1;//置登录状态为已登录
            $user= array('phone' => $phone,'regtime' => $regtime );
            $arr = array('ec' => 0,'msg' => 'success','user' => $user);//返回你需要的基本信息
            exit(json_encode($arr));
        }else{
            $arr = array('ec' => -1,'msg' => '失败：'.json_encode($stmt2->errorInfo()));
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