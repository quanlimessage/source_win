<?php
/*******************************************************************************
更新プログラム

	ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

$sql = "
SELECT
	SCHEDULE_ID,
	DAY_1,
	DAY_2,
	DAY_3,
	DAY_4,
	DAY_5,
	DAY_6,
	DAY_7,
	DAY_8,
	DAY_9,
	DAY_10,
	DAY_11,
	DAY_12,
	DAY_13,
	DAY_14,
	DAY_15,
	DAY_16,
	DAY_17,
	DAY_18,
	DAY_19,
	DAY_20,
	DAY_21,
	DAY_22,
	DAY_23,
	DAY_24,
	DAY_25,
	DAY_26,
	DAY_27,
	DAY_28,
	DAY_29,
	DAY_30,
	DAY_31,
	COMM_1,
	COMM_2,
	COMM_3,
	COMM_4,
	COMM_5,
	COMM_6,
	COMM_7,
	COMM_8,
	COMM_9,
	COMM_10,
	COMM_11,
	COMM_12,
	COMM_13,
	COMM_14,
	COMM_15,
	COMM_16,
	COMM_17,
	COMM_18,
	COMM_19,
	COMM_20,
	COMM_21,
	COMM_22,
	COMM_23,
	COMM_24,
	COMM_25,
	COMM_26,
	COMM_27,
	COMM_28,
	COMM_29,
	COMM_30,
	COMM_31
FROM
	".SCHEDULE."
WHERE
	(YEAR = '$y') AND (MONTH = '$m') AND (DEL_FLG = '0')
";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

$sql_month = "
	SELECT SCHEDULE_ID,YEAR,MONTH FROM SCHEDULE WHERE (DEL_FLG = '0') ORDER BY YEAR,MONTH
";

$fetch_month = dbOpe::fetch($sql_month,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>