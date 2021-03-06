<?php
/*******************************************************************************
アクセス解析ファイルマネージャー
Logic:以下の処理を行う
	・削除処理	※完全にログファイルを削除します。

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found"); exit();
}

#----------------------------------------------------------------
# POSTデータの受取と共通な文字列処理（対象IDが不正：強制終了）
#----------------------------------------------------------------
extract(utilLib::getRequestParams("post",array(8,7,1,4)));

// ファイル名のチェック
if(!strstr($filename,"access_log_db")||empty($filename)){
	die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
}

////////////////////////////////////////////////////////////////
// 該当ファイルの完全削除

	// ログファイルの削除
	if(file_exists(ACCESS_PATH.$filename)){
		unlink(ACCESS_PATH.$filename) or die("ファイルの削除に失敗しました。");
	}

?>
