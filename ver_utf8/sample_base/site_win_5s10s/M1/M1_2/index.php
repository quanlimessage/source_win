<?php
include("login.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
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
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

   <ul id="menu">

   <li><b>N系プログラム</b></li>
		<li><a href="../N1/">新着情報&nbsp;(N1)&nbsp;ﾘﾝｸﾅｼ</a></li>
		<li><a href="../N2/">新着情報&nbsp;(N2)&nbsp;ﾎﾞﾀﾝ</a></li>
		<li><a href="../N3_1/">新着情報&nbsp;(N3_1)&nbsp;ﾍﾟｰｼﾞ</a></li>
		<li><a href="../N3_2/">新着情報&nbsp;(N3_2)&nbsp;ﾎﾟｯﾌﾟ</a></li>
		<li><a href="../N4_1/">テロップ&nbsp;(N4_1)&nbsp;ｼｮｳｻｲ</a></li>
		<li><a href="../N4_2/">テロップ&nbsp;(N4_2)&nbsp;ﾘﾝｸ</a></li>
		<li><a href="../N5/">テロップ&nbsp;(N5)&nbsp;ﾘﾝｸﾅｼ</a></li>
		<li><a href="../N6_1/">日記&nbsp;(N6_1)</a></li>
		<li><a href="../N6_2/">日記&nbsp;(N6_2)</a></li>
	<li><b>S系プログラム</b></li>
		<li><a href="../S1/">商品紹介&nbsp;(S1)</a></li>
		<li><a href="../S2/">商品紹介&nbsp;(S2)</a></li>
		<li><a href="../S3A/">商品紹介&nbsp;(S3A)</a></li>
		<li><a href="../S3B/">商品紹介&nbsp;(S3B)</a></li>
		<li><a href="../S4/">before／after&nbsp;(S4)</a></li>
		<li><a href="../S5_1/">商品紹介&nbsp;(S5_1)</a></li>
		<li><a href="../S5_2/">商品紹介&nbsp;(S5_2)</a></li>
		<li><a href="../S6_1/">商品紹介&nbsp;(S6_1)</a></li>
		<li><a href="../S6_2/">商品紹介&nbsp;(S6_2)</a></li>
		<li><a href="../S6_3/">商品紹介&nbsp;(S6_3)</a></li>
		<li><a href="../S6_4/">商品紹介&nbsp;(S6_4)</a></li>
		<li><a href="../S7/">商品紹介&nbsp;(S7)</a></li>
		<li><a href="../S9/">商品紹介&nbsp;(S9)</a></li>
		<li><a href="../S10/">商品紹介&nbsp;(S10)</a></li>
		<li><a href="../S14/">ピックアップ商品&nbsp;(S14)</a></li>
	<li><b>W系プログラム</b></li>
		<li><a href="../W1/">リンク集&nbsp;(W1)</a></li>
		<li><a href="../W3/">ファイルダウンロード(W3)</a></li>
	<li><b>P系プログラム</b></li>
		<li><a href="../P1/">Flashの画像&nbsp;(P1)</a></li>
	<li><b>A系プログラム</b></li>
		<li><a href="../A1/">お問い合わせ&nbsp;(A1)</a></li>
	<li><b>B系プログラム</b></li>
		<li><a href="../B1/">掲示板&nbsp;(B1)</a></li>
	<li><b>D系プログラム</b></li>
		<li><a href="../D1/">ファイルの書き出し&nbsp;(D1)</a></li>
	<li><b>H系プログラム</b></li>
		<li><a href="../member/">メール配信&nbsp;(H1A)</a></li>
		<li><a href="../back_office/">アクセス解析&nbsp;(H2)</a></li>
	<li><b>R系プログラム</b></li>
		<li><a href="../R1/" target="_blank">AIブログ&nbsp;(R1)</a></li>
		<li><a href="../">トップページ</a></li>
	</ul>

	<div id="main">
		<div id="index_image"><br><br><br><br><br><br><h5>Winシリーズ★更新プログラム<br><br>サンプルサイト&nbsp;10･20･30<br><br><br>会員ページ（Ｍ１）<br><br>
		<br>
		<br></h5>
		<br><br><br>
<?php
  	//会員でログインしている場合、会員用ショッピングページへ移動
	if(!($_SESSION["ID"] && $_SESSION["PASS"])){//会員でログインしている場合
	?>
	<form method="post" action="./">
		<table width="300" border="1">
			<tr>
				<td colspan="2">会員ID/PW</td>
			</tr>
			<tr>
				<td width="100">ID</td>
				<td width="200" align="left"><input type="text" name="id" size="20" maxlength="10" style="ime-mode:disabled;width: 150px;height: 15px;"></td>
			</tr>
			<tr>
				<td width="100">PASSWORD</td>
				<td width="200" align="left"><input type="password" name="pass" size="20" maxlength="10" style="ime-mode:disabled;width: 150px;height: 15px;"></td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="submit" value="ログイン" style="width: 150px;margin: 5px;">
				<input type="hidden" name="state" value="log_in">
				</td>
			</tr>
		</table>
		<br><br>
		<?php echo $erro_mes;?>

	</form>
	<?php }else{
	//ログインに成功した場合
	?>
		<table width="300" border="1">
			<tr>
				<td colspan="2">会員ID/PW</td>
			</tr>
			<tr>
				<td width="100">会員名</td>
				<td width="200" align="left">&nbsp;<?php echo $_SESSION["ID"];?>様</td>
			</tr>
			<tr>
				<td colspan="2">
				<form method="post" action="./">
				<input type="submit" value="ログアウト" style="width: 150px;margin: 5px;">
				<input type="hidden" name="state" value="log_out">
				</td>
			</tr>
		</table>
	<?php } ?>

		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
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