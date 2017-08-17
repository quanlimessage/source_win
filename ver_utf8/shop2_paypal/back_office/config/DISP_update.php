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
<?php if(!empty($error_mes)):?>
<p class="err">
<?php echo $error_mes;?>
</p>
<?php endif;?>
<p class="explanation">
▼修正を行う場合は下記の入力欄の内容を修正してください。<br>
▼修正を終えられましたら下記にあります「<strong>確認</strong>」と書かれたボタンを押してください。
</p>
<form action="./" method="post" onSubmit="return inputChk(this);" style="margin:0;">
	<table width="750" border="0" cellpadding="5" cellspacing="2">
		<tr align="center">
			<th colspan="2" nowrap class="tdcolored">■管理者情報更新</th>
		</tr>
		<tr>
			<th width="20%" nowrap align="right" class="tdcolored">
			メール表示用会社名：
			<br>
			<div class="detail-txt">メールで会社名として表示</div>
			</th>
			<td class="other-td"><input name="name" type="text" size="50" maxlength="50" value="<?php echo ($_POST["name"])?$_POST["name"]:$fetch[0]['NAME']; ?>" style="ime-mode:active;"></td>
		</tr>
		<tr>
			<th nowrap align="right" class="tdcolored">
			ネットショップ受注メールアドレス：
			<br>
			※ネットショップの受注用アドレス<br>
			※お客様宛メール内にも表示
			</th>
			<td class="other-td"><input name="email" type="text" size="50" maxlength="100" value="<?php echo ($_POST['email'])?$_POST['email']:$fetch[0]['EMAIL']; ?>" style="ime-mode:disabled;"></td>
		</tr>

		<tr>
			<th nowrap align="right" class="tdcolored">
			ネットショップメール表示用会社情報：
			<br>
			※利用客様宛メール内末尾に表示
			</th>
			<td class="other-td">
			<textarea name="company_info" cols="70" rows="8"><?php echo ($_POST["company_info"])?$_POST["company_info"]:$fetch[0]['COMPANY_INFO']; ?></textarea>
			<p>
			【例】<br><br>
			================================================================<br>
			○■株式会社
			<br><br>
			〒114-0024<br>
			東京都千代田区三崎町2-4-1 TUG-Iビル3F<br>
			TEL (03) 5210-3144<br>
			FAX (03) 5210-3166<br>
			<br>
			E-mail ：*****@xxxxx.com<br>
			URL　　：http://www.xxxx.jp<br>
			================================================================<br>
			<br>
			</p>
			</td>
		</tr>
		<tr>
			<th nowrap align="right" class="tdcolored">
			銀行口座情報：
			<br>
			※口座振込利用客宛メール表示
			</th>
			<td class="other-td">
			<textarea name="bank_info" cols="60" rows="5"><?php echo ($_POST["bank_info"])?$_POST["bank_info"]:$fetch[0]['BANK_INFO']; ?></textarea>
			<p>
			【例】<br><br>
			銀行名　　東京銀行 水道橋支店<br>
			普通口座　0000000<br>
			口座名　　○■社（株）<br>
			<br>
			</p>
			</td>
		</tr>
		<?php /* ?>
		<tr>
			<th nowrap align="right" class="tdcolored">
			ショッピングタイトル：
			<br>
			※カート画面のタイトル
			</th>
			<td class="other-td"><input name="shopping_title" type="text" size="40" value="<?php echo ($_POST["shopping_title"])?$_POST["shopping_title"]:$fetch[0]['SHOPPING_TITLE']; ?>" style="ime-mode:active;"></td>
		</tr>
		<tr>
			<th nowrap align="right" class="tdcolored">
			管理画面タイトル：
			<br>
			※管理画面のタイトル
			</th>
			<td class="other-td"><input name="bo_title" type="text" size="40" value="<?php echo ($_POST["bo_title"])?$_POST["bo_title"]:$fetch[0]['BO_TITLE']; ?>" style="ime-mode:active;"></td>
		</tr>
		<?php */ ?>
	</table>
	<input type="submit" value="確認" style="width:150px;">
	<input type="hidden" name="action" value="confirm">
</form>
</body>
</html>
