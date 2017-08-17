<?php
/*******************************************************************************
カテゴリ対応

商品の登録・更新：メインコントローラー

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
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");	// 共通設定情報
require_once("../../common/INI_ShopConfig.php");// ショップ用設定情報
require_once('../tag_pg/LGC_color_table.php');//ＨＴＭＬタグのカラーパレット
require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ
//require_once('imgOpe.php');					// 画像アップロードクラスライブラリ
require_once('../../common/imgOpe2.php');	// 画像アップロードクラスライブラリ(gif・png対応)

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch($_POST["status"]):
case "product_entry_completion":
///////////////////////////////////////
//	DB登録+完了画面表示
//				※エラー発生時は入力画面再表示

	// 入力データのチェック(PHP)
	require_once("LGC_inputChk.php");

	// エラー発生時
	if(!empty($error_message)){

		# 入力画面再表示用にDBデータの取得
		include("LGC_getDB-data.php");

			# 入力画面表示
			if($_POST["regist_type"] == "new"):
				# 新規登録時のエラーなら新規登録画面表示
				include("DISP_product_input.php");
			elseif($_POST["regist_type"] == "update"):
				# 更新登録時のエラーなら更新登録画面表示
				include("DISP_product_update.php");
			endif;

		break;
	}

	// 商品登録情報（新規／修正）を画像処理＆ＤＢ登録し、完了画面を表示
	include("LGC_regist_productEntry.php");
	include("DISP_completion.php");

	break;
case "product_edit":case "copy":
///////////////////////////////////////
//	更新画面
//
		#----------------------------------------------------------------------------
		# 「編集」をクリックしたら対象のPRODUCT_IDを条件にＤＢより取得し、
		# 商品修正画面を開く（edit_pidはPRODUCT_ID）
		#----------------------------------------------------------------------------
		include("LGC_getDB-data.php");
		include("DISP_product_update.php");

	break;
case "product_entry":
///////////////////////////////////////
//	新規登録
//
	// 新規商品登録の入力画面（実際にはメインカテゴリーのみを選択させる仕掛け）
	include("LGC_getDB-data.php");
	include("DISP_product_input.php");

	break;
case "recommend":
///////////////////////////////////////
//	おすすめ商品登録
//
		#-------------------------------------------------------------------------------
		# 該当製品データからおすすめ商品の登録をおこなう
		#-------------------------------------------------------------------------------
		include("LGC_changeRecommendFLG.php");

	// 指定された検索条件を元に商品一覧情報を取得して結果表示
	include("LGC_getDB-data.php");
	include("DISP_serch_result.php");

	break;
case "delflg_change":
///////////////////////////////////////
//	削除
//
		#-------------------------------------------------------------------------------
		# 該当製品データのDEL_FLG上書き後、製品リスト再読込み
		#-------------------------------------------------------------------------------
		include("LGC_changeDEL_FLG.php");
		include("LGC_getDB-data.php");
		include("DISP_serch_result.php");

	break;
case "display_change":
///////////////////////////////////////
//	表示/非表示
//
		#-------------------------------------------------------------------------------
		# 表示／非表示フラグがあれば変更／結果再取得プログラムを実行して結果表示
		# ※一度検索結果を表示した場合に発生（ステータス“search_result”を一度経験済み）
		#-------------------------------------------------------------------------------
		include("LGC_changeDisplayFLG.php");
		include("LGC_getDB-data.php");
		include("DISP_serch_result.php");

	break;
case "search_result":
///////////////////////////////////////
//	検索結果表示
//

	// 指定された検索条件を元に商品一覧情報を取得して結果表示
	include("LGC_getDB-data.php");
	include("DISP_serch_result.php");

	break;
default:

		#-------------------------------------------------------------------
		# 初めてのアクセス。
		# 登録済み商品検索画面を表示（メインカテゴリー情報をＤＢより取得）
		#	※一応検索結果のセッションを破棄
		#-----------------------------------------------------------------
		$_SESSION["search_cond"] = array();
		include("LGC_getDB-data.php");
		include("DISP_serch_input.php");

endswitch;

// デバッグ用
/*
print_r($_POST);
echo "<hr>";
print_r($_SESSION);
*/
?>
