<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
W系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_W3.php');
require_once('util_lib.php');
require_once('dbOpe.php');

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

	// 商品情報取得
	include("LGC_getDB-data.php");

	include("DISP_List.php");

?>