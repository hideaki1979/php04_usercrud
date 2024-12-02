<?php
    // 書籍情報変更画面用PHP
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_BOOKSQL_PHP); // DB検索用phpを読み込む

    session_start();
    $pdo = getPdoConnection();  // DB接続
    $publishers = getPublisherAll($pdo);    // 出版社テーブル全件取得
    $book = "";
    if(!empty($_SESSION["keyId"])) {
        $book = getBookKey($pdo, $_SESSION["keyId"]);
    }
    if(!empty($_GET["id"])) {
        $book = getBookKey($pdo, $_GET["id"]);
    }
    unset($_SESSION["keyId"]);
    $message = "";
    if(isset($_SESSION["message"])){
        $message = nl2br(htmlspecialchars($_SESSION["message"], ENT_QUOTES, "UTF-8"));
    }
    unset($_SESSION["message"]);
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
            <form class="registform" action="bookupdate.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="screenname">書籍情報変更</h2>
                <div class="row regrow">
                    <label class="reglabel">書籍名：</label>
                    <input type="text" name="title" class="title" value="<?=htmlspecialchars($book["title"], ENT_QUOTES, "UTF-8") ?>" required>
                </div>
                <div class="row regrow">
                    <label class="reglabel">出版社：</label>
                    <select name="publisher" class="publisher">
                        <option value=""></option>
                        <?php foreach($publishers as $publisher): ?>
                            <?php if($book["publisher"] == $publisher["publish_cd"] ){ ?>
                                <option value=<?=htmlspecialchars($publisher["publish_cd"], ENT_QUOTES, "UTF-8") ?> selected>
                                    <?=$publisher["publish_name"] ?>
                                </option>
                            <?php } else { ?>
                                <option value=<?=htmlspecialchars($publisher["publish_cd"], ENT_QUOTES, "UTF-8") ?>>
                                <?=$publisher["publish_name"] ?>
                                </option>
                            <?php } ?>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="row regrow">
                    <label class="reglabel">著者名：</label>
                    <input type="text" name="author" class="author" value="<?=htmlspecialchars($book["author"], ENT_QUOTES, "UTF-8") ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">値段：</label>
                    <input type="text" name="price" class="price" value="<?=htmlspecialchars($book["price"], ENT_QUOTES, "UTF-8") ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">表紙URL：</label>
                    <input type="text" name="imageurl" class="imageurl" value="<?=htmlspecialchars($book["imageurl"], ENT_QUOTES, "UTF-8") ?>">
                </div>
                <div class="row regrow">
                    <label class="reglabel">説明：</label>
                    <textarea name="caption" cols="60" rows="5" class="caption"><?=htmlspecialchars($book["caption"], ENT_QUOTES, "UTF-8") ?></textarea>
                </div>
                <div class="row regrow">
                    <label class="reglabel">発売日：</label>
                    <input type="date" name="releasedate" class="releasedate" value="<?=$book["releasedate"] ?>">
                </div>
                <input type="submit" class="formbutton update" value="更新">
                <input type="hidden" name="id" value="<?=htmlspecialchars($book["id"], ENT_QUOTES, "UTF-8") ?>">
            </form>
        </div>
    </div>
</body>
</html>