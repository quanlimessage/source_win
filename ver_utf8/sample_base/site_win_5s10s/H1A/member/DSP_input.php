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
utilLib::httpHeadersPrint("UTF-8",true,false,false,false);
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
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>メール配信会員登録フォーム&nbsp;（Ｈ１Ａ）
	<br>
		<div style="width:300;border: solid 1px #cccccc;background-color:#FFFFFF;color:#333333;margin: 5px;padding: 5px;" align="left">
		サンプルプログラムＨ１Ａ<br>

		<ul>
			<li>こちらのフォームから会員情報の登録を行います。また、管理画面からでも会員登録を行えます。</li>
			<li>会員情報の最大登録件数は１００００件です。</li>
			<li>デモ用に作成しておりますのでSQL文は作成する案件の仕様に合わせて作成してください。</li>
			<li>会員登録者に登録内容をメールで送信をします。不必要な場合は【LGC_sendmail.php】の会員登録者宛のメール送信処理をコメントアウトしてください。</li>
			<li>同じメールアドレスでの会員登録はできません。</li>
			<li>管理画面で会員情報の変更削除が行えます。</li>
		</ul>

		こちらはデモ用のサンプルプログラムです。<br>
		サンプルプログラムのご使用は【sample_base】フォルダーのプログラムをご使用ください。
		</div>
		</h5>

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

		  <form name="inputForm" method="post" onSubmit="return inputChk(this);" action="./">
			<table width="520px"  border="0" cellspacing="2" cellpadding="5" bgcolor="#999999">
			  <tr>
				<td width="520" colspan="2" align="center">当社への会員登録は、下のフォームよりお願い致します。</td>
			   </tr>
			   <tr bgcolor="#FFFFFF" align="left" style="table-layout: fixed;">
				<td width="140">名前<span style="color:crimson;">（必須）</span></td>
				<td width="380">
					<input name="name" type="text" id="name" size="30" style="ime-mode:active;" maxlength="127" value="<?php echo $name;?>">
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>フリガナ<span style="color:crimson;">（必須）</span></td>
				<td>
					<input name="kana" type="text" id="kana" size="30" style="ime-mode:active;" maxlength="127" value="<?php echo $kana;?>">
					</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>郵便番号</td>
				<td>〒<input name="zip1" type="text" size="5" id="zip1" maxlength="3" style="ime-mode:disabled;" value="<?php echo $zip1;?>">
						-
					  <input name="zip2" type="text" size="7" id="zip2" maxlength="4" style="ime-mode:disabled;" value="<?php echo $zip2;?>">
                        </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>ご住所</td>
				<td>都道府県
                           <select name="state">
                              <option value="">▼選択してください▼</option>
                              <?php for($i=1;$i<count($state_list);$i++):?>
                              	<option value="<?php echo $i;?>" <?php echo ($state == $i)?"selected":"";?>><?php echo $state_list[$i];?></option>
                              <?php endfor;?>
                            </select><br>
				市区町村<br>
				<input name="address1" type="text" size="50" id="address1" maxlength="127" style="ime-mode:active;" value="<?php echo $address1;?>"><br>
				マンション名など<br>
				<input name="address2" type="text" size="50" id="address2" maxlength="127" style="ime-mode:active;" value="<?php echo $address2;?>">
                </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>電話番号</td>
				<td><input name="tel" type="text" id="tel" maxlength="30" size="20" style="ime-mode:disabled;" value="<?php echo $tel;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>FAX番号</td>
				<td><input name="fax" type="text" id="fax" maxlength="30" size="20" style="ime-mode:disabled;" value="<?php echo $fax;?>"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>E-MAIL<span style="color:crimson;">（必須）</span></td>
				<td><input name="email" type="text" id="email" size="40" maxlength="127" style="ime-mode:disabled;" value="<?php echo $email;?>"></td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>年代</td>
				<td>
					<?php for($i=1;$i<count($generation_list);$i++):?>
						<input type="radio" name="generation" value="<?php echo $i;?>" id="Generation<?php echo $i;?>"  <?php echo ($generation == $i)?"checked":"";?>><label for="Generation<?php echo $i;?>"><?php echo $generation_list[$i];?></label>　<br>
					<?php endfor;?>
				</td>
			  </tr>

			  <tr bgcolor="#FFFFFF" align="left">
				<td>職業</td>
				<td>
					<?php for($i=1;$i<count($job_list);$i++):?>
						<input type="radio" name="job" value="<?php echo $i;?>" id="job<?php echo $i;?>" <?php echo ($job == $i)?"checked":"";?>><label for="job<?php echo $i;?>"><?php echo $job_list[$i];?></label>　<br>
					<?php endfor;?>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>メルマガ配信</td>
				<td>
						<input type="radio" name="mailmag" value="1" id="mailmag1" <?php echo ($mailmag != "0")?"checked":"";?>><label for="mailmag1">希望する</label><br>
						<input type="radio" name="mailmag" value="0" id="mailmag2" <?php echo ($mailmag == "0")?"checked":"";?>><label for="mailmag2">希望しない</label>
				</td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td colspan="2" align="center">
					<input name="Submit" type="submit" class="font1" value="確認画面へ&nbsp;&gt;&gt;" style="width: 150px;">
					<input type="hidden" name="action" value="confirm">
				</td>

			  </tr>
			  <tr>
				<td colspan="2" align="center">※&nbsp;各項目を入力の上、”確認画面へ”のボタンを押してください。</td>
			  </tr>
			</table>
			</form>

	</div>
</div>
	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>
</body>
</html>
