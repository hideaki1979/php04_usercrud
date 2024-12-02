<?php
//SQLエラー関数：sql_error($stmt)
function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//画面遷移（リダイレクト）関数: $file_name画面名、$message画面に表示するメッセージ、$idテーブルのID（クエリパラメータにIDを入れない場合は0を指定する）
function scrRedirect($file_name, $message, $id){
    session_start();
    $message ? $_SESSION["message"] = $message : "";
    $id !== 0 ? $_SESSION["keyId"] = $id : "";
    header("Location: $file_name");
    exit();
}

// Session Check
function sschk() {
    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()) {
        exit("ログインしてください！");
    } else {
        session_regenerate_id(true);    // セッションハイジャック対策！必ずtrue指定すること！
        $_SESSION["chk_ssid"] = session_id();
    }
}

?>