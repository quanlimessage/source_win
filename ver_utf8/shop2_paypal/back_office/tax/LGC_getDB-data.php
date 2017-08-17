<?php
/*******************************************************************************
管理者情報
Logic：ＤＢより情報を取得

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

//////////////////////////////////////////////////////////////////
// DBより全管理者情報を取得する

	$sql = "
	SELECT
		NAME,
		EMAIL,
		COMPANY_INFO,
		BANK_INFO,
		SHOPPING_TITLE,
		BO_TITLE
	FROM
		".CONFIG_MST."
	WHERE
		(CONFIG_ID = '1')
	";
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//////////////////////////////////////////////////////////////////
// まだDBに管理者情報が未登録な場合、仮情報を登録

if(empty($fetch)):
	// 仮管理者情報をDB登録
	$ins_sql="
	INSERT INTO ".CONFIG_MST."(
		NAME,
		EMAIL,
		EMAIL2
	)
	VALUES(
		'クライアント会社名',
		'".WEBMST_SHOP_MAIL."',
		'".WEBMST_CONTACT_MAIL."'
	)";

	if(!empty($ins_sql)){
		$db_result = dbOpe::regist($ins_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
		if($db_result)die("ディフォルト情報セットに失敗しました。<hr>{$db_result}");
	}
endif;
?>
