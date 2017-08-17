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
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>お問い合わせ&nbsp;（Ａ１）</h5>
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
	   <form method="post" action="./#mf" onSubmit="return inputChk(this,true)">
		<table width="520px"  border="0" cellspacing="2" cellpadding="5" bgcolor="#999999">
		  <tr>
			<td colspan="2" align="left">
				<p>

				下記の内容で承ります。<br>
				入力内容に誤りがないか再度確認してください。<br>
				万が一誤りがあった場合は、「前に戻り修正します」ボタンを押して<br>
				入力画面に戻り修正してください。<br>
				間違いがなければ「上記の内容で送信します」ボタンを押してください。<br>
				後日、担当者よりご連絡いたします。
				</p>
			</td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td>名前</td>
			<td><?php echo ($name)?$name:"&nbsp;";?><input name="name" type="hidden" value="<?php echo $name;?>"></td>
		  </tr>
		   <tr bgcolor="#FFFFFF" align="left">
				<td>フリガナ<span style="color:crimson;"></span></td>
				<td><?php echo ($kana)?$kana:"&nbsp;";?><input name="kana" type="hidden" value="<?php echo $kana;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>性別<span style="color:crimson;"></span></td>
				<td><?php echo ($sex)?$sex:"&nbsp;";?><input name="sex" type="hidden" value="<?php echo $sex;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>郵便番号<span style="color:crimson;"></span></td>
				<td>〒<?php echo ($zip1)?$zip1:"&nbsp;";?><input name="zip1" type="hidden" value="<?php echo $zip1;?>">
						-
					  <?php echo ($zip2)?$zip2:"&nbsp;";?><input name="zip2" type="hidden" value="<?php echo $zip2;?>">
                </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>ご住所<span style="color:crimson;"></span></td>
				<td><?php echo ($state)?$state:"&nbsp;";?><input name="state" type="hidden" value="<?php echo $state?>"><br>
					<?php echo ($address)?$address:"&nbsp;";?><input name="address" type="hidden" value="<?php echo $address;?>">
                </td>

			</tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td>電話番号</td>
			<td><?php echo ($tel)?$tel:"&nbsp;";?><input name="tel" type="hidden" value="<?php echo $tel;?>"></td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td>FAX番号</td>
			<td><?php echo ($fax)?$fax:"&nbsp;";?><input name="fax" type="hidden" value="<?php echo $fax;?>"></td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td>メールアドレス</td>
			<td><?php echo ($email)?$email:"&nbsp;";?><input name="email" type="hidden" value="<?php echo $email;?>"></td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td>コメント</td>
			<td><?php echo ($comment)?nl2br($comment):"&nbsp;";?><input type="hidden" name="comment" value="<?php echo $comment;?>"></td>
		  </tr>
		  <tr bgcolor="#FFFFFF" align="left">
			<td colspan="2">
			<div align="center">
			<input type="button" value="&lt;&lt;&nbsp;前に戻り修正します" onClick="javascript:history.back();">

			<input name="Submit" type="submit" value="上記の内容で送信します&nbsp;&gt;&gt;">
			<input type="hidden" name="action" value="completion"><br>

			</div>
			</td>

		  </tr>
		   <tr>
			<td>&nbsp;</td>
			<td align="left">※&nbsp;各項目を入力の上、送信ボタンを押してください。</td>
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