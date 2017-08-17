<?php
/*******************************************************************************
アパレル対応ショッピングカート
	ショッピングカートプログラム バックオフィス

商品の更新
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
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#===============================================================================
# POSTデータの受け取りと共通な文字列処理
#===============================================================================
@extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#===============================================================================
# カテゴリー情報を取得（選択タグで使用）
#		※初期表示画面・新規登録画面・更新登録画面
#===============================================================================
$sql = "
SELECT
	CATEGORY_CODE,
	CATEGORY_NAME
FROM
	".CATEGORY_MST."
WHERE
	(DEL_FLG='0')
ORDER BY
	CATEGORY_CODE ASC";
$fetchCateList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//カテゴリー名の横に登録件数を表示させる

for($i=0;$i<count($fetchCateList);$i++){
	${'ca_cnt'.$i} = $fetchCateList[$i]['CATEGORY_CODE'];

	${'sql_ca'.$i} = "
	SELECT
		PRODUCT_ID,
		CATEGORY_CODE
	FROM
		".PRODUCT_LST."
	WHERE
		(CATEGORY_CODE = '${'ca_cnt'.$i}')
	AND
		(DEL_FLG = '0')
	";

	// ＳＱＬを実行
	${'fetchCA_ca'.$i} = dbOpe::fetch(${'sql_ca'.$i},DB_USER,DB_PASS,DB_NAME,DB_SERVER);
}
#===============================================================================
# $_POST["status"]の内容によりＤＢより取得する情報を分岐
#===============================================================================
switch($_POST["status"]):

case "product_entry_completion":
/////////////////////////////////////////////////////////////////////////////
// 更新登録で入力エラーがあった場合の再読込み

		if($_POST["regist_type"] != "update"):
			break;	// 新規登録処理の場合はここで処理終了(既存データ取得はなし。)
		endif;

		// 既存データ更新時のエラーはbreakせずに該当データを初期表示用に再度取得

case "product_edit":case "copy":
//////////////////////////////////////////////////////////////////
// 更新

		////////////////////////////////////////////////
		// 該当商品データの取得
		$sql = "
		SELECT
			".PRODUCT_LST.".PRODUCT_ID,
			".PRODUCT_LST.".CATEGORY_CODE,
			".CATEGORY_MST.".CATEGORY_NAME,
			".PRODUCT_LST.".PART_NO,
			".PRODUCT_LST.".PRODUCT_NAME,
			".PRODUCT_LST.".CAPACITY,
			".PRODUCT_LST.".ITEM_DETAILS,
			".PRODUCT_LST.".PRODUCT_DETAILS,
			".PRODUCT_LST.".TITLE_TAG,
			".PRODUCT_LST.".SELLING_PRICE,
			".PRODUCT_LST.".DISPLAY_FLG,
			".PRODUCT_LST.".NEW_ITEM_FLG,
			YEAR(".PRODUCT_LST.".SALE_START_DATE) AS S_YEAR,
			MONTH(".PRODUCT_LST.".SALE_START_DATE) AS S_MONTH,
			DAYOFMONTH(".PRODUCT_LST.".SALE_START_DATE) AS S_DAY,
			HOUR(".PRODUCT_LST.".SALE_START_DATE) AS S_HOUR,
			YEAR(".PRODUCT_LST.".SALE_END_DATE) AS E_YEAR,
			MONTH(".PRODUCT_LST.".SALE_END_DATE) AS E_MONTH,
			DAYOFMONTH(".PRODUCT_LST.".SALE_END_DATE) AS E_DAY,
			HOUR(".PRODUCT_LST.".SALE_END_DATE) AS E_HOUR,
			".PRODUCT_LST.".CART_CLOSE_FLG
		FROM
			".PRODUCT_LST."
				INNER JOIN
					".CATEGORY_MST."
				ON
					".PRODUCT_LST.".CATEGORY_CODE = ".CATEGORY_MST.".CATEGORY_CODE
		WHERE
			(".PRODUCT_LST.".PRODUCT_ID = '$product_id')
		AND
			(".PRODUCT_LST.".DEL_FLG = '0')
		AND
			(".CATEGORY_MST.".DEL_FLG = '0')
		";
		$fetchProductData = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		// 該当商品が保持しているカラー情報を取得（セッションに保存）
		$sql = "
		SELECT
			DSC_ID,
			COLOR_NAME,
			SIZE_NAME,
			STOCK_QUANTITY
		FROM
			".PRODUCT_PROPERTY_DATA."
		WHERE
			(PRODUCT_ID = '$product_id')
		AND
			(DEL_FLG='0')
		ORDER BY
			DSC_ID ASC
		";
		$fetchColSizeData = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		// カテゴリー情報を取得（選択タグで使用）
		$cate_sql = "
		SELECT
			CATEGORY_CODE,
			CATEGORY_NAME
		FROM
			".CATEGORY_MST."
		WHERE
			(DEL_FLG='0')
		ORDER BY
			CATEGORY_CODE ASC";
		$fetchCateList = dbOpe::fetch($cate_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	break;

case "delflg_change":
/////////////////////////////////////////////////////////////////////////////
//	削除処理後 ※breakせずそのままセッションの検索条件情報をもとにデータ抽出

case "display_change":
/////////////////////////////////////////////////////////////////////////////
// 表示/非表示処理後 ※breakせずそのままセッションの検索条件情報でデータ抽出

case "search_result":case "recommend":
/////////////////////////////////////////////////////////////////////////////
// 指定された検索条件を元に商品一覧情報を取得

	// 共通な文字列処理
	$search_product_name = mb_convert_kana($search_product_name,"KV");
	$search_part_no = mb_convert_kana($search_part_no,"a");
	$search_category_code = mb_convert_kana($search_category_code,"a");
	$search_stock_quantity = mb_convert_kana($search_stock_quantity,"n");
	$search_display = mb_convert_kana($search_display,"n");
	$search_cart = mb_convert_kana($search_cart,"n");
	$search_recommend = mb_convert_kana($search_recommend,"n");

	// 検索文字セッション格納処理
	if($_SESSION["search_cond"]["product_name"])$search_product_name = $_SESSION["search_cond"]["product_name"];
	if($_SESSION["search_cond"]["part_no"])$search_part_no = $_SESSION["search_cond"]["part_no"];
	if($_SESSION["search_cond"]["search_category_code"])$search_category_code = $_SESSION["search_cond"]["search_category_code"];
	if($_SESSION["search_cond"]["search_stock_quantity"])$search_stock_quantity = $_SESSION["search_cond"]["search_stock_quantity"];
	if($_SESSION["search_cond"]["search_display"])$search_display = $_SESSION["search_cond"]["search_display"];
	if($_SESSION["search_cond"]["search_cart"])$search_cart = $_SESSION["search_cond"]["search_cart"];
	if($_SESSION["search_cond"]["search_recommend"])$search_recommend = $_SESSION["search_cond"]["search_recommend"];

	// 検索条件をセッションに格納
	$_SESSION["search_cond"]["product_name"] = $search_product_name;
	$_SESSION["search_cond"]["part_no"] = $search_part_no;
	$_SESSION["search_cond"]["search_category_code"] = $search_category_code;
	$_SESSION["search_cond"]["search_stock_quantity"] = $search_stock_quantity;
	$_SESSION["search_cond"]["search_display"] = $search_display;
	$_SESSION["search_cond"]["search_cart"] = $search_cart;
	$_SESSION["search_cond"]["search_recommend"] = $search_recommend;

	# 検索SQLの基本文を作成
	$sql = "
	SELECT
		".PRODUCT_LST.".PRODUCT_ID,
		".PRODUCT_LST.".PART_NO,
		".PRODUCT_LST.".CATEGORY_CODE,
		".CATEGORY_MST.".CATEGORY_NAME,
		".PRODUCT_LST.".PRODUCT_NAME,
		".PRODUCT_LST.".DISPLAY_FLG,
		".PRODUCT_LST.".RECOMMEND_FLG
	FROM
		".PRODUCT_LST."
			INNER JOIN
				".CATEGORY_MST."
			ON
				".PRODUCT_LST.".CATEGORY_CODE = ".CATEGORY_MST.".CATEGORY_CODE
			INNER JOIN
				".PRODUCT_PROPERTY_DATA."
			ON
				".PRODUCT_LST.".PRODUCT_ID = ".PRODUCT_PROPERTY_DATA.".PRODUCT_ID
	WHERE
			(".PRODUCT_LST.".DEL_FLG = '0')
		AND
			(".CATEGORY_MST.".DEL_FLG = '0')
		AND
			(".PRODUCT_PROPERTY_DATA.".DEL_FLG = '0')
	";

	#---------------------------------------------------------------
	# 検索条件の有無によりWHERE句以下を組立て
	#---------------------------------------------------------------
	// 商品名
	if($search_product_name){
		$sql .= "
		AND
			(".PRODUCT_LST.".PRODUCT_NAME LIKE '%$search_product_name%')
		";
	}
	// 型番
	if($search_part_no){
		$sql .= "
		AND
			(".PRODUCT_LST.".PART_NO = '$search_part_no')
		";
	}
	// カテゴリ
	if($search_category_code){
		$sql .= "
		AND
			(".PRODUCT_LST.".CATEGORY_CODE = '$search_category_code')
		";
	}
	// 在庫数
	switch($search_stock_quantity):
		case "2":
				$sql .= "
				AND
					(".PRODUCT_PROPERTY_DATA.".STOCK_QUANTITY = 0)
				";
			break;
		case "3":
				$sql .= "
				AND
					(".PRODUCT_PROPERTY_DATA.".STOCK_QUANTITY > '0')
				";
			break;
	endswitch;
	// 表示/非表示
	switch($search_display):
		case "2":
				$sql .= "
				AND
					(".PRODUCT_LST.".DISPLAY_FLG = '1')
				";
			break;
		case "3":
				$sql .= "
				AND
					(".PRODUCT_LST.".DISPLAY_FLG = '0')
				";
			break;
	endswitch;
	// カートボタン表示/非表示
	switch($search_cart):
		case "2":
				$sql .= "
				AND
					(".PRODUCT_LST.".CART_CLOSE_FLG = '0')
				";
			break;
		case "3":
				$sql .= "
				AND
					(".PRODUCT_LST.".CART_CLOSE_FLG = '1')
				";
			break;
	endswitch;
	// お勧め
	switch($search_recommend):
		case "2":
				$sql .= "
				AND
					(".PRODUCT_LST.".RECOMMEND_FLG = 1)
				";
			break;
		case "3":
				$sql .= "
				AND
					NOT(".PRODUCT_LST.".RECOMMEND_FLG = '1')
				";
			break;
	endswitch;
	// グループ化
	$sql .="
	GROUP BY
		".PRODUCT_LST.".PRODUCT_ID

	";

	#---------------------------------------------------------------
	# ソート順条件
	#---------------------------------------------------------------
	$sql .= "
	ORDER BY
		".CATEGORY_MST.".VIEW_ORDER ASC,
		".PRODUCT_LST.".VIEW_ORDER ASC
	";

	// 検索SQL実行
	$fetchProductList = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	// おすすめフラグが立っている商品数を取得
	$sql = "
	SELECT
		COUNT(*) as CNT
	FROM
		".PRODUCT_LST."
	WHERE
		(RECOMMEND_FLG = '1')
	AND
		(DEL_FLG = '0')
	";

	$fetchREC = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	break;

endswitch;
?>
