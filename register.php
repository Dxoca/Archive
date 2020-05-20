<?php
require_once 'model.php';//加载模块
header('Content-type:text/html; charset=utf-8');
$mysqli = connect();//连接数据库

if($_GET['action']=="register"){
    echo "注册";
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

