<?php
/*******************************************************************************
SiteWin10 20 30用バックオフィス
トップページ画面

2005/10/29 Yossee
*******************************************************************************/

// 設定＆ライブラリファイル読み込み
require_once("../common/config.php");
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('authOpe.php');

#---------------------------------------------------------------------
# サイト公開後のお客様のdemoページアクセスチェック

#---------------------------------------------------------------------\
if($siteopne_flg)location($domain);

//////////////////////////////////////////////////////////////////////
// DBより全管理情報を取得し、認証ライブラリを使ってBasic認証を行う
$sql = "SELECT BO_ID AS user , BO_PW AS password FROM APP_ID_PASS";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
$result = authOpe::basicCheck2($fetch,"for Webmaster");

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>管理画面</title>
</head>

<frameset rows="80,*" cols="*" frameborder="NO" border="0" framespacing="0">
		<frame src="head.php" name="head" frameborder="no" scrolling="NO" noresize title="head" >
		<frameset cols="220,*" frameborder="NO" border="0" framespacing="0">
				<frame src="menu.php" name="menu" frameborder="no" scrolling="auto" noresize marginwidth="0" marginheight="0" title="menu">
				<frame src="main.php" name="main" frameborder="no" title="main">
		</frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
