<?php
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_FUNC_PHP);
    $message = "";
    session_start();    // セッション生成
    sschk();
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
            <form class="userform" action="userinsert.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="userguide">ユーザー登録</h2>
                <div class="userrow">
                    <label class="userlabel">名前</label>
                    <input type="text" name="name" class="usertext" required>
                </div>
                <div class="userrow">
                    <label class="userlabel">USERID</label>
                    <input type="text" name="lid" class="usertext" required>
                </div>
                <div class="userrow">
                    <label class="userlabel">PASSWORD</label>
                    <input type="password" name="lpw" class="usertext" required>
                </div>
                <div class="userrow">
                    <label class="userlabelradio">権限付与</label>
                    <div class="radioarea">
                        <input type="radio" name="kanriflg" class="kanriflg" value="0" checked>
                        <label class="userlabel">一般</label>
                        <input type="radio" name="kanriflg" class="kanriflg" value="1">
                        <label class="userlabel">管理者</label>
                    </div>
                </div>
                <input type="submit" class="formbutton registbtn" value="ユーザー登録">
            </form>
        </div>
    </div>
</body>
</html>