<?php
session_start();
require("MySqlConnect.php");
date_default_timezone_set("Asia/Taipei");
$data = SelectUserAccount();
$data2 = GetLastLotteryNews();
function SelectUserAccount()
{
    $db = DB();
    $sql = "SELECT `ac_acount` FROM `admin` WHERE `ac_id` = :ac_id";
    $result = $db->prepare($sql);
    $result->bindParam(':ac_id', $_SESSION['ac_id']);
    $result->execute();
    $data = $result->fetchAll();
    return $data;
}
function GetLastLotteryNews()
{
    $db = DB();
    $sql = " SELECT IF((`open_time`-CURTIME()) <= 0,TRUE,FALSE) AS IS_OPEN,
        `id`, `id_code`, `date`, `fold_time`, `open_time`, `stop_time`, `lottery`
        FROM `LotteryNews`
        WHERE (`open_time`- CURTIME()) > 0 OR (`stop_time` - CURTIME()) > 0 AND `lottery` != '' AND `date` = CURDATE()
        ORDER BY (`open_time`- CURTIME()),(`stop_time`- CURTIME()) 
        LIMIT 1 ";
    $result = $db->prepare($sql);
    $result->execute();
    $data2 = $result->fetchAll();
    return $data2;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>簡易的彩卷系統</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link href = "assets/css/bootstrap.min.css" rel = "stylesheet">
</head>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
<body>
    <?php include("ShowMenu.php"); ?>
        <div class="container" id='page_header'>
        </div>
        <div class = "panel panel-default" id='page_body'>
            <div id="periodic_timer_minutes"></div>
            <div class = "panel-heading">最新消息</div>
            <div class = "panel-body">
                <div class = "dataTable_wrapper">
                    <h1 align = 'center'>本日開獎已結束<h1>
                </div>
            </div>
        </div>
    <script src = "assets/js/jquery.js"></script>
    <script src = "assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.1.7.2.js"></script>
    <script type="text/javascript" src="assets/js/jquery.syotimer.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
</body>
</html>