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
<title></title>
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
▼修正を終えられましたら下記にあります「<strong>確認</strong>」と書かれたボタンを押してください。<br>
▼複数名登録される場合は、カンマ区切りで追加をお願いします。<br>
(例）info@stagegroup.jp,mail@stagegroup.j,test@stagegroup.jp
</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return inputChk(this);" style="margin:0;">

<table width="750" border="0" cellpadding="5" cellspacing="2">
		<tr align="center">
			<th colspan="2" nowrap class="tdcolored">■管理者情報更新</th>
		</tr>
		<tr>
			<th nowrap align="right" class="tdcolored">
			メールアドレス（お問い合わせフォーム）:
			<br>
			※お問合せフォームの受付アドレスに使用<br>
			※管理ID・パスワード変更時通知アドレス
			</th>
			<td class="other-td"><input name="email1" type="text" size="50" value="<?php echo ($_POST['email1'])?$_POST['email1']:$fetch[0]['EMAIL1']; ?>" style="ime-mode:disabled;"></td>
		</tr>
		<!--<tr>
			<th nowrap align="right" class="tdcolored">
			メールアドレス２（代理店向けお問い合わせフォーム）:
			<br>
			※代理店のお問合せフォームの受付アドレスに使用
			</th>
			<td class="other-td"><input name="email2" type="text" size="50" maxlength="100" value="<?php echo ($_POST['email2'])?$_POST['email2']:$fetch[0]['EMAIL2']; ?>" style="ime-mode:disabled;"></td>
		</tr>
		-->
		<tr>
			<th nowrap align="right" class="tdcolored">
			自動返信メール表示用内容：
			<br>
			※利用客様宛メール内に表示
			</th>
			<td class="other-td">
			<textarea name="content" cols="70" rows="8"><?php echo ($_POST["content"])?$_POST["content"]:$fetch[0]['CONTENT']; ?></textarea>
			</td>
		</tr>
	</table>

	<input type="submit" value="確認" style="width:150px;">
	<input type="hidden" name="action" value="confirm">
</form>
</body>
</html>
