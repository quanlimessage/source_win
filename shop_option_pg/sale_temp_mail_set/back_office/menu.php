<?php
/*******************************************************************************

	��˥塼����

*******************************************************************************/
session_start();
require_once("../common/INI_config.php");		// �����������
require_once("../common/INI_ShopConfig.php");	// ����å����������
require_once("util_lib.php");		// ���ѽ������饹�饤�֥��

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ./err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("HTTP/1.0 404 Not Found");exit();
}

#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?php echo BO_TITLE;?></title>
<link href="for_bkmanu.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
<form action="./" method="post" target="_parent">
	<input name="submit" type="submit" style="width:150px;" value="�������̥ȥåפ�">
  </form>
</div>
<p><strong><font color="#666666">���������򤷤Ƥ�������</font></strong></p>
<font color="#FFFFFF">
<!--��˥塼�ơ��֥�-->
</font>

<div class="largespace"></div>
<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			�� ���ʴ���
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="product/" target="main">���ʤ���Ͽ������</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			���ʤο�����Ͽ����¸���ʥǡ����ι�����Ԥ��ޤ���<br>
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="sort/" target="main">���ʤ��¤��ؤ�</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			��������ڡ���(�����ڡ���)�Ǥ�ɽ�����֤����ꤷ�ޤ���
			</td>
		</tr>
	</table>
	<div class="largespace"></div>

	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			�� �ܵ�/������
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="user/" target="main">�桼��������</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�ܵҥǡ����θ�����������Ԥ��ޤ���<br>
			<br>�Ƹܵҥǡ������̤���ܵҾ�����Խ���Ԥ��ޤ���
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="sales/" target="main">������</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�������θ�����������Ԥ��ޤ���
			<br><br>
			��Ѿ��������������δ�����Ԥ��ޤ���
			</td>
		</tr>
		<!--<tr>
			<td class="space">&nbsp;</td>
		</tr>

		<tr>
			<td class="subtitle">
			��<a href="count/" target="main">��奫�����</a>

			</td>
		</tr>
		<tr>
			<td class="explanation">
			���ν��פ�ɽ�����ޤ���
			</td>
		</tr>-->
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="stm_product/" target="main">�����߳�ǧ��ȯ����ǧ�᡼��ƥ�ץ졼�ȴ���</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�����߳�ǧ��ȯ����ǧ�᡼��ƥ�ץ졼�Ȥδ�����Ԥ��ޤ���
			</td>
		</tr>

	</table>
	<div class="largespace"></div>
	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			�� �����Ծ������
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="./config/" target="main">�����Ծ���ι���</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�����ѥ᡼�륢�ɥ쥹������伫ư�ֿ��᡼��ؤ�ź�ղ�Ҿ���ʤɤ����ꤷ�ޤ���
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="./config_cnt/" target="main">���䤤��碌�ե�������<br>
			&nbsp;�����Ծ���ι���</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			����礻�ѥ᡼�륢�ɥ쥹�ʤɤ����ꤷ�ޤ���
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="./changepass/" target="main">����ID/�ѥ���ɤδ���</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			����ID/�ѥ���ɤ�������ޤ���
			</td>
		</tr>
	</table>

	<div class="largespace"></div>
	<table border="0" cellpadding="0" cellspacing="0" width="90%">
		<tr>
			<td class="menutitle">
			�� ������������
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="./log/" target="main">������������</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�������������򸫤�����Ǥ��ޤ���
			</td>
		</tr>
		<tr>
			<td class="space">&nbsp;</td>
		</tr>
		<tr>
			<td class="subtitle">
			��<a href="./fmanager/" target="main">�ե�����ޥ͡����㡼</a>
			</td>
		</tr>
		<tr>
			<td class="explanation">
			�����������ե����������Ԥ��ޤ���
			</td>
		</tr>
	</table>

<div align="center">
<form action="./" method="post" target="_parent">
<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
</form>
</div>
</body>
</html>