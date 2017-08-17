<?php
/*******************************************************************************
管理者ID/PASSの管理

	ID/PASSの更新画面出力

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

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<script type="text/javascript" src="inputCheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ID/PASS管理</p>
<p class="explanation">
▼管理ID、管理パスワードを変更したい場合は下記に新IDと新パスワードを入力後、<strong>「ID/パスワードを更新する」</strong>をクリックしてください。<br>
▼ID/パスワードを変更すると自動的に通知メールが<strong>「管理者情報」</strong>で登録した<strong>「管理用メールアドレス1」</strong>に送信されます。<br>
▼通知メールに変更後の新ID、新パスワードを確認用に記載するには<strong>「通知メールに新ID・新パスワードを記載する」</strong>へチェックを入れてください。
</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<form action="./" method="post" onSubmit="return inputChk2(this);" style="margin:0px;">
<table width="400" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="3" class="tdcolored">
		■管理ID/PASSの変更
		</th>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理ID：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_id" type="text" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理パスワード：</td>
		<td class="other-td">
		※半角英数字で4文字以上8文字以内<br>
		<input name="new_pw" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">新しい管理パスワード(確認)：</td>
		<td class="other-td">
		※再度新しい管理パスワードを入力してください。<br>
		<input name="new_pw2" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td class="other-td" colspan="2">
		※管理IDと管理パスワードを変更すると管理者用メールアドレス宛にID/パスワードが変更された旨の通知メールが送信されます。<br>
		メール内容に新規IDと新規パスワードを記載する場合はチェックを入れてください。<br>
		<input type="checkbox" name="notice" value="1" id="1"><label for="1"><strong>通知メールに新ID・新パスワードを記載する</strong></label>
		<br><br>
		<input type="submit" value="ID/パスワードを更新する" style="width:150px;">
		</td>
		</tr>
</table>
<br><br>
<input type="hidden" name="status" value="completion">
<input type="hidden" name="color_id" value="<?php echo $color_id;?>">
</form>
</body>
</html>
