<?php

session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
 

$id_code = substr(strip_tags(addslashes(trim($_POST['id_code']))),0,12);
$bitOdd = substr(strip_tags(addslashes(trim($_POST['bitOdd']))),0,3);
$bitDouble = substr(strip_tags(addslashes(trim($_POST['bitDouble']))),0,3);
$bitBig = substr(strip_tags(addslashes(trim($_POST['bitBig']))),0,3);
$bitSmall = substr(strip_tags(addslashes(trim($_POST['bitSmall']))),0,3);

$id = $_SESSION['ac_id'];
$date = date("Y-m-d");
$time = date("H:i:s");
$remark = "樂透遊戲--下注金額";
$type = "出款";



//判斷數字不能為0
if ($bitOdd == 0 && $bitDouble == 0 && $bitBig == 0 && $bitSmall == 0) {
    echo "<script>alert('尚未下注金額');</script>";
    header("Refresh:0.5; url = Main.php");
    exit();
}

//檢查數字
function checkNum($Num)
{
    if (!preg_match("/^([0-9]+)$/", $Num)) {
        echo "<script>alert('請填入數字');</script>";
        header("Refresh:0.5; url = Main.php");
        exit();
    }
}

checkNum($bitOdd);
checkNum($bitDouble);
checkNum($bitBig);
checkNum($bitSmall);

//金額不能大於500
if ($bitOdd > 500 || $bitDouble > 500 || $bitBig > 500 || $bitSmall > 500) {
    echo "<script>alert('單筆下注不能大於500');</script>";
    header("Refresh:0.5; url = Main.php");
    exit();
}

$money = $bitOdd + $bitDouble + $bitBig + $bitSmall;
doDesposit($id, $money, $remark, $type);
DoRecord($id_code, $id, $date, $time, $bitOdd, $bitDouble, $bitBig, $bitSmall);

function doDesposit($id, $money, $remark, $type)
{
    $url = 'https://rd2-kay-yu.c9users.io/BankSystem/API/Transfer';
    $fields = array(
        'id'=>urlencode($id),
        'remark'=>urlencode($remark),
        'money'=>urlencode($money),
        'type'=>urlencode($type)
    );
    
    foreach ($fields as $key=>$value) { 
        $fields_string .= $key.'='.$value.'&';
    }
    
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $showResult = json_decode($result, true); 
    curl_close($ch);
    echo $showResult['message'];
}



function DoRecord($id_code, $id, $date, $time, $bitOdd, $bitDouble, $bitBig, $bitSmall)
{
    //計算賠率
    $expBitOdd = floor($bitOdd * 1.5);
    $expBitDouble = floor($bitDouble * 1.5);
    $expBitBig = floor($bitBig * 1.5);
    $expBitSmall = floor($bitSmall * 1.5);
    
    
    if ($bitOdd != 0) {
        $db = DB();
        $sql = "INSERT INTO `AdminRecord`(`id_code`, `ac_id`, `date`, `time`,
            `play`, `playMoney`,`winMoney`) VALUES 
            (:id_code, :ac_id, :date, :time,'單數', :bitOdd, :expBitOdd)";
        $result = $db->prepare($sql);
        $result->bindParam(':id_code', $id_code);
        $result->bindParam(':ac_id', $id);
        $result->bindParam(':date', $date);
        $result->bindParam(':time', $time);
        $result->bindParam(':bitOdd', $bitOdd);
        $result->bindParam(':expBitOdd', $expBitOdd);
        $result->execute();
    }
    
    if ($bitDouble != 0) {
        $sql = "INSERT INTO `AdminRecord`(`id_code`, `ac_id`, `date`, `time`,
            `play`, `playMoney`,`winMoney`) VALUES 
            (:id_code, :ac_id, :date, :time,'雙數', :bitDouble, :expBitDouble)";
        $result = $db->prepare($sql);
        $result->bindParam(':id_code', $id_code);
        $result->bindParam(':ac_id', $id);
        $result->bindParam(':date', $date);
        $result->bindParam(':time', $time);
        $result->bindParam(':bitDouble', $bitDouble);
        $result->bindParam(':expBitDouble', $expBitDouble);
        $result->execute();
    }
    
    if ($bitBig != 0) {
        $sql = "INSERT INTO `AdminRecord`(`id_code`, `ac_id`, `date`, `time`,
            `play`, `playMoney`,`winMoney`) VALUES 
            (:id_code, :ac_id, :date, :time,'大', :bitBig, :expBitBig)";
        $result = $db->prepare($sql);
        $result->bindParam(':id_code', $id_code);
        $result->bindParam(':ac_id', $id);
        $result->bindParam(':date', $date);
        $result->bindParam(':time', $time);
        $result->bindParam(':bitBig', $bitBig);
        $result->bindParam(':expBitBig', $expBitBig);
        $result->execute();
    }
    
    if ($bitSmall != 0) {
        $sql = "INSERT INTO `AdminRecord`(`id_code`, `ac_id`, `date`, `time`,
            `play`, `playMoney`,`winMoney`) VALUES 
            (:id_code, :ac_id, :date, :time,'小', :bitSmall, :expBitSmall)";
        $result = $db->prepare($sql);
        $result->bindParam(':id_code', $id_code);
        $result->bindParam(':ac_id', $id);
        $result->bindParam(':date', $date);
        $result->bindParam(':time', $time);
        $result->bindParam(':bitSmall', $bitSmall);
        $result->bindParam(':expBitSmall', $expBitSmall);  
        $result->execute();
    }
    header("Refresh:0.5; url = Main.php"); 
}