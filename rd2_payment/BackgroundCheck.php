<?php
session_start();
require("MySqlCconnect.php");
date_default_timezone_set("Asia/Taipei");
ignore_user_abort(true);
set_time_limit(0);
function handle()
{
    $db = DB();
    $sql = "SELECT `AdminRecord`.`id`, `AdminRecord`.`date`, `AdminRecord`.`id_code`, 
        `AdminRecord`.`play`,`AdminRecord`.`playMoney` , `AdminRecord`.`status` , 
        `AdminRecord`.`winMoney`, `AdminRecord`.`ac_id` , `LotteryNews`.`BigSmall` , 
        `LotteryNews`.`OddDouble`, `admin`.`ac_acount` FROM `AdminRecord` 
        LEFT JOIN `LotteryNews` ON `LotteryNews`.`id_code` = `AdminRecord`.`id_code`
        LEFT JOIN `admin` on `admin`.`ac_id` =`AdminRecord`.`ac_id`
        WHERE `AdminRecord`.`status` = '未結清' AND `LotteryNews`.`lottery` <> ''";
    $result = $db->prepare($sql);
    $result->execute();
    $data = $result->fetchAll();
    
    foreach ($data as $row) {
        $play = $row['play'];
        $parms_id = $row['id'];;
        $parms_winMoney = $row['winMoney'];
        $parms_status = '未結清';
        $parms_ac_acount = $row['ac_acount'];
        $parms_ac_id = $row['ac_id'];
        switch ($play) {
            case "單數":
                $parms_status = ($row['OddDouble'] == '雙數') ? '輸':'贏';
                break;
            case "雙數":
                $parms_status = ($row['OddDouble'] == '單數') ? '輸':'贏';
                break;
            case "大":
                $parms_status = ($row['BigSmall'] == '小') ? '輸':'贏';
                break;
             case "小":
                $parms_status = ($row['BigSmall'] == '大') ? '輸':'贏';
                break;;
            default:
                  echo "<script>alert('無法判斷');</script>";
                break;
        }
        
        if ($parms_status == '贏') {
            try {
                $db = DB();
                $db->beginTransaction();
                $sql = "UPDATE `admin` SET `ac_acount` = `ac_acount`+ :ac_acount WHERE `ac_id` = :ac_id";
                $result = $db->prepare($sql);
                $new_ac_account = $parms_ac_acount + $parms_winMoney;
                $result->bindParam(':ac_acount',$parms_winMoney);
                $result->bindParam(':ac_id',$parms_ac_id);
                $result->execute();
                  $db->commit();
                } catch (PDOException $e) {
                    $db->rollBack();
                    echo $e->getMessage();
                }
        }

        $sql = "UPDATE `AdminRecord` SET `status`= :status WHERE `id` = :parms_id";
        $result = $db->prepare($sql);
        $result->bindParam(':status',$parms_status);
        $result->bindParam(':parms_id',$parms_id);
        $result->execute();
    }
}
//core
function countDown($totalTime)
{
    $time = 0;
    while ($time < $totalTime) {
        handle();
        sleep(10);  //10sec. 
        $time++;
    }
}
countDown (1440000); //6mins x 40 times