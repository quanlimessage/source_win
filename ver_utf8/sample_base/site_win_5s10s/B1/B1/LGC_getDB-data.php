<?php
/*******************************************************************************
更新プログラム

	ＤＢ情報取得処理ファイル

*******************************************************************************/

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------

// MASTER DATA取得

$cntsql = "SELECT COUNT(*) AS CNT FROM ".BBS_LOG_MST_DATA." WHERE (DISPLAY_FLG = 1) AND (DEL_FLG = 0)";
$fetchCNT = dbOpe::fetch($cntsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

if(empty($page) or !is_numeric($page))$page=1;

$st = ($page-1) * BBS_MAX;

$sql = "
	SELECT
		MASTER_ID,
		NAME,
		EMAIL,
		URL,
		TITLE,
		COMMENT,
		date_format(REG_DATE, '%Y/%m/%d %T') AS REG_DATE
	FROM
		".BBS_LOG_MST_DATA."
	WHERE
		(DISPLAY_FLG = 1)
	AND
		(DEL_FLG = 0)
	ORDER BY
		REG_DATE DESC
	LIMIT
		".$st.",".BBS_MAX."
	";
$MAIN_fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

for($i=0;$i<count($MAIN_fetch);$i++):

	if($MAIN_fetch[$i]['MASTER_ID']){
		// SUB DATA取得
		$sql = "
			SELECT
				SUB_ID,
				NAME,
				EMAIL,
				URL,
				COMMENT,
				date_format(REG_DATE, '%Y/%m/%d %T') AS REG_DATE
			FROM
				".BBS_LOG_SUB_DATA."
			WHERE
				(MASTER_ID = '".$MAIN_fetch[$i]['MASTER_ID']."')
			AND
				(DISPLAY_FLG = 1)
			AND
				(DEL_FLG = 0)
			ORDER BY
				REG_DATE DESC
			";
		$SUB_fetch[$i] = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	}

endfor;

if($master_id && $res){

	$sql = "
	SELECT
		NAME,
		TITLE,
		COMMENT
	FROM
		".BBS_LOG_MST_DATA."
	WHERE
		(MASTER_ID = '$master_id')
	";

	// ＳＱＬを実行
	$_SESSION["fetch"] = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
}

?>