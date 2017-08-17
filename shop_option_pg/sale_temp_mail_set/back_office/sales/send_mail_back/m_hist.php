<?php
/*******************************************************************************
�᡼���������Ͽ��ɽ��

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

	//������Ͽ���������
		$sql = "
		SELECT
			*
		FROM
			MAIL_HIST_LST
		WHERE
			(ORDER_ID  = '$target_order_id')
		ORDER BY
			INS_DATE DESC
		";

		$fetchMH = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}

#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>������Ͽ</title>
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>

<p class="page_title">���������᡼��������Ͽ</p>
<p class="explanation">
�����ޤ�����줿�᡼������Ƥ�ɽ������ޤ���
</p>

<?php if(count($fetchMH) == 0):?>
<strong>������Ͽ������ޤ���</strong>
<br><br><br>
<?php else:?>

<table width="800" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="4" class="tdcolored">���᡼��������Ͽ</th>
	</tr>
	<tr>
		<th class="tdcolored" width="5%">������</th>
		<th class="tdcolored" width="10%">������</th>
		<th class="tdcolored" width="20%">��̾</th>
		<th class="tdcolored">��ʸ</th>
	</tr>
	<?php for($i=0;$i < count($fetchMH);$i++){?>
	<tr>
		<th class="other-td"><?php echo $fetchMH[$i]["INS_DATE"];?></th>
		<th class="other-td" ><?php echo $fetchMH[$i]["EMAIL"];?></th>
		<th class="other-td" ><?php echo $fetchMH[$i]["SUBJECT"];?></th>
		<th class="other-td" align="left">
			<span  style="overflow:auto;width:100%;height:400px;">
			<?php echo nl2br($fetchMH[$i]["CONTENT"]);?>
			</span>
		</th>
	</tr>
	<?php }?>
</table>
<?php endif;?>

</body>
</html>