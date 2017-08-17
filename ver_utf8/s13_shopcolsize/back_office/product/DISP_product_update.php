<?php
/*******************************************************************************
カテゴリ対応
	ショッピングカートプログラム バックオフィス

商品の更新
View：既存商品の登録内容修正：入力画面

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

</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">商品管理：更新</p>
<p class="explanation">
▼更新したいデータを上書きして「上記の内容で登録する」をクリックしてください。
</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<input type="submit" value="下記の内容で登録する" style="width:150px;" onClick="chgsubmit();return inputChk(this.form);">
<br>
<table width="650" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="3" nowrap class="tdcolored">■商品データ更新</th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">カテゴリー：</th>
		<td colspan="2" class="other-td">
		<select name="category_code">
		<option value="">選択してください</option>
		<?php for($i=0;$i<count($fetchCateList);$i++):
			if($fetchCateList[$i]['CATEGORY_CODE'] == $fetchProductData[0]["CATEGORY_CODE"]){
		?>
		<option value="<?php echo $fetchCateList[$i]['CATEGORY_CODE'];?>" selected><?php echo $fetchCateList[$i]['CATEGORY_NAME'];?></option>
		<?php }else{?>
		<option value="<?php echo $fetchCateList[$i]['CATEGORY_CODE'];?>"><?php echo $fetchCateList[$i]['CATEGORY_NAME'];?></option>
		<?php }?>
		<?php endfor;?>
		</select>
		</td>
	</tr>
	<tr>
		<th width="150" nowrap class="tdcolored">商品番号：</th>
		<td width="500" colspan="2" class="other-td">
		<input name="part_no" type="text" size="20" value="<?php echo ($_POST["part_no"])?$_POST["part_no"]:$fetchProductData[0]["PART_NO"];?>" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品名：</th>
		<td colspan="2" class="other-td">
		<input name="product_name" type="text" size="40" value="<?php echo ($_POST["product_name"])?$_POST["product_name"]:$fetchProductData[0]["PRODUCT_NAME"];?>" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th width="150" nowrap class="tdcolored">内容量：</th>
		<td colspan="2" class="other-td">
		<input name="capacity" type="text" size="40" maxlength="200" value="<?php echo ($_POST["capacity"])?$_POST["capacity"]:$fetchProductData[0]["CAPACITY"];?>" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品単価：</th>
		<td colspan="2" class="other-td">※税抜き価格で入力してください。<br>
		<input name="selling_price" type="text" size="20" value="<?php echo ($_POST["selling_price"])?$_POST["selling_price"]:$fetchProductData[0]["SELLING_PRICE"];?>" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
	  <th width="150" nowrap class="tdcolored">備考：</th>
		<td colspan="2" class="other-td">
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

		<textarea name="item_details" cols="70" rows="8" style="ime-mode:active" onFocus="SaveOBJ(this)"><?php echo ($_POST["item_details"])?$_POST["item_details"]:$fetchProductData[0]["ITEM_DETAILS"];?></textarea>

		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">titleタグ用文章：</th>
		<td class="other-td">
		<input name="title_tag" type="text" value="<?php echo ($_POST["title_tag"])?$_POST["title_tag"]:$fetchProductData[0]["TITLE_TAG"];?>" size="60" style="ime-mode:active">
		</td>
	</tr>

	<tr>
		<th colspan="2" nowrap class="tdcolored">■在庫設定<br><span style="color:#FF0000;">※カラーサイズが無い商品の場合は【カラーサイズ無し】の在庫数を入力してください。</span></th>
	</tr>
	<tr>
		<th nowrap class="tdcolored">在庫数：</th>
		<td class="other-td">

			<table width="100%" border="0" cellspacing="5" cellpadding="0" style="float:left;">
				<tr>
					<td colspan="2">
						カラーサイズ無し：
						<input name="stock_quantity" type="text" style="ime-mode:disabled" value="<?php echo ($_POST["stock_quantity"])?$_POST["stock_quantity"]:$stock[0];?>" size="10" maxlength="20">
					</td>
				<tr>
			</table>

<?php	//カラーを主体に各サイズを表示していく
					for($i=0;$i<count($fetchCSList);$i++){//divタグで自動的に改行する様にした場合横への区切り線が無い為ぱっと見わかりづらいので見えないテーブルで囲ってそこから線を表示したテーブルを表示させていく
        					//テーブルの頭を作る
						echo "<table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\"><tr>";

							//3列表示
							$cs_line = 3;

							for($j=0;$j<$cs_line;$j++):

								echo "<td width=\"".ceil(100/$cs_line)."%\" valign=\"top\">";//列のテーブルを生成

								//カラー名を表示させる
								if(count($fetchCSList[$i+$j]['COLOR_CODE'])){//有効なデータがあれば表示処理をする
									//先頭部分を生成
										echo "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"2\">
											<tr>
												<th colspan=\"2\" nowrap class=\"tdcolored\">".$fetchCSList[$i+$j]['COLOR_NAME']."</th>
											</tr>
											<tr>
												<th nowrap class=\"tdcolored\">サイズ名</th>
												<th nowrap class=\"tdcolored\">在庫数</th>
											</tr>
										";

										//ここからサイズと在庫数入力欄を表示していく
										for($k=0;$k < count($fetchCSList[$i+$j]['size_list']);$k++){

											//在庫数のチェック　在庫数があればそれを渡して無ければ0を入れる
											$stock_value = ($stock[$fetchCSList[$i+$j]['size_list'][$k]['SIZE_CODE']])?$stock[$fetchCSList[$i+$j]['size_list'][$k]['SIZE_CODE']]:0;

											echo "
												<tr>
													<td nowrap class=\"other-td\">".$fetchCSList[$i+$j]['size_list'][$k]['SIZE_NAME']."</td>
													<td nowrap class=\"other-td\"><input name=\"stock[".$fetchCSList[$i+$j]['size_list'][$k]['SIZE_CODE']."]\" type=\"text\" style=\"ime-mode:disabled;\" value=\"".$stock_value."\" size=\"6\"></td>
												</tr>
											";

										}

									//最後にテーブルを閉じる
									echo "</table>";

								}else{//有効なデータが無い場合

									//有効なデータが無い場合は空白を生成
									echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"2\">
											<tr>
												<th>&nbsp;</th>
											</tr>
										</table>
										";

								}

								//列の閉じを表示
								echo "</td>";

							endfor;
						echo "</tr></table>";//テーブルを閉じる
						$i = $i+$cs_line-1;//表示する位置を調整

					}
					?>

	<tr>
		<th width="150" rowspan="2" nowrap class="tdcolored">サムネイル画像：</th>
		<td colspan="2" class="other-td">
		<?php if(search_file_flg(PRODUCT_IMG_FILEPATH,$fetchProductData[0]['PRODUCT_ID']."_s.*") and !$copy_flg){?>
			<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetchProductData[0]['PRODUCT_ID']."_s.*","",1);?>
		<?php }?>
		</td>
	</tr>
	<tr>
		<td height="35" colspan="2" class="other-td">
		※差し替える場合は下記より選択してください。下記サイズに自動的に整形されます。<br>
		<b>横<?php echo IMG_SIZE_SX;?>px×縦 自動算出<!--<?php //echo IMG_SIZE_SY;?>px--></b><br>
		<input type="file" name="thumbnail_file" value="" class="chkimg">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">商品の表示</th>
		<td colspan="2" class="other-td">
		<input type="radio" name="display_flg" value="1" id="disp1"<?php echo ($fetchProductData[0]['DISPLAY_FLG'] == 1)?" checked":"";?>>
		<label for="disp1">表示する</label>
		&nbsp;&nbsp;
		<input type="radio" name="display_flg" value="0" id="disp2"<?php echo ($fetchProductData[0]['DISPLAY_FLG'] == 0)?" checked":"";?>>
		<label for="disp2">表示しない</label>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">ＮＥＷアイコンの表示</th>
		<td class="other-td">
		<label for="new_item_flg">ＮＥＷアイコンを表示させる場合はチェックを入れてください</label>
		<input type="checkbox" name="new_item_flg" value="1" id="new_item_flg" <?php echo ($fetchProductData[0]["NEW_ITEM_FLG"]==1)?" checked":""?>>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">カートの表示</th>
		<td colspan="2" class="other-td">
		<label for="cart_close_flg">“カートに入れる”を表示させない場合はチェックを入れてください</label>
		<input type="checkbox" name="cart_close_flg" value="1" id="cart_close_flg"<?php echo ($fetchProductData[0]["CART_CLOSE_FLG"]==1)?" checked":""?>>
		</td>
	</tr>
	<tr>
		<td colspan="3">
		<p class="explanation">
		▼現在、商品詳細紹介ページで表示されている画像一覧です。
		</p>
		</td>
	</tr>
	<?php for($i=1;$i<=PRODUCT_IMG_NUM;$i++):?>
	<tr>
	<th width="150" nowrap class="tdcolored">詳細画像<?php echo $i;?></th>
		<td colspan="2" class="other-td">
		<?php if(search_file_flg(PRODUCT_IMG_FILEPATH,$fetchProductData[0]['PRODUCT_ID']."_".$i.".*") and !$copy_flg):?>
		<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetchProductData[0]['PRODUCT_ID']."_".$i.".*","",1);?>
		<?php if($i != 1){?><br><input type="checkbox" name="del_img[]" value="<?php echo $i;?>" id="<?php echo $i;?>"><label for="<?php echo $i;?>">この画像を削除</label><?php }?>
		<br>
		<?php endif;?>
		※差し替える場合は下記より選択してください。下記サイズに自動的に整形されます。<br>
		<b>横<?php echo IMG_SIZE_LX;?>px×縦 自動算出<!--<?php //echo IMG_SIZE_LY;?>px--></b><br>
		<input type="file" name="product_img_file[<?php echo $i;?>]" value="" class="chkimg">
		</td>
	</tr>
	<?php endfor;?>
	<tr>
		<td colspan="3">
		<p class="explanation">
		▼<strong>「販売開始日時」</strong>を設定するとサイト上での販売開始日時を指定できます。指定がなければ商品登録後すぐに販売が開始されます。<br>
		▼<strong>「販売終了日時</strong>」を設定するとサイト上での販売終了日時を指定できます。指定がなければ在庫が無くなるまで商品が販売されます。
		</p>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored" width="150">販売期間限定</th>
		<td width="500" class="other-td">
		<strong>■販売開始日時</strong>(未設定だと、商品データ登録後すぐに販売が開始されます。)<br>
		<select name="y1">
		<option value="">未設定</option>
		<?php for($yn=2010;$yn<=(date("Y")+10);$yn++):?>
		<option value="<?php echo $yn;?>"<?php echo ($fetchProductData[0]['S_YEAR'] == $yn)?" selected":"";?>>
		<?php echo $yn;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m1">
		<option value="">未設定</option>
		<?php for($mn=1;$mn<=12;$mn++):?>
		<option value="<?php echo $mn;?>"<?php echo ($fetchProductData[0]['S_MONTH'] == $mn)?" selected":"";?>>
		<?php echo $mn;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d1">
		<option value="">未設定</option>
		<?php for($dn=1;$dn<=31;$dn++):?>
		<option value="<?php echo $dn;?>"<?php echo ($fetchProductData[0]['S_DAY'] == $dn)?" selected":"";?>>
		<?php echo $dn;?>
		</option>
		<?php endfor;?>
		</select>
		日
		<?php
			// 年月日が登録されている場合のみselectedを有効にする
			$start_flg = false;
			if($fetchProductData[0]['S_YEAR'] && $fetchProductData[0]['S_MONTH'] && $fetchProductData[0]['S_DAY']){
				$start_flg = true;
			}
		?>
		<select name="h1">
		<option value="">未設定</option>
		<?php for($hn=0;$hn<=23;$hn++):?>
		<option value="<?php echo $hn;?>"<?php echo (($fetchProductData[0]['S_HOUR'] == $hn) && $start_flg)?" selected":"";?>>
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
		<option value="<?php echo $yn;?>"<?php echo ($fetchProductData[0]['E_YEAR'] == $yn)?" selected":"";?>>
		<?php echo $yn;?>
		</option>
		<?php endfor;?>
		</select>
		年
		<select name="m2">
		<option value="">未設定</option>
		<?php for($mn=1;$mn<=12;$mn++):?>
		<option value="<?php echo $mn;?>"<?php echo ($fetchProductData[0]['E_MONTH'] == $mn)?" selected":"";?>>
		<?php echo $mn;?>
		</option>
		<?php endfor;?>
		</select>
		月
		<select name="d2">
		<option value="">未設定</option>
		<?php for($dn=1;$dn<=31;$dn++):?>
		<option value="<?php echo $dn;?>"<?php echo ($fetchProductData[0]['E_DAY'] == $dn)?" selected":"";?>>
		<?php echo $dn;?>
		</option>
		<?php endfor;?>
		</select>
		日
		<?php
			// 年月日が登録されている場合のみselectedを有効にする
			$end_flg = false;
			if($fetchProductData[0]['E_YEAR'] && $fetchProductData[0]['E_MONTH'] && $fetchProductData[0]['E_DAY']){
				$end_flg = true;
			}
		?>
		<select name="h2">
		<option value="">未設定</option>
		<?php for($hn=0;$hn<=23;$hn++):?>
		<option value="<?php echo $hn;?>"<?php echo (($fetchProductData[0]['E_HOUR'] == $hn) && $end_flg)?" selected":"";?>>
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
<?php if($copy_flg){?>
<input type="hidden" name="regist_type" value="new">
<input type="hidden" name="copy_type" value="new">
<?php }else{ ?>
<input type="hidden" name="regist_type" value="update">
<?php }?>
<input type="hidden" name="product_id" value="<?php echo $fetchProductData[0]['PRODUCT_ID'];?>">

<input type="submit" value="一覧プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview('<?php echo PREV_PATH;?>')">
<input type="submit" value="詳細プレビューを見る" style="width:150px;margin-top:1em;" onClick="chgpreview_d('<?php echo PREV_PATH;?>')"><br>
</form>
<br>
<form action="./" method="post" style="margin:0px;">
<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>
</body>
</html>
