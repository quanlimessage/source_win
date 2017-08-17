<?php
/*******************************************************************************
アパレル対応

	受注情報：メインコントローラー

*******************************************************************************/
// 検索情報管理用にセッション管理開始
session_start();

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 共通設定情報
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("../../common/INI_pref_list.php");		// 都道府県＆送料データ（配列）

#===============================================================================
# $_POST["regist_type"]の内容により更新処理を分岐
#===============================================================================
////////////////////////////////
// 決済・配送処理
switch ($_POST["regist_type"]):
	case "change_payment_flg":
			// 決済フラグ変更処理
			include("LGC_change_payment_flg.php");
		break;
	case "change_shipped_flg":
			// 配送フラグ変更処理
			include("LGC_change_shipped_flg.php");
		break;
	case "add_config_memo":
			// 管理メモ更新処理
			include("LGC_config_memo.php");
		break;
endswitch;

#===============================================================================
# $_POST["status"]の内容により出力処理を分岐
#===============================================================================
if($_GET["status"])$_POST["status"] = $_GET["status"];

switch($_POST["status"]):
case "disp_details":
////////////////////////////////
// 注文情報詳細の表示

	// 注文情報詳細情報のデータ取得
	include("LGC_getDB-data.php");
	include("DISP_purchase_Details.php");

	break;
case "search_result":

	// 指定された検索条件を元に顧客情報一覧を取得して結果表示
	include("LGC_getDB-data.php");
	include("DISP_serch_result.php");

	break;
default:

	#-------------------------------------------------------------------
	# 初めてのアクセス。
	# 注文検索画面を表示（注文情報リストをＤＢ（PURCHASE_LST）より取得）
	#	※一応検索結果のセッションを破棄
	#-------------------------------------------------------------------
	$_SESSION["search_cond"] = array();
	include("DISP_serch_input.php");

endswitch;
?>
