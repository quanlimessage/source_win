<?php
/*******************************************************************************
更新プログラム

	BBS DATA ADMIN

*******************************************************************************/

session_start();

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/config_B1.php");	// 設定情報
require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once('imgOpe.php');						// 画像アップロードクラスライブラリ

if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));}

if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));}

#===============================================================================
# $_POST["action"]の内容により処理を分岐
#===============================================================================
if($status == "completion"){
//////////////////////////////////////////////////
//	登録完了画面出力

	include("LGC_inputChk.php");

	if(!$error_mes){

		include("LGC_registDB.php");
		// 指定された検索条件を元に一覧情報を取得して結果表示
		$reg_ed = 1;
		if($_SESSION["fetch"]){$_SESSION["fetch"] = array();}

		header("Location: ./");
		break;
	}

}

// 指定された検索条件を元に一覧情報を取得して結果表示
include("LGC_getDB-data.php");
include("DISP_listview.php");

?>