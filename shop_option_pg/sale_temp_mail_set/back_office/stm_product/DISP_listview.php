<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
View����Ͽ���ư���ɽ���ʺǽ��ɽ�������

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
// �����������������å���ľ�ܤ��Υե�����˥���������������
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
}

	#--------------------------------------------------------
	# �ڡ������ѥ��ʸ�������
	#--------------------------------------------------------

	//�ڡ�����󥯤ν����
	$link_prev = "";
	$link_next = "";

		// ���ڡ����ֹ�
		$next = $p + 1;
		// ���ڡ����ֹ�
		$prev = $p - 1;

		// ���������
		$tcnt = count($fetchCNT);
		// ���ڡ�����
		$totalpage = ceil($tcnt/DISP_MAXROW_BACK);

		// ���ƥ��꡼�̤�ɽ�����Ƥ���Хڡ������ܤ⥫�ƥ��꡼�ѥ�᡼������Ĥ���
		if($ca)$cpram = "&ca=".urlencode($ca);

		// ���ڡ����ؤΥ��
		if($p > 1){
			$link_prev = "<a href=\"./?p=".urlencode($prev).$cpram."\">&lt;&lt; Prev</a>";
		}

		//���ڡ������
		if($totalpage > $p){
			$link_next = "<a href=\"./?p=".urlencode($next).$cpram."\">Next &gt;&gt;</a>";
		}

#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿ͭ�����¤����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,false,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title></title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="sort<?php echo (STM_SORT_TYPE == 1)?"":"2";?>.php" method="post">
		<input type="submit" value="�¤��ؤ���Ԥ�" style="width:150px;">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo STM_TITLE;?>��������Ͽ</p>
<p class="explanation">
�������ǡ�������Ͽ��Ԥ��ݤϡ�<strong>�ֿ����ɲá�</strong>�򥯥�å����Ƥ���������<br>
��������Ͽ�����<strong><?php echo STM_DBMAX_CNT;?>��</strong>�Ǥ���
</p>
<?php
#-----------------------------------------------------
# ������ġʺ�����Ͽ�����ã���Ƥ��ʤ��ˤξ���ɽ��
#-----------------------------------------------------
if(count($fetchCNT) < STM_DBMAX_CNT):?>
<form action="./" method="post">
<input type="submit" value="�����ɲ�" style="width:150px;">
<input type="hidden" name="act" value="new_entry">
</form>
<?php else:?>
<p class="err">������Ͽ���<?php echo STM_DBMAX_CNT;?>���ã���Ƥ��ޤ���<br>
������Ͽ��Ԥ����ϡ������줫�δ�¸�ǡ����������Ƥ���������</p>
<?php endif;?>
<p class="page_title"><?php echo STM_TITLE;?>����Ͽ����</p>
<p class="explanation">
����¸�ǡ����ν�����<strong>���Խ���</strong>�򥯥�å����Ƥ�������<br>
��<strong>��ɽ����סָ�����ɽ����</strong>�򥯥�å������ؤ����ɽ���ڡ����Ǥ�ɽ�������椷�ޤ���<br>
��<strong>�ֺ����</strong>�򥯥�å��������Ͽ����Ƥ���ǡ������������ޤ���<br>
��<strong>��������ǡ����������Ǥ��ޤ���</strong>��ʬ����դ��ƽ�����ԤäƤ���������<br>
��<strong>�����Ԥä����</strong>���֤��������������뤿�ᡢ�嵭�ܥ������<strong>�¤��ؤ���</strong>��¹Ԥ��Ƥ�������
</p>
<?php if(!$fetch):?>
<p><b>��Ͽ����Ƥ���ǡ����Ϥ���ޤ���</b></p>
<?php else:?>
<div>����Ͽ�ǡ��������<strong><?php echo count($fetchCNT);?></strong>&nbsp;��</div>

<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
	</tr>
</table>

<table width="510" border="1" cellpadding="2" cellspacing="0">
	<tr class="tdcolored">
		<!--<th width="5%" nowrap>ɽ����</th>-->
		<th width="15%" nowrap>������</th>
		<!--<th width="10%" nowrap>����</th>-->
		<th nowrap>�����ȥ�</th>
		<th width="5%" nowrap>�Խ�</th>
		<!--<th width="10%" nowrap>ɽ������</th>-->
		<th width="5%" nowrap>���</th>
		<!--<th width="10%">�ץ�ӥ塼</th>-->
		<th width="10%">ʣ��</th>
	</tr>
	<?php for($i=0;$i<count($fetch);$i++):?>
	<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
		<!--<td align="center"><?php //echo $fetch[$i]['VIEW_ORDER'];?></td>-->
		<td align="center"><?php echo $fetch[$i]["Y"].".".$fetch[$i]["M"].".".$fetch[$i]["D"];?></td>

		<?php /*<td align="center">
			<?php if(search_file_flg(S2_IMG_PATH,$fetch[$i]['RES_ID']."_1.*")):?>
				<a href="<?php echo search_file_disp(S2_IMG_PATH,$fetch[$i]['RES_ID']."_1.*","",2);?>" target="_blank">
				<?php echo search_file_disp(S2_IMG_PATH,$fetch[$i]['RES_ID']."_1.*","border=\"0\" width=\"".S2_IMGSIZE_SX."\"");?>
				</a>
			<?php else:
				echo '&nbsp;';
			endif;?>
		</td>*/?>
		<td align="center">&nbsp;<?php echo (!empty($fetch[$i]['TITLE']))?mb_strimwidth($fetch[$i]['TITLE'], 0, 80, "...", euc):"No Title";?></td>
		<td align="center">
		<form method="post" action="./" style="margin:0;">
		<input type="submit" name="reg" value="�Խ�">
		<input type="hidden" name="act" value="update">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<form method="post" action="./" style="margin:0;" onSubmit="return confirm('���Υǡ��������˺�����ޤ���\n�ǡ����������Ͻ���ޤ���\n������Ǥ�����');">
		<input type="submit" value="���">
		<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
		<input type="hidden" name="act" value="del_data">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
		<td align="center">
		<?php if(count($fetchCNT) < STM_DBMAX_CNT){?>
				<form method="post" action="./" style="margin:0;">
				<input type="submit" name="reg" value="ʣ��">
				<input type="hidden" name="act" value="copy">
				<input type="hidden" name="res_id" value="<?php echo $fetch[$i]['RES_ID'];?>">
				<input type="hidden" name="p" value="<?php echo $p;?>">
				<input type="hidden" name="copy_flg" value="1">
			</form>
		<?php }else{?>
			&nbsp;
		<?php }?>
		</td>
	</tr>
	<?php endfor;?>
</table>
<?php endif;?>

<table width="500" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" align="left">
		<?php echo $link_prev;?>
		</td>
		<td width="50" align="right">
		<?php echo $link_next;?>
		</td>
	</tr>
</table>

</body>
</html>