<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
$id = $_SESSION['ac_id'];
$result2 = AccountDetail($id);

function AccountDetail($id)
{
    $url = 'https://rd2-kay-yu.c9users.io/BankSystem/API/AccountDetail';
    $fields = array(
        'id'=>urlencode($id),
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
    $result2 = json_decode($result, true); 
    curl_close($ch);
    return $result2['number'];
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
        交易明細
    </div>
    <div class = "panel-body">
        <div class = "dataTable_wrapper">
            <table width = "100%" class = "table table-striped table-bordered table-hover" id = "dataTables-example">
                <thead>
                <tr>
                    <th>交易序號</th>
                    <th>日期</th>
                    <th>時間</th>
                    <th>入款/出款</th>
                    <th>交易明細</th>
                    <th>原帳戶金額</th>
                    <th>交易金額</th>
                    <th>餘額</th>
                </tr>
                </thead>
                <tbody>
                 <?php foreach($result2 as $row){?> 
                    <tr class = "odd gradeX">
                        <td><?PHP echo $row['number']; ?></td>
                        <td><?PHP echo $row['date']; ?></td>
                        <td><?PHP echo $row['time']; ?></td>
                        <td><?PHP echo $row['type']; ?></td>
                        <td><?PHP echo $row['remark']; ?></td>
                        <td><?PHP echo $row['blance']; ?></td>
                        <td><?PHP echo $row['money']; ?></td>
                        <td><?PHP echo $row['newBlance']; ?></td>
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