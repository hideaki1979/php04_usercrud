<?php
    // ユーザーパスワード変更画面用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    $message = "";
    session_start();    // セッション生成
    if(isset($_SESSION["message"])){
        $message = nl2br(htmlspecialchars($_SESSION["message"], ENT_QUOTES, "UTF-8"));
    }
    unset($_SESSION["message"]);   // セッション情報破棄
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
            <form class="userform" action="userpwchg.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="userguide">パスワード変更</h2>
                <div class="userrow">
                    <label class="userlabel">USERID</label>
                    <input type="text" name="lid" class="usertext" value="<?=$_GET["lid"] ?>" readonly>
                </div>
                <div class="userrow">
                    <label class="userlabel">PASSWORD</label>
                    <input type="password" name="lpw" class="usertext" required>
                </div>
                <div class="userrow">
                    <label class="userlabel">PASSWORD確認</label>
                    <input type="password" name="lpwConfirm" class="usertext" required>
                </div>
                <input type="submit" class="formbutton registbtn" value="パスワード変更">
                <input type="hidden" name="id" value="<?=$_GET["id"] ?>">
            </form>
        </div>
    </div>
</body>
</html>