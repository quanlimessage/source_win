<?php
/*******************************************************************************
���ѥ���б�

	�������DB����μ���
		Logic�����ꤵ�줿�������򸵤ˣģ¤����ʸ���������PURCHASE_LST�ˤ����

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}
// �����������������å���ľ�ܤ��Υե�����˥���������������
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

////////////////////////////////////////////////
// ���ꤵ�줿�������򸵤˸ܵҰ�����������
// ����ʤ�������������

// POST�ǡ����μ������ȶ��̤�ʸ�������
extract(utilLib::getRequestParams("post",array(8,7,1,4,5)));

#---------------------------------------------------------------
# $_POST["status"]�����Ƥˤ�����ʬ��
#---------------------------------------------------------------
switch($_POST["status"]):
	case "disp_details":
			#-------------------------------------------------------------------------------
			# ���ꤵ�줿CUSTOMER_ID��$_POST["target_customer_id"]�ˤ����
			# �Ŀ;���ȹ�����������
			#-------------------------------------------------------------------------------

			// ��ʸ����ȸĿ;���
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

				".PURCHASE_LST.".RESER_FLG,
				".PURCHASE_LST.".RESER_STYLE,

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

			$fetchOrderCust = dbOpe::fetch($sql1,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			// ��ʸ����ܺ�
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

		break;
	case "search_result":

			if($_GET['p']){
				$p = utilLib::strRep($_GET['p'],8);
				$p = utilLib::strRep($p,7);
				$p = utilLib::strRep($p,1);
				$p = utilLib::strRep($p,4);
			}
			// �ڡ����ֹ������(GET�����ǡ������ʤ����1�򥻥å�)
			if(empty($p) or !is_numeric($p))$p=1;

			////////////////////////////////////////////
			// ���å����˳�Ǽ����Ƥ븡���������

			// ��г��ϰ��֤λ���
			$st = ($p-1) * SALES_MAXROW;

			$st = utilLib::strRep($st,5);

			if($_SESSION["search_cond"]){
				// ��г���ǯ����
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
				$buy_item =  $_SESSION["search_cond"]["buy_item"];
				$buy_item_num =  $_SESSION["search_cond"]["buy_item_num"];

			}

			// ���������ꤵ�줿�������򥻥å����˳�Ǽ
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
			$_SESSION["search_cond"]["buy_item"] = $buy_item;
			$_SESSION["search_cond"]["buy_item_num"] = $buy_item_num;

			#--------------------------------------------------------------------------------
			# �ӣѣ���Ω��
			#--------------------------------------------------------------------------------

			// ���������������SQLʸ
			$sql_cnt = "
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
				INNER JOIN
					PURCHASE_ITEM_DATA
				ON
					(PURCHASE_ITEM_DATA.ORDER_ID = ".PURCHASE_LST.".ORDER_ID)
			WHERE
				(".CUSTOMER_LST.".DEL_FLG = '0')
			AND
				(".PURCHASE_LST.".DEL_FLG = '0')
			AND
				(PURCHASE_ITEM_DATA.DEL_FLG = '0')
			";

				////////////////////////////////
				// �������˽��ä�WHERE���ղ�

				// ����ǯ�Τ�
				if($start_y && !$start_m && !$start_d):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-01-01 00:00:00')
					";
				// ����ǯ��
				elseif($start_y && $start_m && !$start_d):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-01 00:00:00')
					";
				// ����ǯ����
				elseif($start_y && $start_m && $start_d):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-$start_d 00:00:00')
					";
				endif;

				// ��λǯ
				if($end_y && !$end_m && !$end_d):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-01-01 00:00:00')
					";
				// ��λǯ��
				elseif($end_y && $end_m && !$end_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-01 00:00:00')
					";
				// ��λǯ����
				elseif($end_y && $end_m && $end_d):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-$end_d 00:00:00')
					";
				endif;

				// ��ʧ��������
				if($search_payment_type):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".PAYMENT_TYPE = '$search_payment_type')
					";
				endif;

				// �������(��г��ϰ���)
				if($start_sum_price):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE >= '$start_sum_price')
					";
				endif;

				// �������(��н�λ����)
				if($end_sum_price):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE < '$end_sum_price')
					";
				endif;

				// ��ѺѤ�
				if(isset($payment_flg)):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".PAYMENT_FLG = '$payment_flg')
					";
				endif;

				// ����
				if(isset($shipped_flg)):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".SHIPPED_FLG = '$shipped_flg')
					";
				endif;

				//���������ֹ�
				if($buy_item_num){
					$sql_cnt .= "
					AND
						(PURCHASE_ITEM_DATA.PART_NO REGEXP '$buy_item_num')
					";
				}

				//��������
				if($buy_item){
					$sql_cnt .= "
					AND
						(PURCHASE_ITEM_DATA.PRODUCT_NAME REGEXP '$buy_item')
					";
				}

				// �����ֹ�
				if($search_order_id):
					$sql_cnt .= "
					AND
						(".PURCHASE_LST.".ORDER_ID = '".utilLib::strRep($search_order_id,5)."')
					";
				endif;

				// ����(��)
				if($search_kana_1):
					$sql_cnt .= "
					AND
						(".CUSTOMER_LST.".LAST_KANA LIKE '%".utilLib::strRep($search_kana_1,5)."%')
					";
				endif;

				// ����(̾)
				if($search_kana_2):
					$sql_cnt .= "
					AND
						(".CUSTOMER_LST.".FIRST_KANA LIKE '%".utilLib::strRep($search_kana_2,5)."%')
					";
				endif;

				// ̾��(��������)
				if($search_name_1):
					$sql_cnt .= "
					AND
						(".CUSTOMER_LST.".LAST_NAME LIKE '%".utilLib::strRep($search_name_1,5)."%')
					";
				endif;

				// ̾��(������̾)
				if($search_name_2):
					$sql_cnt .= "
					AND
						(".CUSTOMER_LST.".FIRST_NAME LIKE '%".utilLib::strRep($search_name_2,5)."%')
					";
				endif;

					$sql_cnt .= "
					GROUP BY
						".PURCHASE_LST.".ORDER_ID
					";

			$fetchPurchaseCNT = dbOpe::fetch($sql_cnt,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			// �١������SQLʸ
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
				INNER JOIN
					PURCHASE_ITEM_DATA
				ON
					(PURCHASE_ITEM_DATA.ORDER_ID = ".PURCHASE_LST.".ORDER_ID)
			WHERE
				(".CUSTOMER_LST.".DEL_FLG = '0')
			AND
				(".PURCHASE_LST.".DEL_FLG = '0')
			AND
				(PURCHASE_ITEM_DATA.DEL_FLG = '0')
			";

				////////////////////////////////
				// �������˽��ä�WHERE���ղ�

				// ����ǯ�Τ�
				if($start_y && !$start_m && !$start_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-01-01 00:00:00')
					";
				// ����ǯ��
				elseif($start_y && $start_m && !$start_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-01 00:00:00')
					";
				// ����ǯ����
				elseif($start_y && $start_m && $start_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE >= '$start_y-$start_m-$start_d 00:00:00')
					";
				endif;

				// ��λǯ
				if($end_y && !$end_m && !$end_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-01-01 00:00:00')
					";
				// ��λǯ��
				elseif($end_y && $end_m && !$end_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-01 00:00:00')
					";
				// ��λǯ����
				elseif($end_y && $end_m && $end_d):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_DATE <= '$end_y-$end_m-$end_d 00:00:00')
					";
				endif;

				// ��ʧ��������
				if($search_payment_type):
					$sql .= "
					AND
						(".PURCHASE_LST.".PAYMENT_TYPE = '$search_payment_type')
					";
				endif;

				// �������(��г��ϰ���)
				if($start_sum_price):
					$sql .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE >= '$start_sum_price')
					";
				endif;

				// �������(��н�λ����)
				if($end_sum_price):
					$sql .= "
					AND
						(".PURCHASE_LST.".SUM_PRICE < '$end_sum_price')
					";
				endif;

				// ��ѺѤ�
				if(isset($payment_flg)):
					$sql .= "
					AND
						(".PURCHASE_LST.".PAYMENT_FLG = '$payment_flg')
					";
				endif;

				// ����
				if(isset($shipped_flg)):
					$sql .= "
					AND
						(".PURCHASE_LST.".SHIPPED_FLG = '$shipped_flg')
					";
				endif;

				//���������ֹ�
				if($buy_item_num){
					$sql .= "
					AND
						(PURCHASE_ITEM_DATA.PART_NO REGEXP '$buy_item_num')
					";
				}

				//��������
				if($buy_item){
					$sql .= "
					AND
						(PURCHASE_ITEM_DATA.PRODUCT_NAME REGEXP '$buy_item')
					";
				}

				// �����ֹ�
				if($search_order_id):
					$sql .= "
					AND
						(".PURCHASE_LST.".ORDER_ID = '".utilLib::strRep($search_order_id,5)."')
					";
				endif;

				// ����(��)
				if($search_kana_1):
					$sql .= "
					AND
						(".CUSTOMER_LST.".LAST_KANA LIKE '%".utilLib::strRep($search_kana_1,5)."%')
					";
				endif;

				// ����(̾)
				if($search_kana_2):
					$sql .= "
					AND
						(".CUSTOMER_LST.".FIRST_KANA LIKE '%".utilLib::strRep($search_kana_2,5)."%')
					";
				endif;

				// ̾��(��������)
				if($search_name_1):
					$sql .= "
					AND
						(".CUSTOMER_LST.".LAST_NAME LIKE '%".utilLib::strRep($search_name_1,5)."%')
					";
				endif;

				// ̾��(������̾)
				if($search_name_2):
					$sql .= "
					AND
						(".CUSTOMER_LST.".FIRST_NAME LIKE '%".utilLib::strRep($search_name_2,5)."%')
					";
				endif;

			// �����ȴ�����
			$sql .= "
					GROUP BY
						".PURCHASE_LST.".ORDER_ID
			ORDER BY
				".PURCHASE_LST.".ORDER_DATE DESC
			LIMIT
				".$st.",".SALES_MAXROW."
			";

			$fetchPurchase = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		break;
endswitch;

?>