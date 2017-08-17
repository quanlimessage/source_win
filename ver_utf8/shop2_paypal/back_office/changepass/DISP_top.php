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
▼確認のために現在の管理IDとパスワードを入力して「入力」をクリックしてください。
</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<form action="./" method="post" onSubmit="return inputChk1(this);" style="margin:0px;">
<table width="400" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="3" class="tdcolored">
		■現管理ID/PASSの確認
		</th>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">管理ID：</td>
		<td class="other-td">
		<input name="chk_id" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td width="150" align="left" nowrap class="tdcolored">管理パスワード：</td>
		<td class="other-td">
		<input name="chk_pass" type="password" size="10" maxlength="10" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" nowrap class="other-td"><input type="submit" value="入力" style="width:150px;"></td>
		</tr>
</table>
<br><br>
<input type="hidden" name="status" value="update">
</form>
</body>
</html>
