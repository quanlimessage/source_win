<?php
/*******************************************************************************
SiteWiN20 20 30（MySQL版）N3_2
新着情報の内容をFlashに出力するプログラム

コントローラー

*******************************************************************************/

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config.php");		// 共通設定情報
require_once("../common/config_N3_2.php");		// 共通設定情報
require_once("../common/dbOpe.php");				// DB操作クラスライブラリ
require_once("../common/util_lib.php");			// 汎用処理クラスライブラリ
require_once('../common/imgOpe2.php');					// 画像アップロードクラスライブラリ

#------------------------------------------------------------------------
#	ページネーション情報の取得
#------------------------------------------------------------------------
//ページネーション
$sql_page = "
SELECT
	RES_ID,
	PAGE_FLG
FROM
	".N3_2WHATSNEW_PAGE."
	";

$fetch_page = dbOpe::fetch($sql_page,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
$page = $fetch_page[0]['PAGE_FLG'];
if($page == 0){
	$page = N3_2DBMAX_CNT;
}

// 実行プログラム読み込み
if($_POST['act']){
	include("LGC_preview.php");//プレビュー表示
}else{
	include("LGC_getDB-list.php");
}

include("DSP_contents.php");	// 取得した情報を表示

?>