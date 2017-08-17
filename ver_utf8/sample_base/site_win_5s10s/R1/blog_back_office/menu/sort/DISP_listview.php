<?php
/*******************************************************************************
ALL-INTERNET BLOG

並び替え：一覧

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if (!$_SESSION['LOGIN']) {
    header("Location: ../../err.php");
    exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("UTF-8", true, true, true, true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title><?php echo BO_TITLE; ?></title>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="sort.js"></script>
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">カテゴリーの並び替え：現在の並び順</p>
<?php
    if (!$fetchList):
        echo "<strong>登録されているジャンル名はありません。</strong><br><br>";
    else:
?>
<p class="explanation">※変更したいカテゴリーを選択、「↓UP」「↑DOWN」ボタンで並び替えを行ってください。</p>
※現在の登録カテゴリー数&nbsp;<strong><?php echo count($fetchList); ?></strong>&nbsp;件
<table width="400" height="0" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="350">
		<table width="100%" border="0" cellpadding="5" cellspacing="2">
			<tr>
				<th width="60%" nowrap class="tdcolored">カテゴリー名</th>
				<th width="40%" nowrap class="tdcolored">現在の<br>表示順</th>
			</tr>
			<?php for ($i = 0; $i < count($fetchList); $i++): ?>
			<tr>
				<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php	echo $fetchList[$i]['CATEGORY_NAME']; ?></td>
				<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchList[$i]['VIEW_ORDER']; ?></td>
			</tr>
			<?php endfor; ?>
		</table>
		</td>
		<td valign="top">
		<table width="150" border="0" cellpadding="0" cellspacing="0">
			<form name="change_sort" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin:0;">
			<tr>
				<td>
				<select name="nvo" size="<?php echo (count($fetchList) > 20)?20:count($fetchList);// sizeは20件を基準にしておく?>">
					<?php for ($i = 0; $i < count($fetchList); $i++): ?>
					<option value="<?php echo $fetchList[$i]['CATEGORY_CODE']; ?>">
						<?php echo $fetchList[$i]['CATEGORY_NAME']; ?>
					</option>
					<?php endfor; ?>
				</select>
				</td>
				<td>
				<input type="button" value="↑UP" onClick="moveUp();" style="width:70px;"><br>
				<input type="button" value="↓DOWN" onClick="moveDn();" style="width:70px;">
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="button" value="上記の並び替え順で更新" style="margin-top:0.5em;width:150px;" onClick="change_sortSubmit();">
				</td>
			</tr>
			<input type="hidden" name="status" value="view_order_update">
			<input type="hidden" name="new_view_order" value="">

			</form>
		</table>
		</td>
	</tr>
</table>
<?php endif; ?>
</body>
</html>
