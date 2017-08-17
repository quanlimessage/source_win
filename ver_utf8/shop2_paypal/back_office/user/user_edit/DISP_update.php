<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	編集画面表示

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
<script language="javascript" src="input_check.js"></script>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<form action="../../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ユーザ管理：データ更新</p>
<p class="explanation">
▼ユーザー情報の編集したい場合はデーターを上書きして「更新」をクリックしてください。
</p>
<?php if(!empty($error_message)):?>
<p class="err"><?php echo $error_message;?></p>
<?php endif;?>
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<form method="post" action="./" onSubmit="return inputChk(this);">
	<tr>
		<th width="25%" align="right" nowrap class="tdcolored">ID：</th>
		<td class="other-td">&nbsp;<?php echo $fetchCust[0]["CUSTOMER_ID"];?></td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">PASSWORD：</th>
		<td class="other-td">&nbsp;<input type="text" name="alpwd" value="<?php echo $fetchCust[0]["ALPWD"];?>" size="30"></td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">登録日：</th>
		<td class="other-td">&nbsp;<?php echo $fetchCust[0]["INS_DATE"];?></td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">氏名：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="last_name" value="<?php echo $fetchCust[0]["LAST_NAME"]?>" size="15">&nbsp;
		<input type="text" name="first_name" value="<?php echo $fetchCust[0]["FIRST_NAME"];?>" size="15">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">氏名(かな)：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="last_kana" value="<?php echo $fetchCust[0]["LAST_KANA"]?>" size="15">&nbsp;
		<input type="text" name="first_kana" value="<?php echo $fetchCust[0]["FIRST_KANA"];?>" size="15">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">メールアドレス：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="email" value="<?php echo $fetchCust[0]["EMAIL"];?>" size="40" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">電話番号：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="tel1" value="<?php echo $fetchCust[0]["TEL1"]?>" size="5" maxlength="5" style="ime-mode:disabled">
		-
		<input type="text" name="tel2" value="<?php echo $fetchCust[0]["TEL2"]?>" size="5" maxlength="5" style="ime-mode:disabled">
		-
		<input type="text" name="tel3" value="<?php echo $fetchCust[0]["TEL3"]?>" size="5" maxlength="5" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">郵便番号：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="zip1" value="<?php echo $fetchCust[0]["ZIP_CD1"]?>" size="5" maxlength="3" style="ime-mode:disabled">
		-
		<input type="text" name="zip2" value="<?php echo $fetchCust[0]["ZIP_CD2"]?>" size="5" maxlength="4" style="ime-mode:disabled">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">都道府県：</th>
		<td class="other-td">&nbsp;
		<select name="state">
		<?php for($i=0;$i<count($shipping_list);$i++):?>
		<option value="<?php echo $i;?>"<?php echo ($i == $fetchCust[0]["STATE"])?" selected":"";?>><?php echo $shipping_list[$i]['pref']?></option>
		<?php endfor;?>
		</select>
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">住所１：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="address1" value="<?php echo $fetchCust[0]["ADDRESS1"];?>" size="50">
		</td>
	</tr>
	<tr>
		<th align="right" nowrap class="tdcolored">住所２：</th>
		<td class="other-td">&nbsp;
		<input type="text" name="address2" value="<?php echo $fetchCust[0]["ADDRESS2"];?>" size="50">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" nowrap class="other-td">
		<input type="submit" name="reg" value="更新" style="width:150px;">
		</td>
	</tr>
	<input type="hidden" name="status" value="completion">
	<input type="hidden" name="customer_id" value="<?php echo $fetchCust[0]["CUSTOMER_ID"];?>">
	</form>
</table>
<form action="../" method="post">
<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

<form action="../" method="post">
<input type="submit" value="ユーザー検索条画面へ" style="width:150px;">
</form>
</body>
</html>
