<?php
/************************************************************************

 お問い合わせフォーム（POST渡しバージョン）
 View：完了画面

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){header("HTTP/1.0 404 Not Found");exit();}

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
			<?php if(!$err_mes){?>
				正常に送信されました。<br>
				会員登録いただき、誠にありがとうございます。<br>
				確認のため自動返信メールをお送り致しました。
			<?php }else{
			//エラー内容を表示します。

			echo "<span style=\"color:#FF0000>{$err_mes}</span>;\"";
			 }?>
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
