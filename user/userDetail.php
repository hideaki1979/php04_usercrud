<?php
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_FUNC_PHP); // 共通関数用PHPを読み込む
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_USERSQL_PHP); // 社員情報SQL用PHPを読み込む
    session_start();    // セッション生成
    sschk();
    $pdo = getPdoConnection();  // DB接続
    $lifeFlgList = getLifeflg($pdo);    // 在職状況テーブル全件取得
    $keyID = "";
    if (isset($_SESSION["keyId"])) {
        $keyID = $_SESSION["keyId"];
    }
    unset($_SESSION["keyId"]);   // セッション情報破棄
    if (isset($_POST["id"])) {
        $keyID = $_POST["id"];
    }
    if(!empty($keyID)) {
        $user = getUserKey($pdo, $keyID);  // idをキーに社員情報を取得
    }
    $message = "";
    if(isset($_SESSION["message"])){
        $message = nl2br(htmlspecialchars($_SESSION["message"], ENT_QUOTES, "UTF-8"));
    }
    unset($_SESSION["message"]);   // セッション情報破棄

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(COM_HEAD_USER_HTML) ?>
</head>
<body>
    <?php include(COM_HEADER_USER_PHP) ?>
    <div class="main">
        <div class="container">
            <form class="userform" action="userupdate.php" method="post">
                <p><?php echo $message; ?></p>
                <h2 class="userguide">ユーザー更新</h2>
                <div class="userrow">
                    <label class="userlabel">名前</label>
                    <input type="text" name="name" class="usertext" value="<?= htmlspecialchars($user["name"]) ?>" required>
                </div>
                <div class="userrow">
                    <label class="userlabel">USERID</label>
                    <input type="text" name="lid" class="usertext" value="<?= htmlspecialchars($user["lid"])?>" required>
                </div>
                <!-- <div class="userrow">
                    <label class="userlabel">PASSWORD</label>
                    <input type="password" name="lpw" class="usertext" required>
                </div> -->
                <div class="userrow">
                    <label class="userlabelradio">権限付与</label>
                    <div class="radioarea">
                        <input type="radio" name="kanriflg" class="kanriflg" value="0" 
                        <?= $user["kanri_flg"] == 0 ? "checked" : "" ?>>
                        <label class="userlabel">一般</label>
                        <input type="radio" name="kanriflg" class="kanriflg" value="1"
                        <?= $user["kanri_flg"] == 1 ? "checked" : "" ?>>
                        <label class="userlabel">管理者</label>
                    </div>
                </div>
                <div class="userrow">
                    <label class="userlabel">在職状況</label>
                    <select name="lifeflg" class="selectlifeflg">
                        <?php foreach($lifeFlgList as $lifeFlg): ?>
                            <?php if($user["life_flg"] == $lifeFlg["life_flg"] ){ ?>
                                <option value=<?=htmlspecialchars($lifeFlg["life_flg"]) ?> selected>
                                    <?=htmlspecialchars($lifeFlg["name"]) ?>
                                </option>
                            <?php } else { ?>
                                <option value=<?=htmlspecialchars($lifeFlg["life_flg"]) ?>>
                                <?=htmlspecialchars($lifeFlg["name"]) ?>
                                </option>
                            <?php } ?>
                        <?php endforeach ?>
                    </select>
                </div>
                <input type="submit" class="formbutton registbtn" value="ユーザー更新">
                <a href="pwchg.php?id=<?=urlencode($user["id"]) ?>&lid=<?=urlencode($user["lid"]) ?>">パスワード変更はこちら</a>
                <input type="hidden" name="id" value="<?=$user["id"] ?>">
            </form>
        </div>
    </div>
</body>
</html>