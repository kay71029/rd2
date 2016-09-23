<?php 
session_start();
require("MySqlConnect.php");
date_default_timezone_set("Asia/Taipei");

$data = checkInitGame();

function checkInitGame()
{
    $db = DB();
    $sql = "SELECT * FROM `LotteryNews` WHERE `date` = CURDATE() AND `lottery` = ''
            AND `open_time` <= CURTIME()";
    $result = $db->prepare($sql);
    $result->execute();
    $data = $result->fetchAll();
    return $data;
}  

if($data != null) {
    if (isset($_POST['excGame'])) { 
        $mystring = "BackgroundGame";
        exec("ps aux | grep \"${mystring}\" | grep -v grep | awk '{ print $2 }' | head -1", $out);
        print "The PID is: " . $out[0];
        $pid = $out[0];
        exec("kill -9 $pid");
        pclose(popen('php /home/ubuntu/workspace/LotteryGame/BackgroundGame.php &', 'r'));
       
    }
}  

if($data != null) {
    if (isset($_POST['excCheck'])) { 
        $mystring = "BackgroundCheck";
        exec("ps aux | grep \"${mystring}\" | grep -v grep | awk '{ print $2 }' | head -1", $out);
        print "The PID is: " . $out[0];
        $pid = $out[0];
        exec("kill -9 $pid");
        pclose(popen('php /home/ubuntu/workspace/LotteryGame/BackgroundCheck.php &', 'r'));
       
    } 
}
 header("Location:ShowInitGame.php");