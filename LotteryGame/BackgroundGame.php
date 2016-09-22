<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset = utf-8');
date_default_timezone_set("Asia/Taipei");
ignore_user_abort(true);
set_time_limit(0);
function getLotteryNumArray() 
{
    $db = DB();
    $sql = "SELECT `number` FROM `ball` WHERE `number` ORDER BY RAND() LIMIT 5";
    $result = $db->prepare($sql);
    $result->execute();
    $data = $result->fetchAll(PDO::FETCH_ASSOC); 
    $number = array();
    
    foreach($data as $row) {
        $number[] = $row['number'];
    }
    
    sort($number);
    return $number;
}
function GetOpenTime()
{
	$db = DB();
	$sql = " SELECT IF((`open_time`-CURTIME())<=0,TRUE,FALSE) AS IS_OPEN,
        (`open_time`-CURTIME()) AS OPEN_DIFF, (`stop_time`-CURTIME()) AS STOP_DIFF, 
        `id`, `id_code`, `date`, `fold_time`, `open_time`, `stop_time`, `lottery`
        FROM `LotteryNews`
        WHERE (`open_time`-CURTIME())>0 OR (`stop_time`-CURTIME())>0 AND `lottery` !='' AND `date` = CURDATE()
        ORDER BY OPEN_DIFF,STOP_DIFF
        LIMIT 1";
	$result = $db->prepare($sql);
	$result->execute();
	return $result->fetchAll();
}
function countDown($totalTime)
{
    $time = 0;
    while ($time <= $totalTime) {
        $db = DB();
        $sql = "SELECT * FROM `LotteryNews` WHERE `date` = CURDATE() AND `lottery` = ''
            AND `open_time` <= CURTIME()"; 
        $result = $db->prepare($sql);
        $result->execute();
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
         
        foreach ($data as $row) {
            //get id_code //key
            $parms_id_code = $row['id_code']; 
            //開獎 //get [1 2 3 4]
            $lotteryArray = getLotteryNumArray(); 
            //數值總和
            $parms_sum = 0;
            foreach ($lotteryArray as $num) {
                $parms_sum += $num;  
            }
            //樂透值
            $parms_lottery = implode(",",$lotteryArray);
            //判斷大小 雙單數
            $parms_OddDouble = ($parms_sum % 2 == 0) ? '雙數':'單數';
            $parms_BigSmall = ($parms_sum > 20) ? '大':'小';
            $sql = "UPDATE `LotteryNews` SET `lottery`= :lottery,
                `sum` = :sum, `OddDouble` = :OddDouble , `BigSmall` = :BigSmall
                , `status` = '已開獎' where `id_code` = :id_code ";
            $result = $db->prepare($sql);
            $result->bindParam(':id_code',$parms_id_code);
            $result->bindParam(':lottery',$parms_lottery);
            $result->bindParam(':sum',$parms_sum);
            $result->bindParam(':OddDouble',$parms_OddDouble);
            $result->bindParam(':BigSmall',$parms_BigSmall);
            $result->execute();
        }
        flush();
        ob_flush();
        sleep(GetOpenTime()[0]['OPEN_TIME']);
        $time++;
    }
}
countDown(1440000);