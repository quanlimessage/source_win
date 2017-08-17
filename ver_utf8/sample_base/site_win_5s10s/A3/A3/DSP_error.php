<?php
/************************************************************************
お問い合わせフォーム（POST渡しバージョン）
 View：入力内容確認画面

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
}

// HTTPヘッダを直接記述して出力
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);
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
<link href="../css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="common.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

		<h1>ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">
	</ul>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>お問い合わせ&nbsp;（A3）</h5>
	<br>
		<table width="520px"  border="0" cellspacing="2" cellpadding="2" bgcolor="#999999">
		  <tr valign="top">
			<td bgcolor="#FFFFFF" align="left">
			* 個人情報の利用目的 ： お問い合わせ内容への回答のために利用いたします。<br>
			* 取得した個人情報は本人の同意無しに、目的以外では利用しません。<br>
			* 情報が漏洩しないよう対策を講じ従業員だけでなく委託業者も監督します。<br>
			* 本人の同意を得ずに第三者に情報を提供しません。<br>
			* 本人からの求めに応じ情報を開示します。<br>
			* 公開された個人情報が事実と異なる場合、訂正や削除に応じます。<br>
			* 個人情報の取扱いに関する苦情に対し、適切・迅速に対処します。 </td>
		  </tr>
		</table>
		<a name="mf"></a>
	   <form method="post" action="./" onSubmit="return inputChk(this,true)">
		<table width="520px"  border="0" cellspacing="2" cellpadding="5" bgcolor="#999999">
		  <tr>
			<td colspan="2" align="left">
			   </td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td colspan="2" align="center">
			<p style="color:#FF0000;font-weight: bold;text-align:center;">
			<br>
			恐れ入りますが、下記の内容を確認してください<br><br>
			<?php echo $error_mes;?>
			</p>
			<div align="center">
			<input type="button" value="&lt;&lt;&nbsp;前に戻り修正します" onClick="javascript:history.back();">

			<br>
			</div>
			</td>
		  </tr>
		</table>
		</form>

   </div>
	</div>

	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="../image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>
</html>
