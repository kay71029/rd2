<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
$data = ShowAdminRecord();
function ShowAdminRecord()
{
     $db = DB();
    $sql = "SELECT * FROM `AdminRecord` WHERE `ac_id` = :ac_id ORDER BY `id` DESC";
    $result = $db->prepare($sql);
    $result->bindParam(':ac_id', $_SESSION['ac_id']);
    $result->execute();
    $data = $result->fetchAll();
    return $data;
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
    <?php include("ShowMenu.php"); ?>
    <div class = "panel panel-default">
        <div id="periodic_timer_minutes"></div>
        <div class = "panel-heading">
            下注記錄
        </div>
        <div class = "panel-body">
            <div class = "dataTable_wrapper">
                <table width = "100%" class = "table table-striped table-bordered table-hover" id = "dataTables-example">
                    <thead>
                    <tr>
                        <th>序號</th>
                        <th>期別</th>
                        <th>日期</th>
                        <th>下注時間</th>
                        <th>玩法</th>
                        <th>下注金額</th>
                        <th>期望值</th>
                        <th>狀態</th>
                    </tr>
                    </thead>
                    <thead>
                    <?php foreach($data as $row){?>
                    <tr>
                        <td><?PHP echo $row['id']; ?></td>
                        <td><?PHP echo $row['id_code']; ?></td>
                        <td><?PHP echo $row['date']; ?></td>
                        <td><?PHP echo $row['time']; ?></td>
                        <td><?PHP echo $row['play']; ?></td>
                        <td><?PHP echo $row['playMoney']; ?></td>
                        <td><?PHP echo $row['winMoney']; ?></td>
                        <td><?PHP echo $row['status']; ?></td>
                    </tr>
                    <?php }?>
                    </thead>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src = "assets/js/jquery.js"></script>
    <script src = "assets/js/bootstrap.min.js"></script>
</body>
</html>