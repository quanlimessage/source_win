<?php
/*******************************************************************************
Nx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
View����Ͽ���ư���ɽ���ʺǽ��ɽ�������

2006/11/22 Eric Tam
*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../index.php");exit();
}
if(!$accessChk){
	header("Location: ../index.php");exit();
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
<title>��奫�����</title>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>

	<form action="../main.php" method="post">
		<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
	</form>

	<p class="page_title">��奫�����</p>
	<p class="explanation">
		��¾�η�����夲��ɽ���������ϡ��ڷ�֡ۤ�ɽ��������������򤷤Ƥ���������
	</p>

	<table width="250" cellpadding="0" cellspacing="1" border="0" bgcolor="#000000">
		<tr bgcolor="#FFFFFF">
			<td align="left" style="padding:5px;"><img src="images/bar.gif" width="50" height="10" align="absmiddle">���������<br><img src="images/bar_u.gif" width="50" height="10" align="absmiddle">���������Կ�<br><img src="images/bar_uu.gif" width="50" height="10" align="absmiddle">�������侦�ʿ��ʸĿ���</td>
		</tr>
	</table>
	<br>

	<table width="300" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th width="40%" align="center" class="tdcolored">���</th>

			<td width="60%" align="left">
				<form name="form1" method="post" action="./" style="margin:0px;">
				  <select name="year_month" id="year_month" onChange="javascript:submit();">
						<?php foreach($yearmonth_fetch as $k=>$v){ ?>
							<option value="<?php echo $v[YM];?>" <?php if($_POST[year_month] == $v[YM]){ echo "selected"; } ?>><?=$v[Y];?>ǯ<?=$v[M];?>��</option>
						<?php } ?>
				  </select>
				</form>
			</td>
		</tr>
	</table>
	<br>

	<table width="600" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="6" align="center" class="tdcolored">����</th>

		</tr>

	  <tr>
		<td width="14%" align="center" class="tdcolored">�����</td>
			<td width="15%" align="left"><?php echo ($total_price_fetch[0][TOTAL_SUM_PRICE])?number_format($total_price_fetch[0][TOTAL_SUM_PRICE])."��":"0��";?></td>

		<td width="14%" align="center" class="tdcolored">�����Կ�</td>
			<td width="15%" align="left"><?php echo ($total_customer_fetch[0][CNT])?$total_customer_fetch[0][CNT]."��":"0��";?></td>

		<td width="17%" align="center" class="tdcolored">���侦�ʿ��ʸĿ���</td>
			<td width="15%" align="left"><?php echo ($total_quantity_fetch[0][TOTAL_QUANTITY])?$total_quantity_fetch[0][TOTAL_QUANTITY]."��":"0��";?></td>
	  </tr>
	</table>
	<br>

	<table width="600" border="1" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2" align="center" class="tdcolored">������</th>

		</tr>

			<?php

				//��׿����Ǽ����(�ѡ�����Ȥ򻻽Ф����)
					$pnum = $total_price_fetch[0]['TOTAL_SUM_PRICE'];//���夲���
					$cnum = $total_customer_fetch[0]['CNT'];//�����Կ�
					$qnum = $total_quantity_fetch[0]['TOTAL_QUANTITY'];//���侦�ʿ�

			for($i=0,$j=0;$j<=23;$j++):?>
				<tr>
				  <td width="20%" align="center" class="tdcolored"><?php echo $j;?>��</td>
				  <td width="80%" align="left">
				  <?php

				  //Ʊ�����֤ξ��Ͻ����򤹤�
				  	if($fetch_time[$i]['HOUR_TIME'] == $j){

				 	 //�����
							$width = @round($fetch_time[$i]['TOTAL_PRICE'] / $pnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������

							?>
								<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['TOTAL_PRICE'])?"(".number_format($fetch_time[$i]['TOTAL_PRICE'])."��)":"0��";?>
					<br>

				  <?php
				  	//�����Կ�
							$width = @round($fetch_time[$i]['CNT'] / $cnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������

							?>
							<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['CNT'])?"(".$fetch_time[$i]['CNT']."��)":"0��";?>
						<br>

				  <?php
				  	//���侦�ʿ��ʸĿ���
							$width = @round($fetch_time[$i]['TOTAL_QUANTITY'] / $qnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������

							?>
							<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
							<?php echo ($fetch_time[$i]['TOTAL_QUANTITY'])?"(".$fetch_time[$i]['TOTAL_QUANTITY']."��)":"0��";?>

				<?php
					//��������ǡ����˰ܹ�
					$i++;

					}else{//Ʊ�����֤�̵�����ν���

					//���ƣ��ο��ͤ�ɽ��
					echo "&nbsp;0��<br>&nbsp;0��<br>&nbsp;0��";

					}?>

				</td>
			  </tr>
	<?php endfor;?>
	</table>
	<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">����</th>

			</tr>

		<?php for($i=0;$i<count($fetch_day);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored"><?=$fetch_day[$i]["M"];?>��<?=$fetch_day[$i]["D"];?>��</td>
			  <td width="80%" align="left">
					<?php
					//�����
						$width = @round($fetch_day[$i]['TOTAL_PRICE'] / $pnum * 100);
					?>
					<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".number_format($fetch_day[$i]['TOTAL_PRICE'])."��)";?>
				<br>

					<?php
					//�����Կ�
						$width = @round($fetch_day[$i]['CNT'] / $cnum * 100);
					?>
					<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".$fetch_day[$i]['CNT']."��)";?>
				<br>

					<?php
					//���侦�ʿ��ʸĿ���
						$width = @round($fetch_day[$i]['TOTAL_QUANTITY'] / $qnum * 100);
					?>
					<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
					<?php echo "(".$fetch_day[$i]['TOTAL_QUANTITY']."��)";?>

			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">������</th>

			</tr>
		<?php

			//ɽ�������������Ǽ
			$week = array("������","������","������","������","������","������","������");

			//��j�ۤ��������Ǽ����i�ۤϥǡ����ΰ��֤��Ǽ
			for($j=0,$i=0;$j<=6;$j++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
				<?php

					// �������ͤ�Ƚ�̤������������
					echo $week[$j];?>
			  </td>
			  <td width="80%" align="left">
						<?php

						//��������äƤ����ɽ�������򤹤�
							if($fetch_week[$i]['DAYOFWEEK'] == ($j + 1)){

								//�����
									$width = @round($fetch_week[$i]['TOTAL_PRICE'] / $pnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������(�����ɽ����)
								?>
								<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
								<?php echo ($fetch_week[$i]['TOTAL_PRICE'])?"(".number_format($fetch_week[$i]['TOTAL_PRICE'])."��)":"0��";?>
							<br>

								<?php
								//�����Կ�
									$width = @round($fetch_week[$i]['CNT'] / $cnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������(�����ɽ����)
								?>
								<img src="images/bar_u.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%

								<?php echo ($fetch_week[$i]['CNT'])?"(".$fetch_week[$i]['CNT']."��)":"0��";?>
							<br>

								<?php
								//���侦�ʿ��ʸĿ���
									$width = @round($fetch_week[$i]['TOTAL_QUANTITY'] / $qnum * 100); // ������ȿ�����������ȿ��ǥѡ�����ơ�������(�����ɽ����)
								?>
								<img src="images/bar_uu.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
								<?php echo ($fetch_week[$i]['TOTAL_QUANTITY'])?"(".$fetch_week[$i]['TOTAL_QUANTITY']."��)":"0��";

								//�ǡ����ΰ��֤򼡤˰ܹ�
								$i++;

							}

						//������̵���ä��������ƣ���ɽ��
							else{

								echo "&nbsp;0��<br>&nbsp;0��<br>&nbsp;0��";

							}
							?>
			</td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">���ƥ��꡼�̤������</th>

			</tr>
				<?php for($j=0;$j < count($category_fetch);$j++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?php

					//ͭ���ʥ��ƥ��꡼��ɽ�����롡VIEW_ORDER������
					//���ƥ��꡼̾��ɽ��
					echo $category_fetch[$j]['CATEGORY_NAME'];
				?>
			  </td>

			  	<td width="80%" align="left">
						<?php

						//���ƥ��꡼�����ɤ�Ʊ�����ɽ���򤹤�ʥ��ƥ��꡼���¤ӽ礬�Ѥ�äƤ����������ɽ���Ǥ��ʤ���礬����Τ����ƤΥ��ƥ����Ĵ�٤ʤ���ɽ�������
						for($i=0,$c_flg=0;(($i < count($fetch_cate)) && ($c_flg == 0));$i++){

							if($category_fetch[$j]['CATEGORY_CODE'] == $fetch_cate[$i]['CATEGORY_CODE']){
							$c_flg = 1;//�ե饰��Ω�Ƥ�
							$width = @round($fetch_cate[$i]['TOTAL_PRICE'] / $pnum * 100);
						?>
						<img src="images/bar.gif" width="<?php echo $width * 3;?>" height="10" align="absmiddle">&nbsp;<?php echo $width;?>%
						<?php echo "(".number_format($fetch_cate[$i]['TOTAL_PRICE'])."��)";?>

						<?php
							}
						}
						//�����������Ƥʤ���У���
						if($c_flg == 0){
						echo "&nbsp;0%(0��)";

						}?>
				</td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">���ڥȥåף��������夲��ۡ�</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_sell);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  ���ʵ��桧<?php echo $fetch_bestten_sell[$i]['PART_NO'];?><br> ����̾&nbsp;&nbsp;&nbsp;��<?php echo $fetch_bestten_sell[$i]['PRODUCT_NAME'];?>��<?php echo number_format($fetch_bestten_sell[$i]['TOTAL_PRICE']);?>�ߡ�
			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">���ڥȥåף�������ʸ�����</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_order);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  ���ʵ��桧<?php echo $fetch_bestten_order[$i]['PART_NO'];?><br> ����̾&nbsp;&nbsp;&nbsp;��<?php echo $fetch_bestten_order[$i]['PRODUCT_NAME'];?>��<?php echo $fetch_bestten_order[$i]['CNT'];?>���
			  </td>
		  </tr>
		<?php endfor;?>
		</table>
		<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0">
			<tr>
				<th colspan="2" align="center" class="tdcolored">���ڥȥåף����ʹ�������</th>

			</tr>

		<?php for($i=0;$i < count($fetch_bestten_access);$i++):?>
			<tr>
			  <td width="20%" align="center" class="tdcolored">
			  <?=$i+1;?>
			  </td>
			  <td width="80%" align="left">
			  ���ʵ��桧<?php echo $fetch_bestten_access[$i]['PART_NO'];?><br> ����̾&nbsp;&nbsp;&nbsp;��<?php echo $fetch_bestten_access[$i]['PRODUCT_NAME'];?>��<?php echo $fetch_bestten_access[$i]['TOTAL_QUANTITY'];?>�ġ�
			  </td>
		  </tr>
		<?php endfor;?>
		</table>

</body>
</html>