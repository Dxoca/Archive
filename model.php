<?php

/**
 * 返回标题文本
 */
function title()
{
    $title = "Archive";//归档
    echo $title;
}

/**
 * @return false|当前时间
 */
function getNowTime()
{
    // 设定要用的时区
    date_default_timezone_set("PRC");
    return date('Y-m-d H:i:s');
}

/**
 * @return Mysqli|返回连接后的数据库对象
 */
function connect()
{
    $serve = 'localhost:3306';
    $username = 'root';
    $password = 'root';
    $dbname = 'archive';//数据库名字
    $mysqli = new Mysqli($serve, $username, $password, $dbname);//创建连接
    if ($mysqli->connect_error) {//判断是否链接成功
        die('connect error:' . $mysqli->connect_errno);
    }
    $mysqli->set_charset('UTF-8'); // 设置数据库字符集
    return $mysqli;
}

/**
 * @return int
 * 判断是否登录
 */
function isLogin()
{
    $email = null;
    // 开启Session
    session_start();
    //首先判断Cookie是否有记住了用户信息
    if (isset($_COOKIE['email'])) {
        # 若记住了用户信息,则直接传给Session
        $_SESSION['email'] = $_COOKIE['email'];
        $_SESSION['isLogin'] = 1;
    }
    if (isset($_SESSION['isLogin'])) {
        return 1;
    } else {
        // 若没有登录
        return 0;
    }
}

/**
 * 判断数据表是否存在
 * @param $mysqli Mysqli对象
 * @param $table 要判断的表
 * @return bool
 */
function checkTable($mysqli, $table)
{
    # 判断内容表是否存在
    $checkTable = $mysqli->query("show tables like '$table'")->fetch_row();
    if (isset($checkTable)) {
       return true;
    } else {
        return false;
    }
}

?>

























