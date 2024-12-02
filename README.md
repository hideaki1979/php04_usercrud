# ①課題名
- MIRRORMAN BOOKSTORE-RETURNS(改)

# ②課題内容（どんな作品か）
- 前回の課題から更新・削除機能の追加とプラスアルファ。  

## ③アプリのデプロイURL  
https://kagami-hide.sakura.ne.jp/php03_kadai_upddel/  

## ④アプリのログイン用IDまたはPassword（ある場合）
- ID: 
- PW: 
  
## ⑤工夫した点・こだわった点
- 更新・削除機能は講義の内容をベースに対応してます。  
＋αで登録画面でISBN番号を入力すると入力項目を自動的に設定する機能を追加しました。  
※RAKUTEN BOOKS APIから書籍情報を取得してます。

## ⑥難しかった点・次回トライしたいこと（又は機能）
【難しかった点】  
- リファクタリングする際に関数化でコードをすっきりしようと思ったのですが、  
凄くわかりづらいコードになった気がします・・・。

【次回トライしたいこと】  
- HTML/CSS  
- セッション情報で検索情報を保持（今は検索すると入力が消えてしまう）  
→やるの忘れてた・・・。
- ラジオボタン、複数チェックボックスでの動的条件（IN句）などを使っての
複雑なSQLの理解を深める。→やるの忘れてた・・・。
- 一部バグを見つけたので取り除きたい。

## ⑦フリー項目（感想、シェアしたいこと等なんでも）
- RAKUTEN BOOKS APIはSDKがあったおかけでそれほど時間がかからずに実装できました。
- リファクタリングはクラスを理解してすっきりとしたコードにしていきたいです。
- そろそろPHP選手権もあるので、PHP⇔JSとの連携も理解を深めていきたいです。
- [参考記事]
  - 1. [楽天API](https://webservice.rakuten.co.jp/)
  - 2. [楽天APIのSDKのGitHub](https://github.com/rakuten-ws/rws-php-sdk?tab=readme-ov-file)