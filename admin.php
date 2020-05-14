<?php
require_once 'model.php';//加载模块
header('Content-type:text/html; charset=utf-8');
$mysqli = connect();//连接数据库
?>

<?php
//session 开始
session_start();
//处理登录信息 从登录界面进入
if (isset($_POST['login'])) {
    $nowTime = getNowTime();//获取 登录时间 方便写入登录 login
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $mysqli->query("select * from `user` where email='$email'");//从user表中查询登录信息符合的字段  and password='$password'
    $mysqli = null;//清理缓存
    //print_r($result);
    $row = $result->fetch_row();//取出email项

    # 接受登录信息
    # 判断登录信息
    if (($email == '') || ($password == '')) {
        # 若为空,视为未填写,提示错误,并3秒后返回登录界面
        header('refresh:1; url=login.php');
        echo "<script>alert('用户名或密码不能为空,系统将在3秒后跳转到登录界面,请重新填写登录信息!')</script>";
        exit;
    } elseif (($email != $row[0]) || ($password != $row[2])) {
        # 用户名或密码错误,同空的处理方式
        header('refresh:1; url=login.php');
        echo "<script>alert('用户名或密码错误,系统将在3秒后跳转到登录界面,请重新填写登录信息!')</script>";
        exit;
    } elseif (($email = $row[0]) && ($password = $row[2])) {
        # 用户名和密码都正确,将用户信息存到Session中
        $_SESSION['email'] = $email;
        $_SESSION['isLogin'] = 1;//1 已登录
        $_SESSION['username'] = $row[1];//用户名
        // 若勾选7天内自动登录,则将其保存到Cookie并设置保留7天
        if ($_POST['remember'] == "yes") {
            setcookie('email', $email, time() + 7 * 24 * 60 * 60);
            setcookie('code', md5($email . md5($password)), time() + 7 * 24 * 60 * 60);
        } else {
            // 没有勾选则删除Cookie
            setcookie('email', '', time() - 999);
            setcookie('code', '', time() - 999);
        }
        // 处理完附加项后跳转到登录成功的首页
        echo "<script>alert('用户 $row[1] 登录成功！$nowTime')</script>";//显示用户名 并提示登录成功
//        header('location:login.php');
    }
} else {//直接进入admin页面
    if (isLogin()) {
        echo "当前已登录";
        echo "后台管理 ";
    } else {
        header('refresh:0.1; url=login.php');
        echo "<script>alert('登录失败，请重新登录')</script>";
    }
}


?>

