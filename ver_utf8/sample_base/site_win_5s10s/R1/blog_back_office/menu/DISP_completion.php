<?php
/*******************************************************************************
ALL-INTERNET BLOG

登録、更新処理完了画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}

if (!$injustice_access_chk) {
    header("HTTP/1.0 404 Not Found");
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
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">カテゴリー管理：処理完了</p>
<strong>登録しました</strong>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<input type="submit" value="カテゴリー管理トップへ戻る" style="width:160px;">
<input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
