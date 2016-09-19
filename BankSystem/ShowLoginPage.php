<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1">
    <meta name = "description" content = "">
    <meta name = "author" content = "">
    <title>簡易的銀行系統</title>
    <link href = "assets/css/bootstrap.min.css" rel = "stylesheet">
    <link href = "assets/metisMenu.min.css" rel = "stylesheet">
    <link href = "assets/css/sb-admin-2.css" rel = "stylesheet">
    <link href = "assets/css/font-awesome.min.css" rel = "stylesheet" type = "text/css">
</head>
<body>
<div class = "container">
    <div class = "row">
        <div class = "col-md-4 col-md-offset-4">
            <div class = "login-panel panel panel-default">
                <div class = "panel-heading">
                    <h3 class = "panel-title">Please Sign In</h3>
                </div>
                <div class = "panel-body">
                    <form action = "CheckLogin.php" method = "post">
                        <fieldset>
                            <div class = "form-group">
                                <input class = "form-control" placeholder = "Username" name = "ac_id" type = "" autofocus>
                            </div>
                            <div class = "form-group">
                                <input class = "form-control" placeholder = "Password" name = "ac_pw" type = "password" value = "">
                            </div>
                            <div class = "checkbox">
                                <br>
                            </div>
                            <button type = "submit" class = "btn btn-lg btn-success btn-block">Login</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src = "assets/jquery.min.js"></script>
<script src = "assets/js/bootstrap.min.js"></script>
<script src = "assets/metisMenu.min.js"></script>
<script src = "assets/js/sb-admin-2.js"></script>
</body>
</html>