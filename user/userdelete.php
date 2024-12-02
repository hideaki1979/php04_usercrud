<?php 
    // ユーザー情報削除用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_FUNC_PHP); // 共通関数用PHPを読み込む
    require_once(COM_USERSQL_PHP); // ユーザーSQL用phpを読み込む
    session_start();
    sschk();

    $pdo = getPdoConnection();  // DB接続
    // 画面側からの一括削除対象のIDを取得する。
    $delIds = json_decode(file_get_contents('php://input'), true);
    // ログイン中のユーザーのログインIDが削除対象の場合はエラーにする。
    if(isset($delIds["delLoginId"]) && is_array($delIds["delLoginId"])) {
        $delLoginIds = $delIds["delLoginId"];
        foreach($delLoginIds as $delLoginId) {
            $loginData = getUserLoginData($pdo, $delLoginId);
            if($_SESSION["lid"] == $loginData["lid"]){
                echo json_encode(["status" => "error", "message" => "削除対象にログイン中のユーザーが含まれています！"]);
            }
        }
    }
    // $delIds["delchkId"]の値が配列のため、引数は$delIds["delchkId"]で指定する。
    if(isset($delIds["delchkId"]) && is_array($delIds["delchkId"])) {
        $response = userDelete($pdo, $delIds["delchkId"]);    //ユーザー情報削除
        
        echo $response; // UserList側にレスポンス情報を返す。
    } else {
        // UserList側にレスポンス情報を返す。
        echo json_encode(["status" => "error", "message" => "削除対象のIDが設定でエラーが発生しております！"]);
    }
?>