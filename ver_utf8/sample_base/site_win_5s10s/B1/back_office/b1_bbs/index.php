<?php
/*******************************************************************************
更新プログラム

	BBS DATA ADMIN

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../../common/config_B1.php");	// 設定情報
require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once('imgOpe.php');						// 画像アップロードクラスライブラリ

if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));}

if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));}

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
switch($_POST["status"]):
case "completion":
//////////////////////////////////////////////////
//	登録完了画面出力

	include("LGC_registDB.php");
	include("DISP_completion.php");

	break;
case "new_entry":
//////////////////////////////////////////////////
//	新規登録画面出力

	include("LGC_getDB-data.php");
	include("DISP_new_input.php");

	break;
case "display_change":
/////////////////////////////////////////////////
//	表示・非表示の切替

	// 表示・非表示フラグの更新
	include("LGC_changeDisplay.php");
	include("LGC_getDB-data.php");
	include("DISP_listview.php");

	break;
case "del_data":
/////////////////////////////////////////////////
//	削除

	// 削除フラグの更新
	include("LGC_delData.php");
	include("LGC_getDB-data.php");
	include("DISP_listview.php");

	break;
default:
	///////////////////////////////////////
	//	検索結果表示

	// 指定された検索条件を元に一覧情報を取得して結果表示
	include("LGC_getDB-data.php");
	include("DISP_listview.php");

endswitch;
// デバッグ用
//print_r($_POST);
?>
