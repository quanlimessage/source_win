<?php
/*******************************************************************************
更新プログラム

	完了画面の出力

2005/05/06 Author K.C
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?> Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title">BBS管理：処理完了</p>
<strong>登録しました</strong>
<br><br><br><br><br>
<form action="./" method="post" enctype="multipart/form-data">
	<input type="submit" value="リスト画面へ" style="width:150px;">
	<input type="hidden" name="page" value="<?php echo $page;?>">
</form>
<form action="../main.php" method="post" enctype="multipart/form-data">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</body>
</html>
