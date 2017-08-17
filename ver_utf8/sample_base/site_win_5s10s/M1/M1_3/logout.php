<?php
//ログアウト処理

//データを破棄する
//setcookie("data", '',time() - 60, '/', '.'.$sname,1);
setcookie ("cookie_login_data", "",mktime(0,0,0,12,31,(date("Y") + 3)), '/', '.'.$_SERVER['SERVER_NAME']);//一旦データを空にする（一行下の行だけだとデータ削除がうまく行かないため）
setcookie ("cookie_login_data");//こちらの処理でブラウザーが閉じるまでを有効期限に設定をする。

//トップページへ戻る
header("Location: ./");

?>