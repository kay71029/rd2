<?php
session_start();
require("MySqlCconnect.php");
header('Content-Type: text/html; charset = utf-8');
if ($_POST["ac_acount"] != null) {
    try {
        $db->beginTransaction();
        $sql = "SELECT * FROM `admin` WHERE `ac_id` = :ac_id FOR UPDATE";
        $result = $db->prepare($sql);
        $result->bindParam(':ac_id', $_SESSION['ac_id']);
        $result->execute();
        $data = $result->fetch();
        $originalMoney = $data['ac_acount'];
        $saveMoney = $_POST["ac_acount"];
        $totalMoney = $originalMoney + $saveMoney;
        $sql = "UPDATE `admin` SET `ac_acount` = `ac_acount` + :ac_acount WHERE"
            . "`ac_id` = :ac_id";
        $result = $db->prepare($sql);
        $result->bindParam(':ac_acount', $saveMoney);
        $result->bindParam(':ac_id', $_SESSION['ac_id']);
        $result->execute();
        $sql = "INSERT INTO `banker_detail`(`ac_id`, `type`, `money`, `date`, "
            . "`blance`, `newBlance`) VALUES (:ac_id, 1, :money, :date, :blance, "
            . ":newBlance)";
        $result = $db->prepare($sql);
        $result->bindParam(':ac_id', $_SESSION['ac_id']);
        $result->bindParam(':money', $saveMoney);
        $result->bindParam(':date', $_POST["time"]);
        $result->bindParam(':blance', $originalMoney);
        $result->bindParam(':newBlance', $totalMoney);
        $result->execute();
        $db->commit();
    } catch (Exception $e) {
        echo $e->getMessage();
        $db->rollBack();
    }
    echo "新增成功";
    header("Refresh:0.5; url = ShowAccountDetailPage.php");
}