<?php
/*******************************************************************************
アパレル対応(カテゴリ+サブカテゴリ)

ユーザーの検索
View：ユーザー検索結果の表示画面

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

$NEXT = $p + 1;

$PREVIOUS = $p - 1;
// CHECK ALL DATA
$TCNT = count($fetchCustListCNT);
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/CUSTOMER_MAXROW);

// SET DISPLAY

if($p > 1){
	$PREVIOUS_PAGE = "<a href='./?p={$PREVIOUS}&status=search_result'>前の".CUSTOMER_MAXROW."件へ</a>";

}else{
	$PREVIOUS_PAGE = "";
}

if($TOTLE_PAGES > $p){
	$NEXT_PAGE = "<a href='./?p={$NEXT}&status=search_result'>次の".CUSTOMER_MAXROW."件へ</a>";
}else{
	$NEXT_PAGE = "";
}

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
<p class="page_title">ユーザ管理：購入者検索結果</p>
<p class="explanation">
▼「表示」をクリックすると詳細情報が表示されます。<br>
▼検索条件を指定し直したい場合は「ユーザー検索画面へ」をクリックしてください。
</p>
<?php if(!$fetchCustList):?>
<p>該当するお客様は登録されておりません。</p>
<?php else:?>
<div>※検索結果：<strong><?php echo count($fetchCustListCNT);?></strong>&nbsp;件</div><br>
<table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?php echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?php echo $NEXT_PAGE;?></td>
	</tr>
</table>
<table width="90%" border="0" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th nowrap>氏名</th>
		<th>氏名(かな)</th>
		<th>総ご利用額<br>(商品代金合計)</th>
		<th>メールアドレス</th>
		<th nowrap>電話番号</th>
		<th>住所</th>
		<th width="5%">詳細</th>
	</tr>
	<?php
	for($i=0;$i<count($fetchCustList);$i++):
		$sid = $fetchCustList[$i]['STATE'];	// 都道府県は変数に入れておく
	?>
	<tr>
		<td align="center" nowrap class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchCustList[$i]["LAST_NAME"]."&nbsp;".$fetchCustList[$i]["FIRST_NAME"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchCustList[$i]["LAST_KANA"]."&nbsp;".$fetchCustList[$i]["FIRST_KANA"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo "\\".number_format($fetchCustList[$i]["TOTAL_SUM_PRICE"]);?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchCustList[$i]["EMAIL"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchCustList[$i]["TEL1"]."-".$fetchCustList[$i]["TEL2"]."-".$fetchCustList[$i]["TEL3"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $shipping_list[$sid]['pref'].$fetchCustList[$i]["ADDRESS1"]."&nbsp;".$fetchCustList[$i]["ADDRESS2"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php // 個人情報の詳細画面を表示（status:cust_detailsへ）?>
		<form action="./" method="post" style="margin:0;">
		<input type="submit" value="表示">
		<input type="hidden" name="target_customer_id" value="<?php echo $fetchCustList[$i]["CUSTOMER_ID"];?>">
		<input type="hidden" name="status" value="cust_details">
		</form>
	 </td>
	</tr>
	<?php endfor;?>
</table>
<table width="90%" border="0" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?php echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?php echo $NEXT_PAGE;?></td>
	</tr>
</table>
<br>
<?php endif;?>
<input type="button" value="この画面を印刷" style="width:150px;" onClick="javascript:PrintPage();">

<form action="./" method="post">
<input type="submit" value="ユーザー検索画面へ" style="width:150px;">
</form>
</body>
</html>
