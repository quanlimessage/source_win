<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
Logic��DB��Ͽ����������

*******************************************************************************/

#=================================================================================
# �����������������å���ľ�ܤ��Υե�����˥���������������
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// �����������������å���ľ�ܤ��Υե�����˥���������������
if(!$accessChk){
	header("Location: ../index.php");exit();
}

// MySQL�ˤ����ƴ�ʸ���򥨥������פ��Ƥ���
$reg_subject = utilLib::strRep($subject,5);//�᡼�����������뤿���̤��ѿ�����Ͽ�Ѥ˽������Ƥ���
$reg_tmpl_data = utilLib::strRep($tmpl_data,5);//�᡼�����������뤿���̤��ѿ�����Ͽ�Ѥ˽������Ƥ���

#==================================================================
# �᡼����ۿ���Ͽ��Ĥ�
#==================================================================

		// �Ŀ;��󡢸�Ǵ�Ϣ�դ��򤷤䤹�������CUSTOMER_ID��ǡ����١����˻Ĥ�
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

		//��ˡ����ʣɣĤ�ȯ��
			$res_id = $makeID();

		$mhl_sql = "
		INSERT INTO MAIL_HIST_LST
			SET
				RES_ID = '$res_id',
				ORDER_ID = '$target_order_id',
				CUSTOMER_ID = '".$fetchCUST[0][CUSTOMER_ID]."',
				EMAIL = '".$fetchCUST[0][EMAIL]."',
				SUBJECT = '$reg_subject',
				CONTENT = '$reg_tmpl_data',
				INS_DATE = NOW(),
				UPD_DATE = NOW(),
				DEL_FLG = 0
		";

// �ӣѣ̤�¹�
if(!empty($mhl_sql)){
	$db_result = dbOpe::regist($mhl_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB��Ͽ���Ԥ��ޤ���<hr>{$db_result}");

}

?>