<?php
include("news.php")
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
<link href="./css/base.css" rel="stylesheet" type="text/css">
<link href="./css/index.css" rel="stylesheet" type="text/css">
<link href="./css/news.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script language="JavaScript" type="text/JavaScript" src="./JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('./image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

	<h1>ＳＥＯワード</h1>
	<h2><a href="./"><img src="./image/header.jpg" alt="" width="760" height="55"></a></h2>

   <ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image"><br><br><br><br><br><br><h5>Winシリーズ★更新プログラム<br><br>サンプルサイト&nbsp;10･20･30<br><br><br>新着情報（Ｎ１）<br><br>
		<br><br></h5></div>
		<div id="news">

			<h3>新着情報</h3>
			<div id="nProgram">

			<dl>
				<?php for($i=0;$i<count($fetch);$i++):
					//リンク設定の初期化
						$link_f = "";//リンクの先頭
						$link_b = "";//リンクの後方

					if($link[$i]){
						if($link_flg[$i] == 2){
							$link_f = "<a href='{$link[$i]}' target=\"_blank\">";
							$link_b = "</a>";
						}
						elseif($link_flg[$i] == 3){
							$link_f = "<a href='{$link[$i]}'>";
							$link_b = "</a>";
						}

					}
				?><dt>
					<?php echo $link_f.$time[$i].$title[$i].$link_b;?>
				</dt>
				<dd>
					<?php echo $link_f.$content[$i].$link_b;?>
				</dd>
				<?php endfor;?>
			</dl>
			</div>

		</div>
		<div id="free">
			フリースペース。<br>会社の住所・連絡先を載せる、地図を載せる、特に重要な記事を載せる・・・など、自由に使えるスペースです。
		</div>
	</div>

	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="./image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="./log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
</html>