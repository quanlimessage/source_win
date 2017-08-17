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

$p = urldecode($p);

$search_word = urldecode($search_word);

$error_flg = false;

if(strlen($search_word) == 1){

		$search_word = "";
		$message    = "検索文字が半角1文字だけでは検索できません。";
		$error_flg = true;

	}elseif(strlen($search_word) == 0){

		// 検索文字がなかったらエラーメッセージを表示
		$search_word = "";
		$message    = "検索文字が入力されていません。";
		$error_flg = true;

}

#------------------------------------------------------------------------
# ページング用
# ページ遷移時にむだなパラメーターを付けない為
# (カテゴリーが送信してない場合に?ca=&p=)
# に送信パラメーターをチェックしてリンクパラメーターを設定する
#------------------------------------------------------------------------
$param="";
/*if(!empty($p) && !empty($search_word)){
	$param="?p=".urlencode($p)."&search_word=".urlencode($search_word);
}elseif(!empty($p) && empty($search_word)){
	$param="?p=".urlencode($p);
}elseif( empty($p) && !empty($search_word) ){
	$param="?search_word=".urlencode($search_word);
}*/

if(!$error_flg):

	// ページ番号の設定(GET受信データがなければ1をセット)
	if(empty($p) or !is_numeric($p))$p=1;

	#------------------------------------------------------------------------
	#	該当商品リスト用情報の取得
	#------------------------------------------------------------------------

	// 抽出開始位置の指定
	$st = ($p-1) * SHOP_MAXROW;

	// SQL組立て
	$sql = "
		SELECT
			PRODUCT_LST.PRODUCT_ID,
			PRODUCT_LST.PART_NO,
			PRODUCT_LST.PRODUCT_NAME,
			PRODUCT_LST.SELLING_PRICE,
			PRODUCT_LST.PRODUCT_DETAILS
		FROM
			PRODUCT_LST
		WHERE
		";

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//あいまい検索 （LIKE %検索ワード%　での処理の場合検索ワードとは一致しないデータも出てくる為、REGEXPで処理をさせる
	//		LIKE を使用したい場合はコメントアウトをはずして使用する）
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////
	//$sql .= "(PRODUCT_LST.PRODUCT_NAME LIKE '%".$search_word."%')";
	$sql .= "(PRODUCT_LST.PRODUCT_NAME REGEXP '".$search_word."')";

	//フラグのチェック
	$sql .= "
		AND
			(PRODUCT_LST.DISPLAY_FLG = '1')
		AND
			(PRODUCT_LST.DEL_FLG = '0')
	";

	$sql .= "
		AND
			(PRODUCT_LST.SALE_START_DATE <= NOW() || PRODUCT_LST.SALE_START_DATE = '0000-00-00 00:00:00')
		AND
			(PRODUCT_LST.SALE_END_DATE > NOW() || PRODUCT_LST.SALE_END_DATE = '0000-00-00 00:00:00')
		ORDER BY
			PRODUCT_LST.VIEW_ORDER ASC
	";

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sql .= "
		LIMIT
			".$st.",".SHOP_MAXROW."
	";

	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$message  = "検索キーワード : ".$search_word."&nbsp;&nbsp;";
	$message .= "該当する商品 : ".count($fetchCNT)."件<br>\n";

endif;

?>
