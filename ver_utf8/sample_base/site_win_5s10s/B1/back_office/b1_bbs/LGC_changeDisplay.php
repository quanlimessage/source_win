<?php
/*******************************************************************************
更新プログラム

	使用と削除の切替
	(DISPLAY_FLGの切替)

2005/05/06 Author K.C
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
if( $_POST["status"] == "display_change" ):

		if($master_id){

			// 表示/非表示のデータ調整
			$up_display = ($display_change == "t")?1:0;

			// GET SUB DATA
			$sql = "
			SELECT
				SUB_ID
			FROM
				".BBS_LOG_SUB_DATA."
			WHERE
				(MASTER_ID = '$master_id')
			";
			// ＳＱＬを実行
			$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			for($i=0;$i<count($fetch);$i++){

				if($fetch[$i]['SUB_ID']){

					// 表示/非表示の更新
					$up_sql2 = "
					UPDATE
						".BBS_LOG_SUB_DATA."
					SET
						DISPLAY_FLG = '$up_display'
					WHERE
						(SUB_ID = '".$fetch[$i]['SUB_ID']."')
					";
				// ＳＱＬを実行（失敗時：エラーメッセージを格納）
				$upResult2 = dbOpe::regist($up_sql2,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($upResult2)die("更新に失敗しました");

				}

			}

			// 表示/非表示の更新
			$up_sql = "
			UPDATE
				".BBS_LOG_MST_DATA."
			SET
				DISPLAY_FLG = '$up_display'
			WHERE
				(MASTER_ID = '$master_id')
			";
			// ＳＱＬを実行（失敗時：エラーメッセージを格納）
			$upResult = dbOpe::regist($up_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($upResult)die("更新に失敗しました");

		}elseif($sub_id){

			if($master_display_change == 1){

				// 表示/非表示のデータ調整
				$up_display = ($display_change == "t")?1:0;

			}else{

				$up_display = 0;

			}

				// 表示/非表示の更新
				$up_sql3 = "
				UPDATE
					".BBS_LOG_SUB_DATA."
				SET
					DISPLAY_FLG = '$up_display'
				WHERE
					(SUB_ID = '$sub_id')
				";
				// ＳＱＬを実行（失敗時：エラーメッセージを格納）
				$upResult3 = dbOpe::regist($up_sql3,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($upResult3)die("更新に失敗しました");

		}

endif;
?>