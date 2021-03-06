<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
並び替えプログラム
	※sort.php（このファイル自身）とsort.jsの２つのファイルで構成

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_S7_3rd.php");	// 共通設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ

//カテゴリー情報の取得
	$sql = "
	SELECT
		CATEGORY_CODE,CATEGORY_NAME,VIEW_ORDER,RES_ID
	FROM
		".S7_3_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//カテゴリー名の横に登録件数を表示させる
	for($i=0;$i<count($fetchCA);$i++){
		${'ca_cnt'.$i} = $fetchCA[$i]['RES_ID'];

		${'sql_ca'.$i} = "
		SELECT
			RES_ID,
			CATEGORY_CODE
		FROM
			".S7_3_PRODUCT_LST."
		WHERE
			(CATEGORY_CODE LIKE '%".${'ca_cnt'.$i}."%')
		AND
			(DEL_FLG = '0')
		";

		// ＳＱＬを実行
		${'fetchCA_ca'.$i} = dbOpe::fetch(${'sql_ca'.$i},DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	}
	// POSTデータの受け取りと共通な文字列処理
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}

	//カテゴリーパラメータが無い場合または数字ではない場合
	if(empty($ca) || !is_numeric($ca)){
		$ca = "";
		$ca_res = "";
		$ca_name="全て表示";
	}else{//カテゴリーが在る場合

		//カテゴリーのコードが存在しない場合もエラー
			for($i=0,$j=0;$i < count($fetchCA);$i++){
				if($fetchCA[$i]['CATEGORY_CODE'] == $ca){
					$ca_name=$fetchCA[$i]['CATEGORY_NAME'];
					$ca_res=$fetchCA[$i]['RES_ID'];
					$j=1;break;
				}
			}

		//カテゴリーコードと一致するのが無かった場合
		if(!$j){
			$ca = "";
			$ca_res = "";
			$ca_name="全て表示";
		}
	}

	//カテゴリーが在る場合は検索条件に付け加える
		$serch_sql .= ($ca_res)?"AND (".S7_3_PRODUCT_LST.".CATEGORY_CODE REGEXP '".$ca_res."')":"";

#===============================================================================
# $_POST['action']があれば新しく並び変えた順番に更新する
#===============================================================================
if(($_POST['action'] == "update")&&(!empty($_POST['new_view_order']))):

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

		$sqlvo[$i] = "
		UPDATE
			".S7_3_VIEW_ORDER_LIST."
		SET
			VIEW_ORDER = '".($i+1)."'
		WHERE
			(RES_ID = '".$vo[$i]."')
		AND
			(C_ID = '".$ca_res."')
		";

	}

	// ＳＱＬを実行
	$db_result = dbOpe::regist($sqlvo,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
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
		".S7_3_PRODUCT_LST.".RES_ID,
		".S7_3_PRODUCT_LST.".TITLE,
		".S7_3_VIEW_ORDER_LIST.".VIEW_ORDER
	FROM
			".S7_3_PRODUCT_LST."
		INNER JOIN
			".S7_3_VIEW_ORDER_LIST."
		ON
			(".S7_3_PRODUCT_LST.".RES_ID = ".S7_3_VIEW_ORDER_LIST.".RES_ID)
	WHERE
		(DEL_FLG = '0')
		AND
		(".S7_3_VIEW_ORDER_LIST.".C_ID = '".$ca_res."')
	ORDER BY
		VIEW_ORDER ASC
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
		<input type="submit" value="リスト画面へ戻る" style="width:150px;">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo TITLE."【".$ca_name."】";?>：並び替え</p>
<p class="explanation">
▼現在表示されている並び順です。<br>
▼変更したいデータを選択し、下記の【最初に移動】【一段上に移動】【一段下に移動】【最後に移動】【ストックする】【挿入する】を使用して並び替えを行ってください。<br>
▼最後に「上記の並び替え順で更新」をクリックすると並び替えの変更が適用されます。
</p>

<form  name="frms" action="./sort.php" method="post" style="margin:0;">

		<select name="ca" onChange="JavaScript:document.frms.submit();">
			<option value="">全て表示</option>
			<?php for($i=0;$i < count($fetchCA);$i++){?>
			<option value="<?php echo $fetchCA[$i]['CATEGORY_CODE'];?>"<?php echo ($ca == $fetchCA[$i]['CATEGORY_CODE'])?" selected":""; ?>><?php echo $fetchCA[$i]['CATEGORY_NAME'];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
			<?php }?>
		</select>←表示したいカテゴリーを選択してください

</form>

<?php
if(!$fetch):
	echo "<strong>登録されているデータはありません。</strong><br><br>";
else:
?>
<div>現在の登録データ件数：&nbsp;<strong><?php echo count($fetch); ?></strong>&nbsp;件</div>
<br>
<table width="850" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="left" valign="top">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<form name="change_sort" action="./sort.php" method="post" style="margin:0;">
			<div style="float:left;margin-right:0.5em">
				並び替えの順番<br>
				<select name="nvo" size="<?php echo (count($fetch) > 20)?20:count($fetch);// sizeは20件を基準にしておく ?>">
				<?php
				for($i=0;$i<count($fetch);$i++){
						$title = ($fetch[$i]['TITLE'])?mb_strimwidth($fetch[$i]['TITLE'], 0, 40, "...", utf8):"No Title";
					echo "<option value=\"{$fetch[$i]['RES_ID']}\">{$fetch[$i]['VIEW_ORDER']}：{$title}</option>\n";
				}?>
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

					<input type="button" value="上記の並び替え順で更新" style="margin-top:0.5em;width:200px;" onClick="change_sortSubmit();">
					<input type="hidden" name="action" value="update">
					<input type="hidden" name="new_view_order" value="">
					<input type="hidden" name="p" value="<?php echo $p;?>">
					<input type="hidden" name="ca" value="<?php echo $ca;?>">

				</div>
			</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
		<tr>
			<td>
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

		<table width="600" border="1" cellpadding="2" cellspacing="0" style="height:inherit;">
				<tr class="tdcolored">
					<th nowrap class="back2">タイトル</th>
					<th width="10%" nowrap class="back2">画像</th>
					<th width="15%" class="back2">現在の<br>表示順</th>
				</tr>
				<?php for($i=0;$i<count($fetch);$i++):?>
				<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
					<td align="center">&nbsp;<?php
									$title = mb_strimwidth($fetch[$i]['TITLE'], 0, 80, "...", utf8);
									echo ($title)?$title:"No Title"; ?>
					</td>
					<td align="center">

						<?php if(search_file_flg(IMG_PATH,$fetch[$i]['RES_ID']."_1.*")):?>
							<?php echo search_file_disp(IMG_PATH,$fetch[$i]['RES_ID']."_1.*","border=\"0\" width=\"".IMGSIZE_SX."\"");?>
						<?php else:?>
							&nbsp;
						<?php endif;?>
					</td>
					<td align="center"><?php echo $fetch[$i]['VIEW_ORDER'];?></td>
				</tr>
				<?php endfor; ?>
		  </table>
	</td>
</tr>

</table>
<?php endif; ?>
</body>
</html>