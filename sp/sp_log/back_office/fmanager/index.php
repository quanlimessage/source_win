<?php
/*******************************************************************************
アクセス解析ファイルマネージャー

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
require_once("../../common/INI_logconfig.php");		// 設定情報
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once("dbOpe.php");					// 汎用処理クラスライブラリ

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch($_POST["action"]):
case "del_file":
/////////////////////////////////////////////////
//	対象ファイルの削除

	include("LGC_delfile.php");
	header("Location: {$_SERVER['PHP_SELF']}");

	break;

default:
/////////////////////////////////////////////////
// ファイル情報を取得し、一覧表示する

include("./DISP_listview.php");

endswitch;

?>
