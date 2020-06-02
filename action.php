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
            if (count((array)$idArray) <= 0) {//如果没有选择 技术数组个数
                echo "<script>alert('请选择需要删除的内容！')</script>";
                header('refresh:0.1; url=admin.php');
            } else {
                //删除数组内所有id对应的项
                foreach ($idArray as $id) {
                    $mysqli->query("delete from content where Id='$id'");
                    echo "删除id:(" . $id . ")成功！<br/>";
                    header('refresh:1; url=admin.php');
                }
            }
//            echo '你选择了:'.implode(',',$idArray);
            break;
        case "edit_content":## 修改内容
//            echo $_GET['id']; 取出要修改的id
            $id = $_GET['id'];
            if ($id == null)//防止直接进入该页面 【实际可以先判断有无id对应项 先暂时敷衍一下...】
                echo "错误";
            else {
                $sql_oldContent = "select * from content where Id='$id'";//查询id对应的项
                $result = $mysqli->query($sql_oldContent)->fetch_row();//项的所有字段取出

                echo <<<TAG
<link rel="stylesheet" type="text/css" href="css/quick.css">
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
<div id="content">
            <div class="bg-light">
 <form action="action.php?action=new_edit_content&id=$id" method="post">
            <table align="center">
                <caption><h4>Archive-修改</h4></caption>
                <tr>
                    <th>
                        <textarea rows="3" cols="40" type="text" name="text" style="background-color: #ffffff4a;">$result[1]</textarea>
                    </th>
                </tr>
                <tr>
                    <th>
                    <label>author:</label>
                        <input  type="text" name="username" value="$result[3]" style="background-color: #ffffff4a;">
                    </th>
                </tr>
                <tr><th><input type="submit" name="edit" value="修改"></th></tr>
            </table>
        </form>
</div>
</div>
TAG;
            }
            break;
        case "new_edit_content":
            print_r($GLOBALS);
            $text = $_POST['text'];
            $username = $_POST['username'];
            $id = $_GET['id'];
            if ($text != '') {
                $sql_update = "UPDATE content SET text = '$text' , username = '$username' WHERE Id = '$id'";//更新数据
                $mysqli->query($sql_update);
                echo "<script>alert('修改成功！');</script>";
            } else {
                echo "<script>alert('内容不能为空');</script>";
            }
            header('refresh:0.5; url=admin.php');

            break;
        case "insert_content":## 添加内容 最后一个是 喜欢
//            print_r($GLOBALS);
            $insert_text = $_POST['text'];
            if ($insert_text != '') {//判断写入内容是否为空
                $username = $_SESSION['username'];
                $datetime = getNowTime();
                $mysqli->query("insert into `content` values (0 ,'$insert_text','$datetime','$username',0)");
                echo "<script>alert('添加成功（ $insert_text $username $datetime ）');</script>";
            } else {
                echo "<script>alert('内容不能为空');</script>";
            }
            header('refresh:0.5; url=admin.php');
            break;
        default:
            ### 若是直接进入该页面 直接返回主页
            echo "404";
            header('refresh:0.5; url=index.php');
    }
}

