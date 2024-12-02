<?php
    // 書籍情報登録画面用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_BOOKSQL_PHP); // DB検索用phpを読み込む

    $pdo = getPdoConnection();  // DB接続
    $publishers = getPublisherAll($pdo);    // 出版社テーブル全件取得
    $message = "";
    session_start();    // セッション生成
    if(isset($_SESSION["message"])){
        $message = nl2br(htmlspecialchars($_SESSION["message"], ENT_QUOTES, "UTF-8"));
    }
    unset($_SESSION["message"]);   // セッション情報破棄

    // RAKUTEN BOOKS APIからのレスポンス情報を受け取る。
    $bookData = "";
    if(isset($_SESSION["jsonData"])) {
        $bookData = $_SESSION["jsonData"];  // セッションにあるRAKUTEN BOOKS APIからのレスポンス情報を格納
        // RAKUTEN BOOKS APIの発売日の設定値クリーニング
        $strDate = str_replace("頃", "",  $bookData["salesDate"]);
        $date = DateTime::createFromFormat("Y年m月d日", $strDate);
        $salesDate = $date->format('Y-m-d');
    } else {
        $salesDate = "";
        $bookData = ["publisherName" => ""];
    }
    unset($_SESSION["jsonData"]);   // セッション情報破棄

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(COM_HEAD_HTML) ?>
</head>
<body>
    <?php include(COM_HEADER_PHP) ?>
    <div class="main">
        <div class="container">
            <form class="registform" action="bookinsert.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="screenname">書籍情報登録</h2>
                <div class="row regrow">
                    <label class="reglabel">ISBN：</label>
                    <input type="text" name="isbn" class="isbn" id="isbnno" 
                    value="<?= isset($bookData["isbn"]) ? htmlspecialchars($bookData["isbn"], ENT_QUOTES, "UTF-8") : '' ?>" required>
                    <input type="button" class="formbutton isbnbtn" id="isbnbtn" value="ISBN検索">
                </div>
                <div class="row regrow">
                    <label class="reglabel">書籍名：</label>
                    <input type="text" name="title" class="title" 
                    value="<?= isset($bookData["title"]) ? 
                    htmlspecialchars(htmlspecialchars($bookData["title"], ENT_QUOTES, "UTF-8")) 
                    : '' ?>" required>
                </div>
                <div class="row regrow">
                    <label class="reglabel">出版社：</label>
                    <select name="publisher" class="publisher">
                        <option value=""></option>
                        <?php foreach($publishers as $publisher): ?>
                            <?php if($bookData["publisherName"] == $publisher["publish_name"] ){ ?>
                                <option value=<?=htmlspecialchars($publisher["publish_cd"], ENT_QUOTES, "UTF-8") ?> selected>
                                    <?=htmlspecialchars($publisher["publish_name"], ENT_QUOTES, "UTF-8") ?>
                                </option>
                            <?php } else { ?>
                                <option value=<?=$publisher["publish_cd"] ?>>
                                <?=htmlspecialchars($publisher["publish_name"], ENT_QUOTES, "UTF-8") ?>
                                </option>
                            <?php } ?>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="row regrow">
                    <label class="reglabel">著者名：</label>
                    <input type="text" name="author" class="author" 
                    value="<?= isset($bookData["author"]) ? htmlspecialchars($bookData["author"], ENT_QUOTES, "UTF-8") : '' ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">値段：</label>
                    <input type="text" name="price" class="price" 
                    value="<?= isset($bookData["itemPrice"]) ? htmlspecialchars($bookData["itemPrice"], ENT_QUOTES, "UTF-8") : '' ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">表紙URL：</label>
                    <input type="text" name="imageurl" class="imageurl" 
                    value="<?= isset($bookData["mediumImageUrl"]) ? htmlspecialchars($bookData["mediumImageUrl"], ENT_QUOTES, "UTF-8") : '' ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">説明：</label>
                    <textarea name="caption" cols="60" rows="5" class="caption">
                        <?= isset($bookData["itemCaption"]) ? htmlspecialchars($bookData["itemCaption"], ENT_QUOTES, "UTF-8") 
                        : '' ?>
                    </textarea>
                </div>
                <div class="row regrow">
                    <label class="reglabel">発売日：</label>
                    <input type="date" name="releasedate" class="releasedate" value="<?=$salesDate ?>">
                </div>
                <input type="submit" class="formbutton registbtn" value="登録">
            </form>
        </div>
    </div>
    <script>
        isbnbtn.addEventListener("click", function() {
            const isbnbtn = document.getElementById("isbnbtn");
            const isbnNo = document.getElementById("isbnno").value;
            location.href = `rakutenapi.php?isbn=${isbnNo}`;
        });
    </script>
</body>
</html>