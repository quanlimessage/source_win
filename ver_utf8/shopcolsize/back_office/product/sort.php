<?php
/*******************************************************************************
通常ショップ対応
	バックオフィス

お勧め商品の並び替え

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("Location: ../");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 設定情報
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ

#===============================================================================
# $_POST['action']があれば新しく並び変えた順番に更新する
#===============================================================================
if(($_POST['action'] == "update")&&(!empty($_POST['new_view_order']))):

	// POSTデータの受け取りと共通な文字列処理
	extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

	#===============================================================================
	# １．並び順が格納されたhiddenデータをタブをデリミタにしてバラす（配列に格納）
	#	・対象のhiddenデータ：$new_view_order（RES_IDがタブ区切りになっている）
	#	・新しいVIEW_ORDERの番号：$voの要素番号に1を足したもの
	#	・他のhiddenデータ：$category_code（対象のカテゴリーコード）
	#		※カテゴリ分類する場合のみ発生します。デフォルトではつけていません。
	#
	# ２．並び替えを更新するＳＱＬを発行（バラした件数分設定する）
	#===============================================================================
	$vo = explode("\t", $new_view_order);

	for($i=0;$i<count($vo);$i++){

		$sql[$i] = "
		UPDATE
			".PRODUCT_LST."
		SET
			RECOMMEND_VO = '".($i+1)."'
		WHERE
			( PRODUCT_ID = '".$vo[$i]."' )
		AND
			( DEL_FLG = '0' )
		";

	}

	// ＳＱＬを実行
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	// 最後に並び替えのトップへ飛ばす
	//header("Location: ./sort.php");

endif;

#===============================================================================
# 現在の表示順に商品リストを表示
#===============================================================================

// 現在の並び順でデータを取得
$sql = "
SELECT
	PRODUCT_ID,
	PART_NO,
	PRODUCT_NAME,
	RECOMMEND_VO,
	DISPLAY_FLG
FROM
	".PRODUCT_LST."
WHERE
	( RECOMMEND_FLG = '1' )
AND
	( DEL_FLG = '0' )
ORDER BY
	RECOMMEND_VO
";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
<title></title>
<script language="JavaScript" src="sort.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="./" method="post">
			<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
			<input type="hidden" name="status" value="search_result">
		</form>
		</td>
	</tr>
</table>

<p class="page_title">お勧め商品の並び替え：現在の並び順</p>
<p class="explanation">
▼現在のトップページでの商品表示順が左に表示されています。<br>
▼並び替えを行う場合は、画面右側の選択エリアより商品名を選択後、「↑UP」「↓DOWN」を使用して並び替えを行ってください。<br>
▼「上記の並び替え順で更新」をクリックすると商品の並び替えが反映されます。
</p>
<?php
if(!$fetch):
	echo "<strong>登録されている商品はありません。</strong><br><br>";
else:
?>
<div>現在の登録記事数：&nbsp;<strong><?php echo count($fetch); ?></strong>&nbsp;件</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="60%" valign="top">
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
						<?php echo search_file_disp(PRODUCT_IMG_FILEPATH,$fetch[$i]['PRODUCT_ID']."_s.*"," width=\"".IMG_LIST_X."\"");?>
					<?php else:
						echo '&nbsp;';
					endif;?>
				</td>
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetch[$i]['RECOMMEND_VO']; ?></td>
			</tr>
			<?php endfor; ?>
	  </table>
	</td>
	<td width="40%" align="left" valign="top">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<form name="change_sort" action="./sort.php" method="post" style="margin:0;">
			<div style="float:left;margin-right:0.5em">
						<select name="nvo" size="<?php echo (count($fetch) > 20)?20:count($fetch);// sizeは20件を基準にしておく ?>">
							<?php for ( $i = 0; $i < count($fetch); $i++ ): ?>
							<option value="<?php echo $fetch[$i]['PRODUCT_ID']; ?>">
								<?php echo $fetch[$i]['PART_NO']; ?>/<?php echo $fetch[$i]['PRODUCT_NAME']; ?>
							</option>
							<?php endfor; ?>
						</select>
			</div>

			<div style="float:left;">
				<input type="button" value="↑UP" onClick="moveUp();" style="width:70px;"><br>
				<input type="button" value="↓DOWN" onClick="moveDn();" style="width:70px;">
			</div>

			<div style="clear:left;">
				<input type="button" value="上記の並び替え順で更新" style="margin-top:0.5em;width:150px;" onClick="change_sortSubmit();">
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="new_view_order" value="">
			</div>
			</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
<?php endif; ?>
</body>
</html>
