<?php
/*******************************************************************************
バックオフィス

    ヘッダー表示

*******************************************************************************/
require_once("../common/blog_config.php");

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
<a href="../blog/" target="_blank"><img src="img/header.jpg" hspace="3" vspace="3" align="left" border="0"></a>
</body>
</html>
