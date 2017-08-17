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
	header("Location: ../err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

//検索条件を配列に戻す
	$target_ca = ($target_ca)?$target_ca:explode(",", $fetch[0]["CATEGORY_CODE"]);

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

<!--<script type="text/javascript" src="../nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	//new nicEditor({panelType :'nostyle'}).panelInstance('area1');
	new nicEditor({panelType :'noimage'}).panelInstance('area1');
	new nicEditor({panelType :'noimage'}).panelInstance('area2');
	//new nicEditor({panelType :'mini'}).panelInstance('area1');
	//new nicEditor({panelType :'nostylemini'}).panelInstance('area1');
});
</script>
-->
</head>
<body>
<div class="header"></div>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		<input type="hidden" name="ca" value="<?php echo $ca;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo TITLE;?>：更新画面</p>
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
		<th width="15%" nowrap class="tdcolored">カテゴリー：</th>
		<td class="other-td">
			<?php for($i=0;$i < count($fetchCA);$i++){?>
				<span style="display: block;width: 200px;float: left;">
				<input type="checkbox" name="target_ca[]" value="<?php echo $fetchCA[$i]['RES_ID'];?>" id="<?php echo $fetchCA[$i]['RES_ID'];?>" <?php echo (array_search($fetchCA[$i]['RES_ID'],$target_ca) !== FALSE )?"checked":"";?>><label for="<?php echo $fetchCA[$i]['RES_ID'];?>"><?php echo $fetchCA[$i]['CATEGORY_NAME'];?></label>&nbsp;&nbsp;
				</span>
			<?php }?>

		</td>
	</tr>

	<tr>
		<th width="15%" nowrap class="tdcolored">タイトル：</th>
		<td class="other-td">
			<input name="title" type="text" value="<?php echo $fetch[0]['TITLE'];?>" size="60" maxlength="127" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">一覧用本文：</th>
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
		<textarea name="content" cols="85" rows="10" style="ime-mode:active" id="area1" onFocus="SaveOBJ(this)"><?php echo $fetch[0]['CONTENT'];?></textarea>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">詳細用本文：</th>
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
		<textarea name="detail_content" cols="85" rows="10" style="ime-mode:active" id="area2" onFocus="SaveOBJ(this)"><?php echo $fetch[0]['DETAIL_CONTENT'];?></textarea>
		</td>
	</tr>

	<tr>
		<th width="15%" nowrap class="tdcolored">titleタグ用文章：</th>
		<td class="other-td">
		<input name="title_tag" type="text" value="<?php echo $fetch[0]['TITLE_TAG'];?>" size="120" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">Youtube：</th>
		<td class="other-td">
		<textarea name="youtube" cols="85" rows="10" style="ime-mode:inactive"><?php echo $fetch[0]['YOUTUBE'];?></textarea>
		</td>
	</tr>
	<?php for($i=1;$i<=IMG_CNT;$i++):?>
	<tr>
		<th nowrap class="tdcolored">
		<?php echo ($i==1)?"画像":"詳細用画像".($i-1);?>：</th>
		<td height="35" class="other-td">
		<?php if(search_file_flg(IMG_PATH,$fetch[0]['RES_ID']."_".$i.".*") and !$copy_flg):?>
			<?php echo search_file_disp(IMG_PATH,$fetch[0]['RES_ID']."_".$i.".*","",1);?><br>
			現在表示中の画像<br>
			<input type="checkbox" name="del_img[]" value="<?php echo $i;?>" id="<?php echo $i;?>"><label for="<?php echo $i;?>">この画像を削除</label>
			<br>
		<?php endif;?>
		アップロード後画像サイズ：<strong>横<?php echo ($i==1)?IMGSIZE_MX1:IMGSIZE_MX2;?>px×縦<?php //echo ($i==1)?IMGSIZE_MY1:IMGSIZE_MY2;echo "px";?> 自動算出</strong>
		<br>
		<input type="file" name="up_img[<?php echo $i;?>]" value="" class="chkimg">
		</td>
	</tr>
	<?php endfor;?>

	<tr>
		<th nowrap class="tdcolored">画像の拡大有/無：</th>
		<td class="other-td">
		<input name="img_flg" id="img_flg1" type="radio" value="1"<?php echo ($fetch[0]["IMG_FLG"]==1)?" checked":"";?>>
		無&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="img_flg" id="img_flg2" type="radio" value="0"<?php echo ($fetch[0]["IMG_FLG"]==0)?" checked":"";?>>
		有
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">トップに登録：</th>
		<td class="other-td">
		<input type="checkbox" name="ins_chk" value="1" id="ins_chk">※この内容を一番上に登録する場合はチェックを入れてください<br>
			※チェックをしなかった場合は一番最後に表示されます。<br>
			※最初からチェック済のカテゴリーは現在の並び順を維持します。
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

	<?php if($copy_flg){?>
		<input type="hidden" name="regist_type" value="new">
		<input type="hidden" name="copy_flg" value="1">
		<input type="hidden" name="copy_res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
	<?php }else{ ?>
		<input type="hidden" name="regist_type" value="update">
	<?php }?>

<input type="hidden" name="act" value="completion">
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">

<input type="submit" value="更新する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">

<input type="submit" value="一覧プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?php echo PREV_PATH;?>')">
<input type="submit" value="詳細プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview_d('<?php echo PREV_PATH;?>')"><br>

</form>

<form action="./" method="post">
	<input type="submit" value="一覧画面へ戻る" style="width:150px;">
	<input type="hidden" name="ca" value="<?php echo $fetch[0]["CATEGORY_CODE"];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
</form>
<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>

</body>
</html>
