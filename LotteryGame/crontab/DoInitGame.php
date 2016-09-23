<?php
require("/home/ubuntu/workspace/LotteryGame/MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
$times = 40;
$start_time = date("H:i:s");
$open_time = 5;
$fold_time = 0;
$stop_time = 1;
function doInitGame($times, $start_time, $fold_time, $open_time, $stop_time)
{
    for ($i = 1; $i <= $times; $i ++) {
        $parms_id_code = $id_code = date('Ymd', time()).sprintf("%'.04d\n", $i);
        $parms_date = date("Y-m-d");
        $parms_fold_time = date("H:i:s",strtotime("+ ".($i * ($fold_time + $open_time + $stop_time) - ($stop_time + $open_time))." minutes", strtotime($start_time)));
        $parms_open_time = date("H:i:s",strtotime("+ ".($i * ($fold_time + $open_time + $stop_time) - $stop_time)." minutes", strtotime($start_time)));
        $parms_stop_time = date("H:i:s",strtotime("+ ".$i * ($fold_time + $open_time + $stop_time)." minutes", strtotime($start_time)));
        
        try {
            $db = DB();
            $db->beginTransaction();
            $sql= "INSERT INTO `lottery`.`LotteryNews` (`id_code`, `date`,`fold_time`, `open_time`, `stop_time`)
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
}