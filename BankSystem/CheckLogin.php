<?php
session_start();
require("MySqlCconnect.php");
header('Content-Type: text/html; charset=utf-8');


$id = substr(strip_tags(addslashes(trim($_POST['ac_id']))),0,20);
$pw = addslashes($_POST['ac_pw']);
$plen = strlen($pw);


//帳號檢查
if (!preg_match("/^([A-Za-z0-9]+)$/", $id)) {
    echo "<script>alert('帳號必須由數字及英文組成');</script>";
    header("Refresh:0.5; url = ShowLoginPage.php");

} else {
        //密碼檢查
        if (!preg_match("/^([A-Za-z0-9]+)$/", $pw) || $plen < 6 || $plen > 15) {
            echo "<script>alert('密碼必須為6-15位的數字和字母的组合');</script>";
            header("Refresh:0.5; url = ShowLoginPage.php");
        } else {

            $sqlpw = md5($pw);
            $sql = "SELECT * FROM `admin` WHERE `ac_id` = :id AND `ac_pw` = :pw";
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_STR);
            $result->bindParam(':pw', $sqlpw, PDO::PARAM_STR);
            $result->execute();
            $count = $result->rowCount();
            $a = $result->fetchAll();

            if ($count !== 1) {
                echo "<script>alert('登入失敗!'');</script>";
                header("Refresh:0.5; url = ShowLoginPage.php");
            } else {
                $_SESSION['ac_id'] = $id;
                echo "<script>alert('登入成功!'');</script>";
                header("Refresh:0.5; url = ShowAccountDetailPage.php");

            }

        }

    }

