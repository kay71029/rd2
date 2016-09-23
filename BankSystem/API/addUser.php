<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
require('MySqlConnect.php');
$id = $_POST["id"];
$date = date("Y-m-d");
$time = date("H:i:s");

checkUser($id);
addUser($id ,$date , $time);
function checkUser($id)
{
    if ($id == null || !preg_match("/^([A-Za-z0-9]+)$/",$id)) {
       echo json_encode(array('id' => $id, 'message' => "帳號有數字及英文組成"),JSON_UNESCAPED_UNICODE); 
       exit();
    }
}
function addUser($id ,$date , $time)
{
    $db = DB();
    $sql = "SELECT `id` FROM `user` WHERE `id`= '$id'";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    $data = $result->fetch();
    $bdId = $data['id'];

    if($bdId == $id) {
        echo json_encode(array('id' => $id, 'message' => "帳號重複"),JSON_UNESCAPED_UNICODE);
        exit();
    }

    $sql = "INSERT INTO `user` (`id`) VALUES (:id)";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    $sql = "INSERT INTO `record`(`id`, `date`, `time`, `remark`, `type`, `money`, `blance`, `newBlance`) 
        VALUES (:id, :date, :time, '樂透遊戲--帳戶開戶', '入款' ,'100' , '0' , '100')";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->bindParam(':date', $date);
    $result->bindParam(':time', $time);
    $result->execute();
    $sql = "UPDATE `user` SET `account` = 100 WHERE `id` = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    echo json_encode(array('id' => $id, 'message' => "開戶成功"),JSON_UNESCAPED_UNICODE);
}