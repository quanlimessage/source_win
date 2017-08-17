<?php
/*******************************************************************************
アパレル対応(カテゴリ)

	配送情報の更新処理

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

///////////////////////////////
// 配送情報更新
if(!$_POST["shipped_flg"]){
	$up_sql .= "
	UPDATE
		".PURCHASE_LST."
	SET
		SHIPPED_FLG = '1',
		SHIPPED_DAY = NOW()
	";
}else{
	$up_sql = "
	UPDATE
		".PURCHASE_LST."
	SET
		SHIPPED_FLG = '0',
		SHIPPED_DAY = '0000-00-00 00:00:00'
	";
}

// WHERE句以下組立て
$up_sql .= "
WHERE
	(".PURCHASE_LST.".ORDER_ID = '".$_POST["target_order_id"]."')
AND
	(DEL_FLG = '0')
";

// SQL実行
$db_result = dbOpe::regist($up_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
if($db_result)die("更新に失敗しました<hr>{$db_result}");
?>
