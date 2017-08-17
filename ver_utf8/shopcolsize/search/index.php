<?php
/*******************************************************************************

	商品閲覧(ブランド別)：メインコントローラー
		※主に商品のカテゴリー別の表示制御を行う

*******************************************************************************/

// 不正アクセスチェックのフラグ
$injustice_access_chk = 1;

// 設定ファイル＆共通ライブラリの読み込み
require_once("../common/INI_config.php");		// 共通設定情報
require_once("../common/INI_ShopConfig.php");	// ショップ用設定情報
require_once("dbOpe.php");						// ＤＢ操作クラスライブラリ
require_once("util_lib.php");					// 汎用処理クラスライブラリ
require_once('tmpl2.class.php');				// テンプレートクラスライブラリ

#=================================================================================
# GET受信パラメーターを元に表示させる情報をＤＢより取得し表示
#
#	$_GET["cn"]の中身の内訳 = CATEGORY_CODE(CATEGORY_MST)
#
#=================================================================================

	// トップページを表示
	include("LGC_getDB-data.php");
	include("DISP_List.php");

?>
