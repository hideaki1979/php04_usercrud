<?php
    require_once("config.php"); // 定数管理用PHP
    require_once("db.php"); //DB接続用PHP読み込み
    require_once("commonfunc.php"); // SQLエラー、リダイレクトなど共通関数読み込み

    //エラー表示
    ini_set("display_errors", 1);

    function getUserLoginData($pdo, $lid) {
        // LOGINチェック
        $stmt = $pdo->prepare("SELECT * FROM gs_user_table WHERE lid=:lid AND life_flg=0");
        $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
        $status = $stmt->execute();

        // SQL実行時にエラーがある場合STOP
        if($status==false){
            sql_error($stmt);
        }

        // 抽出データ数を取得
        return $stmt->fetch();  //1レコードだけ取得する方法
    }

    function getLifeflg($pdo) {
        $sql = "SELECT * FROM gs_lifeflg";
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute();
        $values = "";
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // 全データ取得
        $values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
        return $values;
    }

    function userInsert($pdo, $post) {
        // 登録画面の項目取得
        $name = htmlspecialchars(filter_input(INPUT_POST, "name"));
        $lid = htmlspecialchars(filter_input(INPUT_POST, "lid"));
        $lpw = htmlspecialchars(filter_input(INPUT_POST, "lpw"));
        $kanriFlg = filter_input(INPUT_POST, "kanriflg", FILTER_SANITIZE_NUMBER_INT);
        $lpw = password_hash($lpw, PASSWORD_DEFAULT);

        // SQL文作成
        $sql = "INSERT INTO gs_user_table (name, lid, lpw, kanri_flg, life_flg) 
        VALUE (:name, :lid, :lpw, :kanriflg, 0)";
        $stmt = $pdo->prepare($sql);
        // 条件値をバインド変数に格納
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":lid", $lid, PDO::PARAM_STR);
        $stmt->bindValue(":lpw", $lpw, PDO::PARAM_STR);
        $stmt->bindValue(":kanriflg", $kanriFlg, PDO::PARAM_INT);

        $status = $stmt->execute(); // insert文実行

        // データ登録処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // クエリパラメータにメッセージを設定し、UserRegist.phpへリダイレクト
        scrRedirect(USER_REGIST_SC, INSERT_COMP, 0);
    }

    function getUserSearchCond($pdo, $conditions) {
        // 検索画面の条件項目を取得する
        $name = $conditions["name"];
        $lid = $conditions["lid"];
        $lifeFlg = $conditions["lifeflg"];

        // ベースのSQL文作成
        $sql = "SELECT USER.*, LIFE.name AS life_name, 
                CASE 
                    WHEN USER.kanri_flg = 0 THEN '一般' 
                    WHEN USER.kanri_flg = 1 THEN '管理者'
                    ELSE '' 
                END AS kanri_flg_name 
                FROM gs_user_table AS USER INNER JOIN gs_lifeflg AS LIFE 
                ON USER.life_flg = LIFE.life_flg";

        // 検索条件を動的に追加するため、条件文とバインド変数に値を配列に格納する。
        $condArray = [];
        $bindArray = [];
        // 名前が入力されていた場合
        if(!empty($name)) {
            $condArray[] = "USER.name LIKE :name";
            $bindArray[":name"] = '%'.$name.'%';
        }
        // ログインIDが入力されていた場合
        if(!empty($lid)) {
            $condArray[] = "USER.lid LIKE :lid";
            $bindArray[":lid"] = '%'.$lid.'%';
        }
        // 在職状況が選択されていた場合
        if(isset($lifeFlg) && $lifeFlg != '') {
            $condArray[] = "USER.life_flg = :lifeFlg";
            $bindArray[":lifeFlg"] = $lifeFlg;
        }
        // 権限付与が選択されていた場合
        if(isset($conditions["kanriflg"])) {
            $condArray[] = "USER.kanri_flg = :kanriFlg";
            $bindArray[":kanriFlg"] = $conditions["kanriflg"];
        }
        // WHERE句のSQL文生成(implodeで配列から文字列に変換)
        if(!empty($condArray)) {
            $sql .= " WHERE ".implode(" AND ", $condArray);
        }
        // SELECT文実行
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute($bindArray);
        // データ検索処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // 検索結果取得し、画面側に返す
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    function getUserKey($pdo, $id) {
        $sql = "SELECT * FROM gs_user_table WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        $status = $stmt->execute();

        //データ表示
        $value = "";
        if($status==false) {
            sql_error($stmt);
        }
        // データ取得
        $value =  $stmt->fetch(); //fetch（1行上の一行）
        // $json = json_encode($value,JSON_UNESCAPED_UNICODE);
        return $value;
    }

    function userUpdate($pdo, $post) {
        // 更新画面の項目取得
        $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
        $name = htmlspecialchars(filter_input(INPUT_POST, "name"));
        $lid = htmlspecialchars(filter_input(INPUT_POST, "lid"));
        // $lpw = htmlspecialchars(filter_input(INPUT_POST, "lpw"));
        $kanriFlg = filter_input(INPUT_POST, "kanriflg", FILTER_SANITIZE_NUMBER_INT);
        $lifeFlg = filter_input(INPUT_POST, "lifeflg", FILTER_SANITIZE_NUMBER_INT);
        // $lpw = password_hash($lpw, PASSWORD_DEFAULT);

        // SQL文作成
        $sql = "UPDATE gs_user_table 
        SET name=:name, lid=:lid, kanri_flg=:kanriflg, life_flg=:lifeFlg 
        WHERE id=:id"; 
        $stmt = $pdo->prepare($sql);
        // 条件値をバインド変数に格納
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":lid", $lid, PDO::PARAM_STR);
        // $stmt->bindValue(":lpw", $lpw, PDO::PARAM_STR);
        $stmt->bindValue(":kanriflg", $kanriFlg, PDO::PARAM_INT);
        $stmt->bindValue(":lifeFlg", $lifeFlg, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $status = $stmt->execute(); // UPDATE文実行

        // データ更新処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // クエリパラメータにメッセージを設定し、UserDetail.phpへリダイレクト
        scrRedirect(USER_DETAIL_SC, UPDATE_COMP, $id);
    }

    // 書籍情報削除処理
    function userDelete($pdo, $delIds) {
        // $id = $get["id"];
        try {
            // トランザクション開始
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM gs_user_table WHERE id=:id");
            foreach($delIds as $id) {
                $stmt->bindValue(':id', $id, PDO::PARAM_INT); 
                $status = $stmt->execute(); //実行
                // データ更新処理後
                if($status==false){
                    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
                    sql_error($stmt);
                }
            }
            // 全件削除成功の場合、コミットする。
            $pdo->commit();
            return json_encode(["status" => "success", "message" => "一括削除処理が成功しました！"]);
        } catch(PDOException $e) {
            // エラーが発生した場合はロールバックを行う
            if($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        // クエリパラメータにメッセージを設定し、search.phpへリダイレクト
        // scrRedirect(USER_SEARCH_SC, DELETE_COMP, 0);
    }

    function lpwChange($pdo, $post) {
        $id = $post["id"];
        $lpw = $post["lpw"];
        $lpw = password_hash($lpw, PASSWORD_DEFAULT);
        $sql = "UPDATE gs_user_table SET lpw=:lpw WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        // 条件値をバインド変数に格納
        $stmt->bindValue(":lpw", $lpw, PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $status = $stmt->execute(); // UPDATE文実行

        // データ更新処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // クエリパラメータにメッセージを設定し、UserDetail.phpへリダイレクト
        scrRedirect(USER_SEARCH_SC, PWCHG_COMP, 0);
    }
?>
