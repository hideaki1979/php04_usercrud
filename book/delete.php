<?php 
    // 書籍情報削除用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_BOOKSQL_PHP); // 書籍SQL用phpを読み込む

    $pdo = getPdoConnection();  // DB接続
    $message = bookDelete($pdo, $_GET);    //書籍情報削除
?>