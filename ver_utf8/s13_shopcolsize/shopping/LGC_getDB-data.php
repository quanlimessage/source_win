<?php
/*******************************************************************************

	LOGIC:DBよりデータの取得

*******************************************************************************/

// 不正アクセスチェック
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#------------------------------------------------------------------------
# GETパラメータがあった場合、商品データの取得
#------------------------------------------------------------------------

#=============================================================================
# 共通処理：GETデータの受け取りと共通な文字列処理
#=============================================================================
if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4,5)));

// 送信される可能性のあるパラメーターはデコードする
$p  = urldecode($p);
$ca = urldecode($ca);

#------------------------------------------------------------------------
# ページング用
# ページ遷移時にむだなパラメーターを付けない為
# (カテゴリーが送信してない場合に?ca=&p=)
# に送信パラメーターをチェックしてリンクパラメーターを設定する
#------------------------------------------------------------------------
$param="";
/*if(!empty($p) && !empty($ca)){
	$param="?p=".urlencode($p)."&ca=".urlencode($ca);
}elseif(!empty($p) && empty($ca)){
	$param="?p=".urlencode($p);
}elseif( empty($p) && !empty($ca) ){
	$param="?ca=".urlencode($ca);
}*/

// カテゴリーパラメーターが送信されたらカテゴリーごとの商品を表示
if(!empty($ca) && is_numeric($ca)){
	$ca_quety = " AND (CATEGORY_CODE = ".$ca.")";
}

// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;

if(empty($pid)):
#------------------------------------------------------------------------
#	該当商品リスト用情報の取得
#------------------------------------------------------------------------

	// 抽出開始位置の指定
	$st = ($p-1) * SHOP_MAXROW;

	// SQL組立て
	$sql = "
		SELECT
			".PRODUCT_LST.".PRODUCT_ID,
			".PRODUCT_LST.".PART_NO,
			".PRODUCT_LST.".PRODUCT_NAME,
			".PRODUCT_LST.".SELLING_PRICE,
			".PRODUCT_LST.".PRODUCT_DETAILS
		FROM
			".PRODUCT_LST."
		WHERE
			(".PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
			(".PRODUCT_LST.".DEL_FLG = '0')
		".$ca_quety."
	";

	$sql .= "
		AND
			(".PRODUCT_LST.".SALE_START_DATE <= NOW() || ".PRODUCT_LST.".SALE_START_DATE = '0000-00-00 00:00:00')
		AND
			(".PRODUCT_LST.".SALE_END_DATE > NOW() || ".PRODUCT_LST.".SALE_END_DATE = '0000-00-00 00:00:00')
		ORDER BY
			".PRODUCT_LST.".VIEW_ORDER ASC
	";

	$fetchCNT = $PDO -> fetch($sql);

	$sql .= "
		LIMIT
			".$st.",".SHOP_MAXROW."
	";

	$fetch = $PDO -> fetch($sql);

else:
#------------------------------------------------------------------------
#	詳細ページ用情報の取得
#------------------------------------------------------------------------
// 対象のGETデータ（商品ID）をチェック
if( isset($_GET['pid']) && preg_match("/^([0-9]{10,})-([0-9]{6})$/",$_GET['pid']) ){

	//////////////////////////////////
	// 商品情報取得

	$sql="
	SELECT
		".PRODUCT_LST.".PRODUCT_ID,
		".PRODUCT_LST.".PART_NO,
		".PRODUCT_LST.".PRODUCT_NAME,
		".PRODUCT_LST.".CAPACITY,
		".PRODUCT_LST.".CATEGORY_CODE,
		".CATEGORY_MST.".CATEGORY_NAME,
		".PRODUCT_LST.".ITEM_DETAILS,
		".PRODUCT_LST.".TITLE_TAG,
		".PRODUCT_LST.".SELLING_PRICE,
		".PRODUCT_LST.".DISPLAY_FLG,
		".PRODUCT_LST.".CART_CLOSE_FLG
	FROM
		".PRODUCT_LST."
		INNER JOIN
			".CATEGORY_MST."
		ON
			".PRODUCT_LST.".CATEGORY_CODE = ".CATEGORY_MST.".CATEGORY_CODE
	WHERE
		( ".PRODUCT_LST.".PRODUCT_ID = '".urldecode($_GET['pid'])."' )
	AND
		( ".PRODUCT_LST.".DISPLAY_FLG = '1' )
	AND
		( ".PRODUCT_LST.".DEL_FLG = '0' )
	AND
		( ".CATEGORY_MST.".DEL_FLG = '0' )
	AND
		(".PRODUCT_LST.".SALE_START_DATE <= NOW() || ".PRODUCT_LST.".SALE_START_DATE = '0000-00-00 00:00:00')
	AND
		(".PRODUCT_LST.".SALE_END_DATE > NOW() || ".PRODUCT_LST.".SALE_END_DATE = '0000-00-00 00:00:00')
	";
	$fetch = $PDO -> fetch($sql);

	if(!$fetch){ header("Location: ./"); }

	//詳細ページネーション用
	// SQL組立て
	$sql = "
		SELECT
			".PRODUCT_LST.".PRODUCT_ID
		FROM
			".PRODUCT_LST."
		WHERE
			(".PRODUCT_LST.".DISPLAY_FLG = '1')
		AND
			(".PRODUCT_LST.".DEL_FLG = '0')
		AND
			(".PRODUCT_LST.".CATEGORY_CODE = ".$fetch[0]['CATEGORY_CODE'].")
		ORDER BY
			".PRODUCT_LST.".VIEW_ORDER ASC
	";

	$fetchCNT = $PDO -> fetch($sql);

	//////////////////////////////////
	// 該当商品サイズ/カラー情報
		$sql_cs="
		SELECT
			".PRODUCT_PROPERTY_DATA.".DSC_ID,
			".PRODUCT_PROPERTY_DATA.".SIZE_CODE,
			".PRODUCT_PROPERTY_DATA.".STOCK_QUANTITY,
			".SIZE_MST.".SIZE_NAME,
			".COLOR_MST.".COLOR_NAME
		FROM
				".PRODUCT_PROPERTY_DATA."
			LEFT JOIN
				".SIZE_MST."
			ON
					(".PRODUCT_PROPERTY_DATA.".SIZE_CODE = ".SIZE_MST.".SIZE_CODE)
				AND
					(".SIZE_MST.".DEL_FLG = '0')
				AND
					(".SIZE_MST.".DISPLAY_FLG = '1')
			LEFT JOIN
				".COLOR_MST."
			ON
					(".SIZE_MST.".COLOR_CODE = ".COLOR_MST.".COLOR_CODE)
				AND
					(".COLOR_MST.".DEL_FLG = '0')
				AND
					(".COLOR_MST.".DISPLAY_FLG = '1')
		WHERE
			".PRODUCT_PROPERTY_DATA.".STOCK_QUANTITY > '0'
		AND
			".PRODUCT_PROPERTY_DATA.".PRODUCT_ID = '".$fetch[0]["PRODUCT_ID"]."'
		AND
			( ".PRODUCT_PROPERTY_DATA.".DEL_FLG = '0' )
		ORDER BY
			".PRODUCT_PROPERTY_DATA.".DSC_ID ASC
		";

		$fetchCS = $PDO -> fetch($sql_cs);

}else{

	// GETデータ内に該当商品IDが無ければエラー
	header("Location: ./");

}

endif;

?>
