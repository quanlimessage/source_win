<?php
/*******************************************************************************
SiteWin10 20 30（MySQL対応版）
N系写メールプログラム コントローラー

*******************************************************************************/

// 不正アクセスチェックフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config_N6_1.php");	// 設定情報
require_once("dbOpe.php");					// ＤＢ操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once('imgOpe.php');					// 画像アップロードクラスライブラリ

// 写メール情報を取得するファイルの読み込み
include("LGC_getMail.php");

//if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));}
if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));}

#------------------------------------------------------------------------
#	ページネーション情報の取得
#------------------------------------------------------------------------
//ページネーション
$sql_page = "
SELECT
	RES_ID,
	PAGE_FLG
FROM
	".N6_1DIARY_PAGE."
	";

$fetch_page = dbOpe::fetch($sql_page,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
$page_flg = $fetch_page[0]['PAGE_FLG'];
if($page_flg == 0){
	$page_flg = N6_1DBMAX_CNT;
}

// 件数を超えていたら古いデータを削除する
if($_POST['status']){
	include("LGC_preview.php");//プレビュー表示
}else{

	// 情報を取得
	include("LGC_getDB-data.php");

	if(N6_1DBMAX_CNT < $fetchCNT[0]['CNT']){
		include("LGC_delDB-data.php");
		header("Location: ./");

	}
}

//一覧表示する
include("DISP_listview.php");

// デバッグ用
//print_r($_POST);
?>