<?php
    session_start();    // セッション開始
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_BOOKSQL_PHP); // DB検索用phpを読み込む
    require_once(COM_FUNC_PHP);   // 共通関数用PHP

    sschk();    // LOGINチェック
    $pdo = getPdoConnection();  // DB接続
    $publishers = getPublisherAll($pdo);    // 出版社テーブル全件取得
    // フォームからPOSTで呼ばれたら書籍一覧用の検索処理を実施
    $books = [];
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $books = getBookSearchCond($pdo, $_POST);
    }
    $message = "";
    // 削除完了メッセージをセッションから取得し画面表示する。
    if(isset($_SESSION["message"])){
        $message = nl2br(htmlspecialchars($_SESSION["message"], ENT_QUOTES, "UTF-8"));
    }
    unset($_SESSION["message"]);   // セッション情報破棄
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(COM_HEAD_HTML) ?>
</head>
<body>
    <?php include(COM_HEADER_PHP) ?>
    <div class="searcharea">
        <!-- formでaciton属性をしてはいけない。検索押下時に上部のPHPスクリプトが実行され、
         getBookSearchCond関数が実行される。 -->
         <p><?php echo $message; ?></p>
        <form method="post" class="searchform">
            <div class="row">
                <input type="text" name="title" placeholder="書籍名を入力（あいまい検索）" class="condtitle">
                <input type="submit" value="検索" class="formbutton searchbtn">
                <label class="publisherlabel">出版社</label>
                <select name="publisher" class="condpublisher">
                    <option value=""></option>
                    <?php foreach($publishers as $publisher): ?>
                        <option value=<?= htmlspecialchars($publisher["publish_cd"]) ?>>
                            <?= htmlspecialchars($publisher["publish_name"]) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="row">
                <label>値段</label>
                <input type="text" name="priceFrom" class="pricefrom">
                <label>～</label>
                <input type="text" name="priceTo" class="priceto">
                <div class="datecontainer">
                    <input type="checkbox" name="orderbyDate" class="datecheck" id="date" value="1">
                    <label for="date" class="datelabel">発売日が最新順</label>
                </div>
            </div>
        </form>
    </div>
    <div class="resultarea">
        <table class="booklist">
            <?php if(!empty($books)): ?>
                <tr>
                    <th class="cover">表紙</th>
                    <th class="title">タイトル</th>
                    <th class="publisher">出版社</th>
                    <th class="author">著者</th>
                    <th class="price">値段</th>
                    <th class="releasedate">発売日</th>
                    <th class="caption">説明</th>
                    <th class="updatebtn">更新</th>
                    <th class="deletebtn">削除</th>
                </tr>
            <?php endif ?>
            <?php foreach($books as $book): ?>
                <tr>
                    <td class="cover"><img src="<?= $book['imageurl'] ?>" alt="本の画像" class="bookimg"></td>
                    <td class="title"><?= htmlspecialchars($book["title"]) ?></td>
                    <td class="publisher"><?= htmlspecialchars($book["publish_name"]) ?></td>
                    <td class="author"><?= htmlspecialchars($book["author"]) ?></td>
                    <td class="price"><?= htmlspecialchars(number_format($book["price"])) ?>円</td>
                    <td class="releasedate"><?php 
                    $date = DateTime::createFromFormat("Y-m-d", $book["releasedate"]);
                    $strDate = $date->format("Y年m月d日");
                    ?>
                    <?=htmlspecialchars($strDate); ?></td>
                    <td class="caption"><?= nl2br(htmlspecialchars($book["caption"])) ?></td>
                    <td class="updatebtn"><a class="a_td_btn" href="detail.php?id=<?=htmlspecialchars($book["id"]) ?>">更新</a></td>
                    <td class="deletebtn"><a class="a_td_btn" href="delete.php?id=<?=htmlspecialchars($book["id"]) ?>">削除</a></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>
</html>