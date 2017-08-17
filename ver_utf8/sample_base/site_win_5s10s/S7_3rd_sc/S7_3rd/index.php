<?php
/***********************************************************
SiteWin10 20 30（MySQL対応版）
S系表示用プログラム コントローラー

***********************************************************/

// 共通ライブラリ読み込み
require_once('../common/config_S7_3rd.php');
require_once('util_lib.php');
require_once('dbOpe.php');
require_once('../common/imgOpe2.php');					// 画像アップロードクラスライブラリ

	// 不正アクセスチェックのフラグ
	$injustice_access_chk = 1;

	#-------------------------------------------------------------------------
	# カテゴリー情報の取得
	#-------------------------------------------------------------------------

	$sql = "
	SELECT
		*
	FROM
		".S7_3_CATEGORY_MST."
	WHERE
		(DEL_FLG = '0')
		AND
		(DISPLAY_FLG = '1')
	ORDER BY
		VIEW_ORDER ASC
	";

	// ＳＱＬを実行
	$fetchCA = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//簡単にカテゴリー名を取得できるように連想配列名を設定した配列を作る
			$fetchCA2 = array();

			for($i=0;$i < count($fetchCA);$i++){

				//カテゴリーの番号で設定
				$fetchCA2['id'][$fetchCA[$i]['CATEGORY_CODE']] = $fetchCA[$i]['RES_ID'];
				$fetchCA2['name'][$fetchCA[$i]['CATEGORY_CODE']] = $fetchCA[$i]['CATEGORY_NAME'];

				//RES_IDで設定
				$fetchCA2['id'][$fetchCA[$i]['RES_ID']] = $fetchCA[$i]['CATEGORY_CODE'];
				$fetchCA2['name'][$fetchCA[$i]['RES_ID']] = $fetchCA[$i]['CATEGORY_NAME'];

			}

	// 商品情報取得
	if($_POST['act']){
		include("LGC_preview.php");//プレビュー表示
	}else{
		include("LGC_getDB-data.php");
	}

	// 商品IDが送信されパラメーターが不正でなければ商品詳細を表示
	// $_POST['act']の値に"prev_d"を受け取った場合は詳細プレビューを表示
	if( ( isset($_GET['id']) && preg_match("/^([0-9]{10,})-([0-9]{6})$/", $_GET['id']) ) || $_POST['act']=="prev_d" ){

		include("DISP_detail.php");

	}else{
	// 取得件数分のデータをHTML出力
		include("DISP_List.php");
	}

?>
