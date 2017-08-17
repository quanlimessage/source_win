<?php
/*******************************************************************************
アパレル対応(カテゴリ二つ)
ショッピングカートプログラム バックオフィス

	メニュー画面

*******************************************************************************/
session_start();
require_once("../common/config.php");
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("Location: ../");exit();
}

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
<link href="for_bkmanu.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
<form action="./" method="post" target="_parent">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</div>
<p><strong>処理を選択してください</strong></p>
	<!--メニューテーブル-->
	<table border="0" cellpadding="0" cellspacing="0" width="90%">

		<tr><td class="menutitle">▼ 更新プログラム管理</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="n3_2whatsnew/" target="main">新着情報の更新</a></td></tr>
		<tr><td class="explanation">新着情報の新規登録や既存データの更新などを行います。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>

		<tr><td class="menutitle">▼ 管理情報管理</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="config/" target="main">管理情報の更新</a></td></tr>
		<tr><td class="explanation">お問合せ用メールアドレスなどを設定します。<!--//お問い合せフォームが無い場合//--><!--//管理ID/パスワードの通知用メールアドレスの設定をします。//--></td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="config/change_idpw.php" target="main">管理ID/パスワードの管理</a></td></tr>
		<tr><td class="explanation">管理ID/パスワードを管理します。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>

		<tr><td class="menutitle">▼ アクセス解析</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="log/" target="main">アクセス解析</a></td></tr>
		<tr><td class="explanation">アクセス状況を解析した結果を表示します。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="./fmanager/" target="main">ファイルマネージャー</a></td></tr>
		<tr><td class="explanation">アクセスログファイル管理を行います。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>

		<tr><td class="menutitle">▼ スマホアクセス解析</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="log_sp/" target="main">アクセス解析</a></td></tr>
		<tr><td class="explanation">アクセス状況を解析した結果を表示します。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="./fmanager_sp/" target="main">ファイルマネージャー</a></td></tr>
		<tr><td class="explanation">アクセスログファイル管理を行います。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>

		<tr><td class="menutitle">▼ タブレットアクセス解析</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="log_tb/" target="main">アクセス解析</a></td></tr>
		<tr><td class="explanation">アクセス状況を解析した結果を表示します。</td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<tr><td class="subtitle">・<a href="./fmanager_tb/" target="main">ファイルマネージャー</a></td></tr>
		<tr><td class="explanation">アクセスログファイル管理を行います。</td></tr>

	</table>
	<div class="largespace"></div>
	<!--メニューテーブルここまで-->
<div align="center">
<form action="./" method="post" target="_parent">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</div>
</body>
</html>
