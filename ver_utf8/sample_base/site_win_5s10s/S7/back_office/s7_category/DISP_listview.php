<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：登録内容一覧表示（最初に表示する）

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}/*
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
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
</head>
<body>
<div class="header"></div>
<br>
<br>
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
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo CATE_TITLE;?>：新規登録</p>
<p class="explanation">
▼新規データの登録を行う際は、<strong>「新規追加」</strong>をクリックしてください。
</p>

<form action="./" method="post">
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="action" value="new_entry">
</form>

<p class="page_title"><?php echo CATE_TITLE;?>：登録一覧</p>
<p class="explanation">
▼既存データの修正は<strong>「編集」</strong>をクリックしてください<br>
▼<strong>「表示中」「現在非表示」</strong>をクリックで切替えると表示ページでの表示を制御します。<br>
▼<strong>「削除」</strong>をクリックすると登録されているカテゴリーのデータが削除されます。
カテゴリーにあります必要なデータは削除前に他のカテゴリーへの変更移動をお願い致します。<br>
▼<strong>削除したデータは復帰できません。</strong>十分に注意して処理を行ってください。<br>
▼<strong>削除を行った後は</strong>順番の整合性を整えるため、上記ボタンより“<strong>並び替え”</strong>を実行してください
</p>
<?php if(!$fetch):?>
<p><b>登録されているデータはありません。</b></p>
<?php else:?>
<div>※登録データ件数：<strong><?php echo count($fetch);?></strong>&nbsp;件</div>
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<th width="5%" nowrap>表示順</th>
		<th nowrap>タイトル</th>
		<th width="5%" nowrap>編集</th>
		<th width="5%" nowrap>表示状態</th>
	    <th width="5%" nowrap>削除</th>
	</tr>
	<?php for($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<td align="center"><?php echo $fetch[$i]['VIEW_ORDER'];?></td>
		<td align="center">&nbsp;<?php echo (!empty($fetch[$i]['CATEGORY_NAME']))?$fetch[$i]['CATEGORY_NAME']:"No Title";?></td>
		<td align="center">
		<form method="post" action="./" style="margin:0;">
		<input type="submit" name="reg" value="編集">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="cate" value="<?php echo $fetch[$i]['CATEGORY_CODE'];?>">
		</form>
		</td>

		<td align="center">
			<form method="post" action="./" style="margin:0;">
			<input type="submit" name="reg" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"表示中":"現在非表示";?>" style="width:75px;">
			<input type="hidden" name="cate" value="<?php echo $fetch[$i]['CATEGORY_CODE'];?>">
			<input type="hidden" name="action" value="display_change">
			<input type="hidden" name="display_change" value="<?php echo ($fetch[$i]["DISPLAY_FLG"] == 1)?"f":"t";?>">
			</form>
		</td>

		<td align="center">
		<form method="post" action="./" style="margin:0;" onSubmit="return confirm('このカテゴリーに登録されていますデータを完全に削除します。\nデータの復帰は出来ません。\nよろしいですか？');">
		<input type="submit" value="削除">
		<input type="hidden" name="cate" value="<?php echo $fetch[$i]['CATEGORY_CODE'];?>">
		<input type="hidden" name="action" value="del_data">
		</form>
		</td>
	</tr>
	<?php endfor;?>
</table>
<?php endif;?>
</body>
</html>