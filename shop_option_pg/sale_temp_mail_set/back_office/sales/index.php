<?php
/*******************************************************************************
���ѥ���б�

	������󡧥ᥤ�󥳥�ȥ��顼

*******************************************************************************/
// ������������Ѥ˥��å�����������
session_start();

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

// �����������������å��Υե饰
$injustice_access_chk = 1;

// ����ե���������̥饤�֥����ɤ߹���
require_once("../../common/INI_config.php");		// �����������
require_once("../../common/INI_ShopConfig.php");	// ����å����������
require_once("dbOpe.php");							// �ģ����饹�饤�֥��
require_once("util_lib.php");						// ���ѽ������饹�饤�֥��
require_once("../../common/INI_pref_list.php");		// ��ƻ�ܸ��������ǡ����������

#===============================================================================
# $_POST["regist_type"]�����Ƥˤ�깹��������ʬ��
#===============================================================================
////////////////////////////////
// ��ѡ���������
switch ($_POST["regist_type"]):
	case "change_payment_flg":
			// ��ѥե饰�ѹ�����
			include("LGC_change_payment_flg.php");
		break;
	case "change_shipped_flg":
			// �����ե饰�ѹ�����
			include("LGC_change_shipped_flg.php");
		break;
	case "add_config_memo":
			// ������⹹������
			include("LGC_config_memo.php");
		break;
endswitch;

#===============================================================================
# $_POST["status"]�����Ƥˤ����Ͻ�����ʬ��
#===============================================================================
if($_GET["status"])$_POST["status"] = $_GET["status"];

switch($_POST["status"]):
case "disp_details":
////////////////////////////////
// ��ʸ����ܺ٤�ɽ��

	// ��ʸ����ܺپ���Υǡ�������
	include("LGC_getDB-data.php");
	include("DISP_purchase_Details.php");

	break;
case "search_result":

	// ���ꤵ�줿�������򸵤˸ܵҾ��������������Ʒ��ɽ��
	include("LGC_getDB-data.php");
	include("DISP_serch_result.php");

	break;
default:

	#-------------------------------------------------------------------
	# ���ƤΥ���������
	# ��ʸ�������̤�ɽ������ʸ����ꥹ�Ȥ�ģ¡�PURCHASE_LST�ˤ�������
	#	�����������̤Υ��å������˴�
	#-------------------------------------------------------------------
	$_SESSION["search_cond"] = array();
	include("DISP_serch_input.php");

endswitch;
?>