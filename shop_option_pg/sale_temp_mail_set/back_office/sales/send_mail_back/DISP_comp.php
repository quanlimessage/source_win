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

</p>

<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td class="other-td" align="center">
		<?php
			//���顼��ȯ�����Ƥ��ʤ����
			if(!$err_mes){
			?>
			<p align=center style="line-height:160%;text-align:center;">
			�������������ޤ�����
			</p>
						<?php
			}else{
			//���顼��ȯ�����Ƥ����票�顼���Ƥ�ɽ������
			echo "<br><p style=\"color:#FF0000;\">".$err_mes."</p>";
			}?>
		</td>
	</tr>
</table>

<form action="../" method="post">
<input type="submit" value="������̲��̤����" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

</body>
</html>