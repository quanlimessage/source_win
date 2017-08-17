<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_N3_S7.php');
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('tmpl2.class.php');// テンプレートクラスライブラリ
require_once('../common/imgOpe2.php');					// 画像アップロードクラスライブラリ

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

#-------------------------------------------------------------------------
# カテゴリー情報の取得
#-------------------------------------------------------------------------

	$sql = "
		SELECT
			CATEGORY_CODE,CATEGORY_NAME,CATEGORY_DETAILS,VIEW_ORDER
		FROM
			".N3_S7_CATEGORY_MST."
		WHERE
			(DEL_FLG = '0')
			AND
			(DISPLAY_FLG = '1')
		ORDER BY
			VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#------------------------------------------------------------------------
#	ページネーション情報の取得
#------------------------------------------------------------------------
	//ページネーション
	$sql_page = "
		SELECT
			RES_ID,
			PAGE_FLG
		FROM
			".N3_S7_WHATSNEW_PAGE."
	";

	// ＳＱＬを実行
	$fetch_page = dbOpe::fetch($sql_page,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$page = $fetch_page[0]['PAGE_FLG'];
	if($page == 0){
		$page = DBMAX_CNT;
	}

#=========================================================================

	// 新着情報取得
	if($_POST['act']){
		include("LGC_preview.php");//プレビュー表示
	}else{
		include("LGC_getDB-data.php");
	}

	include("DSP_contents.php");

?>