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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo BO_TITLE;?> Shopping System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">商品管理：新規登録</p>
<p class="explanation">
▼商品の新規登録を行う場合は「新規追加」をクリックしてください。<br>
▼最大登録件数は<strong><?php echo PRODUCT_MAX_NUM;?>件</strong>です。
</p>
<?php if(PRODUCT_ENTRY_FLG != 1):?>
<form action="./" method="post">
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="status" value="product_entry">
</form>
<br>
<?php else:?>
<p class="err">商品最大登録数<?php echo PRODUCT_MAX_NUM;?>件に達しています。</p>
<?php endif;?>
<p class="page_title">商品管理：既存商品データの更新・在庫設定</p>
<p class="explanation">
▼更新・在庫数設定を行う場合は該当商品を検索します。<br>
▼下記で検索条件を指定してください。条件にマッチした全商品データがリスト表示されます。<br>
▼何も条件を指定しなければ、全商品データがリスト表示されます。
</p>
<form action="./" method="post" style="margin:0px;">
<table width="650" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td colspan="2" class="tdcolored">■登録済み商品検索</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">商品名(一部でも可)：</td>
		<td class="other-td"><input type="text" name="search_product_name" value="<?php echo ($_POST["search_product_name"])?$_POST["search_product_name"]:"";?>"></td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">商品番号：</td>
		<td class="other-td"><input type="text" name="search_part_no" value="<?php echo ($_POST["search_part_no"])?$_POST["search_part_no"]:"";?>"></td>
	</tr>
	<tr>
		<td class="tdcolored">カテゴリー：</td>
		<td class="other-td">
		<select name="search_category_code">
		<option value="">選択しない</option>
		<?php for($i=0;$i<count($fetchCateList);$i++):?>
			<?php if($fetchCateList[$i]['CATEGORY_CODE'] == $_POST["search_category_code"]){?>
				<option value="<?php echo $fetchCateList[$i]['CATEGORY_CODE'];?>" selected><?php echo $fetchCateList[$i]['CATEGORY_NAME'];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
			<?php }else{?>
				<option value="<?php echo $fetchCateList[$i]['CATEGORY_CODE'];?>"><?php echo $fetchCateList[$i]['CATEGORY_NAME'];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
			<?php }?>
		<?php endfor;?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">現在登録在庫：</td>
		<td class="other-td">
		<input type="radio" name="search_stock_quantity" value="1" id="q1" checked><label for="q1">指定無し</label><br>
		<input type="radio" name="search_stock_quantity" value="2" id="q2"><label for="q2">在庫切れのみ</label><br>
		<input type="radio" name="search_stock_quantity" value="3" id="q3"><label for="q3">在庫有りのみ</label>
		</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">表示/非表示：</td>
		<td class="other-td">
		<input type="radio" name="search_display" value="1" id="d1" checked><label for="d1">指定無し</label><br>
		<input type="radio" name="search_display" value="2" id="d2"><label for="d2">現在表示中</label><br>
		<input type="radio" name="search_display" value="3" id="d3"><label for="d3">現在非表示中</label>
		</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">[カートへ]ボタン表示/非表示：</td>
		<td class="other-td">
		<input type="radio" name="search_cart" value="1" id="c1" checked><label for="c1">指定無し</label><br>
		<input type="radio" name="search_cart" value="2" id="c2"><label for="c2">現在表示中</label><br>
		<input type="radio" name="search_cart" value="3" id="c3"><label for="c3">現在非表示中</label>
		</td>
	</tr>
	<?php if(RECOMMEND_DISPLAY_FLG):?>
	<tr>
		<td class="tdcolored" width="200">お勧め商品：</td>
		<td class="other-td">
		<input type="radio" name="search_recommend" value="1" id="r1" checked><label for="r1">指定無し</label><br>
		<input type="radio" name="search_recommend" value="2" id="r2"><label for="r2">お勧めのみ</label><br>
		<input type="radio" name="search_recommend" value="3" id="r3"><label for="r3">お勧め以外</label>
		</td>
	</tr>
	<?php endif?>
</table>
<input type="submit" value="検索開始" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
