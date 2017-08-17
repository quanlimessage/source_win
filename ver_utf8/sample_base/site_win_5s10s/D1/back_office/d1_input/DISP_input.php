<?php
/*******************************************************************************
ＣＳＶでの登録
*******************************************************************************/

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
<title>System Back Office</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ＣＳＶでの新規登録</p>
<p class="explanation">
▼CSVファイルからデータを登録します。<br>
▼CSVファイルを選択しましたら<strong>「新規追加」</strong>をクリックしてください
</p>

<?php echo ($mess)?$mess:"";?>

<form action="./" method="post" enctype="multipart/form-data" name="csv_data">
<table width="650" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<td colspan="2" class="tdcolored" align="center">■ＣＳＶ登録</td>
	</tr>
	<tr>
		<td class="tdcolored" width="200">CSVファイルの選択：</td>
		<td class="other-td"><input type='file' name='UploadFile'></td>
	</tr>
</table>

<br><br>
<input type="submit" value="新規追加" style="width:150px;">
<input type="hidden" name="status" value="product_entry">
</form>

<br>

</body>
</html>
