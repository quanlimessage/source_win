<?php
/*******************************************************************************
アパレル対応(カテゴリ)
	ショッピングカートプログラム バックオフィス

ユーザーの更新
Logic：指定された検索条件を元にＤＢより情報を取得

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

////////////////////////////////////////////////
// 指定された検索条件を元に顧客一覧情報を取得
// 指定なし：全情報を取得

// POSTデータの受け取りと共通な文字列処理
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

if($_GET['p']){
	$p = utilLib::strRep($_GET['p'],8);
	$p = utilLib::strRep($p,7);
	$p = utilLib::strRep($p,1);
	$p = utilLib::strRep($p,4);
}
// ページ番号の設定(GET受信データがなければ1をセット)
if(empty($p) or !is_numeric($p))$p=1;

// 抽出開始位置の指定
$st = ($p-1) * CUSTOMER_MAXROW;

$st = utilLib::strRep($st,5);

if($_SESSION["search_cond"]){
	$search_order_id = $_SESSION["search_cond"]["search_order_id"];
	$search_kana_1 = $_SESSION["search_cond"]["search_kana_1"];
	$search_kana_2 = $_SESSION["search_cond"]["search_kana_2"];
	$search_name_1 = $_SESSION["search_cond"]["search_name_1"];
	$search_name_2 = $_SESSION["search_cond"]["search_name_2"];
	$search_email = $_SESSION["search_cond"]["search_email"];
	$search_customer_id = $_SESSION["search_cond"]["search_customer_id"];
	$search_state = $_SESSION["search_cond"]["search_state"];
	$search_total_sum = $_SESSION["search_cond"]["search_total_sum"];
}

// 検索条件をセッションに格納

$_SESSION["search_cond"]["search_order_id"] = $search_order_id;
$_SESSION["search_cond"]["search_kana_1"] = $search_kana_1;
$_SESSION["search_cond"]["search_kana_2"] = $search_kana_2;
$_SESSION["search_cond"]["search_name_1"] = $search_name_1;
$_SESSION["search_cond"]["search_name_2"] = $search_name_2;
$_SESSION["search_cond"]["search_email"] = $search_email;
$_SESSION["search_cond"]["search_customer_id"] = $search_customer_id;
$_SESSION["search_cond"]["search_state"] = $search_state;
$_SESSION["search_cond"]["search_total_sum"] = $search_total_sum;

#--------------------------------------------------------------------------------
# 選択された検索項目により発行するＳＱＬを分岐
#--------------------------------------------------------------------------------

// 検索件数の総数抽出SQL文
	$sql_cnt = "
	SELECT
		".PURCHASE_LST.".ORDER_ID,
		".PURCHASE_LST.".CUSTOMER_ID,
		SUM(".PURCHASE_LST.".SUM_PRICE) AS TOTAL_SUM_PRICE,
		".CUSTOMER_LST.".LAST_NAME,
		".CUSTOMER_LST.".FIRST_NAME,
		".CUSTOMER_LST.".LAST_KANA,
		".CUSTOMER_LST.".FIRST_KANA,
		".CUSTOMER_LST.".ZIP_CD1,
		".CUSTOMER_LST.".ZIP_CD2,
		".CUSTOMER_LST.".STATE,
		".CUSTOMER_LST.".ADDRESS1,
		".CUSTOMER_LST.".ADDRESS2,
		".CUSTOMER_LST.".EMAIL,
		".CUSTOMER_LST.".TEL1,
		".CUSTOMER_LST.".TEL2,
		".CUSTOMER_LST.".TEL3,
		".CUSTOMER_LST.".INS_DATE,
		".CUSTOMER_LST.".UPD_DATE,
		".CUSTOMER_LST.".DEL_FLG
	FROM
		".CUSTOMER_LST."
		INNER JOIN
			".PURCHASE_LST."
		ON
			".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
	WHERE
		(".PURCHASE_LST.".DEL_FLG = '0')
	AND
		(".CUSTOMER_LST.".DEL_FLG = '0')
	";

/////////////////
// 抽出条件付加

// 受注番号
if($search_customer_id):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".CUSTOMER_ID = '".utilLib::strRep($search_customer_id,5)."')
	";
endif;

// 受注番号
if($search_order_id):
	$sql_cnt .= "
	AND
		(".PURCHASE_LST.".ORDER_ID = '".utilLib::strRep($search_order_id,5)."')
	";
endif;

// かな(姓)
if($search_kana_1):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".LAST_KANA LIKE '%".utilLib::strRep($search_kana_1,5)."%')
	";
endif;

// かな(名)
if($search_kana_2):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".FIRST_KANA LIKE '%".utilLib::strRep($search_kana_2,5)."%')
	";
endif;

// 名前(漢字・姓)
if($search_name_1):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".LAST_NAME LIKE '%".utilLib::strRep($search_name_1,5)."%')
	";
endif;

// 名前(漢字・名)
if($search_name_2):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".FIRST_NAME LIKE '%".utilLib::strRep($search_name_2,5)."%')
	";
endif;

// メールアドレス
if($search_email):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".EMAIL = '".utilLib::strRep($search_email,5)."')
	";
endif;

// 居住都道府県
if($search_state != "no"):
	$sql_cnt .= "
	AND
		(".CUSTOMER_LST.".STATE = '".utilLib::strRep($search_state,5)."')
	";
endif;

/////////////////
// グループ化(カスタマーIDでグループ化)
$sql_cnt .="
GROUP BY
	".CUSTOMER_LST.".CUSTOMER_ID
";

	// 合計購入額が条件に指定されてたらHAVING句追加
	if($search_total_sum):
		$sql_cnt .= "
		HAVING
			(TOTAL_SUM_PRICE >= '".utilLib::strRep($search_total_sum,5)."')
		";
	endif;

$fetchCustListCNT = $PDO -> fetch($sql_cnt);

// SQLベース

	$sql = "
	SELECT
		".PURCHASE_LST.".ORDER_ID,
		".PURCHASE_LST.".CUSTOMER_ID,
		SUM(".PURCHASE_LST.".SUM_PRICE) AS TOTAL_SUM_PRICE,
		".CUSTOMER_LST.".LAST_NAME,
		".CUSTOMER_LST.".FIRST_NAME,
		".CUSTOMER_LST.".LAST_KANA,
		".CUSTOMER_LST.".FIRST_KANA,
		".CUSTOMER_LST.".ZIP_CD1,
		".CUSTOMER_LST.".ZIP_CD2,
		".CUSTOMER_LST.".STATE,
		".CUSTOMER_LST.".ADDRESS1,
		".CUSTOMER_LST.".ADDRESS2,
		".CUSTOMER_LST.".EMAIL,
		".CUSTOMER_LST.".TEL1,
		".CUSTOMER_LST.".TEL2,
		".CUSTOMER_LST.".TEL3,
		".CUSTOMER_LST.".INS_DATE,
		".CUSTOMER_LST.".UPD_DATE,
		".CUSTOMER_LST.".DEL_FLG
	FROM
		".CUSTOMER_LST."
		INNER JOIN
			".PURCHASE_LST."
		ON
			".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
	WHERE
		(".PURCHASE_LST.".DEL_FLG = '0')
	AND
		(".CUSTOMER_LST.".DEL_FLG = '0')
	";

/////////////////
// 抽出条件付加

// 受注番号
if($search_customer_id):
	$sql .= "
	AND
		(".CUSTOMER_LST.".CUSTOMER_ID = '".utilLib::strRep($search_customer_id,5)."')
	";
endif;

// 受注番号
if($search_order_id):
	$sql .= "
	AND
		(".PURCHASE_LST.".ORDER_ID = '".utilLib::strRep($search_order_id,5)."')
	";
endif;

// かな(姓)
if($search_kana_1):
	$sql .= "
	AND
		(".CUSTOMER_LST.".LAST_KANA LIKE '%".utilLib::strRep($search_kana_1,5)."%')
	";
endif;

// かな(名)
if($search_kana_2):
	$sql .= "
	AND
		(".CUSTOMER_LST.".FIRST_KANA LIKE '%".utilLib::strRep($search_kana_2,5)."%')
	";
endif;

// 名前(漢字・姓)
if($search_name_1):
	$sql .= "
	AND
		(".CUSTOMER_LST.".LAST_NAME LIKE '%".utilLib::strRep($search_name_1,5)."%')
	";
endif;

// 名前(漢字・名)
if($search_name_2):
	$sql .= "
	AND
		(".CUSTOMER_LST.".FIRST_NAME LIKE '%".utilLib::strRep($search_name_2,5)."%')
	";
endif;

// メールアドレス
if($search_email):
	$sql .= "
	AND
		(".CUSTOMER_LST.".EMAIL = '".utilLib::strRep($search_email,5)."')
	";
endif;

// 居住都道府県
if($search_state != "no"):
	$sql .= "
	AND
		(".CUSTOMER_LST.".STATE = '".utilLib::strRep($search_state,5)."')
	";
endif;

/////////////////
// グループ化(カスタマーIDでグループ化)
$sql .="
GROUP BY
	".CUSTOMER_LST.".CUSTOMER_ID
";

	// 合計購入額が条件に指定されてたらHAVING句追加
	if($search_total_sum):
		$sql .= "
		HAVING
			(TOTAL_SUM_PRICE >= '".utilLib::strRep($search_total_sum,5)."')
		";
	endif;

/////////////////
// ソート条件(登録日時順にグループ化)
$sql .="
ORDER BY
	".CUSTOMER_LST.".INS_DATE DESC
LIMIT
	".$st.",".CUSTOMER_MAXROW."
";

$fetchCustList = $PDO -> fetch($sql);

?>
