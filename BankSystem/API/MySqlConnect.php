<?php
function DB() 
{
    $dbType = 'mysql';
    $dbHost = 'localhost';
    $dbName = 'bank';
    $dbUser = 'root';
    $dbPassword = '';
     
    try {
        $db = new PDO($dbType . ':host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        $db->query('SET NAMES UTF8');
        return $db;
        date_default_timezone_set("Asia/Taipei");
    } catch (PDOException $e) {
        echo 'Error!:' . $e->getMessage() . '<br />';
        // return false;
    }
}