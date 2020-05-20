<?php
require_once 'model.php';//加载模块
header('Content-type:text/html; charset=utf-8');

$mysqli = connect();//连接数据库
# 接受登录信息
$email = $_POST['email'];
$password = $_POST['password'];
initTable($mysqli);//初始化创建表(内容表 日志表 用户表)
?>

<?php
//session 开始
session_start();
//处理登录信息 从登录界面进入
if (isset($_POST['login'])) {
    $ip = getIP();//获取客户端真实ip
    # 查user表 从user表中查询登录信息符合的字段  and password='$password'
    $result = $mysqli->query("select * from `user` where email='$email'");

    //print_r($result);
    $row = $result->fetch_row();//取出email(主键)的数据段
    $username = $row[1];//取出用户名
    $nowTime = getNowTime();//获取 登录时间 方便写入登录 loginLog

    # 判断登录信息
    if (($email == '') || ($password == '')) {
        # 若为空,视为未填写,提示错误,并0.1秒后返回登录界面
        header('refresh:0.1; url=login.php');
        echo "<script>alert('用户名或密码不能为空,系统将在3秒后跳转到登录界面,请重新填写登录信息!')</script>";
        exit;
    } elseif (($email != $row[0]) || ($password != $row[2])) {
        //登录[失败 error]信息写入loginLog,
        $mysqli->query("insert into `loginLog` values(null,'$ip','$username','$email','$nowTime','error')");
        # 用户名或密码错误,同空的处理方式
        header('refresh:0.1; url=login.php');
        echo "<script>alert('用户名或密码错误,系统将在3秒后跳转到登录界面,请重新填写登录信息! ')</script>";
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
        //登录[成功 succeed]信息写入数据库
        $mysqli->query("insert into `loginLog` values(null,'$ip','$username','$email','$nowTime','succeed')");
        // 处理完附加项后跳转到登录成功的首页
        echo "<script>alert('用户 $row[1] 登录成功！$nowTime')</script>";//显示用户名 并提示登录成功
        header('refresh:0.1; url=admin.php');
    }
} else if (isset($_POST['register'])) {
    echo "注册";
} else {//直接进入admin页面
    # 已登录
    if (isLogin()) {
        ?>

        <html>
        <title>后台-<?php title() ?></title>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
            <meta charset="UTF-8">
            <!--IE 8浏览器的页面渲染方式-->
            <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
            <!--默认使用极速内核：针对国内浏览器产商-->
            <meta name="renderer" content="webkit">
            <style type="text/css">.error {
                    color: #F00
                }</style>
        </head>
        <body>
        <?php
        //  主页内容
        echo "你好! " . $_SESSION['username'] . ' ,欢迎来到后台!<br>';
        echo "<a href='logout.php'>注销</a>&nbsp;<a href='index.php'>首页</a><br>";
        ?>
        这里是对数据进行操作 增删改查 记得记录管理员登录日志<br>
        <?php
        ## 打印 loginLog表
        //执行SQL 查询语句，并将结果集以表格的形式输出
        $loginLog_sql = "select * from loginLog";//查出表中所有数据
        $result = $mysqli->query($loginLog_sql);
        //        print_r($result); //调试数据
        echo '<table align="center" border="2" width="800">';
        echo '<caption><h3>登录日志</h3></caption>';
        echo '<tr>';
        echo '<th>id</th><th>ip</th><th>user</th><th>email</th><th>datetime</th><th>state</th>';
        echo '</tr>';
        //fetch_row()函数每执行一次，指针向后自动移动一位，直到最后没有数据记录返回false
        while ($row = $result->fetch_row()) {
            echo '<tr align ="center">';
//        print_r($row[0] .'<br>'); //调试数据
            foreach ($row as $value) {//遍历每一行的每个数据[遍历row数组]
                ## 登录失败的项目标红
                if ($value == "error") echo "<td class='error'>{$value}</td>";
                else echo "<td >{$value}</td>";
            }
            echo '</tr>';
        }
        ## colspan 占6列
        echo '<tr align ="center" ><td colspan="6">
                    <a href=delete.php?action=delete_loginLog>清空日志</a>
                </td></tr></table>';
        ?>
        </body>
        </html>
        <?php
    } else {## 未登录
        header('refresh:0.1; url=login.php');
//        echo "<script>alert('登录失效，请重新登录')</script>";
    }
}
$mysqli = null;//清理缓存
?>

