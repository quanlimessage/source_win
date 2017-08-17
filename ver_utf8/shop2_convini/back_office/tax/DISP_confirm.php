<?php
/*******************************************************************************
管理者情報
View：既存管理者情報の登録内容修正　情報入力画面

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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="text/css; charset=UTF-8">
<title><?php echo BO_TITLE; ?></title>
<script type="text/javascript" src="input_check.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">消費税の更新</p>
<p class="explanation">
▼これで宜しければ下記にあります<b>「更新を確定する」</b>をクリックしてください。<br>
▼内容を修正する場合は<b>「戻る」</b>をクリックしてください。
</p>
<table width="750" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" align="center" nowrap class="tdcolored">■販売商品の税率</th>
	</tr>
	<tr>
		<th width="20%" nowrap align="right" class="tdcolored">
		販売商品の税率：
		</th>
		<td class="other-td"><?php echo $tax;?>％</td>
	</tr>
	<tr>
		<th width="20%" nowrap align="right" class="tdcolored">
		販売商品の消費税の端数処理：
		</th>
		<td class="other-td">
			<?php echo ($tax_float != 1)?"端数は切り捨て ":"端数は切り上げ";?>
		</td>
	</tr>
</table>
<form action="./" method="post" onSubmit="return regChk(this);" style="margin:0;">
	<input type="submit" value="更新を確定する" style="width:150px;">
	<input type="hidden" name="tax" value="<?php echo $tax;?>">
	<input type="hidden" name="tax_float" value="<?php echo $tax_float;?>">
	<input type="hidden" name="action" value="completion">
</form>
<form action="./" method="post" style="margin:0;">
	<input type="submit" value="戻る" name="action" style="width:150px;">
	<input type="hidden" name="tax" value="<?php echo $tax;?>">
	<input type="hidden" name="tax_float" value="<?php echo $tax_float;?>">
	<input type="hidden" name="action" value="edit">
</form>
</body>
</html>
