<!--登录注销界面-->
<?php
/**
 * @package user
 * 用户注销界面
 */
?>


<?php
    header('Content-type:text/html; charset=utf-8');
    // 注销后的操作
    session_start();
    // 清除Session
    $email = $_SESSION['email'];  //用于后面的提示信息
    $username = $_SESSION['username'];
    $_SESSION = array();
    session_destroy();
    // 清除Cookie
    setcookie('email', '', time() - 99);
    setcookie('code', '', time() - 99);
    // 提示信息
    header('refresh:0.1; url=index.php');
    echo "<script>alert('欢迎下次光临！$username : $email')</script>";




