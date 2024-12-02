<?php
    // ユーザー情報登録用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_USERSQL_PHP); // ユーザー共通SQL用phpを読み込む

    $pdo = getPdoConnection();  // DB接続
    userInsert($pdo, $_POST);    //ユーザー情報登録

?>