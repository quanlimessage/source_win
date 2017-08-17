<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
View：新規登録画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,false,true);
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
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title"><?php echo N5_TITLE;?>：新規登録</p>
<p class="explanation">
▼新規データの登録画面です。<br>
▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてデータを登録してください。<br>
▼最大登録表示文字数は日本語で<strong><?php echo N5_TXTMAX_CNT;?>文字まで</strong>です。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" onSubmit="return confirm_message(this);" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■新規登録</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">コメント：</th>
		<td class="other-td">
		<input name="comment" type="text" value="<?php echo $comment;?>" size="<?php echo N5_TXTMAX_CNT*2;?>" maxlength="<?php echo N5_TXTMAX_CNT;?>" style="ime-mode:active;">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1" checked><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"><label for="dispoff">非表示</label>
		</td>
	</tr>
</table>
<input type="submit" value="上記の内容で登録する" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="completion">
<input type="hidden" name="regist_type" value="new">
</form>

<form action="./" method="post">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
</form>
</body>
</html>