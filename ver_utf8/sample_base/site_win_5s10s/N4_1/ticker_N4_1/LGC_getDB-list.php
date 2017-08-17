<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）N3_2
新着情報の内容を表示するプログラム

LGC：対象のパラメーター（ID）を条件に
	 DBより新着情報の詳細情報を取得

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------------------
# 対象ID（RES_ID）データのチェック
#-------------------------------------------------------------------------
$res_id = urldecode($_GET['id']);

// パラメータがないもしくは不正なデータを混入された状態でアクセスされた場合のエラー処理
if(empty($res_id) || !ereg("^([0-9]{10,})-([0-9]{6})$",$res_id) ){
	header("Location: ../");exit();
}
$sql = "
SELECT
	RES_ID,COMMENT,CONTENT,
	YEAR(DISP_DATE) AS Y,
	MONTH(DISP_DATE) AS M,
	DAYOFMONTH(DISP_DATE) AS D,
	DISPLAY_FLG
FROM
	N4_1TICKER
WHERE
	(RES_ID = '".addslashes($res_id)."')
AND
	(DISPLAY_FLG = '1')
";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

// ＳＱＬの実行を取得できてなければ処理をしない
if(empty($fetch[0]["RES_ID"])){
	header("Location: ../");exit();
}
?>