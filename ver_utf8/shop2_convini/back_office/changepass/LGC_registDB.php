<?php
/*******************************************************************************
管理者ID/PASSの管理

	DB更新処理

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

/////////////////////////////////////////////////////////////////////////////////
// 更新

if(empty($error_message) && $_POST["status"] == "completion"):
	#-----------------------------------------------------
	# SQL組立て
	#-----------------------------------------------------

	$sql = "
	UPDATE
		".CONFIG_MST."
	SET
		BO_ID = '".utilLib::strRep($new_id,5)."',
		BO_PW = '".utilLib::strRep($new_pw,5)."',
		UPD_DATE = NOW()
	WHERE
		(CONFIG_ID = '1')
	";

endif;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB更新に失敗しました<hr>{$db_result}");
}

?>
