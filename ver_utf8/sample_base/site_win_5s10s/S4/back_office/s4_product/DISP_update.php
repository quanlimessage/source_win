<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：更新画面表示

******************************************************************************/

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
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../actchange.js" type="text/javascript"></script>

<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
<script type="text/javascript" src="./uploadcheck.js"></script>

<!--
<script type="text/javascript" src="../nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	//new nicEditor({panelType :'nostyle'}).panelInstance('area1');
	new nicEditor({panelType :'noimage'}).panelInstance('area1');
	//new nicEditor({panelType :'mini'}).panelInstance('area1');
	//new nicEditor({panelType :'nostylemini'}).panelInstance('area1');
});
</script>
-->
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
		<form action="sort<?php echo (S4_SORT_TYPE == 1)?"":"2";?>.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo S4_TITLE;?>：既存データ編集画面</p>
<p class="explanation">
▼現在のデータ内容が初期表示されています。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■データの更新</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">タイトル：</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $fetch[0]["TITLE"];?>" size="60" maxlength="125" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">本文：</th>
		<td class="other-td">
			<select name="fontsize" onChange="CheckObj();addFontsSize(Temp.name,this); this.options.selectedIndex=0; return false;">
			<option value="">サイズ</option>
			<option value="x-small">極小</option>
			<option value="small">小</option>
			<option value="medium">小さめ</option>
			<option value="large">中</option>
			<option value="x-large">大きめ</option>
			<option value="xx-large">大</option>
			</select>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:left;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_left.png" width="16" height="16" alt="テキストを左寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:center;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_center.png" width="16" height="16" alt="テキストを真中寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="addStyle(Temp.name,'p','text-align:right;margin-top:0px;margin-bottom:0px;'); return false;"><img src="../tag_pg/img/text_align_right.png" width="16" height="16" alt="テキストを右寄せ" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addLink(Temp.name); return false;"><img src="../tag_pg/img/link.png" width="16" height="16" alt="リンク" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'strong'); return false;"><img src="../tag_pg/img/text_bold.png" width="16" height="16" alt="太字" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();addTag(Temp.name,'u'); return false;"><img src="../tag_pg/img/text_underline.png" width="16" height="16" alt="下線" border="0"></a>
			<a href="javascript:void(0)" onClick="CheckObj();obj=Temp.name;MM_showHideLayers('<?php echo $layer_free;?>',obj.name,'show');OnLink('<?php echo $layer_free;?>',event.x,event.y,event.pageX,event.pageY); return false;"><img src="../tag_pg/img/rainbow.png" alt="テキストカラー" border="0"></a>
			<br>
		<textarea name="content"id="area1" cols="85" rows="10" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $fetch[0]["CONTENT"];?></textarea></td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">before画像：</th>
		<td height="35" class="other-td">
		<?php if(search_file_flg(S4_IMG_PATH,$fetch[0]['RES_ID']."_1.*") and !$copy_flg):?>
			<?php echo search_file_disp(S4_IMG_PATH,$fetch[0]['RES_ID']."_1.*","",1);?><br>
			現在表示中の画像<br>
			<input type="checkbox" name="del_img[]" value="1" id="1"><label for="1">この画像を削除</label>
			<br>
		<?php endif;?>
		アップロード後画像サイズ：<strong>横<?php echo S4_IMGSIZE_MX1;?>px×縦<?php //echo S4_IMGSIZE_MY1."px";?> 自動算出</strong>
		<br>
		<input type="file" name="up_img[1]" value="" class="chkimg">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">after画像：</th>
		<td class="other-td">
		<?php if(search_file_flg(S4_IMG_PATH,$fetch[0]['RES_ID']."_2.*") and !$copy_flg):?>
			<?php echo search_file_disp(S4_IMG_PATH,$fetch[0]['RES_ID']."_2.*","",1);?><br>
			現在表示中の画像<br>
			<input type="checkbox" name="del_img[]" value="2" id="2"><label for="2">この画像を削除</label>
			<br>
		<?php endif;?>

			アップロード後画像サイズ：<strong>横<?php echo S4_IMGSIZE_MX2;?>px×縦<?php //echo S4_IMGSIZE_MY2."px";?> 自動算出</strong>
			<br>
			<input type="file" name="up_img[2]" value="" class="chkimg">

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
<input type="submit" value="更新する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">
<input type="hidden" name="act" value="completion">
<?php if($copy_flg){?>
<input type="hidden" name="regist_type" value="new">
<input type="hidden" name="copy_type" value="new">
<?php }else{ ?>
<input type="hidden" name="regist_type" value="update">
<?php }?>
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">

<input type="submit" value="プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?php echo PREV_PATH;?>')">

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
