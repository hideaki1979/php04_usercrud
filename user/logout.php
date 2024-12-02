<?php
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_FUNC_PHP);   // 共通関数用PHP
    // セッション開始は必ず最初に記述すること！
    session_start();

    // SESSIONを初期化
    $_SESSION = array();

    // Cookieに保存してあるSessionIDの保存期間を過去にして破棄
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(), '', time()-42000, '/');
    }

    // サーバー側でのセッションIDの破棄
    session_destroy();

    // ログアウト後、login.phpへリダイレクト
    scrRedirect(LOGIN_SC, "", 0);
?>