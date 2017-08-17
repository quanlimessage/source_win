<?php
/*******************************************************************************
更新プログラム

	Logic:ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#--------------------------------------------------------------------------------
# 選択された処理action（$_POST["action"]）により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------
switch($_POST["status"]):
	case "update":
	///////////////////////////////////////////
	// 更新指示のあった該当データの取得

		$sql = "
		SELECT
			DIARY_ID,
			SUBJECT,
			EMAIL,
			COMMENT,
			DISPLAY_FLG,
			YEAR(REG_DATE) AS Y,
			MONTH(REG_DATE) AS M,
			DAYOFMONTH(REG_DATE) AS D,
			LINK,
			LINK_FLG,
			IMG_FLG
		FROM
			N6_1DIARY
		WHERE
			(DIARY_ID = '$diary_id')
		AND
			(DEL_FLG = '0')
		";

		break;
	default:
	///////////////////////////////////////////
	// リスト一覧用データの取得

	$cntsql = "SELECT COUNT(*) AS CNT FROM ".N6_1DIARY."";
	$fetchCNT = dbOpe::fetch($cntsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if(empty($page) or !is_numeric($page))$page=1;

	$st = ($page-1) * N6_1BOF_MAX;

	$sql = "
		SELECT
			DIARY_ID,
			SUBJECT,
			COMMENT,
			EMAIL,
			DISPLAY_FLG,
			YEAR(REG_DATE) AS Y,
			MONTH(REG_DATE) AS M,
			DAYOFMONTH(REG_DATE) AS D
		FROM
			N6_1DIARY
		WHERE
			(DEL_FLG = '0')
		ORDER BY
			REG_DATE DESC
		LIMIT
			".$st.",".N6_1BOF_MAX."
	";

$sql_page = "
	SELECT
		RES_ID,
		PAGE_FLG
	FROM
		".N6_1DIARY_PAGE."
	";
$fetch_page = dbOpe::fetch($sql_page,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

endswitch;

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
?>