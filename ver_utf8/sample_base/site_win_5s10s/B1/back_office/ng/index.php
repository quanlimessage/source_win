<?php
/*******************************************************************************
更新プログラム

	NG KEY WORD

2005/05/06 Author K.C
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

if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));}

// UPDATE KEY WORDS
if($_POST["regist_type"] == "update"){

	include("LGC_inputChk.php");

	if($error_mes){

		$e_msg = $error_mes;
		// DBより情報を取得し、一覧表示する
		include("LGC_getDB-data.php");
		include("DISP_update.php");

	}else{

		$e_msg = "<br>更新しました。";
		include("LGC_registDB.php");
		// DBより情報を取得し、一覧表示する
		include("LGC_getDB-data.php");
		include("DISP_update.php");
	}

}else{

	// DBより情報を取得し、一覧表示する
	include("LGC_getDB-data.php");
	include("DISP_update.php");

}

// デバッグ用
//print_r($_POST);
?>