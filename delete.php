<?php
require_once 'model.php';
header('Content-type:text/html; charset=utf-8');


## 防止未登录进入此页面
if (!isLogin()) {
    echo "<script>alert('当前未登录');</script>";
    header('refresh:0.1; url=index.php');
} else {
    $mysqli = connect();
## 删除登录日志
### 判断地址栏参数action的值是
    switch ($_GET['action']) {
        case "delete_loginLog":#### 登录日志
            ## 删除表 truncate  truncate 是整体删除
            $sql_delete_login = "truncate table loginLog";
            mysqli_query($mysqli, $sql_delete_login);
            echo "<script>alert('清空成功');javascript:history.back(1);</script>";
            break;
        case "delete_content":#### 删除内容
            break;
        default:
            ### 若是直接进入该页面 直接返回主页
            header('refresh:0; url=index.php');
    }
}

