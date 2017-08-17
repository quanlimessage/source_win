<?php
/*******************************************************************************
アパレル対応ショッピングカート
	ショッピングカートプログラム バックオフィス

商品の更新
Logic：削除フラグの変更
	※検索結果画面の「削除」リンクをクリックした場合に実行

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
# DEL_FLGのデータ更新
#================================================================================

// 削除対象商品IDデータのチェック
if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$del_pid)){die("致命的エラー：不正な処理データが送信されましたので強制終了します！");}

// 表示／非表示の更新
$del_sql = "
UPDATE
	".PRODUCT_LST."
SET
	DEL_FLG = '1'
WHERE
	(PRODUCT_ID = '$del_pid')
AND
	(DEL_FLG = '0')
";
// ＳＱＬを実行（失敗時：エラーメッセージを格納）
$PDO -> regist($del_sql);

#================================================================================
# 該当商品の商品画像削除
#================================================================================
// 対象はRES_IDが一致するファイル全て
	search_file_del(PRODUCT_IMG_FILEPATH,$del_pid."*");

?>
