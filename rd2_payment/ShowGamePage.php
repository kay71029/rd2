<?php
session_start();
require("MySqlCconnect.php");

function ShowGame()
{
    $db = DB();
    $sql = "SELECT * FROM  `LotteryNews` ORDER BY  `id` ASC ";
    $result = $db->prepare($sql);
    $result->execute();
    $data = $result->fetchAll();
    return $data;
}
$data = ShowGame();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>簡易的彩卷系統</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link href = "assets/css/bootstrap.min.css" rel = "stylesheet">
</head>
<body>
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
<div class = "panel panel-default">
    <div class = "panel-heading">
        開獎資訊
    </div>
    <div class = "panel-body">
        <div class = "dataTable_wrapper">
            <table width = "100%" class = "table table-striped table-bordered table-hover" id = "dataTables-example">
                <thead>
                <tr>
                    <th>期別</th>
                    <th>日期</th>
                    <th>下注時間起</th>
                    <th>開獎時間</th>
                    <th>開獎號碼</th>
                    <th>總和</th>
                    <th>單數/雙數</th>
                    <th>大/小</th>
                    <th>狀態</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data as $row){?>
                    <tr class = "odd gradeX">
                        <td><?PHP echo $row['id_code']; ?></td>
                        <td><?PHP echo $row['date']; ?></td>
                        <td><?PHP echo $row['fold_time']; ?></td>
                        <td><?PHP echo $row['open_time']; ?></td>
                        <td><?PHP echo $row['lottery']; ?></td>
                        <td><?PHP echo $row['sum']; ?></td>
                        <td><?PHP echo $row['OddDouble']; ?></td>
                        <td><?PHP echo $row['BigSmall']; ?></td>
                        <td><?PHP echo $row['status']; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src = "assets/js/jquery.js"></script>
<script src = "assets/js/bootstrap.min.js"></script>
</body>
</html>