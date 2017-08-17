<?php
/*******************************************************************************
カテゴリ対応
	ショッピングカートプログラム バックオフィス

商品の更新
View：下記の処理の完了画面

	１．新規商品登録及び、既存商品の登録内容修正
	２．在庫数更新登録

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$injustice_access_chk){
//	header("HTTP/1.0 404 Not Found"); exit();
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
<title><?php echo BO_TITLE;?> Shopping System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">商品管理：登録管理</p>
<strong>登録しました</strong>
<br><br>

<form action="./" method="post">
<input type="submit" value="商品管理画面トップへ" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>
<form action="./" method="post">
    <input type="submit" value="検索結果画面へ戻る" style="width:150px;">
    <input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
