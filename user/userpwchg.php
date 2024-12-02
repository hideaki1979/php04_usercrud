<?php
    // パスワード変更実行用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_USERSQL_PHP); // ユーザー共通SQL用phpを読み込む

    $pdo = getPdoConnection();  // DB接続
    // パスワードと確認の入力値が異なる場合はエラー
    if($_POST["lpw"] != $_POST["lpwConfirm"]) {
        session_start();
        $_SESSION["message"] = PW_CONFIRM_INVALID_ERR;
        header("Location: ".PWCHG_SC."?id=".$_POST["id"]."&lid=".$_POST["lid"]);
        exit();
    }

    lpwChange($pdo, $_POST);    //ユーザーパスワード変更

?>