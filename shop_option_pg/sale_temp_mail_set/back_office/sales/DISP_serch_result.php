<?php
/*******************************************************************************
���ѥ���б�

	������󡧻��긡�����Ǥθ������

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

$NEXT = $p + 1;

$PREVIOUS = $p - 1;
// CHECK ALL DATA
$TCNT = count($fetchPurchaseCNT);
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/SALES_MAXROW);

// SET DISPLAY

if($p > 1){
	$PREVIOUS_PAGE = "<a href='./?p={$PREVIOUS}&status=search_result'>����".SALES_MAXROW."���</a>";

}else{
	$PREVIOUS_PAGE = "";
}

if($TOTLE_PAGES > $p){
	$NEXT_PAGE = "<a href='./?p={$NEXT}&status=search_result'>����".SALES_MAXROW."���</a>";
}else{
	$NEXT_PAGE = "";
}

//�᡼��Υƥ�ץ졼�Ȱ�������������롣
	$sql_temp_mail = "
	SELECT
		*
	FROM
		STM_PRODUCT_LST
	WHERE
		(DEL_FLG = '0')
	ORDER BY
		VIEW_ORDER ASC
	";
	$fetchTM = dbOpe::fetch($sql_temp_mail,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

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
<p class="page_title">���������������</p>
<p class="explanation">
����ѡ����������ξ����ַ�ѡס���������Υ���å����ڤ��ؤ��뤳�Ȥ�����ޤ���<br>
����ѡ���������򹹿���������եǡ����⹹������ޤ���<br>
����ɽ���פ򥯥�å�����ȳ�������ξܺپ���ɽ������ޤ���
</p>
<?php if(count($fetchPurchase) == 0):?>
<strong>ɽ������ǡ���������ޤ���</strong>
<br><br><br>
<?php else:?>
<div>��������̡�<strong><?php echo count($fetchPurchaseCNT);?></strong>&nbsp;��</div><br>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?PHP echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?PHP echo $NEXT_PAGE;?></td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="2">
	<tr class="tdcolored">
		<th width="15%" nowrap>��ʸ�ֹ�</th>
		<th width="25%" nowrap>��̾</th>
		<th width="20%" nowrap>�᡼�륢�ɥ쥹</th>
		<th width="5%" nowrap>��Ѿ���</th>
		<th width="5%" nowrap>��������</th>
		<th width="10%" nowrap>����������</th>
		<th width="5%" nowrap>��ʧ��ˡ</th>
		<th width="10%" nowrap>�᡼��<br>����</th>
		<th width="5%" nowrap>��ʸ��</th>
		<th width="5%" nowrap>��ʧ��</th>
		<th width="5%" nowrap>������</th>
		<th width="10%" nowrap>�ܺ�ɽ��</th>
	</tr>
	<?php
	for($i=0;$i<count($fetchPurchase);$i++):?>
	<tr>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["ORDER_ID"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["LAST_NAME"]."&nbsp;".$fetchPurchase[$i]["FIRST_NAME"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php echo $fetchPurchase[$i]["EMAIL"];?></td>
		<form method="post" action="" style="margin:0px;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if($fetchPurchase[$i]["PAYMENT_FLG"] == 1):?>
		<input type="submit" value="��Ѵ�λ" style="width:60px;" onClick="return confirm('̤��Ѥˤ��ޤ����������Ǥ�����');">
		<?php elseif($fetchPurchase[$i]["PAYMENT_FLG"] == 0):?>
		<input type="submit" value="̤���" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('��Ѵ�λ�ˤ��ޤ����������Ǥ�����');">
		<?php elseif($fetchPurchase[$i]["PAYMENT_FLG"] == 2):?>
		<font style="font-size:12px;color:#FF0000;">��Ѽ���</font>
		<?php endif;?>
		</td>
		<input type="hidden" name="payment_flg" value="<?php echo $fetchPurchase[$i]["PAYMENT_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_payment_flg">
		<input type="hidden" name="status" value="search_result">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		</form>
		<form method="post" action="" style="margin:0px;">
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<?php if($fetchPurchase[$i]["SHIPPED_FLG"] == 1):?>
		<input type="submit" value="������" style="width:60px;" onClick="return confirm('̤�����˽������ޤ����������Ǥ�����');">
		<?php else:?>
		<input type="submit" value="̤����" style="background-color:#FF9900;border-color:#FF9900;width:60px;" onClick="return confirm('������λ�ˤ��ޤ����������Ǥ�����');">
		<?php endif;?>
		</td>
		<input type="hidden" name="shipped_flg" value="<?php echo $fetchPurchase[$i]["SHIPPED_FLG"];?>">
		<input type="hidden" name="regist_type" value="change_shipped_flg">
		<input type="hidden" name="status" value="search_result">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		</form>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">\<?php echo number_format($fetchPurchase[$i]["SUM_PRICE"]);?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>"><?php switch($fetchPurchase[$i]["PAYMENT_TYPE"]):case 1:echo "���쥸�å�";break;case 2:echo "��Կ���";break;case 3:echo "�����";break;endswitch;?></td>

		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
			<form method="post" action="./send_mail/" style="margin:0px;">
				<select name="send_mail_type">
					<?php for($k =0;$k < count($fetchTM);$k++){?>
					<option value="<?php echo $fetchTM[$k]['RES_ID'];?>"><?php echo $fetchTM[$k]['TITLE'];?></option>
					<?php }?>
				</select>
				<br>
				<input type="submit" name="submit" value="�᡼������" style="width:100px;">
				<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
				<input type="hidden" name="status" value="send_pay">
			</form>
			<br>
			<form method="post" action="./send_mail/m_hist.php" style="margin:0px;" target="_blank">
				<input type="submit" name="submit" value="������Ͽ" style="width:100px;">
				<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
			</form>

		</td>

		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["ORDER_DATE"] &&($fetchPurchase[$i]["ORDER_DATE"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["ORDER_DATE"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["PAYMENT_DATE"] &&($fetchPurchase[$i]["PAYMENT_DATE"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["PAYMENT_DATE"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">&nbsp;<?php if($fetchPurchase[$i]["SHIPPED_DAY"] &&($fetchPurchase[$i]["SHIPPED_DAY"] != "0000-00-00 00:00:00"))echo $fetchPurchase[$i]["SHIPPED_DAY"];?></td>
		<td align="center" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<form method="post" action="./" style="margin:0px;">
		<input type="submit" name="submit" value="ɽ��">
		<input type="hidden" name="target_order_id" value="<?php echo $fetchPurchase[$i]["ORDER_ID"];?>">
		<input type="hidden" name="status" value="disp_details">
		</form>
		</td>
	</tr>
	<?php if(!empty($fetchPurchase[$i]["CONFIG_MEMO"])):?>
	<tr>
		<td colspan="12" class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<strong>����ʸ�ֹ�<?php echo $fetchPurchase[$i]["ORDER_ID"];?>������</strong>(�ǽ�������<?php echo $fetchPurchase[$i]["UPD_DATE"];?>)<br>
		<?php echo nl2br($fetchPurchase[$i]["CONFIG_MEMO"]);?>
		</td>
	</tr>
	<?php endif;?>
	<?php endfor;?>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	  <td width="50%" align="left"><?PHP echo $PREVIOUS_PAGE;?></td>
	  <td align="right"><?PHP echo $NEXT_PAGE;?></td>
	</tr>
</table>
<br>
<?php endif;?>
<input type="button" value="���β��̤����" style="width:150px;" onClick="javascript:PrintPage();">

<form action="./" method="post">
<input type="submit" value="��ʸ���󸡺����ϲ��̤�" style="width:150px; ">
</form>
</body>
</html>
