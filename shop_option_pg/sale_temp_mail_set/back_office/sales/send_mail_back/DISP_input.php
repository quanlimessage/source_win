<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if(!$accessChk){
	header("Location: ../../index.php");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>

	</tr>
</table>

<p class="page_title">売上管理：メール送信編集画面</p>
<p class="explanation">
▼メール送信のデータ内容が初期表示されています。<br>

</p>
<form action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■メール送信内容</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">件名：</th>
		<td class="other-td">
		<input name="subject" type="text" value="<?php echo ($subject)?$subject:$fetchTMPL[0]["SUBJECT"];?>" size="150" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">本文：</th>
		<td class="other-td">
		<textarea name="tmpl_data" cols="120" rows="40" ><?php echo $tmpl_data;?></textarea>

		</td>
	</tr>
</table>
<input type="submit" value="確認画面へ" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="confirm">
<input type="hidden" name="status" value="<?php echo $status;?>">
<input type="hidden" name="target_order_id" value="<?php echo $target_order_id;?>">
</form>

<form action="../" method="post">
<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

</body>
</html>