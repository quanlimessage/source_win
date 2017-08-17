<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_S2.php');
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

	include("DISP_List.php");

?>