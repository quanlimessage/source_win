<?php
/*******************************************************************************
カテゴリ対応
	バックオフィス

商品の並び替え
View：商品一覧表示画面

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title><?php echo BO_TITLE; ?> Shopping System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="sort.js"></script>
</head>
<body>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
</td>
<td>
<form action="./" method="post">
	<input type="submit" value="商品の並び替えトップへ" style="width:150px;">
</form>
</td>
	</tr>
</table>
<p class="page_title">商品の並び替え：現在の並び順</p>
<p class="explanation">
▼商品販売ページでの商品表示順を設定します。<br>
▼変更したい商品を選択し、下記の「↓UP」「↑DOWN」ボタンを使用して並び替えを行ってください。<br>
▼「上記の並び替えで更新」をクリックすると並び順が反映されます。
</p>
<?php
if( !$fetch ):
	echo "<strong>登録されている商品はありません。</strong><br><br>";
else:
?>
<div>【<?php echo $fetch[0]["CATEGORY_NAME"];?>】現在の登録商品数：&nbsp;<strong><?php echo count($fetch); ?></strong>&nbsp;件</div>
<table width="700" border="0" cellpadding="0" cellspacing="0">
<!--並び替えの順番の更新-->
<tr>
<td align="left" valign="top">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<form name="change_sort" action="./" method="post" style="margin:0;">
			<div style="float:left;margin-right:0.5em">
			並び替えの順番<br>
						<select name="nvo" size="<?php echo (count($fetch) > 20)?20:count($fetch);// sizeは20件を基準にしておく ?>">
							<?php for ( $i = 0; $i < count($fetch); $i++ ): ?>
							<option value="<?php echo $fetch[$i]['PRODUCT_ID']; ?>">
								<?php echo $fetch[$i]['PART_NO']; ?>/<?php echo $fetch[$i]['PRODUCT_NAME']; ?>
							</option>
							<?php endfor; ?>
						</select>
					</div>

				<div style="float:left;">

					<input type="button" value="最初に移動" onClick="f_moveUp();" style="width:100px;">
					<br>
					<input type="button" value="一段上に移動" onClick="moveUp();" style="width:100px;">
					<br>
					<input type="button" value="一段下に移動" onClick="moveDn();" style="width:100px;">
					<br>
					<input type="button" value="最後に移動" onClick="l_moveDn();" style="width:100px;">
					<br>
					<br>
					<input type="button" value="ストックする" onClick="stock_move();" style="width:100px;">
					<br>
					<input type="button" value="挿入する" onClick="on_move();" style="width:100px;">

				</div>

				<div style="float:left;padding-left:10px;">
					ストックリスト<br>
					<select name="stock_nvo" size="10" style="width:150px;" multiple></select>

				</div>
				<br>

				<div style="clear:left;">
						<input type="button" value="上記の並び替え順で更新" style="margin-top:0.5em;width:150px;" onClick="change_sortSubmit();">
						<input type="hidden" name="status" value="view_order_update">
						<input type="hidden" name="new_view_order" value="">
						<input type="hidden" name="category_id" value="<?php echo $category_id;?>">
				<input type="hidden" name="p" value="<?php echo $p;?>">
				</div>
			</form>
			</td>
		</tr>
	</table>
	</td>
</tr>

<tr>
<!--データリスト-->
	<td valign="top">
	<p class="explanation">
			<span style="color:#FF0000;">ボタンのご説明</span><br>
			▼【最初に移動】は選択したデータの順番を一番最初に移動させます。<br>
			▼【一段上に移動】は選択したデータの一つ上に移動させます。<br>
			▼【一段下に移動】は選択したデータの一つ下に移動させます。<br>
			▼【最後に移動】は選択したデータの順番を一番最後に移動させます。<br>

			▼【ストックする】は選択したデータを右側のストックリストに移動させます。<br>
			▼【挿入する】は右側の【ストックリスト】で選択したデータを左側の【並び替えの順番】で選択された位置に挿入します。<br>
			▼【ストックリスト】は複数選択することが出来ます。キーボードの【Ctrl】ボタンを押しながら選択、または、ドラッグで範囲選択が出来ます。<br>
			</p>

			<br>
			現在の並び順<br>
		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="height:inherit;">
			<tr class="tdcolored">
				<th width="15%" nowrap class="back2">商品番号</th>
				<th width="30%" nowrap class="back2">商品名</th>
				<th width="20%" nowrap class="back2">商品画像</th>
				<th width="15%" nowrap class="back2">現在の<br>表示順</th>
			</tr>
			<?php for ( $i = 0; $i < count($fetch); $i++ ): ?>
			<tr>
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetch[$i]['PART_NO']; ?></td>
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php	echo $fetch[$i]['PRODUCT_NAME']; ?></td>
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
				<?php if(search_file_flg(PRODUCT_IMG_FILEPATH,$fetch[$i]['PRODUCT_ID']."_s.*")):?>
				<a href="<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetch[$i]['PRODUCT_ID']."_s.*","",2);?>" target="_blank"><?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetch[$i]['PRODUCT_ID']."_s.*","border=\"0\" width=\"".IMG_LIST_X."\"");?></a>
				<?php else:
					echo '&nbsp;';
				endif;?>
				</td>
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetch[$i]['VIEW_ORDER']; ?></td>
			</tr>
			<?php endfor; ?>
		</table>
	</td>
</tr>
</table>
<br><br>
<?php endif; ?>

</body>
</html>
