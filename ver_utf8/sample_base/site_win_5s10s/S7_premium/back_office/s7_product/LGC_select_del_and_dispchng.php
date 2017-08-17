<?php
/*******************************************************************************
	・一括表示/非表示の切替(DISPLAY_FLGの切替)
	・一括削除処理	※完全にデータを削除します。(DELETE文)

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4)));

#---------------------------------------------------------------
# $_POST["action"]の内容で処理を分岐
#---------------------------------------------------------------
switch($_POST["act"]):
case "select_del_data":
////////////////////////////////////////////////////////////////
// 一括データの完全削除

	$select_stock = explode(",", $del_id_stock);//IDを配列ごとに分ける

	for($i=0;$i < count($select_stock);$i++){

		if($select_stock[$i]){//データが存在すれば処理を行う

		// 対象記事IDデータのチェック
			if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$select_stock[$i])||empty($select_stock[$i])){
				die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$select_stock[$i]}");
			}

			//削除対象はRES_IDが一致するファイル
				search_file_del(IMG_PATH,$select_stock[$i]."*");

			//データベースから削除
				//登録データの削除
				$db_result = dbOpe::regist("DELETE FROM ".S7_PRODUCT_LST." WHERE(RES_ID = '".$select_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

		}

	}
	break;

case "select_don_data":
////////////////////////////////////////////////////////////////
// 一括表示の切替（フラグを更新）

	$select_stock = explode(",", $disp_on_id_stock);//IDを配列ごとに分ける

	for($i=0;$i < count($select_stock);$i++){

		if($select_stock[$i]){//データが存在すれば処理を行う

		// 対象記事IDデータのチェック
			if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$select_stock[$i])||empty($select_stock[$i])){
				die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$select_stock[$i]}");
			}

			//表示するに切り替える
				$db_result = dbOpe::regist("UPDATE ".S7_PRODUCT_LST." SET DISPLAY_FLG = '1' WHERE(RES_ID = '".$select_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

		}

	}

	break;

case "select_doff_data":
////////////////////////////////////////////////////////////////
// 一括非表示の切替（フラグを更新）

	$select_stock = explode(",", $disp_off_id_stock);//IDを配列ごとに分ける

	for($i=0;$i < count($select_stock);$i++){

		if($select_stock[$i]){//データが存在すれば処理を行う

		// 対象記事IDデータのチェック
			if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$select_stock[$i])||empty($select_stock[$i])){
				die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$select_stock[$i]}");
			}

			//非表示するに切り替える
				$db_result = dbOpe::regist("UPDATE ".S7_PRODUCT_LST." SET DISPLAY_FLG = '0' WHERE(RES_ID = '".$select_stock[$i]."')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");

		}

	}

	break;

endswitch;

?>
