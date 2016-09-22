<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');

$id = $_SESSION['ac_id'];
$money = $_POST["money"];
$remark = "樂透遊戲--金額入款";
$type = "入款";

doDesposit($id, $money, $remark, $type);

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
    header("Refresh:0.5; url = ShowAccountDetailPage.php");
}