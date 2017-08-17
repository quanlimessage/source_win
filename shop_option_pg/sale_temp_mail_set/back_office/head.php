<?php
/*******************************************************************************
バックオフィス

	ヘッダー表示

2005/4/7 tanaka
2005/7/27 : uzura
*******************************************************************************/
require_once("../common/INI_config.php");
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<link href="for_bkmanu.css" rel="stylesheet" type="text/css">
</head>
<body>
<a href="../" target="_blank"><img src="img/header.jpg" hspace="3" vspace="3" border="0" align="left"></a>
</body>
</html>