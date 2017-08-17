<?php
/*******************************************************************************
会員メール配信　 バックオフィス（MySQL対応版）
メインコントローラー

*******************************************************************************/
// セッション管理スタート(検索指定情報管理)
session_start();

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_H1A.php");	// 共通設定情報
require_once("util_lib.php");						// 汎用処理クラスライブラリ

// POSTデータの受け取りと共通な文字列処理
if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

$status = ($_POST["status"])?$_POST["status"]:$_GET["status"];
$sending_check = ($_POST["sending_check"])?$_POST["sending_check"]:$_GET["sending_check"];
$err_check = ($_POST["err_check"])?$_POST["err_check"]:$_GET["err_check"];

//DBからデータを取得
include("LGC_getDB-data.php");

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch($status):
case "history_detail":

	include("DISP_history_detail.php");
	break;

default:

	include("DISP_listview.php");

endswitch;

// デバッグ用
/*
echo $sql;
echo $sql2;
echo nl2br(print_r($_SESSION , true));
*/
?>
