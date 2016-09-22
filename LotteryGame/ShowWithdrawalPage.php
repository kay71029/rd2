<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>簡易的彩卷系統</title>
    <meta name = "viewport" content = "width = device-width, initial-scale = 1">
    <link href = "assets/css/bootstrap.min.css" rel = "stylesheet">
</head>
<body onload="ShowTime()">
    <?php include("ShowMenu.php"); ?>
    <form method = "post" action  = "DoWithdrawal.php">
        <div class = "form-group input-group" id = "showbox">
        </div>
        <div class = "form-group input-group">
            <span class = "input-group-addon">金額</span>
            <input type = "number"class = "form-control"  aria-describedby = "basic-addon1" name = "money" min = "0">
        </div>
        <br>
        <button type = "submit" class = "btn btn-default navbar-btn" name = "saveOk">確認</button>
    </form>
    <script src = "assets/js/jquery.js"></script>
    <script src = "assets/js/bootstrap.min.js"></script>
    <script language="JavaScript" type="text/javascript">
        function ShowTime()
        {
            var NowDate = new Date();
            var d = NowDate.getDay();
            var dayNames = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            document.getElementById('showbox').innerHTML = '目前時間：' + NowDate.toLocaleString() + '（' + dayNames[d] + '）';
            setTimeout('ShowTime()', 1000);
        }
    </script>
</body>
</html>