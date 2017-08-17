<?php
/*******************************************************************************

	ユーザー情報：購入詳細情報を表示

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
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

////////////////////////////////////////////////
// 指定された検索条件を元に顧客一覧情報を取得
// 指定なし：全情報を取得

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#-------------------------------------------------------------------------------
# 指定されたCUSTOMER_ID（$_POST["target_customer_id"]）を条件に
# 個人情報と購入履歴を取得
#-------------------------------------------------------------------------------

// 注文情報と個人情報
$sql1 = "
SELECT
	".PURCHASE_LST.".ORDER_ID,
	".PURCHASE_LST.".CUSTOMER_ID,
	".CUSTOMER_LST.".LAST_NAME,
	".CUSTOMER_LST.".FIRST_NAME,
	".CUSTOMER_LST.".LAST_KANA,
	".CUSTOMER_LST.".FIRST_KANA,
	".CUSTOMER_LST.".ALPWD,
	".CUSTOMER_LST.".ZIP_CD1,
	".CUSTOMER_LST.".ZIP_CD2,
	".CUSTOMER_LST.".STATE,
	".CUSTOMER_LST.".ADDRESS1,
	".CUSTOMER_LST.".ADDRESS2,
	".CUSTOMER_LST.".EMAIL,
	".CUSTOMER_LST.".TEL1,
	".CUSTOMER_LST.".TEL2,
	".CUSTOMER_LST.".TEL3,
	".PURCHASE_LST.".TOTAL_PRICE,
	".PURCHASE_LST.".SUM_PRICE,
	".PURCHASE_LST.".SHIPPING_AMOUNT,
	".PURCHASE_LST.".DAIBIKI_AMOUNT,
	".PURCHASE_LST.".DELI_LAST_NAME,
	".PURCHASE_LST.".DELI_FIRST_NAME,
	".PURCHASE_LST.".DELI_ZIP_CD1,
	".PURCHASE_LST.".DELI_ZIP_CD2,
	".PURCHASE_LST.".DELI_STATE,
	".PURCHASE_LST.".DELI_ADDRESS1,
	".PURCHASE_LST.".DELI_ADDRESS2,
	".PURCHASE_LST.".DELI_TEL1,
	".PURCHASE_LST.".DELI_TEL2,
	".PURCHASE_LST.".DELI_TEL3,
	".PURCHASE_LST.".PAYMENT_TYPE,
	".PURCHASE_LST.".ORDER_DATE,
	".PURCHASE_LST.".PAYMENT_FLG,
	".PURCHASE_LST.".PAYMENT_DATE,
	".PURCHASE_LST.".SHIPPED_FLG,
	".PURCHASE_LST.".SHIPPED_DAY,
	".PURCHASE_LST.".CONFIG_MEMO,
	".PURCHASE_LST.".REMARKS
FROM
	".PURCHASE_LST.",".CUSTOMER_LST."
WHERE
	".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
AND
	(".PURCHASE_LST.".ORDER_ID = '".$target_order_id."')
AND
	(".PURCHASE_LST.".DEL_FLG = '0')
AND
	(".CUSTOMER_LST.".DEL_FLG = '0')
";

$fetchOrderCust = dbOpe::fetch($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// 注文情報詳細
$sql3 = "
SELECT
	".PURCHASE_ITEM_DATA.".PART_NO,
	".PURCHASE_ITEM_DATA.".PRODUCT_NAME,
	".PURCHASE_ITEM_DATA.".SELLING_PRICE,
	".PURCHASE_ITEM_DATA.".QUANTITY
FROM
	".PURCHASE_ITEM_DATA."
WHERE
	(".PURCHASE_ITEM_DATA.".ORDER_ID = '".$target_order_id."')
AND
	(".PURCHASE_ITEM_DATA.".DEL_FLG = '0')
ORDER BY
	".PURCHASE_ITEM_DATA.".PID ASC
";
$fetchPerItem = dbOpe::fetch($sql3,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
?>
