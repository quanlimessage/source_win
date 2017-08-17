<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	完了画面の表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

if( !$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
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
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ユーザ管理：データ更新</p>
<p><strong>更新しました</strong></p>
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" class="tdcolored">■更新ユーザデータ</th>
	</tr>
	<tr>
		<th width="150" class="tdcolored">ユーザーID</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["CUSTOMER_ID"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">氏名</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["LAST_NAME"];?>&nbsp;<?php echo $fetchEditCust[0]["FIRST_NAME"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">氏名(かな)</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["LAST_KANA"];?>&nbsp;<?php echo $fetchEditCust[0]["FIRST_KANA"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">パスワード</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["ALPWD"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">郵便番号</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["ZIP_CD1"];?>-<?php echo $fetchEditCust[0]["ZIP_CD2"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">住所</th>
		<td class="other-td">
		<?php echo $shipping_list[$fetchEditCust[0]['STATE']]['pref']?><br>
		<?php echo $fetchEditCust[0]["ADDRESS1"];?><br>
		<?php echo $fetchEditCust[0]["ADDRESS2"];?>
		</td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">メールアドレス</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["EMAIL"];?></td>
	</tr>
	<tr>
		<th width="150" class="tdcolored">電話番号</th>
		<td class="other-td"><?php echo $fetchEditCust[0]["TEL1"];?>-<?php echo $fetchEditCust[0]["TEL2"];?>-<?php echo $fetchEditCust[0]["TEL3"];?></td>
	</tr>
</table>
<form action="../" method="post">
<input type="submit" value="ユーザ詳細画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="cust_details">
<input type="hidden" name="target_customer_id" value="<?php echo $fetchEditCust[0]["CUSTOMER_ID"];?>">
</form>
</body>
</html>
