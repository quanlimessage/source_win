<?php
/*******************************************************************************
管理者情報メインコントローラー

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

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/INI_config.php");		// 共通設定情報
require_once("../../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("dbOpe.php");							// ＤＢ操作クラスライブラリ
require_once("util_lib.php");						// 汎用処理クラスライブラリ

#===============================================================================
# $_POST['action']の内容により処理を分岐
#===============================================================================
switch($_POST['action']):
case "completion":
//////////////////////////////////////////////////////////////////////
// 商品登録情報（新規／修正）を画像処理＆ＤＢ登録し、完了画面を表示

	include("LGC_regist_data.php");
	include("DISP_completion.php");

	break;
case "confirm":
//////////////////////////////////////////////////////////////////////
// 更新内容の確認

	#入力内容のチェック
	include("LGC_inputChk.php");
	if(!empty($error_mes)){
		// エラー発生時
		include("DISP_update.php");
		break;
	}else{
		// エラーが無ければ確認画面表示
		include("DISP_confirm.php");
	}
	break;

case "edit":
//////////////////////////////////////////////////////////////////////
// 修正
	include("LGC_inputChk.php");
	include("DISP_update.php");
	break;

default:
//////////////////////////////////////////////////////////////////////
// 登録済み管理者情報を初期セットしたフォーム表示
	$tax = TAX;
	$tax_float = TAX_FLOAT;
	include("DISP_update.php");

endswitch;

?>
