<?php
/*******************************************************************************
ALL-INTERNET BLOG

View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../err.php");
    exit();
}
if (!$accessChk) {
    header("Location: ../");
    exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, false, false, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		</td>
	</tr>
</table>

<p class="page_title">コメント管理：記事編集画面</p>
<p class="explanation">
▼現在の記事内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>

<form name="new_regist" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin:0px;">
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>

	  <th nowrap colspan="2" class="tdcolored2">■コメント内容更新</th>
	</tr>

	<tr>
		<td class="entry-co-td" width="50%">
		エントリーの内容<br>
		<textarea name="content" cols="40" rows="20" style="ime-mode:active"><?php echo $fetch[0]["CONTENT"];?></textarea>
		</td>
		<td class="entry-co-td" width="50%" valign="top">
		<table width="100%" height="300" border="0" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="other-td" height="40" valign="middle">公開・非公開：
			<input name="display_flg" id="dispon" type="radio" value="1"<?php echo ($fetch[0]["DISPLAY_FLG"]==1)?" checked":"";?>><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="display_flg" id="dispoff" type="radio" value="0"<?php echo ($fetch[0]["DISPLAY_FLG"]==0)?" checked":"";?>><label for="dispoff">非表示</label>
			</td>
		  </tr>
		  <tr>
			<td class="otherColTd" height="50" valign="middle">タイトル：<input name="title" type="text" value="<?php echo $fetch[0]["TITLE"];?>" size="30" maxlength="200"><br></td>
		  </tr>
		  <tr>
			<td class="other-td" height="50" valign="middle">名前：<input name="name" type="text" value="<?php echo $fetch[0]["NAME"];?>" size="30" maxlength="200"><br></td>
		  </tr>
		  <tr>
			<td class="otherColTd" height="50" valign="middle">E-MAIL：<input name="e_mail" type="text" value="<?php echo $fetch[0]["EMAIL"];?>" size="30" maxlength="200"><br></td>
		  </tr>
		  <tr>
			<td class="other-td" height="50" valign="middle">投稿日付： <?php echo $fetch[0]["DISP_DATE"];?></td>
		  </tr>
		  <tr>
			<td class="otherColTd" height="50" valign="middle">IPアドレス： <?php echo $fetch[0]["IP"];?></td>
		  </tr>
		</table>
		</td>
	</tr>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="comment_id" value="<?php echo $fetch[0]["COMMENT_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">

</form>

<form action="<?php echo $_SERVER['PHP_SELF']."?p=".$p;?>" method="post">
	<input type="submit" value="記事リスト画面へ戻る" style="width:150px;">
</form>

</body>
</html>
