<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_S7.php');
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('tmpl2.class.php');// テンプレートクラスライブラリ
require_once('../common/imgOpe2.php');					// 画像アップロードクラスライブラリ

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

	// 商品情報取得
	if($_POST['act']){
		include("LGC_preview.php");//プレビュー表示
	}else{
		include("LGC_getDB-data.php");
	}
	// 商品IDが送信されパラメーターが不正でなければ商品詳細を表示
	// $_POST['act']の値に"prev_d"を受け取った場合は詳細プレビューを表示
	if( ( isset($_GET['id']) && preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_GET['id']) ) || $_POST['act']=="prev_d" ){

		include("DISP_detail.php");

	}else{
	// 取得件数分のデータをHTML出力
		include("DISP_List.php");
	}

?>
