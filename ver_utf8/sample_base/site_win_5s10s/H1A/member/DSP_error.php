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
</head>

<body>
<div id="stage">
 <div id="content">
<div id="stage">
 <div id="content">
  <h1>ＳＥＯワード</h1>
	<h2><img src="../image/header.jpg" alt="" width="760" height="55"></h2>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>メール配信会員登録フォーム&nbsp;（Ｈ１Ａ）</h5>
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

		<br>
		<table width="520px"  border="0" cellspacing="2" cellpadding="2" bgcolor="#999999">
		  <tr valign="top">
			<td bgcolor="#FFFFFF" align="left">
				下記のエラーが発生致しました。<br>
				戻るボタンをクリックし、再度入力してください。<br><br>
				<?php echo $error_mes; ?>
				<br><br>
				<form action="./" method="post" style="margin:0px;">

					<input type="hidden" name="action" value="">
					<input type="hidden" name="name" value="<?php echo ($name)?$name:"";?>">
					<input type="hidden" name="kana" value="<?php echo ($kana)?$kana:"";?>">
					<input type="hidden" name="zip1" value="<?php echo ($zip1)?$zip1:"";?>">
					<input type="hidden" name="zip2" value="<?php echo ($zip2)?$zip2:"";?>">
					<input type="hidden" name="state" value="<?php echo ($state)?$state:"";?>">
					<input type="hidden" name="address1" value="<?php echo ($address1)?$address1:"";?>">
					<input type="hidden" name="address2" value="<?php echo ($address2)?$address2:"";?>">
					<input type="hidden" name="tel" value="<?php echo ($tel)?$tel:"";?>">
					<input type="hidden" name="fax" value="<?php echo ($fax)?$fax:"";?>">
					<input type="hidden" name="email" value="<?php echo ($email)?$email:"";?>">
					<input type="hidden" name="generation" value="<?php echo ($generation)?$generation:"";?>">
					<input type="hidden" name="job" value="<?php echo ($job)?$job:"";?>">
					<input type="hidden" name="mailmag" value="<?php echo ($mailmag)?$mailmag:"0";?>">

					<input type="submit" name="back" value="戻る" style="width: 150px;">
				</form>
			</td>
		  </tr>
		</table>

	</div>
</div>
	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>
</body>
</html>
