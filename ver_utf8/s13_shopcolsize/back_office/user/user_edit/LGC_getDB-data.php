<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	指定ユーザ情報の取得

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
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

// 注文情報と個人情報
$sql = "
SELECT
	CUSTOMER_ID,
	LAST_NAME,
	FIRST_NAME,
	LAST_KANA,
	FIRST_KANA,
	ALPWD,
	ZIP_CD1,
	ZIP_CD2,
	STATE,
	ADDRESS1,
	ADDRESS2,
	EMAIL,
	TEL1,
	TEL2,
	TEL3,
	UPD_DATE,
	INS_DATE
FROM
	".CUSTOMER_LST."
WHERE
	(CUSTOMER_ID = '{$customer_id}')
AND
	(DEL_FLG = '0')
";

$fetchCust = $PDO -> fetch($sql);
?>
