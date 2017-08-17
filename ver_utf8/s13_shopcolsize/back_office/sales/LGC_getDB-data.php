<?php
/*******************************************************************************
アパレル対応

	受注情報：DB情報の取得
		Logic：指定された検索条件を元にＤＢより注文情報一覧（PURCHASE_LST）を取得

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

#---------------------------------------------------------------
# $_POST["status"]の内容により処理分岐
#---------------------------------------------------------------
switch($_POST["status"]):
	case "disp_details":
			#-------------------------------------------------------------------------------
			# 指定されたCUSTOMER_ID（$_POST["target_customer_id"]）を条件に
			# 個人情報と購入履歴を取得
			#-------------------------------------------------------------------------------

			// 注文情報と個人情報
			$sql1 = "
			SELECT
				".PURCHASE_LST.".ORDER_ID,
				".PURCHASE_LST.".CUSTOMER_ID,
				".CUSTOMER_LST.".LAST_NAME,
				".CUSTOMER_LST.".FIRST_NAME,
				".CUSTOMER_LST.".LAST_KANA,
				".CUSTOMER_LST.".FIRST_KANA,
				".CUSTOMER_LST.".ALPWD,
				".CUSTOMER_LST.".ZIP_CD1,
				".CUSTOMER_LST.".ZIP_CD2,
				".CUSTOMER_LST.".STATE,
				".CUSTOMER_LST.".ADDRESS1,
				".CUSTOMER_LST.".ADDRESS2,
				".CUSTOMER_LST.".EMAIL,
				".CUSTOMER_LST.".TEL1,
				".CUSTOMER_LST.".TEL2,
				".CUSTOMER_LST.".TEL3,
				".PURCHASE_LST.".TOTAL_PRICE,
				".PURCHASE_LST.".SUM_PRICE,
				".PURCHASE_LST.".SHIPPING_AMOUNT,
				".PURCHASE_LST.".DAIBIKI_AMOUNT,
				".PURCHASE_LST.".DELI_LAST_NAME,
				".PURCHASE_LST.".DELI_FIRST_NAME,
				".PURCHASE_LST.".DELI_ZIP_CD1,
				".PURCHASE_LST.".DELI_ZIP_CD2,
				".PURCHASE_LST.".DELI_STATE,
				".PURCHASE_LST.".DELI_ADDRESS1,
				".PURCHASE_LST.".DELI_ADDRESS2,
				".PURCHASE_LST.".DELI_TEL1,
				".PURCHASE_LST.".DELI_TEL2,
				".PURCHASE_LST.".DELI_TEL3,
				".PURCHASE_LST.".PAYMENT_TYPE,
				".PURCHASE_LST.".ORDER_DATE,
				".PURCHASE_LST.".PAYMENT_FLG,
				".PURCHASE_LST.".PAYMENT_DATE,
				".PURCHASE_LST.".SHIPPED_FLG,
				".PURCHASE_LST.".SHIPPED_DAY,
				".PURCHASE_LST.".CONFIG_MEMO,
				".PURCHASE_LST.".REMARKS
			FROM
				".PURCHASE_LST.",".CUSTOMER_LST."
			WHERE
				".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
			AND
				(".PURCHASE_LST.".ORDER_ID = '".$_POST["target_order_id"]."')
			AND
				(".PURCHASE_LST.".DEL_FLG = '0')
			AND
				(".CUSTOMER_LST.".DEL_FLG = '0')
			";

			$fetchOrderCust = $PDO -> fetch($sql1);

			// 注文情報詳細
			$sql3 = "
			SELECT
				".PURCHASE_ITEM_DATA.".PART_NO,
				".PURCHASE_ITEM_DATA.".PRODUCT_NAME,
				".PURCHASE_ITEM_DATA.".COLOR_NAME,
				".PURCHASE_ITEM_DATA.".SIZE_NAME,
				".PURCHASE_ITEM_DATA.".SELLING_PRICE,
				".PURCHASE_ITEM_DATA.".QUANTITY
			FROM
				".PURCHASE_ITEM_DATA."
			WHERE
				(".PURCHASE_ITEM_DATA.".ORDER_ID = '".$_POST["target_order_id"]."')
			AND
				(".PURCHASE_ITEM_DATA.".DEL_FLG = '0')
			ORDER BY
				".PURCHASE_ITEM_DATA.".PID ASC
			";
			$fetchPerItem = $PDO -> fetch($sql3);

		break;
	case "search_result":

			if($_GET['p']){
				$p = utilLib::strRep($_GET['p'],8);
				$p = utilLib::strRep($p,7);
				$p = utilLib::strRep($p,1);
				$p = utilLib::strRep($p,4);
			}
			// ページ番号の設定(GET受信データがなければ1をセット)
			if(empty($p) or !is_numeric($p))$p=1;

			////////////////////////////////////////////
			// セッションに格納されてる検索条件を取得

			// 抽出開始位置の指定
			$st = ($p-1) * SALES_MAXROW;

			$st = utilLib::strRep($st,5);

			if($_SESSION["search_cond"]){
				// 抽出開始年月日
				$start_y = $_SESSION["search_cond"]["start_y"];
				$start_m = $_SESSION["search_cond"]["start_m"];
				$start_d = $_SESSION["search_cond"]["start_d"];
				$end_y = $_SESSION["search_cond"]["end_y"];
				$end_m = $_SESSION["search_cond"]["end_m"];
				$end_d = $_SESSION["search_cond"]["end_d"];
				$search_payment_type = $_SESSION["search_cond"]["search_payment_type"];
				$start_sum_price = $_SESSION["search_cond"]["start_sum_price"];
				$end_sum_price = $_SESSION["search_cond"]["end_sum_price"];
				$payment_flg = $_SESSION["search_cond"]["payment_flg"];
				$shipped_flg = $_SESSION["search_cond"]["shipped_flg"];
			}

			// 新しく指定された検索条件をセッションに格納
			$_SESSION["search_cond"]["start_y"] = $start_y;
			$_SESSION["search_cond"]["start_m"] = $start_m;
			$_SESSION["search_cond"]["start_d"] = $start_d;
			$_SESSION["search_cond"]["end_y"] = $end_y;
			$_SESSION["search_cond"]["end_m"] = $end_m;
			$_SESSION["search_cond"]["end_d"] = $end_d;
			$_SESSION["search_cond"]["search_payment_type"] = $search_payment_type;
			$_SESSION["search_cond"]["start_sum_price"] = $start_sum_price;
			$_SESSION["search_cond"]["end_sum_price"] = $end_sum_price;
			$_SESSION["search_cond"]["payment_flg"] = $payment_flg;
			$_SESSION["search_cond"]["shipped_flg"] = $shipped_flg;

			#--------------------------------------------------------------------------------
			# ＳＱＬ組立て
			#--------------------------------------------------------------------------------

				////////////////////////////////
				// 検索条件に従ってWHERE句付加
				$addwhere = "";

				// 開始年のみ
				if($start_y && !$start_m && !$start_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-01-01 00:00:00')
					";
				// 開始年月
				elseif($start_y && $start_m && !$start_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-01 00:00:00')
					";
				// 開始年月日
				elseif($start_y && $start_m && $start_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-$start_d 00:00:00')
					";
				endif;

				// 終了年
				if($end_y && !$end_m && !$end_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-01-01 23:59:59')
					";
				// 終了年月
				elseif($end_y && $end_m && !$end_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-01 23:59:59')
					";
				// 終了年月日
				elseif($end_y && $end_m && $end_d):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-$end_d 23:59:59')
					";
				endif;

				// 支払いタイプ
				if($search_payment_type):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".PAYMENT_TYPE = '$search_payment_type')
					";
				endif;

				// 購入金額(抽出開始位置)
				if($start_sum_price):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE >= '$start_sum_price')
					";
				endif;

				// 購入金額(抽出終了位置)
				if($end_sum_price):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE < '$end_sum_price')
					";
				endif;

				// 決済済み
				if(isset($payment_flg)):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".PAYMENT_FLG = '$payment_flg')
					";
				endif;

				// 配送
				if(isset($shipped_flg)):
					$addwhere .= "
					AND
						(".PURCHASE_LST.".SHIPPED_FLG = '$shipped_flg')
					";
				endif;

			// 検索件数の総数抽出SQL文
			$sql_cnt = "
			SELECT
				COUNT(*) AS CNT
			FROM
				".PURCHASE_LST."
				INNER JOIN
					".CUSTOMER_LST."
				ON
					".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
			WHERE
				(".CUSTOMER_LST.".DEL_FLG = '0')
			AND
				(".PURCHASE_LST.".DEL_FLG = '0')
			";

			$sql_cnt = $sql_cnt.$addwhere;
			$fetchPurchaseCNT = $PDO -> fetch($sql_cnt);

			// ベース抽出SQL文
			$sql = "
			SELECT
				".PURCHASE_LST.".ORDER_ID,
				".PURCHASE_LST.".CUSTOMER_ID,
				".CUSTOMER_LST.".LAST_NAME,
				".CUSTOMER_LST.".FIRST_NAME,
				".CUSTOMER_LST.".EMAIL,
				".PURCHASE_LST.".TOTAL_PRICE,
				".PURCHASE_LST.".SUM_PRICE,
				".PURCHASE_LST.".SHIPPING_AMOUNT,
				".PURCHASE_LST.".DAIBIKI_AMOUNT,
				".PURCHASE_LST.".PAYMENT_TYPE,
				".PURCHASE_LST.".ORDER_DATE,
				".PURCHASE_LST.".PAYMENT_FLG,
				".PURCHASE_LST.".PAYMENT_DATE,
				".PURCHASE_LST.".SHIPPED_FLG,
				".PURCHASE_LST.".SHIPPED_DAY,
				".PURCHASE_LST.".CONFIG_MEMO,
				".PURCHASE_LST.".UPD_DATE
			FROM
				".PURCHASE_LST."
				INNER JOIN
					".CUSTOMER_LST."
				ON
					".PURCHASE_LST.".CUSTOMER_ID = ".CUSTOMER_LST.".CUSTOMER_ID
			WHERE
				(".CUSTOMER_LST.".DEL_FLG = '0')
			AND
				(".PURCHASE_LST.".DEL_FLG = '0')
			";

			$sql = $sql.$addwhere;

			// ソート基準指定
			$sql .= "
			ORDER BY
				".PURCHASE_LST.".ORDER_DATE DESC
			LIMIT
				".$st.",".SALES_MAXROW."
			";

			$fetchPurchase = $PDO -> fetch($sql);

		break;
endswitch;

?>
