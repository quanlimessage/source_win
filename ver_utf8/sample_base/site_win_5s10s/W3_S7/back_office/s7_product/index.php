<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
メインコントローラー

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_S7.php");	// 共通設定情報
require_once("../tag_pg/LGC_color_table.php");	// タグ処理のプログラム
require_once("dbOpe.php");					// DB操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
//require_once('imgOpe.php');					// 画像アップロードクラスライブラリ
require_once('../../common/imgOpe2.php');	// 画像アップロードクラスライブラリ(gif・png対応)

	//現在の資料ファイル登録個数
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".S7_PRODUCT_LST." WHERE(DEL_FLG = '0')AND(PDF_FLG = '1')";
	$fetch_PDF_NUM = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch($_POST["act"]):
case "completion":

	// データ登録処理を行い一覧へ戻る
	include("LGC_regist.php");
	header("Location: ./?ca=".urlencode($ca)."&p=".urlencode($p));

	break;
case "update":case "copy":
//////////////////////////////////////////////////
//	更新画面出力

	include("LGC_getDB-data.php");
	include("DISP_update.php");

	break;
case "new_entry":
//////////////////////////////////////////////////
//	新規登録画面出力

	include("LGC_getDB-data.php");
	include("DISP_newInput.php");

	break;
case "display_change":case "del_data":
/////////////////////////////////////////////////
//	対象データの表示・非表示の切替 OR 削除

	include("LGC_del_and_dispchng.php");
	header("Location: ./?ca=".urlencode($ca)."&p=".urlencode($p));

default:
/////////////////////////////////////////////////
// DBより情報を取得し、一覧表示する

	include("LGC_getDB-data.php");
	include("DISP_listview.php");

endswitch;
?>