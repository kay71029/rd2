<?php
session_start();
require("MySqlCconnect.php");
header('Content-Type: text/html; charset=utf-8');
$ac_id = $_SESSION['ac_id'];
$ac_acount = $_POST["ac_acount"];
$time = $_POST["time"];
DoWithdrawal($ac_id, $ac_acount, $time);
function DoWithdrawal($ac_id, $ac_acount, $time)
{
    if ($ac_acount != null) {
        try {
                $db = DB();
                $db->beginTransaction();
                $sql = "SELECT * FROM `admin` WHERE `ac_id` = :ac_id FOR UPDATE";
                $result = $db->prepare($sql);
                $result->bindParam('ac_id', $ac_id);
                $result->execute();
                $data = $result->fetch();
                $originalMoney = $data['ac_acount'];
                $payMoney = $ac_acount;
                $totalMoney = $originalMoney - $payMoney;
    
                if ($originalMoney < $payMoney) {
                    echo "<script>alert('餘額不足');</script>";
                    header("Refresh:0.5; url = ShowAccountDetailPage.php");
                    exit();
                }
                $sql = "UPDATE `admin` SET `ac_acount`= `ac_acount` - :ac_acount WHERE"
                    . "`ac_id` = :ac_id ";
                $result = $db->prepare($sql);
                $result->bindParam(':ac_acount', $payMoney);
                $result->bindParam(':ac_id', $ac_id);
                $result->execute();
                
                $sql = "INSERT INTO `banker_detail`(`ac_id`, `type`, `money`, `date`, "
                    . "`blance`, `newBlance`) VALUES (:ac_id, '出款', :money, :date, :blance,"
                    . ":newBlance)";
                $result = $db->prepare($sql);
                $result->bindParam(':ac_id', $ac_id);
                $result->bindParam(':money', $payMoney);
                $result->bindParam(':date', $time);
                $result->bindParam(':blance', $originalMoney);
                $result->bindParam(':newBlance', $totalMoney);
                $result->execute();
                $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            echo $e->getMessage();
            header("Refresh:0.5; url = ShowWithdrawalPage.php");
            exit();
        }
        echo "<script>alert('出款成功');</script>";
        header("Refresh:0.5; url = ShowAccountDetailPage.php");
    }
}