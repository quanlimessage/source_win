<?php
/*******************************************************************************
アパレル対応ショッピングカート
	ショッピングカートプログラム バックオフィス

商品の更新
View：商品検索画面（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
// 画像再読込み用パラメータ
$time = time();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

// 削除ボタンがクリックされた際の確認
function del_chk(){
		return confirm('この商品データを完全に削除します。\n商品データの復帰は出来ません。\nよろしいですか？');
}
//-->
</script>
</head>
<body>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<?php if(RECOMMEND_DISPLAY_FLG):?>
		<td>
		<form action="sort.php" method="post">
		<input type="submit" value="お勧め商品の並び替え" style="width:150px;">
		</form>
		</td>
		<?php endif;?>
	</tr>
</table>
<p class="page_title">商品管理：検索結果</p>
<p class="explanation">
▼データの修正は「編集」をクリックしてください<br>
▼データの新規登録は「新規追加」ボタンをクリックしてください。<br>
▼「表示中」「OFF」を切り替えて販売ページへの表示/非表示を制御します。<br>
▼「削除」をクリックすると該当商品を完全に削除します。<br>
▼いったん削除した商品データは復帰できないので十分注意してください。
<?php if(RECOMMEND_DISPLAY_FLG):?><br>▼トップページのお勧め商品に表示させる場合は「お勧め」をチェックしてください。<?php endif;?>
</p>
<?php if(!$fetchProductList):?>
<p><b>登録されている商品に検索条件に該当する商品はありません。</b></p>
<?php else:?>
<div>※検索結果：<strong><?php echo count($fetchProductList);?></strong>&nbsp;件</div>
<table width="100%" border="0" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th width="10%" nowrap>商品番号</th>
		<th width="15%" nowrap>カテゴリー</th>
		<th nowrap>画像</th>
		<th width="50%" nowrap>商品名</th>
		<?php if(RECOMMEND_DISPLAY_FLG):?>
		<th nowrap>お勧め</th>
		<?php endif;?>
		<th nowrap>編集</th>
		<th nowrap>表示状況</th>
		<th nowrap>削除</th>
		<th nowrap>一覧プレビュー</th>
		<th nowrap>詳細プレビュー</th>
		<th nowrap>複製</th>
	</tr>
	<?php for($i=0;$i<count($fetchProductList);$i++):?>
	<tr>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchProductList[$i]["PART_NO"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchProductList[$i]["CATEGORY_NAME"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if(search_file_flg(PRODUCT_IMG_FILEPATH,$fetchProductList[$i]['PRODUCT_ID']."_s.*")):?>
		<a href="<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetchProductList[$i]['PRODUCT_ID']."_s.*","",2);?>" target="_blank">
		<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetchProductList[$i]['PRODUCT_ID']."_s.*","border=\"0\" width=\"".IMG_LIST_X."\"");?>
		</a>
		<?php else:
			echo '&nbsp;';
		endif;?>
		</td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchProductList[$i]['PRODUCT_NAME'];?></td>
		<?php if(RECOMMEND_DISPLAY_FLG):?>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if($fetchREC[0]['CNT'] < RECOMMEND_DBMAX_CNT || $fetchProductList[$i]['RECOMMEND_FLG']==1){?>
		<form method="post" action="./">
		<input type="checkbox" name="recommend_flg" value="1"<?php echo ($fetchProductList[$i]['RECOMMEND_FLG'] == 1)?" checked":"";?> onClick="javascript:this.form.submit();">
		<input type="hidden" name="status" value="recommend">
		<input type="hidden" name="product_id" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		</form>
		<?php }else{?>
		&nbsp;
		<?php }?>
		</td>
		<?php endif?>
		<form method="post" action="./">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><input type="submit" name="reg" value="編集" style="width:60px;"></td>
		<input type="hidden" name="status" value="product_edit">
		<input type="hidden" name="product_id" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		</form>
		<form method="post" action="./">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<input type="submit" name="reg" value="<?php echo ($fetchProductList[$i]["DISPLAY_FLG"] == 1)?"表示中":"OFF";?>" style="width:60px;">
		</td>
		<input type="hidden" name="pid" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		<input type="hidden" name="status" value="display_change">
		<input type="hidden" name="display_change" value="<?php echo ($fetchProductList[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
		</form>
		<form method="post" action="./" onSubmit="return del_chk(this);">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><input type="submit" value="削除" style="width:60px;"></td>
		<input type="hidden" name="del_pid" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		<input type="hidden" name="status" value="delflg_change">
		</form>

		<form method="post" action="<?php echo PREV_PATH;?>" target="_blank" style="margin:0;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<input type="submit" value="一覧プレビュー">
		<input type="hidden" name="product_id" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		<input type="hidden" name="ca" value="<?php echo $ca;?>">
		<input type="hidden" name="status" value="prev">
		</td>
		</form>

		<form method="post" action="<?php echo PREV_PATH;?>" target="_blank" style="margin:0;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<input type="submit" value="詳細プレビュー">
		<input type="hidden" name="product_id" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		<input type="hidden" name="status" value="prev_d">
		</td>
		</form>

		<form method="post" action="./">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<input type="submit" name="reg" value="複製"<?php echo (PRODUCT_ENTRY_FLG)?' disabled':'';?>>
		<input type="hidden" name="product_id" value="<?php echo $fetchProductList[$i]['PRODUCT_ID'];?>">
		<input type="hidden" name="status" value="copy">
		<input type="hidden" name="copy_flg" value="1">
		</td>
		</form>

	</tr>
	<?php endfor;?>
</table>
<?php endif;?>

<?php if(PRODUCT_ENTRY_FLG != 1):?>
<form action="./" method="post">
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="status" value="product_entry">
</form>
<?php else:?>
<p class="err">商品最大登録数<?php echo PRODUCT_MAX_NUM;?>件に達しています。</p>
<?php endif;?>
<form action="./" method="post">
<input type="submit" value="検索条件入力画面へ" style="width:150px;">
</form>
</body>
</html>
