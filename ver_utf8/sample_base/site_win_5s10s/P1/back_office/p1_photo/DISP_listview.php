<?php
/*******************************************************************************
Px系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../");exit();
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
<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
<script type="text/javascript" src="./uploadcheck.js"></script>

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
	</tr>
</table>

<p class="page_title"><?php echo P1_TITLE?>：編集画面</p>
<p class="explanation">
▼現在の表示されている画像が表示されています。<br>
画像を変更したい場合は参照ボタンから画像を選択し下の「更新する」ボタンをクリックしてください。<br>
▼画像の変更がトップページに反映されてない場合はブラウザを開き直して確認してください。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="3" nowrap class="tdcolored">■画像の更新</th>
	</tr>
	<?php for($i=1;$i<=P1_IMG_CNT;$i++):?>
	<tr>
		<th width="60px" nowrap class="tdcolored">画像<?php echo $i;?>：</th>
		<td width="<?php echo P1_IMGSIZE_MX+50;?>" height="<?php echo P1_IMGSIZE_MY+50;?>" align="center" class="other-td">
		<?php if(search_file_flg(P1_IMG_PATH,"photo0".$i.".*")):?>
		<?php echo search_file_disp(P1_IMG_PATH,"photo0".$i.".*","",1);?><br>
			現在表示中の画像<br>
			<input type="checkbox" name="del_img[]" value="<?php echo $i;?>" id="<?php echo $i;?>"><label for="<?php echo $i;?>">この画像を削除</label>
			<br>
		<?php else:?>
			&nbsp;No Flash Photo
		<?php endif;?>
		</td>
		<td width="150px" align="center" class="other-td">
			参照ボタンからアップロード<br>
			画像を選択してください。<br><br>
			アップロード後画像サイズ：<br>
			<strong>横<?php echo P1_IMGSIZE_MX.'px';?>×縦<?php echo P1_IMGSIZE_MY.'px';?></strong>
			<input type="file" name="up_img[<?php echo $i;?>]" value="" class="chkimg">
		</td>
	</tr>
	<?php endfor;?>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">
<input type="hidden" name="act" value="completion">
<input type="hidden" name="regist_type" value="update">
</form>
</body>
</html>
