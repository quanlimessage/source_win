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
<link rel="alternate" type="application/rss+xml" title="SampleSite RSS" href="<?php echo SITE_LINK;?>/rss.php">
</head>

<body onLoad="MM_preloadImages('./image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

	<h1>ＳＥＯワード</h1>
	<h2><a href="./"><img src="./image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">
		<li><b>メニュー</b></li>
		<li><a href="./A1/">A1</a></li>
		<li><a href="#">N3</a></li>
		<li><a href="./S7/">S7</a></li>
		<li><a href="./back_office/" target="_blank">管理画面</a></li>
	</ul>

	<div id="main">
		<div id="index_image"><br><br><br><br><br><br><h5>Winシリーズ★更新プログラム<br><br>他社サーバー動作確認用サンプルサイト&nbsp;10･20･30<br><br><br>
		<br><br></h5></div>
		<div id="news">

			<h3>新着情報</h3>
			<a href="./rss.php" target="_blank"><img src="./image/rss.gif" border="0"></a>
			<div id="nProgram">

			<dl>
				<?php for($i=0;$i<count($fetch);$i++):?>
				<dt>
					<?php echo $time[$i];?>
				</dt>
				<dd>
					<?php

					if($link[$i]){
						if($link_flg[$i] == 1){
							echo "<a href='./news_N3_2/?p={$p[$i]}#{$id[$i]}'>{$title[$i]}</a>";
						}
						elseif($link_flg[$i] == 2){
							echo "<a href='{$link[$i]}' target=\"_blank\">{$title[$i]}</a>";
						}
						elseif($link_flg[$i] == 3){
							echo "<a href='{$link[$i]}'>{$title[$i]}</a>";
						}

					}
					else{
						echo "<a href='./news_N3_2/?p={$p[$i]}#{$id[$i]}'>{$title[$i]}</a>";
					}
					?>
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
<script type="text/javascript" src="https://www.google.com/jsapi?key="></script>
<script src="https://mx16.all-internet.jp/state/state2.js" language="javascript" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var s_obj = new _WebStateInvest();
var _accessurl = setUrl();
document.write('<img src="./log.php?referrer='+escape(document.referrer)+'&st_id_obj='+encodeURI(String(s_obj._st_id_obj))+'" width="1" height="1">');
//-->
</script>

</body>
</html>