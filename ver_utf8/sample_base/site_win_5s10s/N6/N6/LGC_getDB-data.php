<?php
/*******************************************************************************
SiteWin10 20 30（MySQL対応版）
N系写メールプログラム コントローラー

LGC：DBより日記の情報を取得

*******************************************************************************/
// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;
// 抽出開始位置の指定
	$st = ($p-1) * $page_flg;

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

$cntsql = "
SELECT
	DIARY_ID
FROM
	N6_1DIARY
WHERE
	(DISPLAY_FLG = 1)
AND

	(DEL_FLG = '0')
";
$fetchCNT = dbOpe::fetch($cntsql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=================================================================================
# ＤＢからデータを取得する
#=================================================================================

//ページ遷移
//if(empty($page) or !is_numeric($page))$page=1;

//$st = ($page-1) * N6_1PAGE_MAX;

//ＳＱＬ文
$sql = "
		SELECT
			DIARY_ID,
			EMAIL,
			SUBJECT,
			COMMENT,
			YEAR(REG_DATE) AS Y,
			MONTH(REG_DATE) AS M,
			DAYOFMONTH(REG_DATE) AS D,
			LINK,
			LINK_FLG,
			IMG_FLG
		FROM
			N6_1DIARY
		WHERE
			(DISPLAY_FLG = 1)
		ORDER BY
			REG_DATE DESC
		LIMIT
			".$st.",".$page_flg."
	";

// ＳＱＬを実行
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>