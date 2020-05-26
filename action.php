<?php
require_once 'model.php';
header('Content-type:text/html; charset=utf-8');
/**
 * 相关action操作模块
 * 删除
 * 修改
 */

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

            $idArray = $_POST['checkbox_content'];
            ## 强制转换为数组，不然报错（为什么呢 是因为刚开始是对象么qwq）
            if (count((array)$idArray) <= 0) {
                echo "<script>alert('请选择需要删除的内容！')</script>";
                header('refresh:0.1; url=admin.php');
            } else {
                foreach ($idArray as $id) {
                    $mysqli->query("delete from content where Id=$id");
                    echo "删除id:(" . $id . ")成功！<br/>";
                    header('refresh:1; url=admin.php');
                }
            }
//            echo '你选择了:'.implode(',',$idArray);
            break;
        case "edit_content":## 修改内容
            echo "修改内容";
            break;
        case "insert_content":## 添加内容
//            print_r($GLOBALS);
            $insert_text = $_POST['text'];
            if ($insert_text != '') {
                $username = $_SESSION['username'];
                $datetime = getNowTime();
                $mysqli->query("insert into `content` values (0 ,'$insert_text','$datetime','$username')");
                echo "<script>alert('添加成功（ $insert_text $username $datetime ）');</script>";
            } else {
                echo "<script>alert('内容不能为空');</script>";
            }
            header('refresh:0.3; url=admin.php');
            break;
        default:
            ### 若是直接进入该页面 直接返回主页
            echo "404";
            header('refresh:0.5; url=index.php');
    }
}

