<?php
require_once 'model.php';//加载模块
header('Content-type:text/html; charset=utf-8');
$mysqli = connect();//连接数据库

$nowTime = getNowTime();//获取 登录时间 方便写入登录 login
# 接受登录信息
$email = $_POST['email'];
$password = $_POST['password'];

?>

<?php
//session 开始
session_start();
//处理登录信息 从登录界面进入
if (isset($_POST['login'])) {
    # 查user表 从user表中查询登录信息符合的字段  and password='$password'
    $result = $mysqli->query("select * from `user` where email='$email'");
    $mysqli = null;//清理缓存
    //print_r($result);
    $row = $result->fetch_row();//取出email(主键)的数据段
    $username = $row[1];//取出用户名
    # 判断登录信息
    if (($email == '') || ($password == '')) {
        # 若为空,视为未填写,提示错误,并3秒后返回登录界面
        header('refresh:0.1; url=login.php');
        echo "<script>alert('用户名或密码不能为空,系统将在3秒后跳转到登录界面,请重新填写登录信息!')</script>";
        exit;
    } elseif (($email != $row[0]) || ($password != $row[2])) {
        # 用户名或密码错误,同空的处理方式
        header('refresh:0.1; url=login.php');
        echo "<script>alert('用户名或密码错误,系统将在3秒后跳转到登录界面,请重新填写登录信息!')</script>";
        exit;
    } elseif (($email = $row[0]) && ($password = $row[2])) {
        # 用户名和密码都正确,将用户信息存到Session中
        $_SESSION['email'] = $email;
        $_SESSION['isLogin'] = 1;//1 已登录
        $_SESSION['username'] = $username;//用户名
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
        ///登录日志表！！
        /// ！
        /// ！
        /// ！
        /// ！
        /// ！
        header('refresh:0.1; url=admin.php');
    }
} else {//直接进入admin页面
    # 已登录
    if (isLogin()) {
        ##！！！！！ 是不是应该弄一个连接到数据库 初始化建表！！！
        # 判断内容表是否已创建 init 内容表;
        if (checkTable($mysqli, 'content')) {
//            echo "content表已存在";
        } else {
            $sql = <<<TAG
                    CREATE TABLE `content` (
                  `Id` int(11) NOT NULL AUTO_INCREMENT,
                  `text` text,
                  `datetime` datetime DEFAULT NULL,
                  `who` varchar(25) DEFAULT NULL,
                  PRIMARY KEY (`Id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                  TAG;
            $mysqli->query($sql);
            $getNowTime = getNowTime();
            $username = $_SESSION['username'];
            $mysqli->query("insert into `content` values (0 ,'欢迎使用Archive~ ','$getNowTime','Dxoca')");
            $mysqli->query("INSERT INTO `content` VALUES (null ,'这是一条测试数据！','$getNowTime','$username')");
//            echo "content表创建完成";
        }
        ?>

        <html>
        <title>后台-<?php title() ?></title>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
            <meta charset="UTF-8">
            <!--IE 8浏览器的页面渲染方式-->
            <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
            <!--默认使用极速内核：针对国内浏览器产商-->
            <meta name="renderer" content="webkit">
        </head>
        <body>
        <?php
        //  主页内容
        echo "你好! " . $_SESSION['username'] . ' ,欢迎来到后台!<br>';
        echo "<a href='logout.php'>注销</a>&nbsp;<a href='index.php'>首页</a>";
        ?>
        这里是对数据进行操作 增删改查  记得记录管理员登录日志

        </body>
        </html>
        <?php
    } else {
        header('refresh:0.1; url=login.php');
        echo "<script>alert('登录失效，请重新登录')</script>";
    }
}


?>

