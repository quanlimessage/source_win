<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_S6_1.php');
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('tmpl2.class.php');// テンプレートクラスライブラリ

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

	// 商品情報取得
	include("LGC_getDB-data.php");

	// 商品IDが送信されパラメーターが不正でなければ商品詳細を表示
	if( !isset($_GET['id']) || !ereg("^([0-9]{10,})-([0-9]{6})$", $_GET['id']) ){

		include("DISP_List.php");

	}else{
	//詳細画面の処理
		if(isset($res_id)&&($pid)){

			// 取得件数分のデータをHTML出力
			//選択した画像をポップアップ表示
			include("DISP_pop.php");

		}else{
			// 取得件数分のデータをHTML出力
			include("DISP_detail.php");
		}
	}

?>