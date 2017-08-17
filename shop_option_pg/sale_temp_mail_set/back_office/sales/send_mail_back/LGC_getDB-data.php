<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：ＤＢ情報取得処理ファイル

*******************************************************************************/

#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if(!$accessChk){
	header("Location: ../../index.php");exit();
}

	//メールのテンプレートを取得する。
		switch($status):
			case('send_pay')://振込み
				$temp_type = "1";
				break;

			case('send_deli')://発送
				$temp_type = "2";
				break;

			default://無ければエラー
				header("Location: ../../index.php");exit();

		endswitch;

		$sql = "
		SELECT
			*
		FROM
			S2_PRODUCT_LST
		WHERE
			(NUMBER_ID = '$temp_type')
		";

		// ＳＱＬを実行
		$fetchTMPL = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
				DATE_FORMAT(".PURCHASE_LST.".ORDER_DATE, '%Y年%m月%d日') AS TYPE_ORDER,
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

			$fetchCUST = dbOpe::fetch($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			// 注文情報詳細
			$sql3 = "
			SELECT
				".PURCHASE_ITEM_DATA.".PART_NO,
				".PURCHASE_ITEM_DATA.".PRODUCT_NAME,
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
			$fetchPerItem = dbOpe::fetch($sql3,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			//テンプレートに必要な文字を入れていく
				$subject_data = $fetchTMPL[0]['SUBJECT'];//件名
				$tmpl_data = $fetchTMPL[0]['CONTENT'];//本文

///////////////////////////////////////////////////////////////////////////////////////////////////
//口座情報
				if ( $fetchCUST[0]['PAYMENT_TYPE'] == 2 ){
					$tmpl_data = str_replace("%BANK_INFO%",BANK_INFO,$tmpl_data);

				}else{
					$tmpl_data = str_replace("%BANK_INFO%","",$tmpl_data);

				}

///////////////////////////////////////////////////////////////////////////////////////////////////
//お問い合わせ先
				$tmpl_data = str_replace("%COMPANY_INFO%","●お問い合わせ先\r\n".COMPANY_INFO,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//お客様のお名前
				$tmpl_data = str_replace("%CUST_NAME%",$fetchCUST[0]['LAST_NAME']." ".$fetchCUST[0]['FIRST_NAME'],$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//ご購入商品

				// 購入商品
					for($i = 0, $items = ""; $i < count($fetchPerItem); $i++){
						$items .= "商品番号：".$fetchPerItem[$i]['PART_NO']."\n";
						$items .= "商品名：".$fetchPerItem[$i]['PRODUCT_NAME']."\n";
						$items .= "\\".number_format($fetchPerItem[$i]['SELLING_PRICE'])."\t";
						$items .= "数量：".$fetchPerItem[$i]['QUANTITY']."\n\n";
					}

				//支払い方法
				switch($fetchCUST[0]['PAYMENT_TYPE']):
					case 1:
						$payment_method = "クレジットカード";
						break;
					case 2:
						$payment_method = "銀行振込";
						break;
					case 3:
						$payment_method = "代引き";
						break;
					case 4:
						$payment_method = "コンビニ決済";
						break;
					case 5:
						$payment_method = "郵便振替";
						break;
				endswitch;

				//購入日付
					$buy_date = $fetchCUST[0]['TYPE_ORDER'];

				//ご購入金額
					$sum_price = number_format($fetchCUST[0]['SUM_PRICE']);

				//配送料
					$deli_price = number_format($fetchCUST[0]['SHIPPING_AMOUNT']);

				//お支払金額
					$total_price = number_format($fetchCUST[0]['TOTAL_PRICE']);

				//代引き
					$daibiki_price = ($fetchCUST[0]['PAYMENT_TYPE'] == 3)?"\r\n　代引手数料：\\".number_format($fetchCUST[0]['DAIBIKI_AMOUNT'])."-":"";

$items_data = "================================================================
■受付番号  ：{$target_order_id}
■購入日時  ：{$buy_date}
■お支払方法：{$payment_method}
---------------------------------------------------------------

■ご購入商品：
{$items}
---------------------------------------------------------------

■ご購入金額：\\{$sum_price}-（税込）
　配送料　　：\\{$deli_price}{$daibiki_price}
　お支払金額：\\{$total_price}-

===============================================================
";

				$tmpl_data = str_replace("%ITEMS%",$items_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//購入者情報

$buyer_data = "●購入者情報
================================================================
名前    ：".$fetchCUST[0]['LAST_NAME']." ".$fetchCUST[0]['FIRST_NAME']."
MAIL    ：".$fetchCUST[0]['EMAIL']."
郵便番号：".$fetchCUST[0]['ZIP_CD1']."-".$fetchCUST[0]['ZIP_CD2']."
住所    ：".$shipping_list[$fetchCUST[0]['STATE']]['pref']." ".$fetchCUST[0]['ADDRESS1']." ".$fetchCUST[0]['ADDRESS2']."
電話番号：".$fetchCUST[0]['TEL1']."-".$fetchCUST[0]['TEL2']."-".$fetchCUST[0]['TEL3']."
================================================================
";
				$tmpl_data = str_replace("%BUYER%",$buyer_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//お届け先情報

$deli_data = "●お届け先情報
================================================================
名前    ：".$fetchCUST[0]['DELI_LAST_NAME']." ".$fetchCUST[0]['DELI_FIRST_NAME']."
郵便番号：".$fetchCUST[0]['DELI_ZIP_CD1']."-".$fetchCUST[0]['DELI_ZIP_CD2']."
住所    ：".$shipping_list[$fetchCUST[0]['DELI_STATE']]['pref']." ".$fetchCUST[0]['DELI_ADDRESS1']." ".$fetchCUST[0]['DELI_ADDRESS2']."
電話番号：".$fetchCUST[0]['DELI_TEL1']."-".$fetchCUST[0]['DELI_TEL2']."-".$fetchCUST[0]['DELI_TEL3']."
================================================================
";

				$tmpl_data = str_replace("%DELI%",$deli_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//備考欄

$deli_data = "●備考欄
================================================================
".$fetchCUST[0]['REMARKS']."
================================================================
";

				$tmpl_data = str_replace("%REMARKS%",$deli_data,$tmpl_data);

?>