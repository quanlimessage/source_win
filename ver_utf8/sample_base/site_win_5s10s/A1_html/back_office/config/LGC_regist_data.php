<?php
/*******************************************************************************
管理情報
Logic：入力情報をチェックし、ＤＢへ登録

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("HTTP/1.0 404 Not Found"); exit();
}

#========================================================================
# POSTデータの受け取りと共通な文字列処理
#========================================================================
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#=================================================================================
# 管理情報の更新
#=================================================================================
/////////////////////////////////////////////////////////////////////////////////
// 更新

	$sql = "
	UPDATE
		".APP_INIT_DATA."
	SET
		EMAIL1 = '$email1',
		EMAIL2 = '$email2',
		CONTENT = '$content'
	WHERE
		( RES_ID = '1' )
	";

// ＳＱＬを実行
if( !empty($sql) ){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("データの更新に失敗しました<hr>{$db_result}");

}

?>