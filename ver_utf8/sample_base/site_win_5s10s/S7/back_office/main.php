<?php
/*******************************************************************************
カテゴリ対応

メインコントローラー

	※最初にログイン画面を表示して認証を行う
	※認証データはセッションに入れて常に持ちまわす（これがないとエラーにする）
	※認証成功の場合はトップメニューを表示
		一覧：
			１．商品の更新
			---------------
			１．ユーザー検索
			２．売り上げ管理

	※それぞれのメニューは独立プログラムとして動作（別途コントローラーを持たせて制御）

2005/4/11 tanaka
2005/7/27 : uzura
*******************************************************************************/
session_start();
require_once("../common/config.php");		// 共通設定ファイル
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
/*
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
	header("HTTP/1.0 404 Not Found");exit();
}*/

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="for_bk.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0">
<table width="98%" height="98%" align="center" cellpadding="00" cellspacing="0">
  <tr>

    <td height="160" align="center"><img src="img/title.gif" width="615" height="124"></td>
  </tr>
  <tr>

    <td align="center" valign="bottom" class="black12px">←左メニューより処理を選択してください。<br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <table width="500" cellspacing="0" cellpadding="5">
        <tr>
          <td align="center">※ 登録する画像は必ずJPEG形式にしてください。<br>
            ※ ブラウザの『戻る』ボタンは押さないようにしてください。<br>
            ※ 長時間操作をしないとタイムアウトとなり、再度ログインする必要があります。<br>
            ※ 半角カタカナ、及び半角記号は入力しても表示されない場合があります。 <br></td>
        </tr>
      </table> </td>
  </tr>
</table>
</body>
</html>