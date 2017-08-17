<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic:以下の処理を行う
	・表示/非表示の切替(DISPLAY_FLGの切替)
	・削除処理	※完全にデータを削除します。(DELETE文)

※$_POST["action"]の内容で分岐


*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}/*
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}*/
if(!$accessChk){
	header("Location: ../");exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4)));
	
// 対象記事IDデータのチェック
if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
	die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
}

#---------------------------------------------------------------
# $_POST["action"]の内容で処理を分岐
#---------------------------------------------------------------
switch($_POST["act"]):
case "del_data":
////////////////////////////////////////////////////////////////
// 該当データの完全削除



	// ブロック登録情報
	$sql = "SELECT * FROM ".CP1_VALUES_LST." WHERE (DEL_FLG = '0') AND (PAGE_ID = '$res_id') ORDER BY VIEW_ORDER ASC ";
	$fetchRgBlock = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	
	for($i = 0; $i < count($fetchRgBlock);$i++){
		$bid = $fetchRgBlock[$i]['RES_ID'];
		search_file_del(CP1_IMG_PATH,$bid."*");
		
	}
	
	// ブロック削除
	$sql_b = "DELETE FROM ".CP1_VALUES_LST." WHERE(PAGE_ID = '".$res_id."')";
	//$sql_b = "UPDATE ".$fetchRgBlock[$i]['_block_format']['FORMAT']['DB_TABLE_NAME']." SET DEL_FLG = 1 WHERE(RES_ID = '".$fetchRgBlock[$i]['RES_ID']."')";
	$db_result = dbOpe::regist($sql_b,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	
	// SQL実行
	$db_result = dbOpe::regist("DELETE FROM ".CP1_PAGE_LST." WHERE(RES_ID = '$res_id')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");
	
	// 記事画像の削除(対象はRES_IDが一致するファイル)
	search_file_del(CP1_IMG_PATH,$res_id."*");
	
	

	break;
case "display_change":
////////////////////////////////////////////////////////////////
// 表示/非表示の切替（フラグを更新）

	// 表示／非表示のデータ調整
	$up_display = ($display_change == "t")?1:0;
	
	// SQLを実行
	$db_result = dbOpe::regist("UPDATE ".CP1_PAGE_LST." SET DISPLAY_FLG = '$up_display' WHERE(RES_ID = '$res_id')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

endswitch;

?>
