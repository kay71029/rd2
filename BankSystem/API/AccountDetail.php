<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
require('MySqlConnect.php');

$id = $_POST["id"];
CheckIdAccount($id);
function CheckIdAccount($id)
{
    if ($id == null) {
        echo json_encode(array('id' => $id, 'message' => "參數設定錯誤"),JSON_UNESCAPED_UNICODE); 
        exit();
    } 
    
    //判斷帳號是否存在
    $db = DB();
    $sql = "SELECT * FROM `record` WHERE `id`= :id ORDER BY `record`.`number` DESC";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    $data = $result->fetchAll();
    $bdId = $data[0]['id'];
  
    if ($id == null || $bdId != $id || !preg_match("/^[A-Za-z0-9]+$/",$id) ) {
        echo json_encode(array('id' => $id, 'message' => "沒有此帳號"),JSON_UNESCAPED_UNICODE); 
        exit();
    } else {
        echo json_encode(array('number' => $data),JSON_UNESCAPED_UNICODE); 
    }
}