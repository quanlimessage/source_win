<?php
/*******************************************************************************
更新プログラム

	Logic:全体のコントローラー

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
require_once("../../common/config_N6_1.php");		// 設定情報
require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once('imgOpe.php');						// 画像アップロードクラスライブラリ
require_once("../tag_pg/LGC_color_table.php");	// タグ処理のプログラム
// 写メール情報を取得するファイルの読み込み
include("../../N6/LGC_getMail.php");

if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));}
if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));}

#===============================================================================
# $_POST["status"]の内容により処理を分岐

#===============================================================================
switch($_POST["status"]):
case "page_flg":
//////////////////////////////////////////////////
//	登録完了画面出力

	include("LGC_registDB.php");
	include("DISP_completion.php");

	break;
case "completion":
//////////////////////////////////////////////////
//	登録完了画面出力

	include("LGC_registDB.php");
	include("DISP_completion.php");

	break;
case "update":
//////////////////////////////////////////////////
//	更新画面出力

	include("LGC_getDB-data.php");
	include("DISP_update.php");

	break;
case "new_entry":
//////////////////////////////////////////////////
//	新規登録画面出力

	include("DISP_new_input.php");

	break;
case "display_change":
/////////////////////////////////////////////////
//	表示・非表示の切替

	// 表示／非表示フラグの更新
	include("LGC_changeDisplay.php");
	// 更新後は一覧表示するのでbreakをしない

case "del_data":
/////////////////////////////////////////////////
//	データの削除

	// データの完全削除
	include("LGC_delData.php");
	// 更新後は一覧表示するのでbreakをしない

default:

	// DBより情報を取得
	include("LGC_getDB-data.php");

	// 件数を超えていたら古いデータを削除する
	if(N6_1DBMAX_CNT < $fetchCNT[0]['CNT']){

	  include("../../N6_1/LGC_delDB-data.php");
	  header("Location: ./");

	}
	//一覧表示する
	include("DISP_listview.php");

endswitch;
// デバッグ用
//print_r($_POST);
?>