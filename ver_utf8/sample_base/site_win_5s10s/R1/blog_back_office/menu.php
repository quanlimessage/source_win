<?php
/*******************************************************************************

    メニュー画面

*******************************************************************************/
session_start();
require_once("../common/blog_config.php");

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ./err.php");
    exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
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
			▼ 投稿管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="entry/" target="main">エントリー登録・更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			新規で記事をエントリーする場合<br>または既存の記事の更新を行います。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="comment/" target="main">コメント管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			エントリーした記事に対しての投稿されたコメントの管理を行います。<br>
			不適切な記事等の削除も行えます。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="menutitle">
			▼ カテゴリー管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="menu/" target="main">カテゴリー登録・更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			カテゴリーの登録・更新を行います。
			</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="menu/sort/" target="main">カテゴリー並び替え</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			カテゴリーの並び替えを行います。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="menutitle">
			▼ タイトル・デザイン管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="title/" target="main">ブログタイトル・デザイン管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			ブログのタイトルサブタイトル<br>デザインの登録・変更を行います。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="menutitle">
			▼ プロフィール管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="profile/" target="main">プロフィール管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			ブログのプロフィールの登録・変更を行います。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="menutitle">
			▼ メールアドレス管理
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="config/change_config.php" target="main">メールアドレスの更新</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			ブログ用管理ID、パスワード返信用メールアドレスを設定します。
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			・<a href="config/change_idpw.php" target="main">ブログ用管理ID/パスワードの管理</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			ブログ専用管理ID・パスワードを管理します。
			</td>
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
