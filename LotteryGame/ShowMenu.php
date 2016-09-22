<nav class = "navbar navbar-inverse" align = right>
    <div class = "container-fluid">
        <div class = "navbar-header">
            <button type = "button" class = "navbar-toggle collapsed" data-toggle="collapse" data-target = "#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class = "sr-only">Toggle navigation</span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
                <span class = "icon-bar"></span>
            </button>
            <a class = "navbar-brand" href="Main.php">首頁</a>
        </div>
        <div class = "collapse navbar-collapse" id = "bs-example-navbar-collapse-1">
            <ul class = "nav navbar-nav">
                <li class = "active"><a href = "ShowDepositPage.php">入款<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowWithdrawalPage.php">出款<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowAccountDetailPage.php">查詢明細<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowGamePage.php">開獎資訊<span class = "sr-only">(current)</span></a></li>
                <li class = "active"><a href = "ShowAdminRecord.php">下注紀錄<span class = "sr-only">(current)</span></a></li>
                <?php if($_SESSION['ac_limit'] == '1'):?>
                <li class = "active"><a href = "ShowInitGame.php">開獎設定<span class = "sr-only">(current)</span></a></li>
                <?php endif ?>
            </ul>
            <form action = "Logout.php">
                <button class = "btn btn-default navbar-btn">登出</button>
            </form>
        </div>
    </div>
</nav>