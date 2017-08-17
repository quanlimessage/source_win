<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	DB情報の更新

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

#=================================================================================
# SQL組立
#=================================================================================

$sql1 = "
UPDATE
	".CUSTOMER_LST."
SET
	LAST_NAME = '$last_name',
	FIRST_NAME = '$first_name',
	LAST_KANA = '$last_kana',
	FIRST_KANA = '$first_kana',
	ALPWD = '$alpwd',
	ZIP_CD1 = '$zip1',
	ZIP_CD2 = '$zip2',
	STATE  = '$state',
	ADDRESS1 = '$address1',
	ADDRESS2 = '$address2',
	EMAIL = '$email',
	TEL1 = '$tel1',
	TEL2 = '$tel2',
	TEL3 = '$tel3',
	UPD_DATE = NOW(),
	DEL_FLG = '0'
WHERE
	(CUSTOMER_ID = '$customer_id')
AND
	(DEL_FLG = '0')
";

#=================================================================================
# SQL実行
#=================================================================================
if(!empty($sql1)){
	$db_result = dbOpe::regist($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB更新失敗しました<hr>{$db_result}");
}

#=================================================================================
# SQL組立
#=================================================================================
$sql2 = "
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
	TEL3
FROM
	".CUSTOMER_LST."
WHERE
	(CUSTOMER_ID = '$customer_id')
";
#=================================================================================
# SQL実行
#=================================================================================
if(!empty($sql2)){
	$fetchEditCust = dbOpe::fetch($sql2,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if(empty($fetchEditCust))die("更新後データが取得出来ません。");
}

?>
