<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
View：新規登録画面表示

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
}

#=============================================================
# HTTPヘッダーを出力
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／有効期限の設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../actchange.js" type="text/javascript"></script>

<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
<script type="text/javascript" src="./uploadcheck.js"></script>

</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="管理画面トップへ" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort<?php echo (STM_SORT_TYPE == 1)?"":"2";?>.php" method="post">
		<input type="submit" value="並び替えを行う" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo STM_TITLE;?>：新規登録</p>
<p class="explanation" style="width:600px;">
	▼現在のデータ内容が初期表示されています。<br>
	▼内容を編集したい場合は上書きをして「更新する」をクリックしてください。<br>
	▼テンプレートに挿入したいデータが在る場合は、下記の【%】に囲まれた文字列を挿入したい箇所に入れてください。<br>
	<br>
	<table border="1">
		<tr><td width="20%" align="center" nowrap>貼り付け文字</td><td width="80%">説明</td></tr>
		<tr><td nowrap align="center">%BANK_INFO%</td>		<td>銀行の振込先情報</td></tr>
		<tr><td nowrap align="center">%COMPANY_INFO%</td>	<td>お問い合わせ先</td></tr>
		<tr><td nowrap align="center">%CUST_NAME%</td>		<td>お客様のお名前</td></tr>

		<tr><td nowrap align="center">%ORDER_TIME%</td>		<td>購入日付</td></tr>
		<tr><td nowrap align="center">%ORDER_ID%</td>		<td>受付番号</td></tr>
		<tr><td nowrap align="center">%SUM_PRICE%</td>		<td>ご購入金額（小計）</td></tr>
		<tr><td nowrap align="center">%PAY_TYPE%</td>		<td>お支払方法</td></tr>

		<tr><td nowrap align="center">%SHIPPING_AMOUNT%</td>	<td>配送料</td></tr>
		<tr><td nowrap align="center">%DAIBIKI_AMOUNT%</td>	<td>代引き</td></tr>
		<tr><td nowrap align="center">%TOTAL_PRICE%</td>	<td>お支払金額（送料、小計など全部合わせた合計金額）</td></tr>
		<tr><td nowrap align="center">%ITEMS%</td>		<td>ご購入商品の【商品番号】【商品名】【単価】【数量】を表示します。</td></tr>
		<tr><td nowrap align="center">%ITEM_NAME%</td>		<td>ご購入商品の【商品名】のみを表示します。</td></tr>

		<tr><td nowrap align="center">%BUYER%</td>		<td>購入者情報の【名前】【MAIL】【郵便番号】【住所】【電話番号】を表示します。</td></tr>
		<tr><td nowrap align="center">%DELI%</td>		<td>お届け先情報の【名前】【郵便番号】【住所】【電話番号】を表示します。</td></tr>
		<tr><td nowrap align="center">%REMARKS%</td>		<td>備考欄</td></tr>

	</table>
</p>

<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">■新規登録</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">タイトル：</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $title;?>" size="60" maxlength="125" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">件名：</th>
		<td class="other-td">
		<input name="subject" type="text" value="<?php echo $subject;?>" size="150" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">本文：</th>
		<td class="other-td">

		<textarea name="content" cols="120" rows="40" ><?php echo $content;?></textarea>
		</td>
	</tr>
</table>

<input type="submit" value="上記内容で登録する" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">
<input type="hidden" name="act" value="completion">
<input type="hidden" name="regist_type" value="new">

</form>
<br>
<form action="./" method="post">
	<input type="submit" value="リスト画面へ戻る" style="width:150px;">
</form>

<?php

//ボタン付近に表示する
cp_disp($layer_free,"0","0");

?>

</body>
</html>