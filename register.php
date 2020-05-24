<?php
require_once 'model.php';//加载模块
header('Content-type:text/html; charset=utf-8');


if ($_GET['action'] == "register") {
    $mysqli = connect();//连接数据库
    $username = $_POST['r_username'];
    $email = $_POST['r_email'];
    $password = $_POST['r_password'];
    echo $email;
    # 判断登录信息
    if (($email == '') || ($password == '') || ($username == '')) {
        echo "<script>alert('用户名或密码不能为空,请重新填写登录信息!');</script>";
        header('refresh:0.1; url=register.php');
        exit;
    } else {
        $result = $mysqli->query("select * from `user` where email='$email'");
        $row = $result->fetch_row();
        if ($result->num_rows == 0) {##0是主键未注册
            $mysqli->query("insert into `user` values ('$email','$username','$password',null )");
            echo "<script>alert('注册成功！');</script>";
            ### 跳转到 登录界面  action 把 信息自动填写，直接登录即可登录原本直接登录了 ，可是没有函数化登录模块！。。
            ##或者 直接 session开启似乎也可以
        } else {
            echo "<script>alert('邮箱：$row[0]已注册！');</script>";
        }
    }
}
?>
<html>
<title><?php title() ?></title>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta charset="UTF-8">
    <!--IE 8浏览器的页面渲染方式-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <!--默认使用极速内核：针对国内浏览器产商-->
    <meta name="renderer" content="webkit">
</head>
<body>
<form action="register.php?action=register" method="post">
    <fieldset>
        <legend>用户注册</legend>
        <ul>
            <li>
                <label>邮   箱:</label>
                <input type="text" name="r_email">
            </li>
            <li>
                <label>昵   称:</label>
                <input type="text" name="r_username">
            </li>
            <li>
                <label>密   码:</label>
                <input type="password" name="r_password">
            </li>
            <li>
                <label> </label>
                <input type="submit" name="register" value="注册">
            </li>
        </ul>
    </fieldset>
</form>
</body>
</html>

