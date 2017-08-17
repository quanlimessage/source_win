<?php
/*******************************************************************************
Nx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
}

#---------------------------------------------------------------
# 有効な購入年月を取得する
#---------------------------------------------------------------
	$yearmonth_sql = "
		SELECT
			EXTRACT(YEAR FROM INS_DATE) AS Y,
			EXTRACT(MONTH FROM INS_DATE) AS M,
			EXTRACT(YEAR_MONTH FROM INS_DATE) AS YM
		FROM
			PURCHASE_ITEM_DATA
		WHERE
			(DEL_FLG = '0')
		GROUP BY
			YM
		ORDER BY
			YM DESC
	";

	$yearmonth_fetch = dbOpe::fetch($yearmonth_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#---------------------------------------------------------------
# データ抽出期間の設定
#---------------------------------------------------------------

	//抽出する期間を格納
	$day = $_POST[year_month];

	//抽出する期間のデータが、無い・値が不正の場合は最後に購入された年月を格納
	if(!$day || !is_numeric($day)){$day = $yearmonth_fetch[0][YM];}

#---------------------------------------------------------------
# 各データ取得関数の定義
#---------------------------------------------------------------

	//時間別
		function time_access ($day){

		//購入数、売り上げを取得
			$sql = "
			SELECT
				SUM(QUANTITY) AS TOTAL_QUANTITY,
				SUM(QUANTITY * SELLING_PRICE) AS TOTAL_PRICE,
				EXTRACT(HOUR FROM INS_DATE) AS HOUR_TIME,
				EXTRACT(YEAR_MONTH FROM INS_DATE) AS YMT
			FROM
				PURCHASE_ITEM_DATA
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,HOUR_TIME
			HAVING
				(YMT = '$day')
			ORDER BY
				HOUR_TIME ASC
				";

			$fetch_time = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//注文数を取得

			$sql = "
			SELECT
				COUNT(ORDER_ID) AS CNT,
				EXTRACT(HOUR FROM ORDER_DATE) AS HOUR_TIME,
				EXTRACT(YEAR_MONTH FROM ORDER_DATE) AS YMT
			FROM
				PURCHASE_LST
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,HOUR_TIME
			HAVING
				(YMT = '$day')
			ORDER BY
				HOUR_TIME ASC
			";

			$fetch_count = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//データを合成する
				for($i=0;$i < count($fetch_count);$i++){
					$fetch_time[$i]['CNT'] = $fetch_count[$i]['CNT'];
				}

		return $fetch_time;
		}

	// 日別
		function day_access ($day){

		//購入数、売り上げを取得
			$sql = "
			SELECT
				SUM(QUANTITY) AS TOTAL_QUANTITY,
				SUM(QUANTITY * SELLING_PRICE) AS TOTAL_PRICE,
				EXTRACT(YEAR_MONTH FROM INS_DATE) AS YMT,
				YEAR(INS_DATE) AS Y,
				MONTH(INS_DATE) AS M,
				DAYOFMONTH(INS_DATE) AS D
			FROM
				PURCHASE_ITEM_DATA
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,D
			HAVING
				(YMT = '$day')
			ORDER BY
				D ASC
				";

			$fetch_day = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//注文数を取得

			$sql = "
			SELECT
				COUNT(ORDER_ID) AS CNT,
				EXTRACT(YEAR_MONTH FROM ORDER_DATE) AS YMT,
				YEAR(ORDER_DATE) AS Y,
				MONTH(ORDER_DATE) AS M,
				DAYOFMONTH(ORDER_DATE) AS D
			FROM
				PURCHASE_LST
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,D
			HAVING
				(YMT = '$day')
			ORDER BY
				D ASC
			";

			$fetch_count = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//データを合成する
				for($i=0;$i < count($fetch_count);$i++){
					$fetch_day[$i]['CNT'] = $fetch_count[$i]['CNT'];
				}

		return $fetch_day;
		}

	//曜日別
		function week_access ($day){

		//購入数、売り上げを取得
		$sql = "
			SELECT
				SUM(QUANTITY) AS TOTAL_QUANTITY,
				SUM(QUANTITY * SELLING_PRICE) AS TOTAL_PRICE,
				EXTRACT(YEAR_MONTH FROM INS_DATE) AS YMT,
				DAYOFWEEK(INS_DATE) AS DAYOFWEEK
			FROM
				PURCHASE_ITEM_DATA
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,DAYOFWEEK
			HAVING
				(YMT = '$day')
			ORDER BY
				DAYOFWEEK ASC
				";

			$fetch_week = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		//注文数を取得

			$sql = "
			SELECT
				COUNT(ORDER_ID) AS CNT,
				EXTRACT(YEAR_MONTH FROM ORDER_DATE) AS YMT,
				DAYOFWEEK(ORDER_DATE) AS DAYOFWEEK
			FROM
				PURCHASE_LST
			WHERE
				(DEL_FLG = '0')
			GROUP BY
				YMT,DAYOFWEEK
			HAVING
				(YMT = '$day')
			ORDER BY
				DAYOFWEEK ASC
			";

			$fetch_count = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//データを合成する
				for($i=0;$i < count($fetch_count);$i++){
					$fetch_week[$i]['CNT'] = $fetch_count[$i]['CNT'];
				}

		return $fetch_week;
		}

	//カテゴリー別
		function category_access ($day){

		$sql = "
			SELECT
				SUM(PURCHASE_ITEM_DATA.QUANTITY) AS TOTAL_QUANTITY,
				SUM(PURCHASE_ITEM_DATA.QUANTITY * PURCHASE_ITEM_DATA.SELLING_PRICE) AS TOTAL_PRICE,
				COUNT(PURCHASE_ITEM_DATA.ORDER_ID) AS CNT,
				EXTRACT(YEAR_MONTH FROM PURCHASE_ITEM_DATA.INS_DATE) AS YMT,
				PRODUCT_LST.CATEGORY_CODE
			FROM
				PURCHASE_ITEM_DATA
			INNER JOIN
				PRODUCT_LST
			ON
				PRODUCT_LST.PRODUCT_ID = PURCHASE_ITEM_DATA.PRODUCT_ID
			WHERE
				(PRODUCT_LST.DEL_FLG = '0')
				AND
				(PURCHASE_ITEM_DATA.DEL_FLG = '0')
			GROUP BY
				YMT,PRODUCT_LST.CATEGORY_CODE
			HAVING
				(YMT = '$day')
			ORDER BY
				PRODUCT_LST.CATEGORY_CODE ASC
				";

			$fetch_category = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		return $fetch_category;
		}

	//売れ筋トップ１０【売り上げ金額】
		function bestten_sell_access($day){

			$sql = "
				SELECT
					SUM(PURCHASE_ITEM_DATA.QUANTITY) AS TOTAL_QUANTITY,
					SUM(PURCHASE_ITEM_DATA.QUANTITY * PURCHASE_ITEM_DATA.SELLING_PRICE) AS TOTAL_PRICE,
					COUNT(PURCHASE_ITEM_DATA.ORDER_ID) AS CNT,
					EXTRACT(YEAR_MONTH FROM PURCHASE_ITEM_DATA.INS_DATE) AS YMT,
					PRODUCT_LST.PRODUCT_NAME,
					PURCHASE_ITEM_DATA.PART_NO
				FROM
					PURCHASE_ITEM_DATA
				INNER JOIN
					PRODUCT_LST
				ON
					PRODUCT_LST.PRODUCT_ID = PURCHASE_ITEM_DATA.PRODUCT_ID
				WHERE
					(PRODUCT_LST.DEL_FLG = '0')
					AND
					(PURCHASE_ITEM_DATA.DEL_FLG = '0')
				GROUP BY
					YMT,PRODUCT_LST.PRODUCT_NAME
				HAVING
					(YMT = '$day')
				ORDER BY
					TOTAL_PRICE DESC
				LIMIT
					0 , ".BEST_COUNT."
					";

				$fetch_bestten_sell = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		return $fetch_bestten_sell;
		}

	//売れ筋トップ１０【注文件数】
		function bestten_order_access($day){

			$sql = "
				SELECT
					SUM(PURCHASE_ITEM_DATA.QUANTITY) AS TOTAL_QUANTITY,
					SUM(PURCHASE_ITEM_DATA.QUANTITY * PURCHASE_ITEM_DATA.SELLING_PRICE) AS TOTAL_PRICE,
					COUNT(PURCHASE_ITEM_DATA.ORDER_ID) AS CNT,
					EXTRACT(YEAR_MONTH FROM PURCHASE_ITEM_DATA.INS_DATE) AS YMT,
					PRODUCT_LST.PRODUCT_NAME,
					PURCHASE_ITEM_DATA.PART_NO
				FROM
					PURCHASE_ITEM_DATA
				INNER JOIN
					PRODUCT_LST
				ON
					PRODUCT_LST.PRODUCT_ID = PURCHASE_ITEM_DATA.PRODUCT_ID
				WHERE
					(PRODUCT_LST.DEL_FLG = '0')
					AND
					(PURCHASE_ITEM_DATA.DEL_FLG = '0')
				GROUP BY
					YMT,PRODUCT_LST.PRODUCT_NAME
				HAVING
					(YMT = '$day')
				ORDER BY
					CNT DESC
				LIMIT
					0 , ".BEST_COUNT."
					";

				$fetch_bestten_order = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		return $fetch_bestten_order;
		}

	//売れ筋トップ１０【購入数】
		function bestten_purchase_access($day){

			$sql = "
				SELECT
					SUM(PURCHASE_ITEM_DATA.QUANTITY) AS TOTAL_QUANTITY,
					SUM(PURCHASE_ITEM_DATA.QUANTITY * PURCHASE_ITEM_DATA.SELLING_PRICE) AS TOTAL_PRICE,
					COUNT(PURCHASE_ITEM_DATA.ORDER_ID) AS CNT,
					EXTRACT(YEAR_MONTH FROM PURCHASE_ITEM_DATA.INS_DATE) AS YMT,
					PRODUCT_LST.PRODUCT_NAME,
					PURCHASE_ITEM_DATA.PART_NO
				FROM
					PURCHASE_ITEM_DATA
				INNER JOIN
					PRODUCT_LST
				ON
					PRODUCT_LST.PRODUCT_ID = PURCHASE_ITEM_DATA.PRODUCT_ID
				WHERE
					(PRODUCT_LST.DEL_FLG = '0')
					AND
					(PURCHASE_ITEM_DATA.DEL_FLG = '0')
				GROUP BY
					YMT,PRODUCT_LST.PRODUCT_NAME
				HAVING
					(YMT = '$day')
				ORDER BY
					TOTAL_QUANTITY DESC
				LIMIT
					0 , ".BEST_COUNT."
					";

				$fetch_bestten_purchase = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		return $fetch_bestten_purchase;
		}

#---------------------------------------------------------------
# データ取得
#---------------------------------------------------------------

	// 該当年月の合計データ取得
	//合計売り上げ金額
		$total_price_sql = "
			SELECT
				SUM(QUANTITY * SELLING_PRICE) AS TOTAL_SUM_PRICE
			FROM
				PURCHASE_ITEM_DATA
			WHERE
				(EXTRACT(YEAR_MONTH FROM INS_DATE) = '$day' )
				AND
				(DEL_FLG = '0')
			";

		$total_price_fetch = dbOpe::fetch($total_price_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//合計注文件数
		$total_customer_sql = "
			SELECT
				COUNT(ORDER_ID) AS CNT
			FROM
				PURCHASE_LST
			WHERE
				(EXTRACT(YEAR_MONTH FROM ORDER_DATE) = '$day' )
				AND
				(DEL_FLG = '0')
			";

		$total_customer_fetch = dbOpe::fetch($total_customer_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//合計購入数
		$total_quantity_sql = "
			SELECT
				SUM(PURCHASE_ITEM_DATA.QUANTITY) AS TOTAL_QUANTITY
			FROM
				PURCHASE_ITEM_DATA
			WHERE
				(EXTRACT(YEAR_MONTH FROM INS_DATE) = '$day' )
				AND
				(DEL_FLG = '0')
			";

		$total_quantity_fetch = dbOpe::fetch($total_quantity_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//抽出する期間のデータを出力する
		$fetch_day = day_access($day);
		$fetch_time = time_access($day);
		$fetch_week = week_access($day);
		$fetch_cate = category_access($day);
		$fetch_bestten_sell = bestten_sell_access($day);
		$fetch_bestten_order = bestten_order_access($day);
		$fetch_bestten_access = bestten_purchase_access($day);

	//カテゴリー情報を取得する

		$category_sql = "
				SELECT
					CATEGORY_NAME,CATEGORY_CODE
				FROM
					CATEGORY_MST
				WHERE
					(DEL_FLG = '0')
				ORDER BY
					VIEW_ORDER ASC
				";

		$category_fetch = dbOpe::fetch($category_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>