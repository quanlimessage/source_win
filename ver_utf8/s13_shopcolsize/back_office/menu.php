<?php
/*******************************************************************************

	メニュー画面

*******************************************************************************/
session_start();
require_once("../common/INI_config.php");		// 共通設定情報
require_once("../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("HTTP/1.0 404 Not Found");exit();
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
<title><?php echo BO_TITLE;?></title>
<link href="for_bkmanu.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
<form action="./" method="post" target="_parent">
	<input name="submit" type="submit" style="width:150px;" value="管理画面トップへ">
  </form>
</div>
<p><strong><font color="#666666">処理を選択してください</font></strong></p>
<font color="#FFFFFF">
<!--メニューテーブル-->
</font>

<div class="largespace"></div>
<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			▼ 商品管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="product/" target="main">商品の登録・更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			商品の新規登録・既存商品データの更新を行います。<br>
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="sort/" target="main">商品の並び替え</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			商品販売ページ(一覧ページ)での表示順番を設定します。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./color_category/" target="main">カラーの更新</a>
			</td>
		</tr>

		<tr>
  		<td class="explanation"> カラーの新規登録や既存データの更新などを行います。<br>
			また、表示順番の変更等の管理もできます。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./size_category/" target="main">サイズの更新</a>
			</td>
		</tr>

		<tr>
  		<td class="explanation"> サイズの新規登録や既存データの更新などを行います。<br>
			また、表示順番の変更等の管理もできます。
			</td>
		</tr>
	</table>
	<div class="largespace"></div>

	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			▼ 顧客/売上管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="user/" target="main">ユーザー検索</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			顧客データの検索・閲覧を行います。<br>
			<br>各顧客データ画面から顧客情報の編集も行えます。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="sales/" target="main">売上管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			受注情報の検索・閲覧を行います。
			<br><br>
			決済状況・配送状況の管理も行えます。
			</td>
		</tr>
	</table>
	<div class="largespace"></div>
	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			▼ 管理者情報管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./config/" target="main">管理者情報の更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			受付用メールアドレスの設定や自動返信メールへの添付会社情報などを設定します。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./config_cnt/" target="main">お問い合わせフォーム用<br>
			&nbsp;管理者情報の更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			お問合せ用メールアドレスなどを設定します。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./tax/" target="main">消費税の変更</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			消費税を設定します。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./changepass/" target="main">管理ID/パスワードの管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			管理ID/パスワードを管理します。
			</td>
		</tr>
	</table>

	<div class="largespace"></div>
	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			▼ アクセス解析
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./log/" target="main">アクセス解析</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			アクセス状況を見る事ができます。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="./fmanager/" target="main">ファイルマネージャー</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			アクセスログファイル管理を行います。
			</td>
		</tr>
	</table>

<div align="center">
<form action="./" method="post" target="_parent">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</div>
</body>
</html>
