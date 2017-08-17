<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
View：メルマガの送信する内容を入力する

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

if( !$injustice_access_chk){
	header("Location: ../"); exit();
}

#=================================================================================
# メールを送信する件数を調べる
#=================================================================================

	$sql_cnt = "
	SELECT
		SENDMAIL_FLG,
		MEMBER_ID
	FROM
		" . MEMBER_LST . "
	WHERE
		SENDMAIL_FLG = '1'
	ORDER BY
		UPD_DATE ASC
	";

// DBの取得データをセッションに格納
$fetchCNT = dbOpe::fetch($sql_cnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
<title></title>
<script type="text/javascript" src="common.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
	</tr>
</table>
<p class="page_title"><?php echo MEMBER_TITLE;?>：メルマガ内容作成</p>
<p class="explanation">▼件名と内容を入力して送信ボタンを押してください。</p><br>
<form action="./" method="post" style="margin:0px;" name="sendform" onSubmit="return inputChk(this,true)">
	<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr align="left">
	<th width="80" class="tdcolored">件名：</th>
	<td class="other-td"><?php echo ($SUBJECT)?$SUBJECT:"&nbsp;";?><input name="SUBJECT" id="SUBJECT" type="hidden" value="<?php echo $SUBJECT;?>"></td>
	</tr>
	<tr align="left">
	<th class="tdcolored">内容：</th>
	<td class="other-td"><?php echo ($COMMENT)?nl2br($COMMENT):"&nbsp;";?><input type="hidden" name="COMMENT" id="COMMENT" value="<?php echo $COMMENT;?>"></td>
	</tr>
	</table>
	<br>
	<input name="Submit" type="submit" value="送信する" style="width:150px;">
	<input type="hidden" name="status" value="send_mail">
	<input type="hidden" name="action" value="send_data">
	</form><br>
	<form action="./" method="post" style="margin:0;">
	<input type="submit" value="前に戻り修正する" name="action" style="width:150px;">
	<input type="hidden" name="SUBJECT" value="<?php echo $SUBJECT;?>">
	<input type="hidden" name="COMMENT" value="<?php echo $COMMENT;?>">
	<input type="hidden" name="status" value="edit_insert_comment">
</form>
<br>
	<form action="./" method="post" style="margin:0px;">
		<input type="submit" value="ユーザー検索画面へ" style="width:150px;">
	</form>
</body>
</html>
