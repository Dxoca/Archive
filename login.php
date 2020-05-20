<!--登录界面 -->
<?php
require_once 'model.php';//加载常量
header('Content-type:text/html; charset=utf-8');
if(isLogin()){
    echo "<script>alert('你已登录！')</script>";
    header('refresh:0.1; url=admin.php');
}
?>
<html>
<title>登录—<?php echo title() ?></title>
<head>
</head>
<body>
<form action="admin.php" method="post">
    <fieldset>
        <legend>用户登录</legend>
        <ul>
            <li>
                <label>邮   箱:</label>
                <input type="text" name="email">
            </li>
            <li>
                <label>密   码:</label>
                <input type="password" name="password">
            </li>
            <li>
                <label> </label>
                <input type="checkbox" name="remember" value="yes">7天内自动登录
            </li>
            <li>
                <label> </label>
                <input type="submit" name="login" value="登录">
                <input type="submit" name="register" value="注册">
            </li>
        </ul>
    </fieldset>
</form>
</body>
</html>
