<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
�ᥤ�󥳥�ȥ��顼

*******************************************************************************/
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("HTTP/1.0 404 Not Found"); exit();
}

// �����������������å��Υե饰
$accessChk = 1;

// ����ե���������̥饤�֥����ɤ߹���
require_once("../../../common/INI_config.php");		// �����������
require_once("../../../common/INI_ShopConfig.php");	// ����å����������
require_once("../../../common/INI_pref_list.php");		// ��ƻ�ܸ��������ǡ����������

require_once("dbOpe.php");					// DB���饹�饤�֥��
require_once("util_lib.php");				// ���ѽ������饹�饤�֥��
require_once('imgOpe.php');					// �������åץ��ɥ��饹�饤�֥��

	// POST�ǡ����μ������ȶ��̤�ʸ��������ʥ᡼�������ѡ�
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}

#===============================================================================
# $_POST["action"]�����Ƥˤ�������ʬ��
#===============================================================================
switch($_POST["action"]):
case "completion":
//	������λ���̽���

	include("LGC_regist.php");//�������Ƥ���Ͽ
	include("LGC_sendmail.php");//�᡼�����������
	include("DISP_comp.php");

	break;
case "confirm":
//////////////////////////////////////////////////
//	��ǧ���̽���

	include("DISP_confirm.php");

	break;

case "edit":
//////////////////////////////////////////////////
//	�������̽���

	include("DISP_input.php");

	break;

default:
/////////////////////////////////////////////////
// DB�����������������ϲ��̤�ɽ������

	include("LGC_getDB-data.php");
	include("DISP_input.php");

endswitch;
?>