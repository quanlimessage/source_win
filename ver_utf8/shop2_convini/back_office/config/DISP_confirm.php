<?php
/*******************************************************************************
管理者情報
View：既存管理者情報の登録内容修正　情報入力画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title><?php echo BO_TITLE; ?></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">管理情報の更新</p>
<p class="explanation">
▼これで宜しければ下記にあります<b>「更新を確定する」</b>をクリックしてください。<br>
▼内容を修正する場合は<b>「戻る」</b>をクリックしてください。
</p>
<table width="750" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" align="center" nowrap class="tdcolored">■管理者情報更新</th>
	</tr>
	<tr>
		<th width="20%" nowrap align="right" class="tdcolored">
		メール表示用会社名：
		<br>
		※メールで会社名として表示
		</th>
		<td class="other-td"><?php echo $name;?></td>
	</tr>
	<tr>
		<th nowrap align="right" class="tdcolored">
		ショップ用メールアドレス：<br>
		※ネットショップ受注用メールアドレス<br>
		※お客様宛メール内にも表示
		</th>
		<td class="other-td"><?php echo $email;?></td>
	</tr>
	<tr>
		<th nowrap align="right" class="tdcolored">
		ネットショップメール表示用会社情報：
		<br>
		※利用客宛メール内末尾に表示する
		</th>
		<td class="other-td"><?php echo nl2br($company_info);?></td>
	</tr>
	<tr>
		<th nowrap align="right" class="tdcolored">
		振込先銀行口座情報：
		<br>
		※口座振込利用客宛メールに表示
		</th>
		<td class="other-td"><?php echo nl2br($bank_info);?></td>
	</tr>
<!--	<tr>
		<th nowrap align="right" class="tdcolored">
		ショッピングタイトル：
		<br>
		※カート画面のタイトル
		</th>
		<td class="other-td"><?php //echo $shopping_title;?></td>
	</tr>
	<tr>
		<th nowrap align="right" class="tdcolored">
		管理画面タイトル：
		<br>
		※管理画面のタイトル
		</th>
		<td class="other-td"><?php //echo $bo_title;?></td>
	</tr>-->
</table>
<form action="./" method="post" onSubmit="return regChk(this);" style="margin:0;">
	<input type="submit" value="更新を確定する" style="width:150px;">
	<input type="hidden" name="name" value="<?php echo $name;?>">
	<input type="hidden" name="email" value="<?php echo $email;?>">
	<input type="hidden" name="company_info" value="<?php echo $company_info;?>">
	<input type="hidden" name="bank_info" value="<?php echo $bank_info;?>">
	<input type="hidden" name="shopping_title" value="<?php echo $shopping_title;?>">
	<input type="hidden" name="bo_title" value="<?php echo $bo_title;?>">
	<input type="hidden" name="action" value="completion">
</form>
<form action="./" method="post" style="margin:0;">
	<input type="submit" value="戻る" name="action" style="width:150px;">
	<input type="hidden" name="name" value="<?php echo $name;?>">
	<input type="hidden" name="email" value="<?php echo $email;?>">
	<input type="hidden" name="company_info" value="<?php echo $company_info;?>">
	<input type="hidden" name="bank_info" value="<?php echo $bank_info;?>">
	<input type="hidden" name="shopping_title" value="<?php echo $shopping_title;?>">
	<input type="hidden" name="bo_title" value="<?php echo $bo_title;?>">
	<input type="hidden" name="action" value="edit">
</form>
</body>
</html>
