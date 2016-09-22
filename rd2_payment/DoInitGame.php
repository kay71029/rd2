<?php
session_start();
require("MySqlCconnect.php");
header('Content-Type: text/html; charset=utf-8');
$times = $_POST["init-game-times"];
$start_time = $_POST["init-game-start-time"];
$open_time = $_POST["init-game-open_time"];
$fold_time = $_POST["init-game-fold_time"];
$stop_time = $_POST["init-game-stop_time"];
DoInitGame($times, $start_time, $open_time, $fold_time,$stop_time);
function DoInitGame($times, $start_time, $open_time, $fold_time, $stop_time)
{
    //判斷值不能是空值
    if ($times == null && $start_time == null && $open_time == null && $fold_time == null) {
        echo "<script>alert('新增失敗');</script>";
        header("Refresh:0.5; url = ShowInitGame.php"); 
        return;
    }
    
     //判斷次數 皆要大於0
    if ($times <= 0 || $open_time <= 0) {
        echo "<script>alert('請填正確的資料');</script>";
        header("Refresh:0.5; url = ShowInitGame.php");
        return;
    }
    
    try {
        for ($i = 1; $i <= $times; $i ++) {
            $parms_id_code = $id_code = date('Ymd', time()).sprintf("%'.04d\n", $i);
            $parms_date = date("Y-m-d");
            $parms_fold_time = date("H:i:s",strtotime("+ ".($i * ($fold_time + $open_time + $stop_time) - ($stop_time + $open_time))." minutes", strtotime($start_time)));
            $parms_open_time = date("H:i:s",strtotime("+ ".($i * ($fold_time + $open_time + $stop_time) - $stop_time)." minutes", strtotime($start_time)));
            $parms_stop_time = date("H:i:s",strtotime("+ ".$i * ($fold_time + $open_time + $stop_time)." minutes", strtotime($start_time)));
            try {
                $db = DB();
                $db->beginTransaction();
                $sql= "INSERT INTO `bank`.`LotteryNews` (`id_code`, `date`,`fold_time`, `open_time`, `stop_time`)
    	           VALUES (:id_code, :date, :fold_time, :open_time ,:stop_time)";
    	        $result = $db->prepare($sql);
    	        $result->bindParam(':id_code', $parms_id_code);
                $result->bindParam(':date', $parms_date);
                $result->bindParam(':fold_time', $parms_fold_time);
                $result->bindParam(':open_time', $parms_open_time);
                $result->bindParam(':stop_time', $parms_stop_time);
    			$result->execute();
                $db->commit();
            } catch (PDOException $e) {
                $db->rollBack();
                echo $e->getMessage();
            }
        }
        echo "<script>alert('新增成功');</script>";
        header("Refresh:0.5; url = ShowInitGame.php");
    } catch (PDOException $exp) {
        echo "<script>alert('新增失敗');</script>";
        header("Refresh:0.5; url = ShowInitGame.php");
    }
}