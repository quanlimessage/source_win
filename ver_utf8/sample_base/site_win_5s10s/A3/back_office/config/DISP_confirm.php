<?php
/*******************************************************************************
管理者情報
View：既存管理者情報の登録内容修正　情報入力画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
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
<title></title>
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
		メールアドレス（お問い合わせフォーム）:
		<br>
		※お問合せフォームの受付アドレスに使用<br>
		※管理ID・パスワード変更時通知アドレス
		</th>
		<td class="other-td"><?php echo $email1;?></td>
	</tr>
	<!--
	<tr>
		<th nowrap align="right" class="tdcolored">
		メールアドレス２（代理店向けお問い合わせフォーム）:<br>
		※代理店のお問合せフォームの受付アドレスに使用
		</th>
		<td class="other-td"><?php echo $email2;?></td>
	</tr>
	-->
	<tr>
		<th nowrap align="right" class="tdcolored">
		自動返信メール表示用内容：
		<br>
			※利用客様宛メール内に表示
		</th>
		<td class="other-td"><?php echo nl2br($content);?></td>
	</tr>
</table>
<form action="./" method="post" onSubmit="return regChk(this);" style="margin:0;">

	<input type="submit" value="更新を確定する" style="width:150px;">

	<input type="hidden" name="email1" value="<?php echo $email1;?>">
	<input type="hidden" name="email2" value="<?php echo $email2;?>">
	<input type="hidden" name="content" value="<?php echo $content;?>">
	<input type="hidden" name="action" value="completion">
</form>
<form action="./" method="post" style="margin:0;">

	<input type="submit" value="戻る" name="action" style="width:150px;">
	<input type="hidden" name="email1" value="<?php echo $email1;?>">
	<input type="hidden" name="email2" value="<?php echo $email2;?>">
	<input type="hidden" name="content" value="<?php echo $content;?>">
	<input type="hidden" name="action" value="edit">
</form>
</body>
</html>
