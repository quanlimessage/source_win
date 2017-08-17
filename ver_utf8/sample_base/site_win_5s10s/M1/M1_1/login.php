<?php
/*******************************************************************************
ID PASSWORDでの会員用ページの表示（Ｍ１）

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config.php");	// 設定ファイル
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('authOpe.php');

//////////////////////////////////////////////////////////////////////
// DBより全管理情報を取得し、認証ライブラリを使ってBasic認証を行う
$sql = "SELECT ID AS user , PW AS password FROM M1_PRODUCT_LST";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
$result = authOpe::basicCheck2($fetch,"for Webmaster");

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
	header("HTTP/1.0 404 Not Found");exit();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<meta http-equiv="Content-script-type" content="text/javascript">
<meta http-equiv="Content-style-type" content="text/css">
<meta http-equiv="imagetoolbar" content="no">
<meta name="description" content="ＳＥＯワード">
<meta name="keywords" content="キーワード">
<meta name="robots" content="index,follow">
<title>Winシリーズ-更新プログラム|サンプルサイト|</title>
<link href="../css/base.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
<link href="../css/news.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
 <div id="content">

  <h1>ＳＥＯワード</h1>
	<h2><img src="../image/header.jpg" alt="" width="760" height="55"></h2>

   <ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image"><br><br><h5>Winシリーズ★更新プログラム<br><br>サンプルサイト&nbsp;10･20･30<br><br><br>会員ページ（Ｍ１）<br><br>
		<br>
		<br></h5>
		<br>
		会員ページへようこそ
		<br>
		<br>
		<a href="./">最初の画面に戻る</a>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
	</div>
	</div>
	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="../image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="../log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
</html>