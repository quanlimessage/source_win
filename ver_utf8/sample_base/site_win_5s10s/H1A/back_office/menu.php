<?php
/*******************************************************************************
アパレル対応(カテゴリ二つ)
ショッピングカートプログラム バックオフィス

	メニュー画面

2005/4/12 tanaka
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
		<tr>
			<td class="menutitle">
			▼ アンケート管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="member_mail/" target="main">会員管理メール配信の更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			会員メルマガ配信などの編集・追加を行います。

			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="subtitle">
			・<a href="member_edit/" target="main">会員管理情報の更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			会員情報の編集・追加を行います。

			</td>
		</tr>

		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="mail_history/" target="main">配信履歴</a>
			</td>
		</tr>

		<tr>
			<td class="explanation">
			会員用メールマガジンの配信履歴を表示します。

			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="menutitle">
			▼ 管理情報管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="subtitle">
			・<a href="config/change_config.php" target="main">管理情報の更新</a>
			</td>
		</tr>

		<tr>
			<td class="explanation">
			お問合せ用メールアドレスなどを設定します。
			<!--//お問い合せフォームが無い場合
			管理ID/パスワードの通知用メールアドレスの設定をします。
			//-->
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="explanation">
			サンプルプログラムＨ１Ａ(SiteWin10_20_30)<br>
			こちらはデモ用のサンプルプログラムです。<br>
			サンプルプログラムのご使用は【sample_base】フォルダーのプログラムをご使用ください。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

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