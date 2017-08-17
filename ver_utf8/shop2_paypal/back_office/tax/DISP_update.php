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
<?php if(!empty($error_mes)):?>
<p class="err">
<?php echo $error_mes;?>
</p>
<?php endif;?>
<p class="explanation">
▼修正を行う場合は下記の入力欄の内容を修正してください。<br>
▼修正を終えられましたら下記にあります「<strong>確認</strong>」と書かれたボタンを押してください。
</p>
<form action="./" method="post" onSubmit="return inputChk(this);" style="margin:0;">
	<table width="750" border="0" cellpadding="5" cellspacing="2">
		<tr align="center">
			<th colspan="2" nowrap class="tdcolored">■販売商品の税率</th>
		</tr>
		<tr>
			<th width="20%" nowrap align="right" class="tdcolored">
			販売商品の税率：
			</th>
			<td class="other-td">
				<select name="tax">
					<?php for($i=0;$i <= 50;$i++){?>
						<option value="<?php echo $i;?>" <?php echo ($tax == $i)?"selected":"";?>><?php echo $i;?></option>
					<?php }?>
				</select>
					％
			</td>
		</tr>
		<tr>
			<th nowrap align="right" class="tdcolored">
			販売商品の消費税の端数処理：
			</th>
			<td class="other-td">
				<input type="radio" name="tax_float" value="0" id="tf0" <?php echo ($tax_float != 1)?"checked":"";?>><label for="tf0">端数は切り捨て</label>
				<br>
				<input type="radio" name="tax_float" value="1" id="tf1" <?php echo ($tax_float == 1)?"checked":"";?>><label for="tf1">端数は切り上げ</label>
			</td>
		</tr>

	</table>
	<input type="submit" value="確認" style="width:150px;">
	<input type="hidden" name="action" value="confirm">
</form>
</body>
</html>
