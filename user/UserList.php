<?php
    session_start();    // セッション開始
    require_once(dirname(__FILE__)."/../common/config.php"); // 共通定数用PHP
    require_once(COM_DB_PHP); // DB接続用phpを読みこむ
    require_once(COM_USERSQL_PHP); // 社員情報SQL用PHPを読み込む
    require_once(COM_FUNC_PHP);   // 共通関数用PHP

    sschk();    // LOGINチェック
    $pdo = getPdoConnection();  // DB接続
    $lifeFlgList = getLifeflg($pdo);    // 在職状況テーブル全件取得
    // フォームからPOSTで呼ばれたら書籍一覧用の検索処理を実施
    $users = [];
    // 他の画面からユーザ一覧画面に遷移してきた場合は条件を初期化
    if(isset($_SESSION["usersearchcond"])) {
        unset($_SESSION["usersearchcond"]);
    }
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $users = getUserSearchCond($pdo, $_POST);
        // 再度検索する際の効率化のため、入力条件をセッションで保持し
        // 再表示時に保持している条件を画面表示する。
        $_SESSION["usersearchcond"] = [
            "name" => $_POST["name"],
            "lid" => $_POST["lid"],
            "lifeflg" => $_POST["lifeflg"],
            // 管理フラグはチェックボックスの為、未チェックの場合はPOST送信されないので、null合体演算子を使う
            "kanriflg" => $_POST["kanriflg"] ?? "",
        ];
    }
    $userCond = $_SESSION["usersearchcond"] ?? [];
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
    <?php include(COM_HEAD_USER_HTML) ?>
</head>
<body>
    <?php include(COM_HEADER_USER_PHP) ?>
    <div class="searcharea">
        <!-- formでaciton属性をしてはいけない。検索押下時に上部のPHPスクリプトが実行され、
         getBookSearchCond関数が実行される。 -->
         <p><?php echo $message; ?></p>
        <form method="post" class="searchform">
            <div class="row">
                <input type="text" name="name" placeholder="名前を入力（あいまい検索）" class="condtitle" 
                value="<?php echo (!empty($userCond["name"]) ? $userCond["name"] : "") ?>">
                <input type="text" name="lid" placeholder="ログインIDを入力（あいまい検索）" class="condtitle" 
                value="<?php echo (!empty($userCond["lid"]) ? $userCond["lid"] : "") ?>">
                <input type="submit" value="検索" class="formbutton searchbtn">
                <label class="lifeflglabel">在職状況：</label>
                <select name="lifeflg" class="condlifeflg">
                    <option value=""></option>
                    <?php foreach($lifeFlgList as $lifeFlg): ?>
                        <option value=<?= htmlspecialchars($lifeFlg["life_flg"]) ?>
                         <?= (isset($userCond["lifeflg"]) && $userCond["lifeflg"] == $lifeFlg["life_flg"]) ? "selected" : "" ?>>
                            <?= htmlspecialchars($lifeFlg["name"]) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="row">
                <label class="conduserlabelradio">権限付与：</label>
                <div class="condradioarea">
                    <input type="radio" name="kanriflg" class="condkanriflg" value="0"
                    <?= (isset($userCond["kanriflg"]) && $userCond["kanriflg"] == 0) ? "checked" : "" ?>>
                    <label class="condkanriflglabel">一般</label>
                    <input type="radio" name="kanriflg" class="condkanriflg" value="1" 
                    <?= (isset($userCond["kanriflg"]) && $userCond["kanriflg"] == 1) ? "checked" : "" ?>>
                    <label class="condkanriflglabel">管理者</label>
                </div>
            </div>
        </form>
        
    </div>
    <div class="deletebtnarea">
        <button type="button" class="formbutton bulkdelbtn" id="bulkdelete">一括削除</button>
    </div>
    <div class="resultarea">
        <table class="userlist">
            <?php if(!empty($users)): ?>
                <tr>
                    <th class="username">名前</th>
                    <th class="userloginid">ログインID</th>
                    <th class="userkanriflg">権限付与</th>
                    <th class="userlifeflg">在職状況</th>
                    <th class="updatebtn">更新</th>
                    <th class="deletebtn">削除</th>
                </tr>
            <?php endif ?>
            <?php foreach($users as $user): ?>
                <tr data-id="<?=htmlspecialchars($user["id"]) ?>">
                    <td class="username"><?= htmlspecialchars($user["name"]) ?></td>
                    <td class="userloginid"><?= htmlspecialchars($user["lid"]) ?></td>
                    <td class="userkanriflg"><?= htmlspecialchars($user["kanri_flg_name"]) ?></td>
                    <td class="userlifeflg"><?= htmlspecialchars($user["life_name"]) ?></td>
                    <td class="updatebtn">
                        <form action="userDetail.php" method="POST">
                            <input type="hidden" name="id" value="<?=htmlspecialchars($user["id"]) ?>">
                            <button type="submit" class="td_upbtn">更新</button>
                        </form>
                    </td>
                    <td class="deletechk"><input type="checkbox" class="delchkbox"></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
<script>
    const bulkDelBtn = document.getElementById("bulkdelete");
    document.getElementById("bulkdelete").addEventListener("click", function() {
        const delchkId = [];    // 一括削除（チェックON）対象のID格納用配列
        const delLoginId = [];  // 一括削除（チェックON）対象のログインID格納用配列
        // 一括削除（チェックON）対象のIDを抽出して配列に格納する。
        document.querySelectorAll(".delchkbox:checked").forEach(function(checkbox) {
            const chkRow = checkbox.closest("tr");
            const id = chkRow.getAttribute("data-id");
            const loginId = chkRow.querySelector(".userloginid").textContent;
            if(loginId) {
                delLoginId.push(loginId);
            }
            if(id) {
                delchkId.push(id);
            }
        });
        console.log(delchkId);
        console.log(delLoginId);
        // idが格納されている場合は一括削除対象のIDをサーバー側に送信し、一括削除を行う。
        if(delchkId.length > 0) {
            fetch("userdelete.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ delchkId: delchkId, delLoginId: delLoginId })
            })
            .then((response) => response.json())
            // .then((response) => {
            //     console.log("レスポンスオブジェクト:", response);
            //     console.log("レスポンステキスト：", response.text());
            //     if (!response.ok) {
            //         throw new Error(`HTTPエラー: ${response.status}`);
            //     }
            //     return response.json();
            // })
            .then((data) => {
                if(data.status == "success") {
                    alert(data.message);    // 一括削除成功メッセージ表示
                    location.reload();  // 画面をリロードで再表示
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error("POST通信エラー：", error);
                alert("一括削除処理でエラーが発生しました！");
            });
        } else {
            alert("削除対象がいないので一括削除が行えません！");
            return
        }
    });
</script>
</body>
</html>