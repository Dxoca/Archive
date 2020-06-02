<?php
require_once 'model.php';//加载常量
$mysqli = connect();
initTable($mysqli);
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
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/quick.css">

</head>
<body>
<?php
### 右上角 登录 已登录（用户名）

/**
 * index body
 * @package custom
 */
?>

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
<?php
if (isLogin()) {
//  主页内容
    $str="你好! <a href='#' class='red'>" . $_SESSION['username'] . '</a>,欢迎来到主页! <a href=\'logout.php\'>注销</a>&nbsp;<a href=\'admin.php\'>后台</a><br>'."目前共计 " . postsNum($mysqli, 'content') . " 句碎言. 继续努力.";
} else {
    $str= "<a href='admin.php'>登录</a><br>"."目前共计 " . postsNum($mysqli, 'content') . " 句碎言.";
    //首页不需要强制登录

//    header('refresh:0.1; url=login.php');
//    echo "<script>alert('当前未登录')</script>";
}
?>
<div id="content" class="app-content" style="width: 50%;margin: 0 auto;">
    <a class="off-screen-toggle hide"></a>
    <main class="app-content-body ">
        <header class="bg-light lter b-b wrapper-md">
            <h1 class="entry-title m-n font-thin h3 text-black l-h"> <?php title(); ?> </h1><br>
            <small class="text-muted letterspacing indexWords"><?php echo $str?></small>

        </header>
        <div class="wrapper">
            <ul class="timeline">
                <?php
                //执行SQL 查询语句，并将结果集以表格的形式输出
                $content_sql = "select * from content  ORDER BY `Id` DESC";//查出表中所有数据 按id逆序！！
                $result = $mysqli->query($content_sql);

                $color = array("light", "info", "dark", "success", "black", "warning", "primary", "danger");
                $year = 0;
                $mon = 0;
                $i = 0;
                $j = 0;
                $output = '';
                $x = 0;
                $num = 0;
                while ($row = $result->fetch_row()) {
                    $timestamp = strtotime($row[2]);
                    //日期转换为UNIX时间戳用函数：strtotime() 进行处理
                    $year_tmp = date('Y', $timestamp);
                    $mon_tmp = date('m', $timestamp);
                    $y = $year;
                    $m = $mon;
                    if ($year > $year_tmp || $mon > $mon_tmp) {
                        $output .= '';
                    }
                    if ($year != $year_tmp || $mon != $mon_tmp) {
                        if ($x != 0) {
                            $output .= '</div>';//.tl-body
                        }
                        $year = $year_tmp;
                        $mon = $mon_tmp;
                        $x++;
                        if ($x >= 8) $x = 1;
                        $colorSec = $color[$x];
                        $output .= '<li class="tl-header">
                        <h2 class="btn btn-sm btn-' . $colorSec . ' btn-rounded m-t-none">' . date('Y年m月', $timestamp) . '</h2>
                        </li>
                        <div class="tl-body" >';//输出月份
                    }
                    $output .= '<li class="tl-item">
                                    <div class="tl-wrap b-' . $colorSec . '">
                                        <span class="tl-date">' . date('d日', $timestamp) . '</span>
                                        <h3 class="tl-content panel padder h5 l-h bg-' . $colorSec . '">
                                            <span class="arrow arrow-' . $colorSec . ' left pull-up" aria-hidden="true"></span>
                                            <a href="#" class="text-lt" title="有 '.$row[4].' 人喜欢">' . $row[1] ." — 「".$row[3]."」 ".'</a>
                                        </h3>
                                    </div>
                                </li>';
                    //输出文章
                }
                echo $output;
                ?>
                <li class="tl-header">
                    <div class="btn btn-sm btn-default btn-rounded">开始</div>
                </li>
            </ul>
        </div>
    </main>
</div>
<!--底部-->
<?php foot()?>
</body>
</html>