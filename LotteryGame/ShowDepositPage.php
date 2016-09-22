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
<nav class = "navbar navbar-inverse" align = right>
    <div class = "container-fluid">
        <div class = "navbar-header">
            <button type = "button" class = "navbar-toggle collapsed" data-toggle="collapse" data-target = "#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class = "sr-only">Toggle navigation</span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>
            <a class = "navbar-brand" href="Main.php">首頁</a>
        </div>
        <div class = "collapse navbar-collapse" id = "bs-example-navbar-collapse-1">
            <ul class = "nav navbar-nav">
                <li class = "active"><a href = "ShowDepositPage.php">入款<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowWithdrawalPage.php">出款<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowAccountDetailPage.php">查詢明細<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowGamePage.php">開獎資訊<span class = "sr-only">(current)</span></a></li>
            </ul>
            <form action = "Logout.php">
                <button class = "btn btn-default navbar-btn">登出</button>
            </form>
        </div>
    </div>
</nav>
    <form method = "post" action  = "DoDeposit.php">
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