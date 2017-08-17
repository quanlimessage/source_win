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
			<table width="520px"  border="0" cellspacing="2" cellpadding="5" bgcolor="#999999">
			  <tr>
				<td colspan="2" align="center">下記内容で送信します。よろしければ「送信」ボタンをクリックしてください。</td>
			   </tr>
			   <tr bgcolor="#FFFFFF" align="left" style="table-layout: fixed;">
				<td width="140">名前<span style="color:crimson;">（必須）</span></td>
				<td width="380">
					<?php echo ($name)?$name:"&nbsp;";?>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>フリガナ<span style="color:crimson;">（必須）</span></td>
				<td>
					<?php echo ($kana)?$kana:"&nbsp;";?>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>郵便番号</td>
				<td>〒<?php echo ($zip1)?$zip1:"&nbsp;";?>
						-
					  <?php echo ($zip2)?$zip2:"&nbsp;";?>
                        </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>ご住所</td>
				<td>
                    			<?php echo ($state)?$state_list[$state]:"&nbsp;";?><br>
				    	<?php echo ($address1)?$address1:"&nbsp;";?><br>
				    	<?php echo ($address2)?$address2:"&nbsp;";?>
                </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>電話番号</td>
				<td><?php echo ($tel)?$tel:"&nbsp;";?></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>FAX番号</td>
				<td><?php echo ($fax)?$fax:"&nbsp;";?></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>E-MAIL<span style="color:crimson;">（必須）</span></td>
				<td><?php echo ($email)?$email:"&nbsp;";?></td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>年代</td>
				<td>
					<?php echo ($generation)?$generation_list[$generation]:"&nbsp;";?>
				</td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>職業</td>
				<td>
					<?php echo ($job)?$job_list[$job]:"&nbsp;";?>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>メルマガ配信</td>
				<td>
					<?php echo ($mailmag == 1)?"希望する":"希望しない";?>&nbsp;
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td colspan="2" align="center">

			<table width="520px"  border="0" cellspacing="0" border="0">
				<tr>
					<td width="50%">

				  		<form name="inputForm" method="post" action="./">
							<input type="submit" name="Submit" value="戻る" style="width: 150px;">
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
						</form>
					</td>
					<td width="50%">

				  		<form name="inputForm" method="post" onSubmit="return inputChk(this,true);" action="./">
							<input type="submit" name="Submit" value="送信" style="width: 150px;">
							<input type="hidden" name="action" value="completion">
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
						</form>
					</td>
				</tr>
				</table>

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
