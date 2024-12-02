<?php
    session_start();    // SESSION開始
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_FUNC_PHP);   // 共通関数用PHP
    require_once(COM_USERSQL_PHP); // 社員情報SQL用PHPを読み込む


    // POSTの値を格納
    $lid = $_POST["lid"];
    $lpw = $_POST["lpw"];

    // DB接続
    $pdo = getPdoConnection();

    // 抽出データ数を取得
    $val = getUserLoginData($pdo, $lid);

    // 該当1レコードがあればSESSIONに値を代入
    //入力したPasswordと暗号化されたPasswordを比較！[戻り値：true,false]
    //$lpw = password_hash($lpw, PASSWORD_DEFAULT);   //パスワードハッシュ化
    $pw = password_verify($lpw, $val["lpw"]);
    if($pw) {
        // LOGIN成功時
        $_SESSION["chk_ssid"] = session_id();   // SESSIONを預ける
        $_SESSION["kanri_flg"] = $val["kanri_flg"];
        $_SESSION["name"] = $val["name"];
        $_SESSION["lid"] = $val["lid"];
        // search.phpにリダイレクト
        scrRedirect("../book/search.php", "", 0);
    } else {
        scrRedirect(LOGIN_SC, "", 0);
    }

?>