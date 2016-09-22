<?php

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
require('MySqlConnect.php');

$id = $_POST["id"];
checkUserAccount($id);
userAccount($id);
function checkUserAccount($id)
{
    
    if ($id == null) {
        echo json_encode(array('id' => $id, 'message' => "沒有該帳戶"),JSON_UNESCAPED_UNICODE); 
        exit();
    }
    
}

function userAccount($id)
{
    $db = DB();
    $sql = "SELECT `account` FROM `user` WHERE `id`= '$id'";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    $data = $result->fetch();
    $account = $data['account'];
    echo json_encode(array('id' => $id, 'account' => $account),JSON_UNESCAPED_UNICODE);

}