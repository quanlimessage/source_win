<?php
/*******************************************************************************
カテゴリ対応
	バックオフィス

商品の並び替え
Logic：ＤＢより情報を取得

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
$category_id = mb_convert_kana( $category_id, "n");

#---------------------------------------------------------------
# 指定されたカテゴリID($category_id)に所属する商品リストを取得
#---------------------------------------------------------------
$sql = "
SELECT
	".PRODUCT_LST.".PRODUCT_ID,
	".PRODUCT_LST.".CATEGORY_CODE,
	".CATEGORY_MST.".CATEGORY_NAME,
	".PRODUCT_LST.".PART_NO,
	".PRODUCT_LST.".PRODUCT_NAME,
	".PRODUCT_LST.".VIEW_ORDER,
	".PRODUCT_LST.".DISPLAY_FLG
FROM
	".PRODUCT_LST."
INNER JOIN
	".CATEGORY_MST."
ON
	".PRODUCT_LST.".CATEGORY_CODE = ".CATEGORY_MST.".CATEGORY_CODE
WHERE
	( ".PRODUCT_LST.".DEL_FLG = '0' )
AND
	( ".PRODUCT_LST.".CATEGORY_CODE = '".$category_id."')
ORDER BY
	".PRODUCT_LST.".VIEW_ORDER
";

$fetch = $PDO -> fetch($sql);
?>
