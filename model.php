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
    return date('Y-m-d H:i:s'); //
}

/**
 * @param $mysqli
 * @param $table //要查的表
 * @return int 统计 $table表 中总数据的条数
 */

function postsNum($mysqli, $table)
{
    $result = $mysqli->query("select * from $table");
    return mysqli_num_rows($result);//返回查到的数据条数
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
        die('connect error:' . $mysqli->connect_errno);//
    }
    $mysqli->set_charset('UTF-8'); // 设置数据库字符集
    return $mysqli;
}

/**
 * @return int
 * 判断是否已登录
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
    //已登录
    if (isset($_SESSION['isLogin'])) {
        return 1;
    } else {// 若没有登录
        return 0;
    }
}

/**
 * 判断数据表是否存在 查看有无数据段count 和postsNum 作用一致 只是返回数据不同
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
 * 通过 getenv — 获取一个环境变量的值
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
        $ip = "UnKnow";//所有常量数据 都没有取到
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
    if (!hasTable($mysqli, 'content')) {
        ##创建内容表
        $sql_content = <<<TAG
                    CREATE TABLE `content` (
                  `Id` int(11) NOT NULL AUTO_INCREMENT,
                  `text` text,
                  `datetime` datetime DEFAULT NULL,
                  `username` varchar(25) DEFAULT NULL,
                  `like` int(11) NOT null default '0',
                  PRIMARY KEY (`Id`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TAG;
        $mysqli->query($sql_content);//创建内容表 sql执行
        $getNowTime = getNowTime();
        $mysqli->query("insert into `content` values (0 ,'欢迎使用Archive~ ','$getNowTime','Dxoca.cn',1)");
        $mysqli->query("INSERT INTO `content` VALUES (null ,'但尽人事，莫问前程。','$getNowTime','寒光',0)");
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
        $mysqli->query("
            INSERT INTO `user` VALUES ('987284242@qq.com','寒光','admin',NULL),
            ('admin@dxoca.cn','admin','admin',NULL);");
    }
}

/**
 * 页面底部内容 多次调用所以写成模块
 */
function foot()
{
    $str = <<<HTML
        <div  class="foot" >
            Powered by <a target="blank" href="https://dxoca.cn/StudyNotes/349.html">Dxoca</a>&nbsp;|&nbsp;Github:<a target="blank" href="https://github.com/Dxoca/Archive/">Achive</a></body>
        </div>
HTML;
    echo $str;
}