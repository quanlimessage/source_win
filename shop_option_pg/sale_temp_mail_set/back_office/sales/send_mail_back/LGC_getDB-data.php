<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
Logic���ģ¾�����������ե�����

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if(!$accessChk){
	header("Location: ../../index.php");exit();
}

	//�᡼��Υƥ�ץ졼�Ȥ�������롣
		switch($status):
			case('send_pay')://������
				$temp_type = "1";
				break;

			case('send_deli')://ȯ��
				$temp_type = "2";
				break;

			default://̵����Х��顼
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

		// �ӣѣ̤�¹�
		$fetchTMPL = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
				DATE_FORMAT(".PURCHASE_LST.".ORDER_DATE, '%Yǯ%m��%d��') AS TYPE_ORDER,
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

			//�ƥ�ץ졼�Ȥ�ɬ�פ�ʸ��������Ƥ���
				$subject_data = $fetchTMPL[0]['SUBJECT'];//��̾
				$tmpl_data = $fetchTMPL[0]['CONTENT'];//��ʸ

///////////////////////////////////////////////////////////////////////////////////////////////////
//���¾���
				if ( $fetchCUST[0]['PAYMENT_TYPE'] == 2 ){
					$tmpl_data = str_replace("%BANK_INFO%",BANK_INFO,$tmpl_data);

				}else{
					$tmpl_data = str_replace("%BANK_INFO%","",$tmpl_data);

				}

///////////////////////////////////////////////////////////////////////////////////////////////////
//���䤤��碌��
				$tmpl_data = str_replace("%COMPANY_INFO%","�����䤤��碌��\r\n".COMPANY_INFO,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//�����ͤΤ�̾��
				$tmpl_data = str_replace("%CUST_NAME%",$fetchCUST[0]['LAST_NAME']." ".$fetchCUST[0]['FIRST_NAME'],$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//����������

				// ��������
					for($i = 0, $items = ""; $i < count($fetchPerItem); $i++){
						$items .= "�����ֹ桧".$fetchPerItem[$i]['PART_NO']."\n";
						$items .= "����̾��".$fetchPerItem[$i]['PRODUCT_NAME']."\n";
						$items .= "\\".number_format($fetchPerItem[$i]['SELLING_PRICE'])."\t";
						$items .= "���̡�".$fetchPerItem[$i]['QUANTITY']."\n\n";
					}

				//��ʧ����ˡ
				switch($fetchCUST[0]['PAYMENT_TYPE']):
					case 1:
						$payment_method = "���쥸�åȥ�����";
						break;
					case 2:
						$payment_method = "��Կ���";
						break;
					case 3:
						$payment_method = "�����";
						break;
					case 4:
						$payment_method = "����ӥ˷��";
						break;
					case 5:
						$payment_method = "͹�ؿ���";
						break;
				endswitch;

				//��������
					$buy_date = $fetchCUST[0]['TYPE_ORDER'];

				//���������
					$sum_price = number_format($fetchCUST[0]['SUM_PRICE']);

				//������
					$deli_price = number_format($fetchCUST[0]['SHIPPING_AMOUNT']);

				//����ʧ���
					$total_price = number_format($fetchCUST[0]['TOTAL_PRICE']);

				//�����
					$daibiki_price = ($fetchCUST[0]['PAYMENT_TYPE'] == 3)?"\r\n������������\\".number_format($fetchCUST[0]['DAIBIKI_AMOUNT'])."-":"";

$items_data = "================================================================
�������ֹ�  ��{$target_order_id}
����������  ��{$buy_date}
������ʧ��ˡ��{$payment_method}
---------------------------------------------------------------

�����������ʡ�
{$items}
---------------------------------------------------------------

����������ۡ�\\{$sum_price}-���ǹ���
��������������\\{$deli_price}{$daibiki_price}
������ʧ��ۡ�\\{$total_price}-

===============================================================
";

				$tmpl_data = str_replace("%ITEMS%",$items_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//�����Ծ���

$buyer_data = "�������Ծ���
================================================================
̾��    ��".$fetchCUST[0]['LAST_NAME']." ".$fetchCUST[0]['FIRST_NAME']."
MAIL    ��".$fetchCUST[0]['EMAIL']."
͹���ֹ桧".$fetchCUST[0]['ZIP_CD1']."-".$fetchCUST[0]['ZIP_CD2']."
����    ��".$shipping_list[$fetchCUST[0]['STATE']]['pref']." ".$fetchCUST[0]['ADDRESS1']." ".$fetchCUST[0]['ADDRESS2']."
�����ֹ桧".$fetchCUST[0]['TEL1']."-".$fetchCUST[0]['TEL2']."-".$fetchCUST[0]['TEL3']."
================================================================
";
				$tmpl_data = str_replace("%BUYER%",$buyer_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//���Ϥ������

$deli_data = "�����Ϥ������
================================================================
̾��    ��".$fetchCUST[0]['DELI_LAST_NAME']." ".$fetchCUST[0]['DELI_FIRST_NAME']."
͹���ֹ桧".$fetchCUST[0]['DELI_ZIP_CD1']."-".$fetchCUST[0]['DELI_ZIP_CD2']."
����    ��".$shipping_list[$fetchCUST[0]['DELI_STATE']]['pref']." ".$fetchCUST[0]['DELI_ADDRESS1']." ".$fetchCUST[0]['DELI_ADDRESS2']."
�����ֹ桧".$fetchCUST[0]['DELI_TEL1']."-".$fetchCUST[0]['DELI_TEL2']."-".$fetchCUST[0]['DELI_TEL3']."
================================================================
";

				$tmpl_data = str_replace("%DELI%",$deli_data,$tmpl_data);

///////////////////////////////////////////////////////////////////////////////////////////////////
//������

$deli_data = "��������
================================================================
".$fetchCUST[0]['REMARKS']."
================================================================
";

				$tmpl_data = str_replace("%REMARKS%",$deli_data,$tmpl_data);

?>