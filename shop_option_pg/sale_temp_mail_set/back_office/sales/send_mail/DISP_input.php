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
<form action="./" method="post" enctype="multipart/form-data" style="margin:0px;">
<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<th colspan="2" nowrap class="tdcolored">���᡼����������</th>
	</tr>
	<tr>
		<th width="15%" nowrap class="tdcolored">��̾��</th>
		<td class="other-td">
		<input name="subject" type="text" value="<?php echo ($subject)?$subject:$fetchTMPL[0]["SUBJECT"];?>" size="150" style="ime-mode:active">
		</td>
	</tr>
	<tr>
		<th nowrap class="tdcolored">��ʸ��</th>
		<td class="other-td">
		<textarea name="tmpl_data" cols="120" rows="40" ><?php echo $tmpl_data;?></textarea>

		</td>
	</tr>
</table>
<input type="submit" value="��ǧ���̤�" style="width:150px;margin-top:1em;">
<input type="hidden" name="action" value="confirm">
<input type="hidden" name="status" value="<?php echo $status;?>">
<input type="hidden" name="target_order_id" value="<?php echo $target_order_id;?>">
</form>

<form action="../" method="post">
<input type="submit" value="������̲��̤����" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

</body>
</html>