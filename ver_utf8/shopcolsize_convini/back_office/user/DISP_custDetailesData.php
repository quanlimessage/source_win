<?php
/*******************************************************************************
アパレル対応

	ユーザー情報：詳細情報表示

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
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../common.js"></script>
</head>
<body>
<form action="../main.php" method="post">
	<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">ユーザ管理：購入者詳細</p>
<p class="explanation">
▼ユーザー情報データを編集したい場合は「編集」をクリックしてください。<br>
▼「ユーザ管理：購入履歴」にはこのユーザーの全お買い物情報が表示されています。<br>
▼各欄右端の「表示」をクリックするとその購入に関する詳細情報が表示されます。
</p>
		<table width="500" border="0" cellpadding="5" cellspacing="2">
			<tr>
				<th width="25%" align="right" nowrap class="tdcolored">ID：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["CUSTOMER_ID"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">PASSWORD：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["ALPWD"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">登録日：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["INS_DATE"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">氏名：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["LAST_NAME"]."&nbsp;".$fetchCust[0]["FIRST_NAME"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">氏名（かな）：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["LAST_KANA"]."&nbsp;".$fetchCust[0]["FIRST_KANA"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">メールアドレス：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["EMAIL"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">電話番号：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["TEL1"]."-".$fetchCust[0]["TEL2"]."-".$fetchCust[0]["TEL3"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">郵便番号：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["ZIP_CD1"]."-".$fetchCust[0]["ZIP_CD2"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">都道府県：</th>
				<td class="other-td">&nbsp;<?php
				$sid = $fetchCust[0]["STATE"];	// 変数に入れてから表示
				echo $shipping_list[$sid]['pref'];?>
				</td>
		 </tr>
			<tr>
				<th align="right" nowrap class="tdcolored">住所１：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["ADDRESS1"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">住所２：</th>
				<td class="other-td">&nbsp;<?php echo $fetchCust[0]["ADDRESS2"];?></td>
			</tr>
			<tr>
				<th align="right" nowrap class="tdcolored">ご利用合計額：<br>商品代合計</th>
				<td class="other-td">&nbsp;<?php echo "\\".number_format($fetchCust[0]["TOTAL_SUM_PRICE"]);?></td>
			</tr>
			<form method="post" action="user_edit/">
			<tr>
				<td colspan="2" class="other-td" align="center">
				<input type="submit" value="編集" style="width:150px;">
				</td>
			</tr>
			<input type="hidden" name="customer_id" value="<?php echo $fetchCust[0]["CUSTOMER_ID"];?>">
			</form>
		</table>

		<!-- 購入履歴 -->
		<p class="page_title">ユーザ管理：購入履歴</p>
		<table width="700" border="0" cellpadding="5" cellspacing="2">
        	<tr class="tdcolored">
        		<th width="26%" nowrap>注文番号（受付番号）</th>
        		<th width="5%" nowrap>決済</th>
        		<th width="5%" nowrap>配送</th>
        		<th width="10%" nowrap>支払い方法</th>
        		<th width="18%" nowrap>注文日</th>
        		<th width="18%" nowrap>支払日</th>
        		<th width="18%" nowrap>配送日</th>
        		<th width="10%" nowrap>詳細表示</th>
			</tr>
        	<?php for($i=0;$i<count($fetchPurchase);$i++):?>
        	<tr>
        		<td height="30" align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchPurchase[$i]["ORDER_ID"];?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["PAYMENT_FLG"]==1)echo "済";elseif($fetchPurchase[$i]["PAYMENT_FLG"]==0)echo "<span style=\"color:red;\">未</span>";elseif($fetchPurchase[$i]["PAYMENT_FLG"]==2)echo "<span style=\"color:red;\">失</span>";?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo ($fetchPurchase[$i]["SHIPPED_FLG"])?"済":"<span style=\"color:red;\">未</span>";?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php switch($fetchPurchase[$i]["PAYMENT_TYPE"]):case 1:echo "クレジット";break;case 2:echo "銀行振込";break;case 3:echo "代引き";break;case 4:echo "コンビニ";break;case 5:echo "郵便振替";break;endswitch;?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchPurchase[$i]["ORDER_DATE"];?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchPurchase[$i]["PAYMENT_DATE"];?></td>
        		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php echo $fetchPurchase[$i]["SHIPPED_DAY"];?></td>
        		<form method="post" action="./">
				<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
				<input type="submit" value="表示">
				</td>
				<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
				<input type="hidden" name="status" value="order_details">
				<input type="hidden" name="target_customer_id" value="<?php echo $target_customer_id;?>">
				</form>
			</tr>
			<?php if(!empty($fetchPurchase[$i]["CONFIG_MEMO"])):?>
			<tr>
				<td height="30" colspan="8" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" align="left">
				<strong>■受注番号<?php echo $fetchPurchase[$i]["ORDER_ID"];?>の管理メモ</strong><br>
				<?php echo nl2br($fetchPurchase[$i]["CONFIG_MEMO"]);?>
				</td>
			</tr>
			<?php endif;?>
        	<?php endfor;?>
</table>
<input type="button" value="この画面を印刷" style="width:150px;" onClick="javascript:PrintPage();">

<form action="./" method="post">
<input type="submit" value="検索結果画面へ戻る" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

<form action="./" method="post">
<input type="submit" value="ユーザー検索条画面へ" style="width:150px;">
</form>
</body>
</html>
