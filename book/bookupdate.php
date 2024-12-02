<?php 
    // 書籍情報登録用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_BOOKSQL_PHP); // DB検索用phpを読み込む

    $pdo = getPdoConnection();  // DB接続
    $message = bookUpdate($pdo, $_POST);    //書籍情報更新
?>