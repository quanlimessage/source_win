<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
�ᥤ�󥳥�ȥ��顼

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}

// �����������������å��Υե饰
$accessChk = 1;

// ����ե���������̥饤�֥����ɤ߹���
require_once("../../common/config_sale_tmpl_mail.php");	// �����������
require_once("../tag_pg/LGC_color_table.php");	// ���������Υץ����

require_once("dbOpe.php");					// DB���饹�饤�֥��
require_once("util_lib.php");				// ���ѽ������饹�饤�֥��
//require_once('imgOpe.php');					// �������åץ��ɥ��饹�饤�֥��
require_once('../../common/imgOpe2.php');	// �������åץ��ɥ��饹�饤�֥��(gif��png�б�)

#===============================================================================
# $_POST["act"]�����Ƥˤ�������ʬ��
#===============================================================================
switch($_POST["act"]):
case "completion":

	// �ǡ�����Ͽ������Ԥ����������
	include("LGC_regist.php");
	header("Location: ./?p=".urlencode($p));

	break;
case "update":case "copy":
//////////////////////////////////////////////////
//	�������̽���

	include("LGC_getDB-data.php");
	include("DISP_update.php");

	break;
case "new_entry":
//////////////////////////////////////////////////
//	������Ͽ���̽���

	include("DISP_newInput.php");

	break;
case "display_change":case "del_data":
/////////////////////////////////////////////////
//	�оݥǡ�����ɽ������ɽ�������� OR ���

	include("LGC_del_and_dispchng.php");
	header("Location: ./?p=".urlencode($p));

default:
/////////////////////////////////////////////////
// DB������������������ɽ������

	include("LGC_getDB-data.php");
	include("DISP_listview.php");

endswitch;
?>