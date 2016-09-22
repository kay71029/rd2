<?php 

if ("addUser" == $_GET['url']) {
    include ("addUser.php");
} 

if ("getUserBalance" == $_GET['url']) {
    include ("getUserBalance.php");
} 

if ("AccountDetail" == $_GET['url']) {
    include ("AccountDetail.php");
} 

if ("Transfer" == $_GET['url'] ) {
    include ("Transfer.php");
}

if ("getUserAccount" == $_GET['url'] ) {
    include ("getUserAccount.php");
}
 