<?php
/*******************************************************************************
���ѥ���б�(���ƥ���)

	��Ѿ���ι�������

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

/////////////////////////////////
// ��Ѿ��󹹿�
if(!$_POST["payment_flg"]){
	$up_sql = "
	UPDATE
		".PURCHASE_LST."
	SET
		PAYMENT_FLG = '1',
		PAYMENT_DATE = NOW()
	";
}else{
	$up_sql = "
	UPDATE
		".PURCHASE_LST."
	SET
		PAYMENT_FLG = '0',
		PAYMENT_DATE = '0000-00-00 00:00:00'
	";
}

// WHERE��ʲ���Ω��
$up_sql .= "
WHERE
	(".PURCHASE_LST.".ORDER_ID = '".$_POST["target_order_id"]."')
AND
	(DEL_FLG = '0')
";

// SQL�¹�
$db_result = dbOpe::regist($up_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
if($db_result)die("�����˼��Ԥ��ޤ���<hr>{$db_result}");
?>