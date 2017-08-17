<?php
/*******************************************************************************
アパレル対応

	受注情報：指定検索条件での検索結果

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
#	文字コードと言語：EUCで日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);

$NEXT = $p + 1;

$PREVIOUS = $p - 1;
// CHECK ALL DATA
$TCNT = count($fetchPurchaseCNT);
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/SALES_MAXROW);

// SET DISPLAY

if($p > 1){
	$PREVIOUS_PAGE = "<a href='./?p={$PREVIOUS}&status=search_result'>前の".SALES_MAXROW."件へ</a>";

}else{
	$PREVIOUS_PAGE = "";
}

if($TOTLE_PAGES > $p){
	$NEXT_PAGE = "<a href='./?p={$NEXT}&status=search_result'>次の".SALES_MAXROW."件へ</a>";
}else{
	$NEXT_PAGE = "";
}

//メールのテンプレート一覧を取得させる。
	$sql_temp_mail = "
	SELECT
		*
	FROM
		STM_PRODUCT_LST
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";
	$fetchTM = dbOpe::fetch($sql_temp_mail,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?php echo BO_TITLE;?></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../common.js"></script>
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="管理画面トップへ" style="width:150px;">
</form>
<p class="page_title">売上管理：検索結果</p>
<p class="explanation">
▼決済・配送状況の情報を「決済」「配送」欄のクリックで切り替えることが出来ます。<br>
▼決済・配送情報を更新すると日付データも更新されます。<br>
▼「表示」をクリックすると該当受注の詳細情報が表示されます。
</p>
<?php if(count($fetchPurchase) == 0):?>
<strong>表示するデータがありません</strong>
<br><br><br>
<?php else:?>
<div>※検索結果：<strong><?php echo count($fetchPurchaseCNT);?></strong>&nbsp;件</div><br>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?PHP echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?PHP echo $NEXT_PAGE;?></td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th width="15%" nowrap>注文番号</th>
		<th width="25%" nowrap>氏名</th>
		<th width="20%" nowrap>メールアドレス</th>
		<th width="5%" nowrap>決済状況</th>
		<th width="5%" nowrap>配送状況</th>
		<th width="10%" nowrap>購入商品代</th>
		<th width="5%" nowrap>支払方法</th>
		<th width="10%" nowrap>メール<br>送信</th>
		<th width="5%" nowrap>注文日</th>
		<th width="5%" nowrap>支払日</th>
		<th width="5%" nowrap>配送日</th>
		<th width="10%" nowrap>詳細表示</th>
	</tr>
	<?php
	for($i=0;$i<count($fetchPurchase);$i++):?>
	<tr>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["ORDER_ID"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["LAST_NAME"]."&nbsp;".$fetchPurchase[$i]["FIRST_NAME"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["EMAIL"];?></td>
		<form method="post" action="" style="margin:0px;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if($fetchPurchase[$i]["PAYMENT_FLG"] == 1):?>
		<input type="submit" value="決済完了" style="width:60px;" onClick="return confirm('未決済にします。宜しいですか？');">
		<?php elseif($fetchPurchase[$i]["PAYMENT_FLG"] == 0):?>
		<input type="submit" value="未決済" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('決済完了にします。宜しいですか？');">
		<?php elseif($fetchPurchase[$i]["PAYMENT_FLG"] == 2):?>
		<font style="font-size:12px;color:#FF0000;">決済失敗</font>
		<?php endif;?>
		</td>
		<input type="hidden" name="payment_flg" value="<?php echo $fetchPurchase[$i]["PAYMENT_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_payment_flg">
		<input type="hidden" name="status" value="search_result">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		</form>
		<form method="post" action="" style="margin:0px;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if($fetchPurchase[$i]["SHIPPED_FLG"] == 1):?>
		<input type="submit" value="配送済" style="width:60px;" onClick="return confirm('未配送に修正します。宜しいですか？');">
		<?php else:?>
		<input type="submit" value="未配送" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('配送完了にします。宜しいですか？');">
		<?php endif;?>
		</td>
		<input type="hidden" name="shipped_flg" value="<?php echo $fetchPurchase[$i]["SHIPPED_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_shipped_flg">
		<input type="hidden" name="status" value="search_result">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		</form>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">\<?php echo number_format($fetchPurchase[$i]["SUM_PRICE"]);?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php switch($fetchPurchase[$i]["PAYMENT_TYPE"]):case 1:echo "クレジット";break;case 2:echo "銀行振込";break;case 3:echo "代引き";break;endswitch;?></td>

		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
			<form method="post" action="./send_mail/" style="margin:0px;">
				<select name="send_mail_type">
					<?php for($k =0;$k < count($fetchTM);$k++){?>
					<option value="<?php echo $fetchTM[$k]['RES_ID'];?>"><?php echo $fetchTM[$k]['TITLE'];?></option>
					<?php }?>
				</select>
				<br>
				<input type="submit" name="submit" value="メールを作成" style="width:100px;">
				<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
				<input type="hidden" name="status" value="send_pay">
			</form>
			<br>
			<form method="post" action="./send_mail/m_hist.php" style="margin:0px;" target="_blank">
				<input type="submit" name="submit" value="送信記録" style="width:100px;">
				<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
			</form>

		</td>

		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["ORDER_DATE"] &&($fetchPurchase[$i]["ORDER_DATE"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["ORDER_DATE"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["PAYMENT_DATE"] &&($fetchPurchase[$i]["PAYMENT_DATE"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["PAYMENT_DATE"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["SHIPPED_DAY"] &&($fetchPurchase[$i]["SHIPPED_DAY"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["SHIPPED_DAY"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<form method="post" action="./" style="margin:0px;">
		<input type="submit" name="submit" value="表示">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		<input type="hidden" name="status" value="disp_details">
		</form>
		</td>
	</tr>
	<?php if(!empty($fetchPurchase[$i]["CONFIG_MEMO"])):?>
	<tr>
		<td colspan="12" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<strong>■注文番号<?php echo $fetchPurchase[$i]["ORDER_ID"];?>の備考</strong>(最終更新日<?php echo $fetchPurchase[$i]["UPD_DATE"];?>)<br>
		<?php echo nl2br($fetchPurchase[$i]["CONFIG_MEMO"]);?>
		</td>
	</tr>
	<?php endif;?>
	<?php endfor;?>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?PHP echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?PHP echo $NEXT_PAGE;?></td>
	</tr>
</table>
<br>
<?php endif;?>
<input type="button" value="この画面を印刷" style="width:150px;" onClick="javascript:PrintPage();">

<form action="./" method="post">
<input type="submit" value="注文情報検索入力画面へ" style="width:150px; ">
</form>
</body>
</html>
