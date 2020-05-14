<?php
require_once 'model.php';//加载常量
header('Content-type:text/html; charset=utf-8');
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
<?php
if (isLogin()) {
//  主页内容
    echo "你好! " . $_SESSION['username'] . ' ,欢迎来到主页!<br>';
    echo "<a href='logout.php'>注销</a>&nbsp;<a href='admin.php'>后台</a>";

} else {

    //首页不需要强制登录

//    header('refresh:0.1; url=login.php');
    echo "<script>alert('当前未登录')</script>";
}
?>
</body>
</html>


