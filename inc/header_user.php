<header>
    <div class="container">
      <div class="headertitle">
        <div class="leftarea">
            <img src="../images/mirrorman-bookstore-logo2.png" alt="" width="300" height="70">
            <?php if(isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"] = session_id()){ ?>
              <p class="loginname"><?=$_SESSION["name"]  ?>さんログイン中</p>
            <?php } ?>
        </div>
      </div>
      <div class="screenlist">
          <ul>
            <li><a class="headerlink" href="../index.php">メイン</a></li>
            <li><a class="headerlink" href="../book/upload.php">書籍一括登録</a></li>
            <li><a class="headerlink" href="../book/regist.php">書籍登録</a></li>
            <li><a class="headerlink" href="../book/search.php">書籍検索</a></li>
            <?php if(isset($_SESSION["kanri_flg"]) && $_SESSION["kanri_flg"] == 1){ ?>
              <li><a class="headerlink" href="UserList.php">ユーザ一覧</a></li>
              <li><a class="headerlink" href="UserRegist.php">ユーザー登録</a></li>
              <?php } ?>
            <?php if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){ ?>
                <li><a class="headerlink" href="login.php">ログイン</a></li>
            <?php } else { ?>
              <li><a class="headerlink" href="logout.php">ログアウト</a></li>
            <?php } ?>
          </ul>
      </div>
    </div>
</header>