<?php
/*******************************************************************************
���ѥ���б�(���ƥ���+���֥��ƥ���)

���夲����
	View��������������̡ʺǽ��ɽ�������

*******************************************************************************/

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
<link href="../for_bk.css" rel="stylesheet" type="text/css">
<script language="javascript" src="inputChk.js"></script>
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
</form>
<p class="page_title">����������ʸ���󸡺�</p>
<p class="explanation">
����������������˹�碌�ƳƸ���������ꤷ�Ƥ���������<br>
��������꤬�ʤ���������ǡ�������Ф��ޤ���
</p>
<form action="./" method="post" style="margin:0px;" onSubmit="return inputChk(this);">
<table width="600" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" class="tdcolored">����ʸ���󸡺�</th>
	</tr>
	<tr>
		<th class="tdcolored">��ʸ����</th>
		<td class="other-td">
		<!-- ��������ǯ���� -->
		<select name="start_y">
		<option value="">--</option>
		<?php for($i=2010;$i<=(date("Y")+5);$i++)echo "<option value=\"{$i}\">{$i}</option>\n";?>
		</select>ǯ
		<select name="start_m">
		<option value="">--</option>
		<?php for($i=1;$i<=12;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>��
		<select name="start_d">
		<option value="">--</option>
		<?php for($i=1;$i<=31;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>��
		&nbsp;��&nbsp;
		<!-- ������λǯ���� -->
		<select name="end_y">
		<option value="">--</option>
		<?php for($i=2010;$i<=(date("Y")+5);$i++)echo "<option value=\"{$i}\">{$i}</option>\n";?>
		</select>ǯ
		<select name="end_m">
		<option value="">--</option>
		<?php for($i=1;$i<=12;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>��
		<select name="end_d">
		<option value="">--</option>
		<?php for($i=1;$i<=31;$i++)echo "<option value=\"".sprintf("%02d",$i)."\">{$i}</option>\n";?>
		</select>��
		</td>
	</tr>
	<tr>
		<th class="tdcolored">��Ѽ��̡�</th>
		<td class="other-td">
		<!--<input type="radio" name="search_payment_type" id="spt1" value="1"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 1)?" checked":"";?>>
		<label for="spt1">���쥸�åȷ��</label>&nbsp;&nbsp; -->
		<input type="radio" name="search_payment_type" id="spt2" value="2"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 2)?" checked":"";?>>
		<label for="spt2">��Կ���</label>&nbsp;&nbsp;
		<input type="radio" name="search_payment_type" id="spt3" value="3"<?php echo ($_SESSION["search_cond"]["search_payment_type"] == 3)?" checked":"";?>>
		<label for="spt3">�����</label>&nbsp;&nbsp;
		<!--

		<input type="radio" name="search_payment_type" id="spt4" value="4"<?php //echo ($_SESSION["search_cond"]["search_payment_type"] == 4)?" checked":"";?>>
		<label for="spt4">����ӥ˷��</label>
		-->
		</td>
	</tr>
	<tr>
		<th class="tdcolored">�����ֹ桧</th>
		<td class="other-td">
			<input type="text" size="50" maxlength="20" name="buy_item_num" style="ime-mode:active;" value="<?php echo ($_SESSION["search_cond"]["buy_item_num"])?$_SESSION["search_cond"]["buy_item_num"]:"";?>">
		</td>
	</tr>

	<tr>
		<th class="tdcolored">����̾��</th>
		<td class="other-td">
			<input type="text" size="50" maxlength="20" name="buy_item" style="ime-mode:active;" value="<?php echo ($_SESSION["search_cond"]["buy_item"])?$_SESSION["search_cond"]["buy_item"]:"";?>">
		</td>
	</tr>

	<tr>
		<th class="tdcolored" width="150">�����ֹ�(�����ֹ�)��</th>
		<td class="other-td"><input type="text" name="search_order_id" value="" size="30" style="ime-mode:disabled"></td>
	</tr>
	<tr>
		<th class="tdcolored">̾��(�եꥬ��)��<br>�����Ǥ��</th>
		<td class="other-td">
		��&nbsp;<input type="text" name="search_kana_1" value="" size="15"><br>
		̾&nbsp;<input type="text" name="search_kana_2" value="" size="15">
		</td>
	</tr>
	<tr>
		<th class="tdcolored">̾��(����)��<br>�����Ǥ��</th>
		<td class="other-td">
		��&nbsp;<input type="text" name="search_name_1" value="" size="15"><br>
		̾&nbsp;<input type="text" name="search_name_2" value="" size="15">
		</td>
	</tr>

	<tr>
		<th class="tdcolored">���������塧</th>
		<td class="other-td">
		<input type="text" size="10" maxlength="20" name="start_sum_price" style="ime-mode:disabled" value="<?php echo ($_SESSION["search_cond"]["start_sum_price"])?$_SESSION["search_cond"]["start_sum_price"]:"";?>">&nbsp;�߰ʾ�<br>
		<input type="text" size="10" maxlength="20" name="end_sum_price" style="ime-mode:disabled" value="<?php echo ($_SESSION["search_cond"]["end_sum_price"])?$_SESSION["search_cond"]["end_sum_price"]:"";?>">&nbsp;��̤��
	</tr>
	<tr>
		<th class="tdcolored">��Ѿ�����</th>
		<td class="other-td">
		<input type="radio" name="payment_flg" value="0" id="payment_flg0">&nbsp;<label for="payment_flg0">̤���</label><br>
		<input type="radio" name="payment_flg" value="1" id="payment_flg1">&nbsp;<label for="payment_flg1">��ѺѤ�</label><br>
		<input type="radio" name="payment_flg" value="2" id="payment_flg2">&nbsp;<label for="payment_flg2">��Ѽ���</label>
	</tr>
	<tr>
		<th class="tdcolored">����������</th>
		<td class="other-td">
		<input type="radio" name="shipped_flg" value="0" id="shipped_flg0">&nbsp;<label for="shipped_flg0">̤����</label><br>
		<input type="radio" name="shipped_flg" value="1" id="shipped_flg1">&nbsp;<label for="shipped_flg1">�����Ѥ�</label>
	</tr>
</table>
<input type="submit" value="��������" style="width:150px; ">
<input type="hidden" name="status" value="search_result">
</form>
</body>
</html>
