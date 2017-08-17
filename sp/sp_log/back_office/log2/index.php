<?php
/*******************************************************************************
アクセス解析

	メインコントローラー

	SQLite対応版

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../sp/common/INI_logconfig.php");		// 設定情報
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once("dbOpe.php");					// SQLite操作クラスライブラリ

#-------------------------------------------------------------------
# デフォルト表示画面
# 結果一覧表示
#-------------------------------------------------------------------

include("./LGC_getDBlog.php");

include("./DISP_data.php");

// echo nl2br(print_r($fetch_state_u , true));

?>
