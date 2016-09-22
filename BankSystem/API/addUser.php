<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
require('MySqlConnect.php');

$id = $_POST["id"];
checkUser($id);
addUser($id);

function checkUser($id)
{
    if ($id == null || !preg_match("/^([A-Za-z0-9]+)$/",$id)) {
       echo json_encode(array('id' => $id, 'message' => "帳號有數字及英文組成"),JSON_UNESCAPED_UNICODE); 
       exit();
    }
}

function addUser($id)
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
    echo json_encode(array('id' => $id, 'message' => "成功"),JSON_UNESCAPED_UNICODE);
}