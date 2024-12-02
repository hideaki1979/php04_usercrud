<?php
    require_once("config.php"); // 定数管理用PHP
    require_once("db.php"); //DB接続用PHP読み込み
    require_once("commonfunc.php"); // SQLエラー、リダイレクトなど共通関数読み込み

    //エラー表示
    ini_set("display_errors", 1);

    // 出版社テーブル全件取得
    function getPublisherAll($pdo) {
        $sql = "SELECT * FROM gs_publisher;";   // 全件取得なのでバインド変数無しで実行
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

    // 書籍情報検索画面用検索処理
    function getBookSearchCond($pdo, $conditions) {
        // 検索画面の条件項目を取得する
        $title = $conditions["title"];
        $publisher = $conditions["publisher"];
        $priceFrom = $conditions["priceFrom"];
        $priceTo = $conditions["priceTo"];
        // ベースのSQL文作成
        $sql = "SELECT BOOK.*, PUB.publish_name FROM gs_book AS BOOK INNER JOIN gs_publisher AS PUB 
            ON BOOK.publisher = PUB.publish_cd";
        
        // 検索条件を動的に追加するため、条件文とバインド変数に値を配列に格納する。
        $condArray = [];
        $bindArray = [];
        // 書籍名が入力されていた場合
        if (!empty($title)) {
            $condArray[] = "BOOK.title LIKE :title";
            $bindArray[":title"] = '%'.$title.'%';
        }

        // 出版社が選択されていた場合
        if(!empty($publisher)) {
            $condArray[] = "BOOK.publisher = :publisher";
            $bindArray[":publisher"] = $publisher;
        }

        // 値段（From）が選択されていた場合
        if(!empty($priceFrom)) {
            $condArray[] = "BOOK.price >= :priceFrom";
            $bindArray[":priceFrom"] = $priceFrom;
        }

        // 値段（To）が選択されていた場合
        if(!empty($priceTo)) {
            $condArray[] = "BOOK.price <= :priceTo";
            $bindArray[":priceTo"] = $priceTo;
        }

        // WHERE句のSQL文生成(implodeで配列から文字列に変換)
        if(!empty($condArray)) {
            $sql .= " WHERE ".implode(" AND ", $condArray);
        }

        // 発売日のチェックがONの場合
        if(isset($_POST["orderbyDate"])) {
            $sql .= " ORDER BY BOOK.releasedate DESC";
        }

        // select文発行
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute($bindArray);
        // データ検索処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // 検索結果取得し、画面側に返す
        $books =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
        return $books;
    }

    // 書籍情報登録処理
    function bookInsert($pdo, $post) {
        // 登録画面の項目取得
        $isbn = $post["isbn"];
        $title = $post["title"];
        $publisher = $post["publisher"];
        $author = $post["author"];
        $price = $post["price"];
        $imageurl = $post["imageurl"];
        $caption = $post["caption"];
        $releasedate = $post["releasedate"];

        // SQL文作成
        $sql = "INSERT INTO gs_book (isbn_no, title, publisher, author, price, imageurl, caption, releasedate, indate) 
        VALUES(:isbn, :title, :publisher, :author, :price, :imageurl, :caption, :releasedate, sysdate());";
        $stmt = $pdo->prepare($sql);
        // 条件値をバインド変数に格納
        $stmt->bindValue(":isbn", $isbn, PDO::PARAM_STR);
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":publisher", $publisher, PDO::PARAM_STR);
        $stmt->bindValue(":author", $author, PDO::PARAM_STR);
        $stmt->bindValue(":price", $price, PDO::PARAM_INT);
        $stmt->bindValue(":imageurl", $imageurl, PDO::PARAM_STR);
        $stmt->bindValue(":caption", $caption, PDO::PARAM_STR);
        $stmt->bindValue(":releasedate", $releasedate, PDO::PARAM_STR);
        $status = $stmt->execute(); // insert文実行

        // データ登録処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // クエリパラメータにメッセージを設定し、regist.phpへリダイレクト
        scrRedirect(BOOK_REGIST_SC, INSERT_COMP, 0);
    }

    // 書籍情報更新処理
    function bookUpdate($pdo, $post) {
        // 変更画面の項目取得
        $title = $post["title"];
        $publisher = $post["publisher"];
        $author = $post["author"];
        $price = $post["price"];
        $imageurl = $post["imageurl"];
        $caption = $post["caption"];
        $releasedate = $post["releasedate"];
        $id = $post["id"];

        // SQL文作成
        $sql = "UPDATE gs_book SET title=:title, publisher=:publisher, author=:author, price=:price, imageurl=:imageurl, caption=:caption, releasedate=:releasedate WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        // 条件値をバインド変数に格納
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":publisher", $publisher, PDO::PARAM_STR);
        $stmt->bindValue(":author", $author, PDO::PARAM_STR);
        $stmt->bindValue(":price", $price, PDO::PARAM_INT);
        $stmt->bindValue(":imageurl", $imageurl, PDO::PARAM_STR);
        $stmt->bindValue(":caption", $caption, PDO::PARAM_STR);
        $stmt->bindValue(":releasedate", $releasedate, PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $status = $stmt->execute(); // update文実行

        // データ更新処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }
        // クエリパラメータにメッセージを設定し、detail.phpへリダイレクト
        scrRedirect(BOOK_DETAIL_SC, UPDATE_COMP, $id);
    }

    // 書籍情報削除処理
    function bookDelete($pdo, $get) {
        $id = $get["id"];
        $stmt = $pdo->prepare("DELETE FROM gs_book WHERE id=:id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT); 
        $status = $stmt->execute(); //実行

        // データ更新処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            sql_error($stmt);
        }

        // クエリパラメータにメッセージを設定し、search.phpへリダイレクト
        scrRedirect(BOOK_SEARCH_SC, DELETE_COMP, 0);
    }

    // 書籍情報検索（IDから取得）
    function getBookKey($pdo, $id) {
        $sql = "SELECT * FROM gs_book WHERE id=:id";
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
        $json = json_encode($value,JSON_UNESCAPED_UNICODE);
        return $value;
    }

?>