<?php
/*******************************************************************************
SiteWin10 20 30（MySQL対応版）
N系写メールプログラム コントローラー

LGC：最大登録件数を超えた時に削除を行う

*******************************************************************************/

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#=================================================================================
# 最大登録件数を上回ったら古いデータから削除を行う
#=================================================================================

	// ＳＱＬ文
	$sql = "SELECT DIARY_ID FROM N6_1DIARY WHERE(DISPLAY_FLG = 1) ORDER BY REG_DATE ASC";
	// ＳＱＬを実行
	$old_fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	// DIARY_IDを変数に入れる
	$tar_res = $old_fetch[0]["DIARY_ID"];
	// データの削除
	$db_result = dbOpe::regist("DELETE FROM N6_1DIARY WHERE(DIARY_ID = '$tar_res')",DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB削除失敗しました<hr>{$db_result}");

	// 画像の削除
	if(file_exists("./up_img/".$tar_res.".jpg")){
		unlink("./up_img/".$tar_res.".jpg") or die("画像の削除に失敗しました。");
	}

	// 管理画面からの画像の削除
	if(file_exists(N6_1IMG_PATH.$tar_res.".jpg")){
		unlink(N6_1IMG_PATH.$tar_res.".jpg") or die("画像の削除に失敗しました。");
	}
?>