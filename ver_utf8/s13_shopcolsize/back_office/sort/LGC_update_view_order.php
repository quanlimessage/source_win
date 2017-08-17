<?php
/*******************************************************************************
カテゴリ対応
	バックオフィス

商品の並び替え
Logic：新しい並び替え順で更新

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("HTTP/1.0 404 Not Found");exit();
}

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#===============================================================================
# １．並び順が格納されたhiddenデータをタブをデリミタにしてバラす（配列に格納）
# 	●対象のhiddenデータ：$new_view_order（PRODUCT_IDがタブ区切りになっている）
# 	●新しいVIEW_ORDERの番号：$voの要素番号に1を足したもの
#		●他のhiddenデータ：$change_brand_id（対象のカテゴリーコード）
#
# ２．並び替えを更新するＳＱＬを発行（バラした件数分設定する）
#===============================================================================
$vo = explode("\t", $new_view_order);

for ( $i = 0; $i < count($vo); $i++ ){

	$sql = "
	UPDATE
		".PRODUCT_LST."
	SET
		VIEW_ORDER = '".($i+1)."'
	WHERE
		( PRODUCT_ID = '".$vo[$i]."' )
	AND
		( DEL_FLG = '0' )
	";
	// ＳＱＬを実行
	$PDO -> regist($sql);
}

?>
