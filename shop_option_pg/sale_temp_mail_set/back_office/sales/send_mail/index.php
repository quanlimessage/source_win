<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
メインコントローラー

*******************************************************************************/
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../../common/INI_config.php");		// 共通設定情報
require_once("../../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("../../../common/INI_pref_list.php");		// 都道府県＆送料データ（配列）

require_once("dbOpe.php");					// DB操作クラスライブラリ
require_once("util_lib.php");				// 汎用処理クラスライブラリ
require_once('imgOpe.php');					// 画像アップロードクラスライブラリ

	// POSTデータの受け取りと共通な文字列処理（メール送信用）
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch($_POST["action"]):
case "completion":
//	送信完了画面出力

	include("LGC_regist.php");//送信内容を登録
	include("LGC_sendmail.php");//メールを送信する
	include("DISP_comp.php");

	break;
case "confirm":
//////////////////////////////////////////////////
//	確認画面出力

	include("DISP_confirm.php");

	break;

case "edit":
//////////////////////////////////////////////////
//	修正画面出力

	include("DISP_input.php");

	break;

default:
/////////////////////////////////////////////////
// DBより情報を取得し、入力画面を表示する

	include("LGC_getDB-data.php");
	include("DISP_input.php");

endswitch;
?>