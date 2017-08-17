<?php
/*******************************************************************************
アパレル対応

	管理メモ更新処理

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

#=================================================================================
# POST受信処理
#=================================================================================
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

// 文字列置換
$config_memo	= mb_convert_kana($config_memo,"KV");		// メモ内容

// 更新処理指示受注IDチェック
if(!ereg("^([0-9]{10,})-([0-9]{6})$",$target_order_id))exit("不正なパラメータが送信されました。処理を中止します。");

#=================================================================================
# SQL組立
#=================================================================================

$config_memo_sql = "
UPDATE
	".PURCHASE_LST."
SET
	CONFIG_MEMO = '$config_memo',
	UPD_DATE = NOW()
WHERE
	(ORDER_ID = '$target_order_id')
";

#=================================================================================
# SQL実行
#=================================================================================
if(!empty($config_memo_sql)){
	$db_result = dbOpe::regist($config_memo_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB更新失敗しました<hr>{$db_result}");
}
?>
