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
function hasTable($mysqli, $table)
{
    # 判断内容表是否存在
    $checkTable = $mysqli->query("show tables like '$table'")->fetch_row();
    if (isset($checkTable)) {
        return true;
    } else {
        return false;
    }
}

/**
 * @return array|false|string
 * 获取登录ip
 */
function getIP()
{
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "UnKnow";
    return $ip;
}

/**
 * @param $mysqli 初始化创建表
 * 内容表
 * 登录日志
 * admin 表
 * 防止 删表之后 没有表可以重新创建
 */
function initTable($mysqli)
{
    ##！！！！！ 是不是应该弄一个连接到数据库 初始化建表！！！
    # 判断内容表是否已创建 init 内容表;
    if (hasTable($mysqli, 'content')) {
        ##创建内容表
        $sql_content = <<<TAG
                    CREATE TABLE `content` (
                  `Id` int(11) NOT NULL AUTO_INCREMENT,
                  `text` text,
                  `datetime` datetime DEFAULT NULL,
                  `who` varchar(25) DEFAULT NULL,
                  PRIMARY KEY (`Id`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                  TAG;

        $mysqli->query($sql_content);
        $getNowTime = getNowTime();
        $username = $_SESSION['username'];
        $mysqli->query("insert into `content` values (0 ,'欢迎使用Archive~ ','$getNowTime','Dxoca')");
        $mysqli->query("INSERT INTO `content` VALUES (null ,'这是一条测试数据！','$getNowTime','$username')");
//            echo "content表创建完成";
    }

    ## 登录日志表 id  ip 用户名 邮箱 时间 登录信息（error succeed）
    if (!hasTable($mysqli, 'loginLog')) {
        $sql_loginLog = <<<TAG
            create table `loginLog`(
             `Id` int(11) not null  auto_increment,
             `ip` varchar(16)  DEFAULT NULL,
             `username` varchar(15) DEFAULT NULL,
             `email` varchar(25) DEFAULT NULL,
             `datetime` datetime DEFAULT NULL,
             `state` varchar (15) DEFAULT NULL,
            PRIMARY KEY (`id`)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8;
            TAG;
        $mysqli->query($sql_loginLog);
    }
    ## 创建登录用户表 user
    if (!hasTable($mysqli, 'user')) {
        $sql_user = <<<TAG
            CREATE TABLE `user` (
              `email` varchar(25) NOT NULL DEFAULT '',
              `username` varchar(15) NOT NULL DEFAULT '',
              `password` varchar(255) DEFAULT NULL,
              `Power` int(2) DEFAULT NULL,
              PRIMARY KEY (`email`)
            ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='user password';
            TAG;
        $mysqli->query($sql_user);
        //默认用户
        $mysqli->query("INSERT INTO `user` VALUES ('987284242@qq.com','寒光','admin',NULL),
            ('admin@dxoca.cn','admin','admin',NULL),
            ('dxoca@xkx.me','user3','admin2',NULL),
            ('i@dxoca.cn','user2','123456',NULL);");
    }
}

?>

























