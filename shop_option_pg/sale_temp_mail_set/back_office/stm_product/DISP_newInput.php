<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
View��������Ͽ����ɽ��

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
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

#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿ͭ�����¤����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,false,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<script type="text/javascript" src="inputcheck.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script src="../tag_pg/cms.js" type="text/javascript"></script>
<script src="../actchange.js" type="text/javascript"></script>

<script type="text/javascript" src="../jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.upload-1.0.2.js"></script>
<script type="text/javascript" src="./uploadcheck.js"></script>

</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort<?php echo (STM_SORT_TYPE == 1)?"":"2";?>.php" method="post">
		<input type="submit" value="�¤��ؤ���Ԥ�" style="width:150px;">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo STM_TITLE;?>��������Ͽ</p>
<p class="explanation" style="width:600px;">
	�����ߤΥǡ������Ƥ����ɽ������Ƥ��ޤ���<br>
	�����Ƥ��Խ����������Ͼ�񤭤򤷤ơֹ�������פ򥯥�å����Ƥ���������<br>
	���ƥ�ץ졼�Ȥ������������ǡ������ߤ���ϡ������Ρ�%�ۤ˰Ϥޤ줿ʸ����������������ս������Ƥ���������<br>
	<br>
	<table border="1">
		<tr><td width="20%" align="center" nowrap>Ž���դ�ʸ��</td><td width="80%">����</td></tr>
		<tr><td nowrap align="center">%BANK_INFO%</td>		<td>��Ԥο��������</td></tr>
		<tr><td nowrap align="center">%COMPANY_INFO%</td>	<td>���䤤��碌��</td></tr>
		<tr><td nowrap align="center">%CUST_NAME%</td>		<td>�����ͤΤ�̾��</td></tr>

		<tr><td nowrap align="center">%ORDER_TIME%</td>		<td>��������</td></tr>
		<tr><td nowrap align="center">%ORDER_ID%</td>		<td>�����ֹ�</td></tr>
		<tr><td nowrap align="center">%SUM_PRICE%</td>		<td>��������ۡʾ��ס�</td></tr>
		<tr><td nowrap align="center">%PAY_TYPE%</td>		<td>����ʧ��ˡ</td></tr>

		<tr><td nowrap align="center">%SHIPPING_AMOUNT%</td>	<td>������</td></tr>
		<tr><td nowrap align="center">%DAIBIKI_AMOUNT%</td>	<td>�����</td></tr>
		<tr><td nowrap align="center">%TOTAL_PRICE%</td>	<td>����ʧ��ۡ����������פʤ�������碌����׶�ۡ�</td></tr>
		<tr><td nowrap align="center">%ITEMS%</td>		<td>���������ʤΡھ����ֹ�ۡھ���̾�ۡ�ñ���ۡڿ��̡ۤ�ɽ�����ޤ���</td></tr>
		<tr><td nowrap align="center">%ITEM_NAME%</td>		<td>���������ʤΡھ���̾�ۤΤߤ�ɽ�����ޤ���</td></tr>

		<tr><td nowrap align="center">%BUYER%</td>		<td>�����Ծ���Ρ�̾���ۡ�MAIL�ۡ�͹���ֹ�ۡڽ���ۡ������ֹ�ۤ�ɽ�����ޤ���</td></tr>
		<tr><td nowrap align="center">%DELI%</td>		<td>���Ϥ������Ρ�̾���ۡ�͹���ֹ�ۡڽ���ۡ������ֹ�ۤ�ɽ�����ޤ���</td></tr>
		<tr><td nowrap align="center">%REMARKS%</td>		<td>������</td></tr>

	</table>
</p>

<form name="new_regist" action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">��������Ͽ</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">�����ȥ롧</th>
		<td class="other-td">
		<input name="title" type="text" value="<?php echo $title;?>" size="60" maxlength="125" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">��̾��</th>
		<td class="other-td">
		<input name="subject" type="text" value="<?php echo $subject;?>" size="150" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">��ʸ��</th>
		<td class="other-td">

		<textarea name="content" cols="120" rows="40" ><?php echo $content;?></textarea>
		</td>
	</tr>
</table>

<input type="submit" value="�嵭���Ƥ���Ͽ����" style="width:150px;margin-top:1em;" onClick="chgsubmit();return confirm_message(this.form);">
<input type="hidden" name="act" value="completion">
<input type="hidden" name="regist_type" value="new">

</form>
<br>
<form action="./" method="post">
	<input type="submit" value="�ꥹ�Ȳ��̤����" style="width:150px;">
</form>

<?php

//�ܥ����ն��ɽ������
cp_disp($layer_free,"0","0");

?>

</body>
</html>