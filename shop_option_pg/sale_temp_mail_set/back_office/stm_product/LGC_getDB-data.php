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
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
}

#--------------------------------------------------------------------------------
# ���򤵤줿����action��$_POST["act"]�ˤˤ��ȯ�Ԥ���ӣѣ̤�ʬ��
#--------------------------------------------------------------------------------
switch($_POST["act"]):
case "update":case "copy";
///////////////////////////////////////////
// �����ؼ��Τ��ä����������ǡ����μ���

	// POST�ǡ����μ������ȶ��̤�ʸ�������
	extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// �оݵ���ID�ǡ����Υ����å�
	if(!ereg("^([0-9]{10,})-([0-9]{6})$",$res_id)||empty($res_id)){
		die("��̿Ū���顼�������ʽ����ǡ�������������ޤ����ΤǶ�����λ���ޤ���<br>{$res_id}");
	}

	$sql = "
	SELECT
		*
	FROM
		".STM_PRODUCT_LST."
	WHERE
		(RES_ID = '$res_id')
	AND
		(DEL_FLG = '0')
	";

	break;
default:
///////////////////////////////////////////
// �����ꥹ�Ȱ����ѥǡ����μ�����

	// POST�ǡ����μ������ȶ��̤�ʸ�������
	if($_POST)extract(utilLib::getRequestParams("post",array(8,7,1,4)));

	// GET�ǡ����μ������ȶ��̤�ʸ�������
	if($_GET)extract(utilLib::getRequestParams("get",array(8,7,1,4)));

		// ����������ǽ���Τ���ѥ�᡼�����ϥǥ����ɤ���
	$p  = urldecode($p);
	$ca = urldecode($ca);

	$res_id = urldecode($res_id);

	#------------------------------------------------------------------------
	# �ڡ�������
	# �ڡ������ܻ��ˤ���ʥѥ�᡼�������դ��ʤ�����
	# (���ƥ��꡼���������Ƥʤ�����?ca=&p=)
	# �������ѥ�᡼����������å����ƥ�󥯥ѥ�᡼���������ꤹ��
	#------------------------------------------------------------------------
	$param="";
	if(!empty($p) && !empty($ca)){
		$param="?p=".urlencode($p)."&ca=".urlencode($ca);
	}elseif(!empty($p) && empty($ca)){
		$param="?p=".urlencode($p);
	}elseif( empty($p) && !empty($ca) ){
		$param="?ca=".urlencode($ca);
	}

	// �ڡ����ֹ������(GET�����ǡ������ʤ����1�򥻥å�)
	if(empty($p) or !is_numeric($p))$p=1;

	// ����ɽ���ѥǡ����μ����ʥꥹ�Ƚ��֤�����ե�����˽�����

	// ��г��ϰ��֤λ���
	$st = ($p-1) * DISP_MAXROW_BACK;

	$sql = "
	SELECT
		RES_ID,TITLE,CONTENT,
		YEAR(DISP_DATE) AS Y,
		MONTH(DISP_DATE) AS M,
		DAYOFMONTH(DISP_DATE) AS D,
		VIEW_ORDER,DISPLAY_FLG
	FROM
		".STM_PRODUCT_LST."
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";

	$fetchCNT = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	$sql .= "
	LIMIT
		".$st.",".DISP_MAXROW_BACK."
	";

endswitch;

// �ӣѣ̤�¹�
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

?>