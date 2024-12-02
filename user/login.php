<?php
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    $message = "";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(COM_HEAD_USER_HTML) ?>
</head>
<body>
    <?php include(COM_HEADER_USER_PHP) ?>
    <div class="main">
        <div class="container">
            <form class="userform" action="login_act.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="userguide">ログイン</h2>
                <div class="userrow">
                    <label class="userlabel">USERID</label>
                    <input type="text" name="lid" class="usertext" required>
                </div>
                <div class="userrow">
                    <label class="userlabel">PASSWORD</label>
                    <input type="password" name="lpw" class="usertext" required>
                </div>
                <input type="submit" class="formbutton loginbtn" value="LogIn">
            </form>
        </div>
    </div>
</body>
</html>