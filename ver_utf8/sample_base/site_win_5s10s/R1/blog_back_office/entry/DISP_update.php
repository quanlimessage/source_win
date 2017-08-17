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
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../actchange.js" type="text/javascript"></script>
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
		<!--
		<form action="sort.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		<input type="hidden" name="ca" value="<?php //echo $ca;?>">
		</form>
		-->
		</td>
	</tr>
</table>

<p class="page_title">更新プログラム管理：既存記事編集画面</p>
<p class="explanation">
▼現在の記事内容が初期表示されています。<br>
▼登録した画像は<strong>[画像]</strong>ボタンで本文中にタグとして挿入されます。<br>
▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。
</p>

<form name="new_regist" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="600" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th nowrap colspan="2" class="tdcolored2">■エントリー内容更新</th>
	</tr>
	<tr>
		<td class="entry-td" width="50%" align="center">タイトル<br>
		<input name="title" type="text" value="<?php echo $fetch[0]["TITLE"];?>" size="50" maxlength="200"><br>
		</td>
		<td class="entry-td" width="50%" align="center">カテゴリー<br>
		<select name="category_code">
		<?php for ($i=0;$i<count($fetch_Ca);$i++):?>
		<option value="<?php echo $fetch_Ca[$i]["CATEGORY_CODE"];?>"<?php echo ($fetch[0]["CATEGORY_CODE"]==$fetch_Ca[$i]["CATEGORY_CODE"])?" selected":"";?>><?php echo $fetch_Ca[$i]["CATEGORY_NAME"];?></option>
		<?php endfor;?>
		</select>
		<br>
		</td>
	</tr>

	<?php for ($i=1;$i<=IMG_COUNT;$i++):
    // 挿入ボタンをあらかじめ作成
    $button_name = (IMG_COUNT>1)?"画像".$i:"画像";
    $ins_button .= "<a href=\"javascript:void(0)\" onClick=\"CheckObj();addImg(Temp.name,'".RI_PATH."up_img/".$res_id."','".$i."'); return false;\">[".$button_name."]</a>\n";
    ?>
	<tr>
		<td class="entry-td" width="50%" align="center">
			アップロード後画像サイズ：<strong>横<?php echo IMGSIZE_MX;?>px×縦<?php //echo IMGSIZE_MY;?><!--px--> 自動算出</strong>
			<br>
			<input type="file" name="up_img[<?php echo $i;?>]" value="">
	  	</td>
		<td class="entry-td" width="50%">
			<?php if (file_exists(IMG_FILE_PATH.$fetch[0]['RES_ID']."_".$i.".jpg")):?>
			現在表示中の画像<br>
			<img src="<?php echo IMG_FILE_PATH.$fetch[0]['RES_ID']."_".$i.".jpg";?>?r=<?php echo rand();?>">
			<br><input type="checkbox" name="del_img[]" value="<?php echo $i;?>" id="del_img"><label for="del_img">この画像を削除</label>
			<?php endif;?>&nbsp;
		</td>
	</tr>
	<?php endfor;?>

	<tr>
		<td colspan="2" class="entry-co-td" align="center">
		エントリーの内容<br>
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
			<?php echo $ins_button;?>
			<br>
		<textarea name="content" cols="70" rows="20" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $fetch[0]["CONTENT"];?></textarea>

		</td>
	</tr>
	<tr>
		<td class="entry-td">
		投稿日付<br>
		<select name="y">
		<?php for ($i=2010;$i<=(date("Y")+10);$i++):?>
		<option value="<?php printf("%04d", $i);?>"<?php echo ($fetch[0]["Y"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m">
		<?php for ($i=1;$i<=12;$i++):?>
		<option value="<?php printf("%02d", $i);?>"<?php echo ($fetch[0]["M"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d">
		<?php for ($i=1;$i<=31;$i++):?>
		<option value="<?php printf("%02d", $i);?>"<?php echo ($fetch[0]["D"] == $i)?" selected":"";?>>
		<?php echo $i;?>
		</option>
		<?php endfor;?>
		</select>
		日
		</td>
		<td class="entry-td">
		公開・非公開<br>
		<input name="display_flg" id="dispon" type="radio" value="1"<?php echo ($fetch[0]["DISPLAY_FLG"]==1)?" checked":"";?>><label for="dispon">表示</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="display_flg" id="dispoff" type="radio" value="0"<?php echo ($fetch[0]["DISPLAY_FLG"]==0)?" checked":"";?>><label for="dispoff">非表示</label><br>
		ﾄﾗｯｸﾊﾞｯｸ&nbsp;許可・不許可<br>
		<input name="tb_flg" id="tbon" type="radio" value="1" <?php echo ($fetch[0]["TB_FLG"]==1)?" checked":"";?>><label for="tbon">許可</label>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="tb_flg" id="tboff" type="radio" value="0" <?php echo ($fetch[0]["TB_FLG"]==0)?" checked":"";?>><label for="tboff">不許可</label>
		</td>
	</tr>
</table>
<input type="submit" value="更新する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return inputChk(this.form);">
<?php/*<input type="submit" value="プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('../../blog/')">*/?>
<input type="hidden" name="act" value="completion">
<input type="hidden" name="regist_type" value="update">
<input type="hidden" name="res_id" value="<?php echo $fetch[0]["RES_ID"];?>">
<input type="hidden" name="p" value="<?php echo $p;?>">
</form>

<form action="<?php echo $_SERVER['PHP_SELF']."?p=".$p;?>" method="post">
	<input type="submit" value="記事リスト画面へ戻る" style="width:150px;">
</form>
<?php

//ボタン付近に表示する
cp_disp($layer_free, "0", "0");

?>

</body>
</html>
