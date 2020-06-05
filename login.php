<!--登录界面 -->
<?php
/**
 * @package user
 * 用户登录，注册界面
 */

require_once 'model.php';//加载常量
header('Content-type:text/html; charset=utf-8');
//判断是否登录
if (isLogin()) {
    echo "<script>alert('你已登录！')</script>";
    header('refresh:0.1; url=admin.php');
}
?>
<html>
<title>登录—<?php echo title() ?></title>
<head>
</head>
<body>
<div class="bg-image-pattern" style="
    background: rgba(255, 255, 255, 0.05) url(images/bg-fixed.png) repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: -997;
"></div>
<div class="bj-gd" style="
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: -998;
    background: url(images/868535394.jpg) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
"></div>
<form action="admin.php" method="post" style="
    width: 320px;
    margin: 200px auto;
">
    <fieldset>
        <legend>用户登录</legend>
        <ul style="list-style-type: none;">
            <li>
                <label>邮   箱:</label>
                <input type="text" name="email" style="background-color: #ffffff30;">
            </li>
            <li>
                <label>密   码:</label>
                <input type="password" name="password" style="background-color: #ffffff30;">
            </li>
            <li>

                <input type="checkbox" name="remember" value="yes">7天内自动登录
            </li>
            <li>
                <label></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" name="login" value="登录">&nbsp;&nbsp;&nbsp;
                <input type="submit" name="register" value="注册">
            </li>
        </ul>
    </fieldset>
</form>
</body>
</html>
