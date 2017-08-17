<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
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
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../tag_pg/cms.js" type="text/javascript"></script>
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

<p class="page_title"><?php echo S6_1TITLE;?>：既存データ編集画面</p>
<p class="explanation">
▼現在のデータ内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>
<form action="./" method="post" enctype="multipart/form-data" onSubmit="return confirm_message(this);" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■データの更新</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">タイトル：</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $fetch[0]["TITLE"];?>" size="60" maxlength="127" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">一覧用本文：</th>
		<td class="other-td">
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'b'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'i'); return false;"><img src="../tag_pg/img/text_italic.png" width="16" height="16" alt="斜体" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
			<br>

		<textarea name="content" cols="85" rows="10" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $fetch[0]["CONTENT"];?></textarea>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">詳細用本文：</th>
		<td class="other-td">
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'b'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'i'); return false;"><img src="../tag_pg/img/text_italic.png" width="16" height="16" alt="斜体" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
			<br>

		<textarea name="detail_content" cols="85" rows="10" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $fetch[0]["DETAIL_CONTENT"];?></textarea>
		</td>
	</tr>

	<?php for($i=1;$i<=S6_1IMG_CNT;$i++):?>
	<tr>
		<th nowrap class="tdcolored"><?php if($i==1)echo "画像";
											if($i==2)echo "詳細画像";
											if($i>=3)echo "拡大画像".($i-2);?>：</th>
		<td height="35" class="other-td">
		<?php if(file_exists(S6_1IMG_PATH.$fetch[0]['RES_ID']."_".$i.".jpg")):?>
		現在表示中の画像<br>
		<img src="<?php echo S6_1IMG_PATH.$fetch[0]['RES_ID']."_".$i.".jpg";?>?r=<?php echo rand();?>"><br>
		<input type="checkbox" name="del_img[]" value="<?php echo $i;?>" id="<?php echo $i;?>"><label for="<?php echo $i;?>">この画像を削除</label>
		<br>
		<?php endif;?>
		アップロード後画像サイズ：<strong>横<?php if($i==1)echo S6_1IMGSIZE_MX1;
												   if($i==2)echo S6_1IMGSIZE_MX2;
												   if($i>=3)echo S6_1IMGSIZE_LX;?>px×縦
											<?php /*if($i==1)echo S6_1IMGSIZE_MY1;
												   if($i==2)echo S6_1IMGSIZE_MY2;
												   if($i>=3)echo S6_1IMGSIZE_LY;echo "px";*/?> 自動算出</strong>
		<br>
		<input type="file" name="up_img[<?php echo $i;?>]" value="">
		</td>
	</tr>
	<?php endfor;?>

	<tr>
		<th nowrap class="tdcolored">資料書類アップロード:</th>
		<td class="other-td">
		<?php

		//アップできる使用ファイルが制限数より下回っていたらアップできるようにする
		if((W3_DBMAX_CNT > $fetch_PDF_NUM[0]['CNT']) || (file_exists(S6_1IMG_PATH.$fetch[0]["RES_ID"].".".$old_extension))){?>

		<?php if(file_exists(S6_1IMG_PATH.$res_id.".".$old_extension)):?>
		<a href="<?php echo S6_1IMG_PATH.$fetch[0]["RES_ID"].".".$old_extension;?>" target="_blank"><img src="./icon_img/icon_<?php echo $old_extension;?>.jpg" border="0"></a><br>
		ファイルサイズ：<?php echo $old_size;?><br>
		MIMEタイプ　　：<?php echo $old_type;?><br>
		<input type="checkbox" name="del_pdf" value="1" id="dpdf_flg"><label for="dpdf_flg">このファイルを削除</label><br>
		<?php endif;?>
		<input type="file" name="up_file_pdf" value=""><br>

		※アップロードファイルサイズ：<strong><?php echo (LIMIT_SIZE - 1);#余裕を持たす為アップできる容量より小さく表記しておく?>MB以内</strong>
		<?php }else{?>
		登録できる資料ファイルの制限個数を越えております。
		<?php }?>
	  　</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">表示／非表示：</th>
		<td class="other-td">
		<input name="display_flg" id="dispon" type="radio" value="1"<?php echo ($fetch[0]["DISPLAY_FLG"]==1)?" checked":"";?>>
		<label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"<?php echo ($fetch[0]["DISPLAY_FLG"]==0)?" checked":"";?>>
		<label for="dispoff">非表示</label>
		</td>
	</tr>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">

<input type="hidden" name="old_type" value="<?php echo $old_type;?>">
<input type="hidden" name="old_size" value="<?php echo $old_size;?>">
<input type="hidden" name="old_extension" value="<?php echo $old_extension;?>">

</form>

<form action="./" method="post">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
	<input type="hidden" name="p" value="<?php echo $p;?>">
</form>

<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>
</body>
</html>