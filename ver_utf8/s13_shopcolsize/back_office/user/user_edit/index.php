<?php
/*******************************************************************************
アパレル対応
	ショッピングカートプログラム バックオフィス

ユーザーの情報の編集
	メインコントローラー

*******************************************************************************/
// 検索条件管理用にセッション管理開始
session_start();
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../../common/INI_config.php");		// 共通設定情報
require_once("../../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("../../../common/INI_pref_list.php");	// 都道府県＆送料データ（配列）
require_once("dbOpe.php");							// ＤＢ操作クラスライブラリ
require_once("util_lib.php");						// 汎用処理クラスライブラリ

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch($_POST["status"]):
case "completion":
////////////////////////////////
// DB更新処理+完了画面表示

	// 入力データのチェック(PHP)
	require_once("LGC_inputChk.php");

	// エラー発生時
	if(!empty($error_message)){

		// 更新画面の再表示
		include("LGC_getDB-data.php");
		include("DISP_update.php");

		break;
	}

	// DB情報の更新
	include("LGC_registDB.php");
	// 完了画面の出力
	include("DISP_completion.php");

	break;
default:

	#-------------------------------------------------------------------
	# POST受信した顧客IDを元に該当ユーザデータを取得
	# 編集用フォームに各データを初期表示
	#-------------------------------------------------------------------
	include("LGC_getDB-data.php");
	include("DISP_update.php");

endswitch;
// デバッグ用
//print_r($_POST);
?>
