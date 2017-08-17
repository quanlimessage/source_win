<?php
/*******************************************************************************
アパレル対応ショッピングカート
	ショッピングカートプログラム バックオフィス

商品の更新
View：新規商品追加：入力画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$injustice_access_chk){
//	header("HTTP/1.0 404 Not Found"); exit();
}
#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../shop_actchange.js" type="text/javascript"></script>

<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
<script type="text/javascript" src="../uploadcheck.js"></script>

<script type="text/javascript" src="common.js"></script>

</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">商品管理：新規登録</p>
<p class="explanation">
▼各項目の入力をしたら「上記の内容で登録する」をクリックして商品を登録します。
</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<input type="submit" value="下記の内容で登録する" style="width:150px;" onClick="chgsubmit();return inputChk(this.form);">
<table width="650" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■商品新規登録</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">カテゴリー：</th>
		<td class="other-td">
		<select name="category_code">
		<option value="">選択してください</option>
		<?php for($i=0;$i<count($fetchCateList);$i++){?>
		<option value="<?php echo $fetchCateList[$i]['CATEGORY_CODE']?>"<?php echo ($category_code == $fetchCateList[$i]['CATEGORY_CODE'])?" selected":"";?>><?php echo $fetchCateList[$i]['CATEGORY_NAME']?></option>
		<?php }?>
		</select>
		</td>
	</tr>
	<tr>
		<th width="150" nowrap class="tdcolored">商品番号：</th>
		<td width="500" class="other-td">
		<input name="part_no" type="text" size="20" maxlength="200" value="<?php echo $part_no;?>" style="ime-mode:disabled;">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品名：</th>
		<td class="other-td">
		<input name="product_name" type="text" style="ime-mode:active" value="<?php echo $product_name;?>" size="40" maxlength="200">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">内容量：</th>
		<td class="other-td">
		<input name="capacity" type="text" style="ime-mode:active" value="<?php echo $capacity;?>" size="40" maxlength="200">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品単価：</th>
		<td class="other-td">※税抜き価格で入力してください。<br>
		<input name="selling_price" type="text" size="20" value="<?php echo $selling_price;?>" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">備考：</th>
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

		<textarea name="item_details" cols="70" rows="8" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo $item_details;?></textarea>

		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">titleタグ用文章：</th>
		<td class="other-td">
		<input name="title_tag" type="text" value="<?php echo $title_tag;?>" size="60" style="ime-mode:active">
		</td>
	</tr>
	<tr valign="top">
		<td height="40" colspan="3">
		<p class="explanation"> <strong style="color:crimson;">▼カラー・サイズのない場合は在庫数のみ入力してください。</strong>
		</p>
		</td>
	</tr>
	<tr>
   		<th nowrap class="tdcolored">カラー・サイズと在庫設定</th>
		<td class="other-td">
		<div style="float:left; height:240px; width:460px; overflow:auto;">
		<table border="0" cellpadding="5" cellspacing="2">
		<tr class="tdcolored">
			<th nowrap>カラー</th>
			<th nowrap>サイズ</th>
			<th nowrap>在庫数</th>
		</tr>
		<?php if(count($stock) <= 5):
			for($i=0;$i<5;$i++):?>
		<tr valign="middle">
			<td align="center" class="other-td"><input name="color[]" type="text" style="ime-mode:active;" value="<?php echo $color[$i];?>" size="30"></td>
			<td align="center" class="other-td"><input name="size[]" type="text" style="ime-mode:active;" value="<?php echo $size[$i];?>" size="30"></td>
			<td align="center" class="other-td"><input name="stock[]" type="text" style="ime-mode:disabled;" value="<?php echo $stock[$i];?>" size="10"></td>
		</tr>
			<?php endfor;
		else:
			foreach($stock as $k => $v):?>
		<tr valign="middle">
			<td align="center" class="other-td"><input name="color[]" type="text" style="ime-mode:active;" value="<?php echo $color[$k];?>" size="30"></td>
			<td align="center" class="other-td"><input name="size[]" type="text" style="ime-mode:active;" value="<?php echo $size[$k];?>" size="30"></td>
			<td align="center" class="other-td"><input name="stock[]" type="text" style="ime-mode:disabled;" value="<?php echo $stock[$k];?>" size="10"></td>
		</tr>
			<?php endforeach;
		endif;?>
		</table>
		<div id="area_dynamic"></div>
		</div>
		<div style="clear:left"><input type="button" onClick="javascript:addTextbox()" value="カラー・サイズ追加"></div>
		</td>
	</tr>
	<tr>
		<th rowspan="2" nowrap class="tdcolored">サムネイル画像：</th>
		<td class="other-td">
		登録するサムネイル画像を選択してください。<br>
		※商品一覧ページにて表示される画像です。<br>
		※画像サイズは<b>横<?php echo IMG_SIZE_SX;?>px×縦 自動算出<!--<?php //echo IMG_SIZE_SY;?>px--></b>に自動整形されます。
		</td>
	</tr>
	<tr>
		<td height="35" class="other-td">
		<input type="file" name="thumbnail_file" value="" class="chkimg">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">トップに登録：</th>
		<td class="other-td">
		<input type="checkbox" name="ins_chk" value="1" id="ins_chk">※この内容を一番上に登録する場合はチェックを入れてください
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品の表示</th>
		<td class="other-td">
		<input type="radio" name="display_flg" value="1" id="disp1" checked>
		<label for="disp1">表示する</label>
		&nbsp;&nbsp;
		<input type="radio" name="display_flg" value="0" id="disp2">
		<label for="disp2">表示しない</label>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">ＮＥＷアイコンの表示</th>
		<td class="other-td">
		<label for="new_item_flg">ＮＥＷアイコンを表示させる場合はチェックを入れてください</label>
		<input type="checkbox" name="new_item_flg" value="1" id="new_item_flg" checked>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">カートの表示</th>
		<td class="other-td">
		<label for="cart_close_flg">“カートに入れる”を表示させない場合はチェックを入れてください</label>
		<input type="checkbox" name="cart_close_flg" value="1" id="cart_close_flg">
		</td>
	</tr>
	<tr>
		<td colspan="2" nowrap class="other-td">
		▼個別商品紹介ページの詳細画像に使用する画像を指定します。<br>
		※詳細画像は<strong>横<?php echo IMG_SIZE_LX;?>px×縦 自動算出<!--<?php //echo IMG_SIZE_LY;?>px--></strong>に自動整形されます。<br>
		</td>
	</tr>
	<?php for($i=1;$i<=PRODUCT_IMG_NUM;$i++):?>
	<tr>
		<th nowrap class="tdcolored">詳細画像<?php echo $i;?></th>
		<td class="other-td">
		<input type="file" name="product_img_file[<?php echo $i;?>]" value="" class="chkimg">
		</td>
	</tr>
	<?php endfor;?>
	<tr>
		<td colspan="2">
		<p class="explanation">
		▼<strong>「販売開始日時」</strong>を設定するとサイト上での販売開始日時を指定できます。指定がなければ商品登録後すぐに販売が開始されます。<br>
		▼<strong>「販売終了日時</strong>」を設定するとサイト上での販売終了日時を指定できます。指定がなければ在庫が無くなるまで商品が販売されます。
		</p>
		</td>
	</tr>
	<tr>
		<th width="150" nowrap class="tdcolored">販売期間限定</th>
		<td width="500" class="other-td">
		<strong>■販売開始日時</strong>(未設定だと、商品データ登録後すぐに販売が開始されます。)<br>
		<select name="y1">
		<option value="">未設定</option>
		<?php for($yn=2010;$yn<=(date("Y")+10);$yn++):?>
		<option value="<?php echo $yn;?>"<?php echo ($y1 == $yn)?" selected":"";?>>
		<?php echo $yn;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m1">
		<option value="">未設定</option>
		<?php for($mn=1;$mn<=12;$mn++):?>
		<option value="<?php echo $mn;?>"<?php echo ($m1 == $mn)?" selected":"";?>>
		<?php echo $mn;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d1">
		<option value="">未設定</option>
		<?php for($dn=1;$dn<=31;$dn++):?>
		<option value="<?php echo $dn;?>"<?php echo ($d1 == $dn)?" selected":"";?>>
		<?php echo $dn;?>
		</option>
		<?php endfor;?>
		</select>
		日
		<select name="h1">
		<option value="">未設定</option>
		<?php for($hn=0;$hn<=23;$hn++):?>
		<option value="<?php echo $hn;?>"<?php echo (($h1 == $hn) && (strlen($h1) != 0))?" selected":"";?>>
		<?php echo $hn;?>
		</option>
		<?php endfor;?>
		</select>
		時
		より販売開始
		<br><br>
		<strong>■販売終了日時</strong>(未設定だと、在庫が無くなるまで販売を続けます。)<br>
		<select name="y2">
		<option value="">未設定</option>
		<?php for($yn=2010;$yn<=(date("Y")+10);$yn++):?>
		<option value="<?php echo $yn;?>"<?php echo ($y2 == $yn)?" selected":"";?>>
		<?php echo $yn;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m2">
		<option value="">未設定</option>
		<?php for($mn=1;$mn<=12;$mn++):?>
		<option value="<?php echo $mn;?>"<?php echo ($m2 == $mn)?" selected":"";?>>
		<?php echo $mn;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d2">
		<option value="">未設定</option>
		<?php for($dn=1;$dn<=31;$dn++):?>
		<option value="<?php echo $dn;?>"<?php echo ($d2 == $dn)?" selected":"";?>>
		<?php echo $dn;?>
		</option>
		<?php endfor;?>
		</select>
		日
		<select name="h2">
		<option value="">未設定</option>
		<?php for($hn=0;$hn<=23;$hn++):?>
		<option value="<?php echo $hn;?>"<?php echo (($h2 == $hn) && (strlen($h2) != 0))?" selected":"";?>>
		<?php echo $hn;?>
		</option>
		<?php endfor;?>
		</select>
		時
		で販売終了
		</td>
	</tr>
</table>
<input type="submit" value="上記の内容で登録する" style="width:150px;" onClick="chgsubmit();return inputChk(this.form);">
<input type="hidden" name="status" value="product_entry_completion">
<input type="hidden" name="regist_type" value="new">

<input type="submit" value="一覧プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?php echo PREV_PATH;?>')">
<input type="submit" value="詳細プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview_d('<?php echo PREV_PATH;?>')"><br>

</form>

<form action="./" method="post">
	<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
	<input type="hidden" name="status" value="search_result">
</form>

<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>
</body>
</html>
