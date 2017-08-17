<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：新規登録画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
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
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo CATE_TITLE;?>：新規登録</p>
<p class="explanation">
▼新規データの登録画面です。<br>
▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてデータを登録してください。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;" onSubmit="return confirm_message(this);">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■新規登録</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">カテゴリータイトル：</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $title;?>" size="60" maxlength="127" style="ime-mode:active">
		</td>
	</tr>

	<tr>
		<th nowrap class="tdcolored">カテゴリー詳細：</th>
		<td class="other-td">
		<input name="detail" type="text" value="<?php echo $detail;?>" size="60" maxlength="127" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1" checked><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"><label for="dispoff">非表示</label>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">トップに登録：</th>
		<td class="other-td">
		<input type="checkbox" name="ins_chk" value="1" id="ins_chk">※この内容を一番上に登録する場合はチェックを入れてください
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