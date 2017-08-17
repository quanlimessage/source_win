<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#--------------------------------------------------------
	# ページング用リンク文字列処理
	#--------------------------------------------------------

	//ページリンクの初期化
	$link_prev = "";
	$link_next = "";

		// 次ページ番号
		$next = $p + 1;
		// 前ページ番号
		$prev = $p - 1;

		// 商品全件数
		$tcnt = $fetchCNT_CA[0]['CNT'];
		// 全ページ数
		$totalpage = ceil($tcnt/DISP_MAXROW_BACK);

		// カテゴリー別で表示していればページ遷移もカテゴリーパラメーターをつける
		if($ca)$cpram = "&ca=".urlencode($ca);

		// 前ページへのリンク
		if($p > 1){
			$link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">&lt;&lt; Prev</a>";
		}

		//次ページリンク
		if($totalpage > $p){
			$link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">Next &gt;&gt;</a>";
		}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../select.js" type="text/javascript"></script>
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
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php
		//タイトルとカテゴリーを表示
			echo TITLE."【".$ca_name."】";
			?>：新規登録</p>

<p class="explanation">
▼新規データの登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。<br>
▼最大登録件数は<strong><?php echo DBMAX_CNT;?>件</strong>です。
</p>

<?php
#-----------------------------------------------------
# 書込許可（最大登録件数に達していない）の場合に表示
#-----------------------------------------------------
	//最大件数を超えてない、またはカテゴリーが存在している場合新規登録が出来るようにする
	//基本的にカテゴリーが無ければそのカテゴリーに登録されているデータが表示または存在が出来ない為、編集ボタン側の表示は制限をしない
	if(($fetchCNT[0]['CNT'] < DBMAX_CNT) && count($fetchCA)):?>
		<form action="./" method="post">
			<input type="submit" value="新規追加" style="width:150px;">
			<input type="hidden" name="act" value="new_entry">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
		</form>
	<?php else:?>
		<p class="err">
		<?php if(count($fetchCA)){?>
		最大登録件数<?php echo DBMAX_CNT;?>件に達しています。<br>
		新規登録を行う場合は、いずれかの既存データを削除してください。
		<?php }else{ //カテゴリーが存在しない為新規ボタンを非表示にした場合の文言 ?>
		カテゴリーの登録がございません。<br>
		新規登録を行う場合はカテゴリーの登録を行ってください。
		<?php }?>
		</p>
	<?php endif;?>

<form name="frms" action="./" method="post" style="margin:0;">

		<select name="ca" onChange="JavaScript:document.frms.submit();">
		<option value="">全て表示</option>
		<?php for($i=0;$i<count($fetchCA);$i++){?>
		<option value="<?php echo $fetchCA[$i]['CATEGORY_CODE'];?>" <?php echo ($ca == $fetchCA[$i]['CATEGORY_CODE'])?" selected":""; ?>><?php echo $fetchCA[$i]['CATEGORY_NAME'];?>(<?php echo count(${'fetchCA_ca'.$i});?>)</option>
		<?php }?>
		</select>←表示したいカテゴリーを選択してください

		<!--<input type="submit" value="カテゴリーを選択">-->
</form>

<p class="page_title"><?php
		//タイトルとカテゴリーを表示
			echo TITLE."【".$ca_name."】";
			?>：登録一覧</p>

	<p class="explanation">
	▼既存データの修正は<strong>「編集」</strong>をクリックしてください<br>
	▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
	▼<strong>「削除」</strong>をクリックすると登録されているデータが削除されます。<br>
	▼<strong>削除したデータは復帰できません。</strong>十分に注意して処理を行ってください。<br>
	▼<strong>削除を行った後は</strong>順番の整合性を整えるため、上記ボタンより“<strong>並び替え”</strong>を実行してください<br>
	▼<strong>「登録データ件数」</strong>は選択されたカテゴリーに表示されるデータの合計件数です。<br>
	▼<strong>「総合件数」</strong>は全ての登録データを合計した合計件数です。
	</p>

<?php if(!$fetch):?>
	<p><b>登録されているデータはありません。</b></p>
<div>※登録データ件数：<strong><?php echo $fetchCNT_CA[0]['CNT'];?></strong>&nbsp;件（総合件数：<strong><?php echo $fetchCNT[0]['CNT'];?></strong>&nbsp;件）</div>
<?php else:?>
<div>※登録データ件数：<strong><?php echo $fetchCNT_CA[0]['CNT'];?></strong>&nbsp;件（総合件数：<strong><?php echo $fetchCNT[0]['CNT'];?></strong>&nbsp;件）</div>

<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
	</tr>
</table>

<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<th width="5%" nowrap>選択</th>
		<th width="5%" nowrap>表示順</th>
		<th width="15%" nowrap>更新日</th>
		<th width="10%" nowrap>画像</th>
		<th nowrap>タイトル</th>
		<th width="5%" nowrap>編集</th>
		<th width="10%" nowrap>表示状態</th>
		<th width="5%" nowrap>削除</th>
		<th width="10%">一覧プレビュー</th>
		<th width="10%">詳細プレビュー</th>
		<th width="10%">複製</th>
	</tr>
	<?php for($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center"><input type="checkbox" name="check_id[]" value="<?php echo $fetch[$i]['RES_ID'];?>"></td>
		<td align="center"><?php echo $fetch[$i]['VIEW_ORDER'];?></td>
		<td align="center"><?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?></td>

		<td align="center">
			<?php if(search_file_flg(IMG_PATH,$fetch[$i]['RES_ID']."_1.*")):?>
				<a href="<?php echo search_file_disp(IMG_PATH,$fetch[$i]['RES_ID']."_1.*","",2);?>" target="_blank">
				<?php echo search_file_disp(IMG_PATH,$fetch[$i]['RES_ID']."_1.*","border=\"0\" width=\"".IMGSIZE_SX."\"");?>
				</a>
			<?php else:
				echo '&nbsp;';
			endif;?>
		</td>

		<td align="center">&nbsp;<?php echo ($fetch[$i]['TITLE'])?mb_strimwidth($fetch[$i]['TITLE'], 0, 80, "...", utf8):"No Title";?></td>

		<td align="center">
			<form method="post" action="./" style="margin:0;">
			<input type="submit" name="reg" value="編集">
			<input type="hidden" name="act" value="update">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
			<input type="hidden" name="p" value="<?php echo $p;?>">
			</form>
		</td>

		<td align="center">
			<form method="post" action="./" style="margin:0;">
			<input type="submit" name="reg" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"現在非表示";?>" style="width:75px;">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="act" value="display_change">
			<input type="hidden" name="display_change" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
			<input type="hidden" name="p" value="<?php echo $p;?>">
			</form>
		</td>

		<td align="center">
			<form method="post" action="./" style="margin:0;" onSubmit="return confirm('このデータを完全に削除します。\nデータの復帰は出来ません。\nよろしいですか？');">
			<input type="submit" value="削除">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="act" value="del_data">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
			<input type="hidden" name="p" value="<?php echo $p;?>">
			</form>
		</td>

		<td align="center">
			<form method="post" action="<?php echo PREV_PATH;?>" target="_blank" style="margin:0;">
			<input type="submit" value="一覧プレビュー">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
			<input type="hidden" name="act" value="prev">
			</form>
		</td>
		<td align="center">
			<form method="post" action="<?php echo PREV_PATH;?>" target="_blank" style="margin:0;">
			<input type="submit" value="詳細プレビュー">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="act" value="prev_d">
			</form>
		</td>
		<td align="center">
			<?php if(($fetchCNT[0]['CNT'] < DBMAX_CNT) && count($fetchCA)){?>
			<form method="post" action="./" style="margin:0;">
			<input type="submit" name="reg" value="複製">
			<input type="hidden" name="act" value="copy">
			<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
			<input type="hidden" name="ca" value="<?php echo $ca;?>">
			<input type="hidden" name="p" value="<?php echo $p;?>">
			<input type="hidden" name="copy_flg" value="1">
			</form>
			<?php }else{?>
			&nbsp;
			<?php }?>
		</td>

	</tr>
	<?php endfor;?>
</table>
<br>

<input type="button" name="sond" value="全てをチェックする" onclick="select_on_data()">
&nbsp;&nbsp;
<input type="button" name="soffd" value="全てのチェックをはずす" onclick="select_off_data()">

<br>

<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
	</tr>
</table>

<br><br>
<table width="500" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<td align="center">
			<form action="./" method="post" name="disp_on">
				<input type="button" name="disp_on_button" value="表示する" style="width:100px;margin:10px;" onclick="disp_on_data();">
				<input type="hidden" name="disp_on_id_stock" id="disp_on_id_stock" value="">
				<input type="hidden" name="act" value="select_don_data">
				<input type="hidden" name="ca" value="<?php echo $ca;?>">
				<input type="hidden" name="p" value="<?php echo $p;?>">
				<br>※選択したデータの表示状態を【表示中】に変更します。
			</form>
		</td>
		<td align="center">
			<form action="./" method="post" name="disp_off">
				<input type="button" name="disp_off_button" value="非表示にする" style="width:100px;margin:10px;" onclick="disp_off_data();">
				<input type="hidden" name="disp_off_id_stock" id="disp_off_id_stock" value="">
				<input type="hidden" name="act" value="select_doff_data">
				<input type="hidden" name="ca" value="<?php echo $ca;?>">
				<input type="hidden" name="p" value="<?php echo $p;?>">
				<br>※選択したデータの表示状態を【現在非表示】に変更します。
			</form>
		</td>
		<td align="center">
			<form action="./" method="post" name="select_del">
				<input type="button" name="del_button" value="削除する" style="width:100px;margin:10px;" onclick="select_del_data();">
				<input type="hidden" name="del_id_stock" id="del_id_stock" value="">
				<input type="hidden" name="act" value="select_del_data">
				<input type="hidden" name="ca" value="<?php echo $ca;?>">
				<input type="hidden" name="p" value="<?php echo $p;?>">
				<br>
				<span style="color:#ff0000;">※選択したデータを削除します。</span>
			</form>
		</td>
	</tr>
</table>

<?php endif;?>

</body>
</html>