<?php
/*******************************************************************************
ＣＳＶ商品登録
*******************************************************************************/

session_start();

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

// 不正アクセスチェックのフラグ
$accessChk = 1;

// 設定ファイル＆共通ライブラリの読み込み
//require_once("../../common/config.php");	// 共通設定情報
require_once("../../common/config_D1.php");	// 共通設定情報

require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4),true));}

#===============================================================================
# $_POST["status"]の内容により処理を分岐
#===============================================================================
switch($_POST["status"]):

case "product_entry":
///////////////////////////////////////
//	CSVで新規登録
//
	// 新規商品登録の入力画面（実際にはメインカテゴリーのみを選択させる仕掛け）
	include("LGC_reg_data.php");

default:
	//最初の入力画面へ
		include("DISP_input.php");

endswitch;

?>