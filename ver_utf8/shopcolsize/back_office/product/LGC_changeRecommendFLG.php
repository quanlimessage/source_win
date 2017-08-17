<?php
/*******************************************************************************
カテゴリ対応
	ショッピングカートプログラム バックオフィス

お勧め商品の登録

Logic：おすすめフラグの変更

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
# おすすめ情報のデータ更新
#================================================================================

if($recommend_flg):

// 並び順：現在のview_orderの一番最後に１を足したものを設定
$vosql = "
	SELECT
		MAX(RECOMMEND_VO) AS VO
	FROM
		".PRODUCT_LST."
	WHERE
		(RECOMMEND_FLG = '1')
	AND
		(DEL_FLG = '0')
";

$fetchVO =  $PDO -> fetch($vosql);
$recommend_vo = ($fetchVO[0]["VO"] + 1);

else:
	$recommend_vo = "";
endif;

// 表示／非表示のデータ調整
//$up_display = ($display_change == "t")?1:0;

// 表示／非表示の更新
$up_sql = "
UPDATE
	".PRODUCT_LST."
SET
	RECOMMEND_FLG = '$recommend_flg',
	RECOMMEND_VO = '$recommend_vo'
WHERE
	(PRODUCT_ID = '$product_id')
AND
	(DEL_FLG = '0')
";
// ＳＱＬを実行（失敗時：エラーメッセージを格納）
$PDO -> regist($up_sql);

?>
