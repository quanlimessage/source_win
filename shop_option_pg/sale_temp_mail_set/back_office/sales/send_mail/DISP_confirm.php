<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
View����������ɽ��

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
<link href="../../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br><br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../../main.php" method="post">
			<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
		</form>
		</td>

	</tr>
</table>

<p class="page_title">���������᡼�������Խ�����</p>
<p class="explanation">
���᡼�������Υǡ������Ƥ����ɽ������Ƥ��ޤ���<br>

</p>

<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">���᡼����������</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">��̾��</th>
		<td class="other-td">
		<?php echo $subject;?>
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">��ʸ��</th>
		<td class="other-td">
		<?php echo nl2br($tmpl_data);?>

		</td>
	</tr>
</table>

<form action="./" method="post" enctype="multipart/form-data" onSubmit="return confirm_message(this);" style="margin:0px;">
	<input type="submit" value="�����򤹤�" style="width:150px;margin-top:1em;">
	<input type="hidden" name="action" value="completion">
	<input type="hidden" name="status" value="<?php echo $status;?>">
	<input type="hidden" name="target_order_id" value="<?php echo $target_order_id;?>">

	<input type="hidden" name="subject" value="<?php echo $subject;?>">
	<input type="hidden" name="tmpl_data" value="<?php echo $tmpl_data;?>">

</form>

<form action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
	<input type="submit" value="���β��̤����" style="width:150px;margin-top:1em;">
	<input type="hidden" name="action" value="edit">
	<input type="hidden" name="status" value="<?php echo $status;?>">
	<input type="hidden" name="target_order_id" value="<?php echo $target_order_id;?>">

	<input type="hidden" name="subject" value="<?php echo $subject;?>">
	<input type="hidden" name="tmpl_data" value="<?php echo $tmpl_data;?>">

</form>

<form action="../" method="post">
<input type="submit" value="������̲��̤����" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

</body>
</html>