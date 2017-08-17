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
require_once("util_lib.php");			// 汎用処理クラスライブラリ
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

$fetch_page = $PDO -> fetch($sql_page);
$page = $fetch_page[0]['PAGE_FLG'];
if($page == 0){
	$page = N3_2DBMAX_CNT;
}

	// 商品情報取得
	if($_POST['act']){
		include("LGC_preview.php");//プレビュー表示
	}else{
		include("LGC_getDB-data.php");
	}

	// 商品IDが送信されパラメーターが不正でなければ商品詳細を表示
	if( ( isset($_GET['id']) && preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_GET['id']) ) || $_POST['act']=="prev_d" ){

		include("DISP_detail.php");

	}else{

		include("DISP_List.php");

	}

?>
