<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
Logic:以下の処理を行う
	・表示/非表示の切替(DISPLAY_FLGの切替)
	・削除処理	※完全にデータを削除します。(DELETE文)

※$_POST["action"]の内容で分岐

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
if(!$accessChk){
	header("Location: ../");exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
extract(utilLib::getRequestParams("post",array(8,7,1,4)));

// 対象記事IDデータのチェック
if(!ereg("^([0-9]{10,})-([0-9]{6})$",$res_id)||empty($res_id)){
	die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
}

#---------------------------------------------------------------
# $_POST["action"]の内容で処理を分岐
#---------------------------------------------------------------
switch($_POST["action"]):
case "del_data":
////////////////////////////////////////////////////////////////
// 該当データの完全削除

	// SQL実行
	$db_result = dbOpe::regist("DELETE FROM N4_2TICKER WHERE(RES_ID = '$res_id')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

	// 記事画像の削除
	if(file_exists(N4_2IMG_PATH.$res_id.".jpg")){
		unlink(N4_2IMG_PATH.$res_id.".jpg") or die("画像の削除に失敗しました。");
	}

	break;
case "display_change":
////////////////////////////////////////////////////////////////
// 表示/非表示の切替（フラグを更新）

	// 表示／非表示のデータ調整
	$up_display = ($display_change == "t")?1:0;

	// SQLを実行
	$db_result = dbOpe::regist("UPDATE N4_2TICKER SET DISPLAY_FLG = '$up_display' WHERE(RES_ID = '$res_id')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

endswitch;

?>