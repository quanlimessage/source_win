<?php
/*******************************************************************************
カテゴリ対応
	ショッピングカートプログラム バックオフィス

商品の更新
Logic：表示／非表示フラグの変更
	※検索結果画面の“表示／非表示”リンクをクリックした場合に実行

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

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#================================================================================
# 表示／非表示のデータ更新
#================================================================================

// 表示／非表示のデータ調整
$up_display = ($display_change == "t")?1:0;

// 表示／非表示の更新
$up_sql = "
UPDATE
	".PRODUCT_LST."
SET
	DISPLAY_FLG = '$up_display'
WHERE
	(PRODUCT_ID = '$pid')
AND
	(DEL_FLG = '0')
";
// ＳＱＬを実行（失敗時：エラーメッセージを格納）
$upResult = dbOpe::regist($up_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
if($upResult)die("更新に失敗しました");

?>
