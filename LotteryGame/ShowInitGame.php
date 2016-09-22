<?php
session_start();
require("MySqlConnect.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8">
    <title>簡易的彩卷系統</title>
    <meta name = "viewport" content = "width = device-width, initial-scale = 1">
    <link href = "assets/css/bootstrap.min.css" rel = "stylesheet">
</head>
<body>
    <?php include("ShowMenu.php"); ?>
    <div class = "panel panel-default">
        <div class = "panel-heading">
            設定開獎期數
        </div>
        <div class = "panel-body">
            <div class = "dataTable_wrapper">
                <form method = "post" action = "DoInitGame.php">
                    <div class = "form-group row">
                        <label for = "init-game-times" class = "col-xs-2 col-form-label">開獎期數</label>
                        <div class = "col-xs-10">
                            <input type = "number" class = "form-control" name = "init-game-times" placeholder = "請輸入要開啟的期數" pattern = "[0-9]">
                        </div>
                    </div>
                    <div class = "form-group row">
                        <label for = "init-game-start-time" class = "col-xs-2 col-form-label">開始時間</label>
                        <div class = "col-xs-10">
                            <input class = "form-control" type = "time" value = "now" name = "init-game-start-time">
                        </div>
                    </div>
                    <div class = "form-group row">
                        <label for = "init-game-open_time" class = "col-xs-2 col-form-label">開始至封牌的時間(單位:分鐘)</label>
                        <div class = "col-xs-10">
                            <input type = "number" value = '5' class = "form-control" name = "init-game-open_time" placeholder = "請輸入開始至封牌的時間" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0">
                        </div>
                    </div>
                    <div class = "form-group row" style="display:none">
                        <label for = "init-game-fold_time" class = "col-xs-2 col-form-label">封牌至開獎時間(單位:分鐘)</label>
                        <div class = "col-xs-10">
                            <input type = "number" value = '0' class = "form-control" name = "init-game-fold_time" placeholder = "請輸入封牌至開獎時間" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0">
                        </div>
                    </div>
                     <div class = "form-group row">
                        <label for = "init-game-stop_time" class = "col-xs-2 col-form-label">停止時間(單位:分鐘)</label>
                        <div class = "col-xs-10">
                            <input type = "number" value = '1' class = "form-control" name = "init-game-stop_time" placeholder = "停止時間" pattern = "[0-9]" title = "請輸入數字" required="required" min = "0">
                        </div>
                    </div>
                    <button type = "submit" class = "btn btn-primary">存檔</button>
                </form>
            </div>
        </div>
    </div>
    <script src = "assets/js/jquery.js"></script>
    <script src = "assets/js/bootstrap.min.js"></script>
    <script type = "text/javascript">
        $(function(){  
            $('input[type="time"][value="now"]').each(function(){    
                var d = new Date(),
                    h = d.getHours(),
                    m = d.getMinutes();
                if(h < 10) h = '0' + h;
                if(m < 10) m = '0' + m;
            $(this).attr({
              'value': h + ':' + m
            });
            });
        });
    </script>
    <style type="text/css">
    input{
      font-size: 3em;
    }
    </style>
</body>
</html>