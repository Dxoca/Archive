<?php
require_once 'model.php';//加载常量
header('Content-type:text/html; charset=utf-8');

if (isLogin()) {
    echo "你好! " . $_SESSION['username'] . ' ,欢迎来到个人中心!<br>';
    echo "<a href='logout.php'>注销</a>";
} else {
    // 若没有登录
    echo "您还没有登录,请<a href='login.php'>登录</a>";
}


