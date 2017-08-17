<?php
/*******************************************************************************
アパレル対応

	ユーザー情報：DBより詳細情報の取得

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

#-------------------------------------------------------------------------------
# 指定されたCUSTOMER_ID（$_POST["target_customer_id"]）を条件に
# 個人情報と購入履歴を取得
#-------------------------------------------------------------------------------

// 個人情報
$sql1 = "
SELECT
	".PURCHASE_LST.".CUSTOMER_ID,
	SUM(".PURCHASE_LST.".SUM_PRICE) AS TOTAL_SUM_PRICE,
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
	".CUSTOMER_LST.".INS_DATE
FROM
	".PURCHASE_LST.",".CUSTOMER_LST."
WHERE
	".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
AND
	(".PURCHASE_LST.".CUSTOMER_ID = '".$_POST["target_customer_id"]."')
AND
	(".CUSTOMER_LST.".DEL_FLG = '0')
GROUP BY
	".CUSTOMER_LST.".CUSTOMER_ID
";

$fetchCust = dbOpe::fetch($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// 購入履歴
$sql2 = "
SELECT
	ORDER_ID,
	CUSTOMER_ID,
	PAYMENT_TYPE,
	ORDER_DATE,
	PAYMENT_FLG,
	PAYMENT_DATE,
	SHIPPED_FLG,
	SHIPPED_DAY,
	CONFIG_MEMO
FROM
	".PURCHASE_LST."
WHERE
	(CUSTOMER_ID = '".$_POST["target_customer_id"]."')
AND
	(DEL_FLG = '0')
";
$fetchPurchase = dbOpe::fetch($sql2,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>
