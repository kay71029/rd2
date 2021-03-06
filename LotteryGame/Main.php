<?php
session_start();
require("MySqlConnect.php");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Taipei");
$id = $_SESSION['ac_id'];
$account = SelectUserAccount($id);
$data2 = GetLastLotteryNews();
$data = ShowAdminRecord();
function SelectUserAccount($id)
{
    $url = 'https://rd2-kay-yu.c9users.io/BankSystem/API/getUserAccount';
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
    $account = $showResult['account'];
    return $account;
}
function GetLastLotteryNews()
{
    $db = DB();
    $sql = " SELECT IF((`open_time`-CURTIME()) <= 0,TRUE,FALSE) AS IS_OPEN,
        `id`, `id_code`, `date`, `fold_time`, `open_time`, `stop_time`, `lottery`
        FROM `LotteryNews`
        WHERE (`open_time`- CURTIME()) > 0 OR (`stop_time` - CURTIME()) > 0 AND `lottery` != '' AND `date` = CURDATE()
        ORDER BY (`open_time`- CURTIME()),(`stop_time`- CURTIME()) 
        LIMIT 1 ";
    $result = $db->prepare($sql);
    $result->execute();
    $data2 = $result->fetchAll();
    return $data2;
}
function ShowAdminRecord()
{
    $db = DB();
    $sql = "SELECT * FROM `AdminRecord` WHERE `ac_id` = :ac_id ORDER BY `AdminRecord`.`id` DESC LIMIT 4";
    $result = $db->prepare($sql);
    $result->bindParam(':ac_id', $_SESSION['ac_id']);
    $result->execute();
    $data = $result->fetchAll();
    return $data;
}
if ($data2 == null || $data[0]['lottery'] != '') {
    header("Location:ShowRestPage.php");
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
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript">  
        $(function(){  
            var bitOdd = $("#bitOdd");
            var bitDouble = $("#bitDouble");
            var bitBig = $("#bitBig");
            var bitSmall = $("#bitSmall");
            var tatal = $("#tatal");
            bitOdd.change(function(){
                var num1 = bitOdd.val();
                var num2 = bitDouble.val();
                var num3 = bitBig.val();
                var num4 = bitSmall.val();
                var sum = (num1-0) + (num2-0) + (num3-0) + (num4-0);
                tatal.text(sum);  
            });
            bitDouble.change(function(){
                var num1 = bitOdd.val();
                var num2 = bitDouble.val();
                var num3 = bitBig.val();
                var num4 = bitSmall.val();
                var sum = (num2-0) + (num1-0) + (num3-0) + (num4-0) ;
                tatal.text(sum);  
            }); 
            bitBig.change(function(){
                var num1 = bitOdd.val();
                var num2 = bitDouble.val();
                var num3 = bitBig.val();
                var num4 = bitSmall.val();
                var sum = (num2-0) + (num1-0) + (num3-0) + (num4-0) ;
                tatal.text(sum);  
            });  
            bitSmall.change(function(){  
                var num1 = bitOdd.val();
                var num2 = bitDouble.val();
                var num3 = bitBig.val();
                var num4 = bitSmall.val();
                var sum = (num2-0) + (num1-0) + (num3-0) + (num4-0) ;
                tatal.text(sum);  
            });  
        });
    </script>
<body>
    <?php include("ShowMenu.php"); ?>
    <div class="container" id='page_header'>
        <div class="starter-template">
            <p class="lead" id="clock_title">倒數時間<br></p>
            <div class="panel panel-default" data-toggle="tooltip" data-placement="top">
                <div class="panel-body">
                    <div class="lead" id="clock"></div>
                </div>
            </div>
            <input class="btn btn-primary" type="button" onclick="$('#showrules').toggle();" value="規則說明">
            <div id="showrules" style="display:none">
            <br>
            <br>
            <p class="lead">規則說明: 隨機取出5個範圍1~9且不重複的數字之總和設定玩法<br></p>
            <p class="lead">玩法一: 押注單數/雙數 賠率1.5<br></p>
            <p class="lead">玩法二: 押注比大小 大於20為大 賠率1.5<br></p>
            </div>
        </div>
    </div>
    <div class = "panel panel-default" id='page_body'>
        <div id="periodic_timer_minutes"></div>
        <?php foreach($data2 as $row2){?>
        <div class = "panel-heading">
             第<?PHP echo $row2['id_code']; ?>期 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;開獎時間:<?PHP echo $row2['open_time']; ?> 
        </div>
        <?php }?>
        <div class = "panel-heading">
            GAME ------您的額度：<?PHP echo $account; ?>
        </div>
        <div class = "panel-body">
            <div class = "dataTable_wrapper">
                <table width = "100%" class = "table table-striped table-bordered table-hover" id = "dataTables-example">
                    <thead>
                    <tr align="center">
                        <th align = "center" width = 40% colspan = 2>玩法一</th>
                        <th align = "center" width = 40% colspan = 2>玩法二</th>
                        <th></th>
                    </tr>
                    <tr align="center">
                        <th>單數</th>
                        <th>雙數</th>
                        <th>大</th>
                        <th>小</th>
                        <th>總金額</th>
                    </tr>
                    </thead>
                    <thead>
                    <form method = "post" action  = "DoRecord.php" id ="myform">
                    <tr align="center">
                        <th><input type = "number" class = "form-control"  aria-describedby = "basic-addon1" name = "bitOdd"  id = "bitOdd" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0"></th>
                        <th><input type = "number" class = "form-control"  aria-describedby = "basic-addon1" name = "bitDouble" id = "bitDouble" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0"></th>
                        <th><input type = "number" class = "form-control"  aria-describedby = "basic-addon1" name = "bitBig" id = "bitBig" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0"></th>
                        <th><input type = "number" class = "form-control"  aria-describedby = "basic-addon1" name = "bitSmall" id = "bitSmall" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0"></th>
                        <th><span id="tatal" style="color:red"></span>  </th>
                    </tr>
                    </thead>
                    </tbody>
                </table>
                        <button type = "submit" class = "btn btn-default navbar-btn" name = "bitOk" id = "bitOk">下注</button>
                        <input type = "hidden" name = "date" value = "<?php echo $date = date("Y-m-d");?>">
                        <input type = "hidden" name = "time" value = "<?php echo $time = date("H:i:s");?>">
                        <input type = "hidden" name = "id_code" value = "<?PHP echo $row2['id_code']; ?>">
                        <input type = "hidden" name = "ac_acount" value = "<?PHP echo $row['ac_acount']; ?>">
                    </form>
            </div>
            <br>
            <br>
        <div class = "panel panel-default">
        <div id="periodic_timer_minutes"></div>
        <div class = "panel-heading">下注記錄----最新4筆資料</div>
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
                        <th>可贏金額</th>
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
        </div>
    </div>
    <script src = "assets/js/jquery.js"></script>
    <script src = "assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.syotimer.min.js"></script>
    <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js"></script>
    <style type="text/css">
        .starter-template {
          padding: 40px 15px;
          text-align: center;
        }
        .panel {
          width: auto;
          margin-left: auto;
          margin-right: auto;
        }
        #btn-reset {
          margin-right: 10px;
        }
        #clock {
          margin-bottom: 0;
        }
    </style>
    <script type="text/javascript">
    
    $(function() {
        
        $('[data-toggle="tooltip"]').tooltip();
            //確認是否已開獎
            var is_open = (<?PHP echo $data2['0']['IS_OPEN'] ?> > 0) ? true : false;
            console.log('是否已經開獎-->'+is_open);//fordebug
            //根據是否開獎決定要以那個時間倒數
            var targetDateTime = new Date();
            if (is_open) {
                $('#clock_title').text("已開獎，系統封牌中。請稍後再下注。");
                $('#bitOk').prop('disabled',true);
                targetDateTime = new Date(<?PHP echo '\''.$data2['0']['date'].' '.$data2['0']['stop_time'].'\''; ?>);
            } else {
                $('#clock_title').text("下注時間倒數");
                $('#bitOk').prop('disabled',false);
                targetDateTime = new Date(<?PHP echo '\''.$data2['0']['date'].' '.$data2['0']['open_time'].'\''; ?>);
            }
            console.log(new Date(targetDateTime)); //fordebug
        
            //使用 count down 套件倒數
            var $clock = $('#clock');
            $clock.countdown(targetDateTime,function(event) {
                $(this).html(event.strftime('%M:%S'));
            })
            .on('finish.countdown', function(event){
                //尚未開獎-->開獎   (跳出訊息之類的可以寫在這裡)
                if(!is_open){
                     $('#bitOk').prop('disabled',false);
                }

                console.log("finish.countdown! please wait for 5 second...");
                //reload after 5 second
                setTimeout(function(){
                   //重載畫面 
                   location.reload();
                }, 2500);  //2.500 = 2.5 second 
                
            });
    }); //end for jquery main function
    </script>
</body>
</html>