<?php
/*******************************************************************************
���ѥ���б�

	������󡧾ܺٹ��������ɽ��

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
<script language="javascript" src="../common.js"></script>
</head>
<body>
<form action="../main.php" method="post">
<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
</form>
<p class="page_title">�������������ܺ�</p>
<p class="explanation">
����ѡ����������ξ����ַ�ѡס���������Υ���å����ڤ��ؤ��뤳�Ȥ�����ޤ���<br>
����ѡ���������򹹿���������եǡ����⹹������ޤ���
</p>
<table width="500" border="0" cellpadding="5" cellspacing="2" style="margin-top:1em;">
	<tr>
		<th colspan="2" align="center" valign="middle" nowrap class="tdcolored">�������ܺ�</th>
	</tr>
	<tr>
		<th width="20%" align="right" valign="middle" nowrap class="tdcolored">��ʸ�ֹ桧</th>
		<td valign="middle" class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["ORDER_ID"];?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">��Ѿ�����</th>
		<form method="post" action="" style="margin:0px;">
		<td valign="middle" class="other-td">&nbsp;
		<?php if($fetchOrderCust[0]["PAYMENT_FLG"] == 1):?>
		<input type="submit" value="��Ѵ�λ" style="width:60px;" onClick="return confirm('̤��Ѥˤ��ޤ����������Ǥ�����');">
		<?php elseif($fetchOrderCust[0]["PAYMENT_FLG"] == 0):?>
		<input type="submit" value="̤���" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('��Ѵ�λ�ˤ��ޤ����������Ǥ�����');">
		<?php elseif($fetchOrderCust[0]["PAYMENT_FLG"] == 2):?>
		<font style="font-size:12px;color:#FF0000;">��Ѽ���</font>
		<?php endif;?>
		</td>
		<input type="hidden" name="payment_flg" value="<?php echo $fetchOrderCust[0]["PAYMENT_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_payment_flg">
		<input type="hidden" name="status" value="disp_details">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchOrderCust[0]["ORDER_ID"];?>">
		</form>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">����������</th>
		<form method="post" action="" style="margin:0px;">
		<td valign="middle" class="other-td">&nbsp;
		<?php if($fetchOrderCust[0]["SHIPPED_FLG"] == 1):?>
		<input type="submit" value="������" style="width:60px;" onClick="return confirm('̤�����˽������ޤ����������Ǥ�����');">
		<?php else:?>
		<input type="submit" value="̤����" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('������λ�ˤ��ޤ����������Ǥ�����');">
		<?php endif;?>
		</td>
		<input type="hidden" name="shipped_flg" value="<?php echo $fetchOrderCust[0]["SHIPPED_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_shipped_flg">
		<input type="hidden" name="status" value="disp_details">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchOrderCust[0]["ORDER_ID"];?>">
		</form>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">��ʧ����ˡ��</th>

  <td valign="middle" class="other-td">&nbsp;
   <?php switch($fetchOrderCust[0]["PAYMENT_TYPE"]){case 1:echo "���쥸�åȷ��";break;case 2:echo "��Կ������";break;case 3:echo "��������";break;case 5:echo "͹�ؿ��ط��";break;}?>
  </td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">��ʸ����</th>
		<td valign="middle" class="other-td">&nbsp;<?php if($fetchOrderCust[0]["ORDER_DATE"] &&($fetchOrderCust[0]["ORDER_DATE"] != "0000-00-00 00:00:00"))echo $fetchOrderCust[0]["ORDER_DATE"];?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">��ʧ����</th>
		<td valign="middle" class="other-td">&nbsp;<?php if($fetchOrderCust[0]["PAYMENT_DATE"] &&($fetchOrderCust[0]["PAYMENT_DATE"] != "0000-00-00 00:00:00"))echo $fetchOrderCust[0]["PAYMENT_DATE"];?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">��������</th>
		<td valign="middle" class="other-td">&nbsp;<?php if($fetchOrderCust[0]["SHIPPED_DAY"] && ($fetchOrderCust[0]["SHIPPED_DAY"] != "0000-00-00 00:00:00"))echo $fetchOrderCust[0]["SHIPPED_DAY"];?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">������ۡ�</th>
		<td valign="middle" class="other-td">&nbsp;<?php echo number_format($fetchOrderCust[0]["TOTAL_PRICE"]);?><?php echo ($fetchOrderCust[0]["PAYMENT_TYPE"] == 3)?"��������������\\".$fetchOrderCust[0]["DAIBIKI_AMOUNT"]."�ˤ�ޤߤޤ�":"";?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">������</th>
		<td valign="middle" class="other-td">&nbsp;<?php echo number_format($fetchOrderCust[0]["SHIPPING_AMOUNT"]);?></td>
	</tr>
	<tr>
		<th align="right" valign="middle" nowrap class="tdcolored">���͡�</th>
		<td valign="middle" class="other-td"><?php echo ($fetchOrderCust[0]["REMARKS"])?nl2br($fetchOrderCust[0]["REMARKS"]):"&nbsp;";?></td>
	</tr>
</table>

<!-- ������ -->
<table width="500" border="0" cellpadding="5" cellspacing="2" style="margin-top:1em;">
	<tr valign="middle">
		<th colspan="2" align="center" nowrap class="tdcolored">��������</th>
	</tr>
	<tr valign="middle">
		<th width="20%" align="right" nowrap class="tdcolored">̾����</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["DELI_LAST_NAME"]."&nbsp;".$fetchOrderCust[0]["DELI_FIRST_NAME"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">�����ֹ桧</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["DELI_TEL1"]."-".$fetchOrderCust[0]["DELI_TEL2"]."-".$fetchOrderCust[0]["DELI_TEL3"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">͹���ֹ桧</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["DELI_ZIP_CD1"]."-".$fetchOrderCust[0]["DELI_ZIP_CD2"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">��ƻ�ܸ���</th>
		<td class="other-td">&nbsp;<?php $sid = $fetchOrderCust[0]["DELI_STATE"];echo $shipping_list[$sid]['pref'];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">���꣱��</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["DELI_ADDRESS1"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">���ꣲ��</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["DELI_ADDRESS2"];?></td>
	</tr>
	<?php if($fetchOrderCust[0]["RESER_FLG"] == 1){?>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">ͽ���ʤ�������ˡ��</th>
		<td class="other-td">&nbsp;<?php echo ($fetchOrderCust[0]["RESER_STYLE"] != 2)?"�ޤȤ��ȯ��":"����ȯ��";?></td>
	</tr>
	<?php }?>
</table>

<!-- ������ -->
<table width="500" border="0" cellpadding="5" cellspacing="2" style="margin-top:1em;">
	<tr valign="middle">
		<th colspan="2" align="center" nowrap class="tdcolored">��������</th>
	</tr>
	<tr valign="middle">
		<th width="20%" align="right" nowrap class="tdcolored">̾����</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["LAST_NAME"]."&nbsp;".$fetchOrderCust[0]["FIRST_NAME"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">�����ֹ桧</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["TEL1"]."-".$fetchOrderCust[0]["TEL2"]."-".$fetchOrderCust[0]["TEL3"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">͹���ֹ桧</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["ZIP_CD1"]."-".$fetchOrderCust[0]["ZIP_CD2"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">��ƻ�ܸ���</th>
		<td class="other-td">&nbsp;<?php $sid = $fetchOrderCust[0]["STATE"];echo $shipping_list[$sid]['pref'];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">���꣱��</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["ADDRESS1"];?></td>
	</tr>
	<tr valign="middle">
		<th align="right" nowrap class="tdcolored">���ꣲ��</th>
		<td class="other-td">&nbsp;<?php echo $fetchOrderCust[0]["ADDRESS2"];?></td>
	</tr>
</table>
<p class="explanation">
�����ʤ˸��ߤδ���������Ƥ�ɽ������Ƥ��ޤ���<br>
�����Ƥ��Խ����������ˤϡ֥��������פ˥�����Ƥ����ϸ塢�����ƹ����פ򲡤��Ƥ���������
</p>
<form method="post" action="./" style="margin:0px;">
<table width="500" border="0" cellpadding="5" cellspacing="2">
	<tr>
		<th colspan="2" class="tdcolored">���������</th>
	</tr>
	<tr>
		<th width="20%" class="tdcolored">���ߤ�����</th>
		<td class="other-td"><?php echo ($fetchOrderCust[0]["CONFIG_MEMO"])?nl2br($fetchOrderCust[0]["CONFIG_MEMO"]):"&nbsp;";?></td>
	</tr>
	<tr>
		<th width="20%" class="tdcolored">���������</th>
		<td class="other-td">
		<p class="explanation" style="width:300px;">
		�����ߤΥ�⤬������ϡ����ɽ������Ƥ��ޤ���<br>
		������<strong>�ɲä������³��������</strong>���Ƥ���������<br>
		��<strong>��񤭤򤹤�ȸ��Υ�����ƤϹ���</strong>����ޤ���
		</p>
		<textarea name="config_memo" rows="5" cols="50"><?=$fetchOrderCust[0]["CONFIG_MEMO"];?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="other-td">
		<input type="submit" value="���ƹ���" style="width:150px;">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchOrderCust[0]["ORDER_ID"];?>">
		<input type="hidden" name="status" value="disp_details">
		<input type="hidden" name="regist_type" value="add_config_memo">
		</td>
	</tr>
</table>
</form>
<table width="500" border="0" cellpadding="5" cellspacing="2��" style="margin-top:1em;">
	<tr class="back2">
		<th colspan="6" nowrap class="tdcolored">����������</th>
	</tr>
	<tr class="tdcolored">
		<th width="20%" nowrap>�����ֹ�</th>
		<th width="40%" nowrap>����̾</th>
		<th width="15%" nowrap>ñ��</th>
		<th width="5%" nowrap>����</th>
	</tr>
	<?php for($i=0;$i<count($fetchPerItem);$i++):?>
	<tr valign="middle">
		<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" align="center"><?php echo $fetchPerItem[$i]["PART_NO"];?></td>
		<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" align="center"><?php echo $fetchPerItem[$i]["PRODUCT_NAME"];?></td>
		<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" align="center"><?php echo number_format($fetchPerItem[$i]["SELLING_PRICE"]);?></td>
		<td class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>" align="center"><?php echo $fetchPerItem[$i]["QUANTITY"];?></td>
	</tr>
	<?php endfor;?>
</table>
<input type="button" value="���β��̤����" style="width:150px;" onClick="javascript:PrintPage();">

<form action="./" method="post">
<input type="submit" value="������̲��̤����" style="width:150px;">
<input type="hidden" name="status" value="search_result">
</form>

<form action="./" method="post">
<input type="submit" value="��ʸ���������Ϥ�" style="width:150px;">
</form>
</body>
</html>
