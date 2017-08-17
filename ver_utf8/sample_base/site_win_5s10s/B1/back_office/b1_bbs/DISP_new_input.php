<?php
/*******************************************************************************
更新プログラム

	View：新規登録画面表示

2005/05/06 Author K.C
*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?> Back Office</title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<p class="page_title">BBS管理：管理人<?php echo ($master_id)?"レス":"";?>投稿</p>
<p class="explanation">
▼入力し終えたら<strong>「上記の内容で登録する」</strong>をクリックしてください。
</p>
<form action="./" method="post" enctype="multipart/form-data" onSubmit="return inputChk<?php echo ($master_id)?"2":"1";?>(this);" style="margin:0px;">
<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■管理人投稿</th>
	</tr>
	<?php if($master_id){?>
	<tr>
		<th nowrap class="tdcolored" width="20%">タイトル：</th>
		<td class="other-td"><?php echo $fetch[0]["TITLE"];?></td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">内容：</th>
		<td class="other-td"><?php echo nl2br($fetch[0]["COMMENT"]);?></td>
	</tr>
	<?php }else{?>
	<tr>
		<th nowrap class="tdcolored">タイトル：</th>
		<td class="other-td"><input name="title" type="text" id="title" size="58" style="ime-mode:active;"></td>
	</tr>
	<?php }?>
	<tr>
		<th nowrap class="tdcolored"><?php echo ($master_id)?"レス":"";?>内容：</th>
		<td class="other-td">
		    <textarea name="comment" cols="58" rows="8" wrap="VIRTUAL" id="comment" style="ime-mode:active;"></textarea>
		</td>
	</tr>
	<?php if(BBS_IMAGE == 1){?>
	<tr>
		<th nowrap class="tdcolored">画像：</th>
		<td class="other-td">
		    <input name="img_file" type="file" id="img_file" value="" size="58">
		</td>
	</tr>
	<?php }?>
</table>
<input type="submit" value="上記の内容で登録する" style="width:150px;">
	<input type="hidden" name="status" value="completion">
	<input type="hidden" name="regist_type" value="<?php echo ($master_id)?"res_new":"new";?>">
	<input type="hidden" name="master_id" value="<?php echo $master_id;?>">
	<input type="hidden" name="page" value="<?php echo $page;?>">
    <input name="name" type="hidden" id="name" value="admin">
</form>

<form action="./" method="post" enctype="multipart/form-data">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
	<input type="hidden" name="page" value="<?php echo $page;?>">
</form>
<form action="../main.php" method="post" enctype="multipart/form-data">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</body>
</html>
