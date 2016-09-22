<?php
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');

$id = substr(strip_tags(addslashes(trim($_POST['ac_id']))),0,20);
$pw = addslashes($_POST['ac_pw']);
$plen = strlen($pw);
checkAddUser($id, $pw, $plen);
AddUser($id);
function checkAddUser($id, $pw, $plen)
{
    //帳號檢查
    if (!preg_match("/^([A-Za-z0-9]+)$/", $id)) {
        echo "<script>alert('帳號必須由數字及英文組成');</script>";
        header("Refresh:0.5; url = ShowAddUserPage.php");

    } else {
        $db = DB();
        $sql = "SELECT `ac_id` FROM `admin` WHERE `ac_id` = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();
        $OriginalUser = $result->fetchAll();
        
        if ($id == $OriginalUser[0]['ac_id']) {
            echo "<script>alert('帳號重複');</script>";

            header("Refresh:0.5; url = ShowAddUserPage.php");
        } else {
            //密碼檢查
            if (!preg_match("/^([A-Za-z0-9]+)$/", $pw) || $plen < 6 || $plen > 15) {
                echo "<script>alert('密碼必須為6-15位的數字和字母的组合');</script>";
                header("Refresh:0.5; url = ShowAddUserPage.php");
            } else {
                $Sqlpw = md5($pw);
               
                $sql = "INSERT INTO `admin`(`ac_id`, `ac_pw`) VALUES (:id, :pw)";
                $result = $db->prepare($sql);
                $result->bindParam(':id', $id, PDO::PARAM_STR);
                $result->bindParam(':pw', $Sqlpw, PDO::PARAM_STR);
                $result->execute();
                $count = $result->rowCount();
                echo "<script>alert('新增成功');</script>";
                header("Refresh:0.5; url = Index.php");
            }
        }
    }
}

function AddUser($id)
{
    $url = 'https://rd2-kay-yu.c9users.io/BankSystem/API/addUser';
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
    $showResult = json_decode($result, true); 
    curl_close($ch);
    echo $showResult['message'];
    header("Refresh:0.5; url = Index.php");
}