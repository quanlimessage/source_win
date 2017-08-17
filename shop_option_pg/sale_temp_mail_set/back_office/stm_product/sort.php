<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
�¤��ؤ��ץ����
	��sort.php�ʤ��Υե����뼫�ȡˤ�sort.js�Σ��ĤΥե�����ǹ���

*******************************************************************************/

#---------------------------------------------------------------
# �����������������å���ľ�ܤ��Υե�����˥���������������
#	���������Ԥ�����ID��PW����פ��뤫�ޤǹԤ�
#---------------------------------------------------------------
session_start();
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if(!$_SERVER['PHP_AUTH_USER']||!$_SERVER['PHP_AUTH_PW']){
//	header("Location: ../index.php");exit();
}

// ����ե���������̥饤�֥����ɤ߹���
require_once("../../common/config_sale_tmpl_mail.php");	// �������
require_once("dbOpe.php");					// �ģ����饹�饤�֥��
require_once("util_lib.php");				// ���ѽ������饹�饤�֥��

	// POST�ǡ����μ������ȶ��̤�ʸ�������
	if($_POST){extract(utilLib::getRequestParams("post",array(8,7,1,4)));}

#===============================================================================
# $_POST['action']������п������¤��Ѥ������֤˹�������
#===============================================================================
if(($_POST['action'] == "update")&&(!empty($_POST['new_view_order']))):

	#===============================================================================
	# �����¤ӽ礬��Ǽ���줿hidden�ǡ����򥿥֤�ǥ�ߥ��ˤ��ƥХ餹������˳�Ǽ��
	#	���оݤ�hidden�ǡ�����$new_view_order��RES_ID�����ֶ��ڤ�ˤʤäƤ����
	#	��������VIEW_ORDER���ֹ桧$vo�������ֹ��1��­�������
	#	��¾��hidden�ǡ�����$category_code���оݤΥ��ƥ��꡼�����ɡ�
	#		�����ƥ���ʬ�ह����Τ�ȯ�����ޤ����ǥե���ȤǤϤĤ��Ƥ��ޤ���
	#
	# �����¤��ؤ��򹹿�����ӣѣ̤�ȯ�ԡʥХ餷�����ʬ���ꤹ���
	#===============================================================================
	$vo = explode("\t", $new_view_order);

	for($i=0;$i<count($vo);$i++){

		$sql[$i] = "
		UPDATE
			".STM_PRODUCT_LST."
		SET
			VIEW_ORDER = '".($i+1)."'
		WHERE
			(RES_ID = '".$vo[$i]."')
		AND

			(DEL_FLG = '0')
		";

	}

	// �ӣѣ̤�¹�
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB��Ͽ���Ԥ��ޤ���<hr>{$db_result}");

	// �Ǹ���¤��ؤ��Υȥåפ����Ф�
	//header("Location: ./sort.php");

endif;

#===============================================================================
# ���ߤ�ɽ����˾��ʥꥹ�Ȥ�ɽ��
#===============================================================================

// ���ߤ��¤ӽ�ǥǡ��������
$sql = "
SELECT
	RES_ID,TITLE,VIEW_ORDER,DISPLAY_FLG
FROM
	".STM_PRODUCT_LST."
WHERE
	(DEL_FLG = '0')
ORDER BY
	VIEW_ORDER ASC
";
$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="content-type" content="text/css; charset=EUC-JP">
<title></title>
<script language="JavaScript" src="sort.js"></script>
<link href="../for_bk.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header"></div>
<br>
<br>
<table width="400" border="0" cellpadding="0" cellspacing="0" style="margin-top:1em;">
	<tr>
		<td>
		<form action="../main.php" method="post">
			<input type="submit" value="�������̥ȥåפ�" style="width:150px;">
		</form>
		</td>
		<td>
		<form action="index.php" method="post">
		<input type="submit" value="�ꥹ�Ȳ��̤����" style="width:150px;">
		<input type="hidden" name="p" value="<?php echo $p;?>">
		</form>
		</td>
	</tr>
</table>

<p class="page_title"><?php echo STM_TITLE;?>���¤��ؤ�</p>
<p class="explanation">
������ɽ������Ƥ����¤ӽ�Ǥ���<br>
���ѹ��������ǡ��������򤷡������Ρںǽ�˰�ư�ۡڰ��ʾ�˰�ư�ۡڰ��ʲ��˰�ư�ۡںǸ�˰�ư�ۡڥ��ȥå�����ۡ���������ۤ���Ѥ����¤��ؤ���ԤäƤ���������<br>
���Ǹ�ˡ־嵭���¤��ؤ���ǹ����פ򥯥�å�������¤��ؤ����ѹ���Ŭ�Ѥ���ޤ���

</p>
<?php
if(!$fetch):
	echo "<strong>��Ͽ����Ƥ���ǡ����Ϥ���ޤ���</strong><br><br>";
else:
?>
<div>���ߤ���Ͽ�ǡ��������&nbsp;<strong><?php echo count($fetch); ?></strong>&nbsp;��</div>
<br>
<table width="850" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="left" valign="top">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<form name="change_sort" action="./sort.php" method="post" style="margin:0;">
			<div style="float:left;margin-right:0.5em">
				�¤��ؤ��ν���<br>
				<select name="nvo" size="<?php echo (count($fetch) > 20)?20:count($fetch);// size��20�����ˤ��Ƥ��� ?>">
				<?php
				for($i=0;$i<count($fetch);$i++){
						$title = ($fetch[$i]['TITLE'])?mb_strimwidth($fetch[$i]['TITLE'], 0, 40, "...", euc):"No Title";
					echo "<option value=\"{$fetch[$i]['RES_ID']}\">{$fetch[$i]['VIEW_ORDER']}��{$title}</option>\n";
				}?>
				</select>
			</div>

				<div style="float:left;">

					<input type="button" value="�ǽ�˰�ư" onClick="f_moveUp();" style="width:100px;">
					<br>
					<input type="button" value="���ʾ�˰�ư" onClick="moveUp();" style="width:100px;">
					<br>
					<input type="button" value="���ʲ��˰�ư" onClick="moveDn();" style="width:100px;">
					<br>
					<input type="button" value="�Ǹ�˰�ư" onClick="l_moveDn();" style="width:100px;">
					<br>
					<br>
					<input type="button" value="���ȥå�����" onClick="stock_move();" style="width:100px;">
					<br>
					<input type="button" value="��������" onClick="on_move();" style="width:100px;">

				</div>

				<div style="float:left;padding-left:10px;">
					���ȥå��ꥹ��<br>
					<select name="stock_nvo" size="10" style="width:150px;" multiple></select>

				</div>
				<br>

				<div style="clear:left;">

					<input type="button" value="�嵭���¤��ؤ���ǹ���" style="margin-top:0.5em;width:200px;" onClick="change_sortSubmit();">
					<input type="hidden" name="action" value="update">
					<input type="hidden" name="new_view_order" value="">
					<input type="hidden" name="p" value="<?php echo $p;?>">
				</div>
			</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
		<tr>
			<td>
			<p class="explanation">
			<span style="color:#FF0000;">�ܥ���Τ�����</span><br>
			���ںǽ�˰�ư�ۤ����򤷤��ǡ����ν��֤���ֺǽ�˰�ư�����ޤ���<br>
			���ڰ��ʾ�˰�ư�ۤ����򤷤��ǡ����ΰ�ľ�˰�ư�����ޤ���<br>
			���ڰ��ʲ��˰�ư�ۤ����򤷤��ǡ����ΰ�Ĳ��˰�ư�����ޤ���<br>
			���ںǸ�˰�ư�ۤ����򤷤��ǡ����ν��֤���ֺǸ�˰�ư�����ޤ���<br>

			���ڥ��ȥå�����ۤ����򤷤��ǡ�����¦�Υ��ȥå��ꥹ�Ȥ˰�ư�����ޤ���<br>
			������������ۤϱ�¦�Ρڥ��ȥå��ꥹ�ȡۤ����򤷤��ǡ�����¦�Ρ��¤��ؤ��ν��֡ۤ����򤵤줿���֤��������ޤ���<br>
			���ڥ��ȥå��ꥹ�ȡۤ�ʣ�����򤹤뤳�Ȥ�����ޤ��������ܡ��ɤΡ�Ctrl�ۥܥ���򲡤��ʤ������򡢤ޤ��ϡ��ɥ�å����ϰ����򤬽���ޤ���<br>
			</p>

			<br>
			���ߤ��¤ӽ�<br>

		<table width="600" border="1" cellpadding="2" cellspacing="0" style="height:inherit;">
				<tr class="tdcolored">
					<th nowrap class="back2">�����ȥ�</th>
					<!--<th width="10%" nowrap class="back2">����</th>-->
					<th width="15%" class="back2">���ߤ�<br>ɽ����</th>
				</tr>
				<?php for($i=0;$i<count($fetch);$i++):?>
				<tr class="<?php echo (($i % 2)==0)?"other-td":"otherColTd";?>">
					<td align="center">&nbsp;<?php
									$title = mb_strimwidth($fetch[$i]['TITLE'], 0, 80, "...", euc);
									echo ($title)?$title:"No Title"; ?>
					</td>
					<td align="center"><?php echo $fetch[$i]['VIEW_ORDER'];?></td>
				</tr>
				<?php endfor; ?>
		  </table>
	</td>
</tr>

</table>
<?php endif; ?>
</body>
</html>