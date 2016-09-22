<?php

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
require('MySqlConnect.php');

$id = $_POST["id"];
$date = date("Y-m-d");
$time = date("H:i:s");
$remark = $_POST["remark"];
$type = $_POST["type"];
$money = $_POST["money"];

checkNull($id, $remark, $type, $money);
checkUser($id);
checkType($type);
checkMoney($money);
transfer($id, $date, $time, $remark, $type, $money);


//判斷參數值是否漏填
function checkNull($id, $remark, $type, $money)
{
    
    if ($id == null || $type == null || $money == null ||  $remark == null) { 
        echo json_encode(array('id' => $id, 'type' => $type, 'remark' => $remark, 'money' => $money,
        'message' => "參數值漏填"),
        JSON_UNESCAPED_UNICODE);
        exit();
    }
    
}

//確認帳號
function checkUser($id)
{
    $db = DB();
    $sql = "SELECT `id` FROM `user` WHERE `id` = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id);
    $result->execute();
    $data = $result->fetch();
    $dbId = $data['id'];
    
    if ($dbId != $id) {
        echo json_encode(array('message' => "沒有此帳號"),JSON_UNESCAPED_UNICODE);
        exit();
    }
    
}

//判斷存款代號
function checkType($type)
{
    
    if ($type != "入款" && $type != "出款") {
        echo json_encode(array('message' => "入款出款代號錯誤"),JSON_UNESCAPED_UNICODE);
        exit();
    }
    
}

 //判斷金錢格式
function checkMoney($money)
{
    
    if (!is_numeric($money)) {
        echo json_encode(array('message' => "數字格式錯誤"),JSON_UNESCAPED_UNICODE);
        exit();
    }
    
}

function transfer($id, $date, $time, $remark, $type, $money)
{
    switch($type)
    {
        case "入款":
            if ($money > 0) {
                $db = DB();
                try {
                    $db->beginTransaction();
                    $sql = "SELECT `account` FROM `user` WHERE `id` = :id FOR UPDATE";
                    $result = $db->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->execute();
                    $data = $result->fetch();
                    $blance = $data['account'];
                    $newBlance = $blance + $money;
                    $sql = "INSERT INTO `record`(`id`, `date`, `time`, `remark`, `type`, `money`, `blance`, `newBlance`) 
                        VALUES (:id, :date, :time, :remark, :type ,:money ,:blance , :newBlance)";
                    $result = $db->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':date', $date);
                    $result->bindParam(':time', $time);
                    $result->bindParam(':remark', $remark);
                    $result->bindParam(':type', $type);
                    $result->bindParam(':money', $money);
                    $result->bindParam(':blance', $blance);
                    $result->bindParam(':newBlance', $newBlance);
                    $result->execute();
                    $sql = "UPDATE `user` SET `account` = `account` + :money WHERE `id` = :id";
                    $result = $db->prepare($sql);
                    $result->bindParam(':money', $money);
                    $result->bindParam(':id', $id);
                    $result->execute();
                    $db->commit();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    $db->rollBack();
                }
                echo json_encode(array('id' => $id, 'date' => $date, 'time' => $time, 'remark' => $remark, 
                'type' => $type, 'blance' => $blance, 'money' => $money, 'newBlance' => $newBlance, 'message' => "入款成功"),JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                echo json_encode(array('id' => $id, 'date' => $date, 'time' => $time, 'remark' => $remark, 
                'type' => $type, 'blance' => $blance, 'money' => $money, 'newBlance' => $newBlance, 'message' => "入款失敗"),JSON_UNESCAPED_UNICODE);
            }
        case "出款":
            if ($money > 0) {
                $db = DB();
                try {
                    $db->beginTransaction();
                    $sql = "SELECT `account` FROM `user` WHERE `id` = :id FOR UPDATE";
                    $result = $db->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->execute();
                    $data = $result->fetch();
                    $blance = $data['account'];
                    $newBlance = $blance - $money;
                    
                    if ($money > $blance) {
                      echo json_encode(array('id' => $id, 'type' => $type,'money' => $money,'message' => "餘額不足"),JSON_UNESCAPED_UNICODE); 
                      exit();
                    }
                
                    $sql = "INSERT INTO `record`(`id`, `date`, `time`, `remark`, `type`, `money`, `blance`, `newBlance`) 
                            VALUES (:id, :date, :time, :remark, :type ,:money ,:blance , :newBlance)";
                    $result = $db->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':date', $date);
                    $result->bindParam(':time', $time);
                    $result->bindParam(':remark', $remark);
                    $result->bindParam(':type', $type);
                    $result->bindParam(':money', $money);
                    $result->bindParam(':blance', $blance);
                    $result->bindParam(':newBlance', $newBlance);
                    $result->execute();
                    $sql = "UPDATE `user` SET `account` = `account` - :money WHERE `id` = :id" ;
                    $result = $db->prepare($sql);
                    $result->bindParam(':money', $money);
                    $result->bindParam(':id', $id);
                    $result->execute();
                    $db->commit();
                } catch (PDOException $e) {
                    $db->rollBack();
                    echo $e->getMessage();
                }
                echo json_encode(array('id' => $id, 'date' => $date, 'time' => $time, 'remark' => $remark, 
                'type' => $type, 'blance' => $blance, 'money' => $money, 'newBlance' => $newBlance, 'message' => "出款成功"),
                JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array('id' => $id, 'date' => $date, 'time' => $time, 'remark' => $remark, 
            'type' => $type, 'blance' => $blance, 'money' => $money, 'newBlance' => $newBlance, 'message' => "出款失敗"),JSON_UNESCAPED_UNICODE);
        }
    }
}