<?php
session_start();
require("MySqlConnect.php");
date_default_timezone_set("Asia/Taipei");
ignore_user_abort(true);
set_time_limit(0);
function handle()
{
    $db = DB();
    $sql = "SELECT `AdminRecord`.`id`, `AdminRecord`.`date`, `AdminRecord`.`id_code`,
        `AdminRecord`.`play`,`AdminRecord`.`playMoney` , `AdminRecord`.`status` , 
        `AdminRecord`.`winMoney`, `AdminRecord`.`ac_id` , `LotteryNews`.`BigSmall` ,
        `LotteryNews`.`OddDouble` FROM `AdminRecord` 
        LEFT JOIN `LotteryNews` ON `LotteryNews`.`id_code` = `AdminRecord`.`id_code`
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
        $sql = "UPDATE `AdminRecord` SET `status`= :status WHERE `id` = :parms_id";
        $result = $db->prepare($sql);
        $result->bindParam(':status',$parms_status);
        $result->bindParam(':parms_id',$parms_id);
        $result->execute();
        
        if ($parms_status == '贏') {
            $id = $parms_ac_id;
            $money = $parms_winMoney;
            $remark = "樂透遊戲--獲利金額";
            $type = "入款";
            $url = 'https://rd2-kay-yu.c9users.io/BankSystem/API/Transfer';
            $fields = array(
                'id'=>urlencode($id),
                'remark'=>urlencode($remark),
                'money'=>urlencode($money),
                'type'=>urlencode($type)
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
            $showResult = json_decode($result, true); 
            curl_close($ch);
            echo $showResult['message'];
        }
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