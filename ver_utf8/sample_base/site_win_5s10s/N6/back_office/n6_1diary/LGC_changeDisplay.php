<?php
/*******************************************************************************
更新プログラム

	Logic:使用と削除の切替(DISPLAY_FLGの切替)

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#================================================================================
# 使用/削除のデータ更新
#================================================================================

// POST受信値を確認した後に表示フラグ更新処理に入る
if( $status == "display_change" ):
		// 使用/削除のデータ調整
		$up_display = ($display_change == "t")?1:0;

		// 使用/削除の更新
		$up_sql = "
		UPDATE
			N6_1DIARY
		SET
			DISPLAY_FLG = '$up_display'
		WHERE
			(DIARY_ID = '$diary_id')
		";
		// ＳＱＬを実行（失敗時：エラーメッセージを格納）
		$upResult = dbOpe::regist($up_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($upResult)die("更新に失敗しました");

endif;
?>